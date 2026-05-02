import {useEffect, useReducer, useRef, useState} from 'react'
import type {ApiResponse} from './RecommendationResult'
import type {Country, FertilizerEntry, FormState, Step} from './types'
import {COUNTRY_COORDS, COUNTRY_INTERCROP, INITIAL_FORM_STATE} from './constants'

// ── Date helpers ──────────────────────────────────────────────────────────────

const today = new Date()

const sixMonthsAgo = new Date(today)
sixMonthsAgo.setMonth(sixMonthsAgo.getMonth() - 6)
export const plantingMin = sixMonthsAgo.toISOString().slice(0, 10)

function addMonthsDays(base: Date, months: number, days = 0): string {
    const d = new Date(base)
    d.setMonth(d.getMonth() + months)
    if (days) d.setDate(d.getDate() + days)
    return d.toISOString().slice(0, 10)
}

export function minHarvestDate(planting: string): string {
    return addMonthsDays(planting ? new Date(planting) : today, 7, 24)
}

export function maxHarvestDate(planting: string): string {
    return addMonthsDays(planting ? new Date(planting) : today, 15)
}

// ── Step helpers ──────────────────────────────────────────────────────────────

export function needsFertilizers(s: string) {
    return s === 'FR' || s === 'COMPLETE'
}

export function totalSteps(s: string): Step {
    return needsFertilizers(s) ? 5 : 4
}

export function scenarioToFlags(s: string, intercropCrop: string) {
    return {
        fertilizer_rec:            s === 'FR'   || s === 'COMPLETE',
        planting_practices_rec:    s === 'PP'   || s === 'COMPLETE',
        scheduled_planting_rec:    s === 'SPHS' || s === 'COMPLETE',
        scheduled_harvest_rec:     s === 'SPHS' || s === 'COMPLETE',
        inter_cropping_maize_rec:  (s === 'IC' && intercropCrop === 'MAIZE')  || s === 'COMPLETE',
        inter_cropping_potato_rec: (s === 'IC' && intercropCrop === 'POTATO') || s === 'COMPLETE',
    }
}

// ── Hook ─────────────────────────────────────────────────────────────────────

export function usePlaygroundForm() {
    const [step, setStep] = useState<Step>(1)
    const [form, setForm] = useReducer(
        (prev: FormState, patch: Partial<FormState>) => ({...prev, ...patch}),
        INITIAL_FORM_STATE,
    )
    const [countries, setCountries] = useState<Country[]>([])
    const [fertilizers, setFertilizers] = useState<FertilizerEntry[]>([])
    const [loadingFerts, setLoadingFerts] = useState(false)
    const [submitting, setSubmitting] = useState(false)
    const [result, setResult] = useState<ApiResponse | null>(null)
    const [apiError, setApiError] = useState<string | null>(null)
    const [validationErrors, setValidationErrors] = useState<Record<string, string>>({})
    const resultRef = useRef<HTMLDivElement>(null)

    useEffect(() => {
        fetch('/api/v1/countries')
            .then((r) => r.json())
            .then((d) => setCountries(d.data ?? d))
            .catch(() => {})
    }, [])

    useEffect(() => {
        if (!form.country || !needsFertilizers(form.scenario)) return
        setLoadingFerts(true)
        setFertilizers([])
        fetch(`/api/v1/fertilizers/country/${form.country}?per_page=50`)
            .then((r) => r.json())
            .then((d) => {
                const items = d.data ?? d
                setFertilizers(
                    items
                        .filter((f: {available: boolean}) => f.available)
                        .map((f: {fertilizer_key: string; name: string; weight: number}) => ({
                            key:             f.fertilizer_key,
                            name:            f.name,
                            fertilizer_type: 'STRAIGHT',
                            weight:          f.weight ?? 50,
                            price:           0,
                            selected:        false,
                        })),
                )
            })
            .catch(() => {})
            .finally(() => setLoadingFerts(false))
    }, [form.country, form.scenario])

    function handleCountryChange(code: string) {
        const coords = COUNTRY_COORDS[code]
        const intercropCrop = COUNTRY_INTERCROP[code]
        setForm({
            country:  code,
            mapLat:   coords ? String(coords[0]) : '',
            mapLong:  coords ? String(coords[1]) : '',
            ...(intercropCrop ? {intercropCrop} : {}),
        })
    }

    function updateFert(key: string, patch: Partial<FertilizerEntry>) {
        setFertilizers((prev) => prev.map((f) => (f.key === key ? {...f, ...patch} : f)))
    }

    function validateStep(s: Step): Record<string, string> {
        const errs: Record<string, string> = {}

        if (s === 2) {
            if (!form.country) errs.country = 'Please select a country'
            else if (form.scenario === 'IC' && !COUNTRY_INTERCROP[form.country])
                errs.country = 'Intercropping is only available for Nigeria (maize) and Tanzania (potato)'
            if (!form.fieldSize || +form.fieldSize < 0.1) errs.fieldSize = 'Enter a valid field size (min 0.1)'
            if (!form.mapLat || isNaN(+form.mapLat))   errs.mapLat  = 'Pin your farm location on the map'
            if (!form.mapLong || isNaN(+form.mapLong)) errs.mapLong = 'Pin your farm location on the map'
        }

        if (s === 3) {
            const fy = Number(form.fieldYield)
            if (isNaN(fy) || fy < 0 || fy > 100)
                errs.fieldYield = 'Enter your current cassava yield (0–100 t/ha)'
            const sq = Number(form.soilQuality)
            if (isNaN(sq) || sq < 0 || sq > 5)
                errs.soilQuality = 'Soil quality must be between 0 and 5'
        }

        if (s === 4 && needsFertilizers(form.scenario)) {
            if (!fertilizers.some((f) => f.selected))
                errs.fertilizers = 'Select at least one fertilizer'
            fertilizers.filter((f) => f.selected).forEach((f) => {
                if (!f.price || f.price <= 0)
                    errs[`price_${f.key}`] = 'Enter a price for this fertilizer'
            })
        }

        const dateStep = needsFertilizers(form.scenario) ? 5 : 4
        if (s === dateStep) {
            if (!form.plantingDate) {
                errs.plantingDate = 'Enter a planting date'
            } else if (form.plantingDate < plantingMin) {
                errs.plantingDate = 'Planting date cannot be more than 6 months in the past'
            }
            if (!form.harvestDate) {
                errs.harvestDate = 'Enter a harvest date'
            } else if (form.plantingDate && form.harvestDate < minHarvestDate(form.plantingDate)) {
                errs.harvestDate = 'Harvest date must be at least ~7.8 months after planting date'
            } else if (form.plantingDate && form.harvestDate > maxHarvestDate(form.plantingDate)) {
                errs.harvestDate = 'Harvest date must be within 15 months of planting date'
            }
            if (![0, 1, 2].includes(Number(form.plantingDateWindow)))
                errs.plantingDateWindow = 'Select 0, 1, or 2 months'
            if (![0, 1, 2].includes(Number(form.harvestDateWindow)))
                errs.harvestDateWindow = 'Select 0, 1, or 2 months'
        }

        return errs
    }

    function advance() {
        const errs = validateStep(step)
        if (Object.keys(errs).length) { setValidationErrors(errs); return }
        setValidationErrors({})
        setStep((prev) => (prev + 1) as Step)
    }

    function back() {
        setValidationErrors({})
        setStep((prev) => (prev - 1) as Step)
    }

    async function submit() {
        const lastStep = totalSteps(form.scenario)
        const errs = validateStep(lastStep)
        if (Object.keys(errs).length) { setValidationErrors(errs); return }
        setValidationErrors({})
        setSubmitting(true)
        setApiError(null)
        setResult(null)

        const todayStr      = today.toISOString().slice(0, 10)
        const defaultHarvest = minHarvestDate(todayStr)
        const fertList = fertilizers.length
            ? fertilizers
            : [{key: 'none', name: 'None', fertilizer_type: 'STRAIGHT', weight: 50, price: 0, selected: false}]

        const payload = {
            country_code:              form.country,
            use_case:                  form.scenario === 'COMPLETE' ? 'FR' : form.scenario,
            field_size:                Number.parseFloat(form.fieldSize),
            area_unit:                 form.areaUnit,
            map_lat:                   Number.parseFloat(form.mapLat),
            map_long:                  Number.parseFloat(form.mapLong),
            lang:                      form.lang,
            field_yield:               Number.parseInt(form.fieldYield, 10),
            soil_quality:              Number.parseFloat(form.soilQuality),
            risk_attitude:             Number.parseInt(form.riskAttitude, 10),
            cassava_produce_type:      form.cassavaProduceType,
            maize_produce_type:        form.maizeProduceType,
            sweet_potato_produce_type: form.sweetPotatoProduceType,
            planting_date:             form.plantingDate || todayStr,
            harvest_date:              form.harvestDate  || defaultHarvest,
            planting_date_window:      Number.parseInt(form.plantingDateWindow, 10) || 0,
            harvest_date_window:       Number.parseInt(form.harvestDateWindow, 10)  || 0,
            fertilizer_list:           fertList,
            ...scenarioToFlags(form.scenario, form.intercropCrop),
        }

        const csrfToken = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? ''
        try {
            const res = await fetch('/playground/compute', {
                method:  'POST',
                headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken},
                body:    JSON.stringify(payload),
            })
            const json = await res.json()
            if (res.ok) {
                setResult(json)
                setTimeout(() => resultRef.current?.scrollIntoView({behavior: 'smooth', block: 'start'}), 100)
            } else {
                setApiError(json.message ?? `Error ${res.status}`)
            }
        } catch {
            setApiError('Network error — please check your connection and try again.')
        } finally {
            setSubmitting(false)
        }
    }

    function reset() {
        setResult(null)
        setApiError(null)
        setStep(1)
        setForm(INITIAL_FORM_STATE)
        setFertilizers([])
    }

    return {
        step, form, setForm,
        countries, fertilizers, loadingFerts,
        submitting, result, apiError, validationErrors,
        resultRef,
        handleCountryChange, updateFert,
        advance, back, submit, reset,
    }
}
