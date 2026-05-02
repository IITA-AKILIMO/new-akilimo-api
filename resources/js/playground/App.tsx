import {useEffect, useReducer, useRef, useState} from 'react'
import RecommendationResult, {type ApiResponse} from './RecommendationResult'
import MapPicker from './MapPicker'
import HistoryPanel from './HistoryPanel'

// ── Types ─────────────────────────────────────────────────────────────────────

interface Country {
    id: number
    code: string
    name: string
}

interface Fertilizer {
    id: number
    name: string
    fertilizer_key: string
    fertilizer_type?: string
    weight: number
    country: string
    available: boolean
}

interface FertilizerEntry {
    key: string
    name: string
    fertilizer_type: string
    weight: number
    price: number
    selected: boolean
}

type Scenario = 'FR' | 'IC' | 'PP' | 'SPHS' | 'COMPLETE'
type IntercropCrop = 'MAIZE' | 'POTATO'
type Step = 1 | 2 | 3 | 4 | 5

interface FormState {
    scenario: Scenario
    intercropCrop: IntercropCrop
    country: string
    fieldSize: string
    areaUnit: string
    mapLat: string
    mapLong: string
    lang: string
    // Crop & yield
    fieldYield: string
    soilQuality: string
    riskAttitude: string
    cassavaProduceType: string
    maizeProduceType: string
    sweetPotatoProduceType: string
    // Dates
    plantingDate: string
    harvestDate: string
    plantingDateWindow: string
    harvestDateWindow: string
}

// ── Constants ─────────────────────────────────────────────────────────────────

const SCENARIOS: { id: Scenario; icon: string; title: string; desc: string }[] = [
    {
        id: 'FR',
        icon: '🌱',
        title: 'Fertilizer Recommendations',
        desc: 'Which fertilizers to apply and in what quantities'
    },
    {id: 'IC', icon: '🌽', title: 'Intercropping', desc: 'Should I grow cassava alongside maize or potato?'},
    {id: 'PP', icon: '🌿', title: 'Planting Practices', desc: 'Land preparation methods and field operations'},
    {id: 'SPHS', icon: '📅', title: 'Planting Schedule', desc: 'Optimal planting and harvest timing'},
    {id: 'COMPLETE', icon: '🗺️', title: 'Complete Farm Plan', desc: 'All recommendations in one response'},
]

const AREA_UNITS = [
    {value: 'ha', label: 'Hectare (ha)'},
    {value: 'acre', label: 'Acre'},
    {value: 'm2', label: 'Square metre (m²)'},
    {value: 'are', label: 'Are'},
]

const LANGUAGES = [
    {value: 'en', label: 'English'},
    {value: 'sw', label: 'Swahili'},
    {value: 'fr', label: 'French'},
]

const CASSAVA_PRODUCE_TYPES = [
    {value: 'roots', label: 'Fresh Roots'},
    {value: 'chips', label: 'Dried Chips'},
    {value: 'flour', label: 'Cassava Flour'},
    {value: 'gari', label: 'Gari'},
]

const MAIZE_PRODUCE_TYPES = [
    {value: 'fresh_cob', label: 'Fresh Cob'},
    {value: 'grain', label: 'Dry Grain'},
]

const SWEET_POTATO_PRODUCE_TYPES = [
    {value: 'tubers', label: 'Fresh Tubers'},
    {value: 'flour', label: 'Flour'},
]

const COUNTRY_COORDS: Record<string, [number, number]> = {
    NG: [7.3451, 6.966],
    TZ: [-5.6408, 35.3456],
    GH: [7.4923, -1.2756],
    RW: [-1.9997, 29.9486],
    BI: [-3.3335, 29.9238],
}

// Intercropping availability is country-specific
const COUNTRY_INTERCROP: Partial<Record<string, IntercropCrop>> = {
    NG: 'MAIZE',
    TZ: 'POTATO',
}

// ── Helpers ───────────────────────────────────────────────────────────────────

function scenarioToFlags(s: Scenario, intercropCrop: IntercropCrop) {
    return {
        fertilizer_rec: s === 'FR' || s === 'COMPLETE',
        planting_practices_rec: s === 'PP' || s === 'COMPLETE',
        scheduled_planting_rec: s === 'SPHS' || s === 'COMPLETE',
        scheduled_harvest_rec: s === 'SPHS' || s === 'COMPLETE',
        inter_cropping_maize_rec: (s === 'IC' && intercropCrop === 'MAIZE') || s === 'COMPLETE',
        inter_cropping_potato_rec: (s === 'IC' && intercropCrop === 'POTATO') || s === 'COMPLETE',
    }
}

function needsFertilizers(s: Scenario) {
    return s === 'FR' || s === 'COMPLETE'
}

function needsDates(_s: Scenario) {
    return true
}

// Step layout: 1=Scenario, 2=Farm Details, 3=Crop & Yield, [4=Fertilizers], 4/5=Dates
function totalSteps(s: Scenario): Step {
    return needsFertilizers(s) ? 5 : 4
}

// ── Component ─────────────────────────────────────────────────────────────────

export default function App() {
    const [step, setStep] = useState<Step>(1)
    const [form, setForm] = useReducer(
        (prev: FormState, patch: Partial<FormState>) => ({...prev, ...patch}),
        {
            scenario: 'FR',
            intercropCrop: 'MAIZE',
            country: '',
            fieldSize: '1',
            areaUnit: 'ha',
            mapLat: '',
            mapLong: '',
            lang: 'en',
            fieldYield: '10',
            soilQuality: '3',
            riskAttitude: '0',
            cassavaProduceType: 'roots',
            maizeProduceType: 'fresh_cob',
            sweetPotatoProduceType: 'tubers',
            plantingDate: '',
            harvestDate: '',
            plantingDateWindow: '0',
            harvestDateWindow: '0',
        },
    )

    const [activeTab, setActiveTab] = useState<'playground' | 'history'>('playground')
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
            .catch(() => {
            })
    }, [])

    useEffect(() => {
        if (!form.country || !needsFertilizers(form.scenario)) return
        setLoadingFerts(true)
        setFertilizers([])
        fetch(`/api/v1/fertilizers/country/${form.country}?per_page=50`)
            .then((r) => r.json())
            .then((d) => {
                const items: Fertilizer[] = d.data ?? d
                setFertilizers(
                    items
                        .filter((f) => f.available)
                        .map((f) => ({
                            key: f.fertilizer_key,
                            name: f.name,
                            fertilizer_type: 'STRAIGHT',
                            weight: f.weight ?? 50,
                            price: 0,
                            selected: false,
                        })),
                )
            })
            .catch(() => {
            })
            .finally(() => setLoadingFerts(false))
    }, [form.country, form.scenario])

    function handleCountryChange(code: string) {
        const coords = COUNTRY_COORDS[code]
        const intercropCrop = COUNTRY_INTERCROP[code]
        setForm({
            country: code,
            mapLat: coords ? String(coords[0]) : '',
            mapLong: coords ? String(coords[1]) : '',
            ...(intercropCrop ? {intercropCrop} : {}),
        })
    }

    function updateFert(key: string, patch: Partial<FertilizerEntry>) {
        setFertilizers((prev) => prev.map((f) => (f.key === key ? {...f, ...patch} : f)))
    }

    // ── Validation ─────────────────────────────────────────────────────────────
    function validateStep(s: Step): Record<string, string> {
        const errs: Record<string, string> = {}

        if (s === 2) {
            if (!form.country) errs.country = 'Please select a country'
            else if (form.scenario === 'IC' && !COUNTRY_INTERCROP[form.country])
                errs.country = 'Intercropping is only available for Nigeria (maize) and Tanzania (potato)'
            if (!form.fieldSize || +form.fieldSize < 0.1) errs.fieldSize = 'Enter a valid field size (min 0.1)'
            if (!form.mapLat || isNaN(+form.mapLat)) errs.mapLat = 'Enter a valid latitude'
            if (!form.mapLong || isNaN(+form.mapLong)) errs.mapLong = 'Enter a valid longitude'
        }

        if (s === 3) {
            const fy = Number(form.fieldYield)
            if (isNaN(fy) || fy < 0 || fy > 100)
                errs.fieldYield = 'Enter your current cassava yield (0–100 t/ha)'
            const sq = Number(form.soilQuality)
            if (isNaN(sq) || sq < 0 || sq > 5)
                errs.soilQuality = 'Soil quality must be between 0 and 5'
        }

        const fertStep = 4
        if (s === fertStep && needsFertilizers(form.scenario)) {
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
            const pdw = Number(form.plantingDateWindow)
            const hdw = Number(form.harvestDateWindow)
            if (![0, 1, 2].includes(pdw))
                errs.plantingDateWindow = 'Select 0, 1, or 2 months'
            if (![0, 1, 2].includes(hdw))
                errs.harvestDateWindow = 'Select 0, 1, or 2 months'
        }

        return errs
    }

    function advance() {
        const errs = validateStep(step)
        if (Object.keys(errs).length) {
            setValidationErrors(errs);
            return
        }
        setValidationErrors({})
        setStep((prev) => (prev + 1) as Step)
    }

    function back() {
        setValidationErrors({})
        setStep((prev) => (prev - 1) as Step)
    }

    // ── Submit ─────────────────────────────────────────────────────────────────
    async function submit() {
        const lastStep = totalSteps(form.scenario)
        const errs = validateStep(lastStep)
        if (Object.keys(errs).length) {
            setValidationErrors(errs);
            return
        }
        setValidationErrors({})
        setSubmitting(true)
        setApiError(null)
        setResult(null)

        const flags = scenarioToFlags(form.scenario, form.intercropCrop)
        const todayStr = today.toISOString().slice(0, 10)
        const defaultHarvest = minHarvestDate(todayStr)  // ~7.8 months from today
        const fertList = fertilizers.length
            ? fertilizers
            : [{key: 'none', name: 'None', fertilizer_type: 'STRAIGHT', weight: 50, price: 0, selected: false}]

        const payload = {
            country_code: form.country,
            use_case: form.scenario === 'COMPLETE' ? 'FR' : form.scenario,
            field_size: Number.parseFloat(form.fieldSize),
            area_unit: form.areaUnit,
            map_lat: Number.parseFloat(form.mapLat),
            map_long: Number.parseFloat(form.mapLong),
            lang: form.lang,
            field_yield: Number.parseInt(form.fieldYield, 10),
            soil_quality: Number.parseFloat(form.soilQuality),
            risk_attitude: Number.parseInt(form.riskAttitude, 10),
            cassava_produce_type: form.cassavaProduceType,
            maize_produce_type: form.maizeProduceType,
            sweet_potato_produce_type: form.sweetPotatoProduceType,
            planting_date: form.plantingDate || todayStr,
            harvest_date: form.harvestDate || defaultHarvest,
            planting_date_window: Number.parseInt(form.plantingDateWindow, 10) || 0,
            harvest_date_window: Number.parseInt(form.harvestDateWindow, 10) || 0,
            fertilizer_list: fertList,
            ...flags,
        }

        const csrfToken = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? ''
        try {
            const res = await fetch('/playground/compute', {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken},
                body: JSON.stringify(payload),
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

    // ── Date bounds ───────────────────────────────────────────────────────────
    // Cassava growth window: ~7.8–15 months after planting
    // 7.8 months ≈ 7 months + 24 days
    const today = new Date()
    const sixMonthsAgo = new Date(today)
    sixMonthsAgo.setMonth(sixMonthsAgo.getMonth() - 6)
    const plantingMin = sixMonthsAgo.toISOString().slice(0, 10)

    function addMonthsDays(base: Date, months: number, days = 0): string {
        const d = new Date(base)
        d.setMonth(d.getMonth() + months)
        if (days) d.setDate(d.getDate() + days)
        return d.toISOString().slice(0, 10)
    }

    function minHarvestDate(planting: string): string {
        const base = planting ? new Date(planting) : today
        return addMonthsDays(base, 7, 24)   // ~7.8 months
    }

    function maxHarvestDate(planting: string): string {
        const base = planting ? new Date(planting) : today
        return addMonthsDays(base, 15)
    }

    // ── Derived ────────────────────────────────────────────────────────────────
    const maxStep = totalSteps(form.scenario)
    const isLastStep = step === maxStep

    const STEP_LABELS: Record<number, string> = {
        1: 'Scenario',
        2: 'Farm Details',
        3: 'Crop & Yield',
        4: needsFertilizers(form.scenario) ? 'Fertilizers' : 'Dates',
        5: 'Dates',
    }

    const intercropCrop = COUNTRY_INTERCROP[form.country]

    // ── Render ─────────────────────────────────────────────────────────────────
    return (
        <div className="pg-wrap">
            <div className="pg-header">
                <h1 className="pg-header-title">Try the <em>recommendations API</em></h1>
                <p className="pg-header-sub">
                    Select a scenario, fill in your farm details, and receive tailored agricultural
                    recommendations — no account required.
                </p>
            </div>

            <div className="pg-notice">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"
                     strokeLinecap="round" strokeLinejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                Limited to 5 requests per minute. For production use, obtain an API key from the admin panel.
            </div>

            {/* Tab switcher */}
            <div className="pg-tabs">
                <button
                    className={`pg-tab${activeTab === 'playground' ? ' pg-tab--active' : ''}`}
                    onClick={() => setActiveTab('playground')}
                >
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M5 3l14 9-14 9V3z"/>
                    </svg>
                    Playground
                </button>
                <button
                    className={`pg-tab${activeTab === 'history' ? ' pg-tab--active' : ''}`}
                    onClick={() => setActiveTab('history')}
                >
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    History
                </button>
            </div>

            {activeTab === 'history' && <HistoryPanel/>}

            {activeTab === 'playground' && <>

                {/* Step indicators */}
                <div className="pg-steps">
                    {Array.from({length: maxStep}, (_, i) => i + 1).map((n, idx) => (
                        <div key={n} style={{display: 'flex', alignItems: 'center'}}>
                            {idx > 0 && <div className="pg-step-connector"/>}
                            <div
                                className={`pg-step${step === n ? ' pg-step--active' : step > n ? ' pg-step--done' : ''}`}>
                                <div className="pg-step-num">
                                    {step > n
                                        ? <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                               stroke="currentColor" strokeWidth="3" strokeLinecap="round"
                                               strokeLinejoin="round">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                        : n}
                                </div>
                                <span className="pg-step-label">{STEP_LABELS[n]}</span>
                            </div>
                        </div>
                    ))}
                </div>

                {/* ── Step 1: Scenario ── */}
                {step === 1 && (
                    <div className="pg-card">
                        <h2 className="pg-card-title">What would you like to know?</h2>
                        <p className="pg-card-sub">Choose a recommendation type for your farm.</p>

                        <div className="scenario-grid">
                            {SCENARIOS.map((s) => (
                                <button key={s.id} type="button"
                                        className={`scenario-card${form.scenario === s.id ? ' scenario-card--active' : ''}`}
                                        onClick={() => setForm({scenario: s.id})}>
                                    <div className="scenario-card-icon">{s.icon}</div>
                                    <div className="scenario-card-title">{s.title}</div>
                                    <div className="scenario-card-desc">{s.desc}</div>
                                </button>
                            ))}
                        </div>

                        {form.scenario === 'IC' && (
                            <div className="pg-notice" style={{marginTop: '1.25rem'}}>
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="8" x2="12" y2="12"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                                Intercropping: 🌾 maize available for Nigeria only · 🥔 potato for Tanzania only.
                            </div>
                        )}

                        <div className="pg-actions">
                            <span/>
                            <button className="btn btn-primary" onClick={advance}>
                                Continue
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                                    <polyline points="9 18 15 12 9 6"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                )}

                {/* ── Step 2: Farm Details ── */}
                {step === 2 && (
                    <div className="pg-card">
                        <h2 className="pg-card-title">Farm details</h2>
                        <p className="pg-card-sub">Tell us about your farm location and size.</p>

                        <div className="pg-form-grid pg-form-grid--3">
                            <div className="pg-field pg-field--full">
                                <label htmlFor="country">Country</label>
                                <select id="country" value={form.country}
                                        onChange={(e) => handleCountryChange(e.target.value)}>
                                    <option value="">Select a country…</option>
                                    {countries.map((c) => (
                                        <option key={c.id} value={c.code}>{c.name}</option>
                                    ))}
                                </select>
                                {validationErrors.country &&
                                    <span className="pg-field-error">{validationErrors.country}</span>}
                                {form.scenario === 'IC' && form.country && COUNTRY_INTERCROP[form.country] && (
                                    <span className="pg-field-hint">
                                    {COUNTRY_INTERCROP[form.country] === 'MAIZE' ? '🌾 Maize' : '🥔 Potato'} intercropping will be computed
                                </span>
                                )}
                            </div>

                            <div className="pg-field">
                                <label htmlFor="fieldSize">Field size</label>
                                <input id="fieldSize" type="number" min="0.1" step="0.1" value={form.fieldSize}
                                       onChange={(e) => setForm({fieldSize: e.target.value})} placeholder="e.g. 2.5"/>
                                {validationErrors.fieldSize &&
                                    <span className="pg-field-error">{validationErrors.fieldSize}</span>}
                            </div>

                            <div className="pg-field">
                                <label htmlFor="areaUnit">Unit</label>
                                <select id="areaUnit" value={form.areaUnit}
                                        onChange={(e) => setForm({areaUnit: e.target.value})}>
                                    {AREA_UNITS.map((u) => <option key={u.value} value={u.value}>{u.label}</option>)}
                                </select>
                            </div>

                            <div className="pg-field">
                                <label htmlFor="lang">Language</label>
                                <select id="lang" value={form.lang} onChange={(e) => setForm({lang: e.target.value})}>
                                    {LANGUAGES.map((l) => <option key={l.value} value={l.value}>{l.label}</option>)}
                                </select>
                            </div>

                        </div>

                        <div className="pg-field pg-field--map">
                            <label>Farm location</label>
                            <MapPicker
                                lat={form.mapLat}
                                lng={form.mapLong}
                                country={form.country}
                                onChange={(lat, lng) => setForm({mapLat: String(lat), mapLong: String(lng)})}
                            />
                            {(validationErrors.mapLat || validationErrors.mapLong) && (
                                <span className="pg-field-error">
                                {validationErrors.mapLat || validationErrors.mapLong}
                            </span>
                            )}
                        </div>

                        <div className="pg-actions">
                            <button className="btn btn-ghost" onClick={back}>← Back</button>
                            <button className="btn btn-primary" onClick={advance}>
                                Continue
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                                    <polyline points="9 18 15 12 9 6"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                )}

                {/* ── Step 3: Crop & Yield ── */}
                {step === 3 && (
                    <div className="pg-card">
                        <h2 className="pg-card-title">Crop &amp; yield details</h2>
                        <p className="pg-card-sub">Help the model calibrate recommendations to your actual farm
                            conditions.</p>

                        <div className="pg-form-grid pg-form-grid--3">
                            <div className="pg-field">
                                <label htmlFor="fieldYield">Current cassava yield (t/ha)</label>
                                <input id="fieldYield" type="number" min="0" max="100" step="1"
                                       value={form.fieldYield}
                                       onChange={(e) => setForm({fieldYield: e.target.value})}
                                       placeholder="e.g. 10"/>
                                {validationErrors.fieldYield &&
                                    <span className="pg-field-error">{validationErrors.fieldYield}</span>}
                                <span className="pg-field-hint">Typical range: 5–30 t/ha</span>
                            </div>

                            <div className="pg-field">
                                <label htmlFor="soilQuality">
                                    Soil quality
                                    {form.scenario === 'IC' && intercropCrop === 'MAIZE' ? ' / maize performance' : ''}
                                    {' '}(0–5)
                                </label>
                                <input id="soilQuality" type="number" min="0" max="5" step="1"
                                       value={form.soilQuality}
                                       onChange={(e) => setForm({soilQuality: e.target.value})}
                                       placeholder="0 = poor · 5 = excellent"/>
                                {validationErrors.soilQuality &&
                                    <span className="pg-field-error">{validationErrors.soilQuality}</span>}
                            </div>

                            <div className="pg-field">
                                <label htmlFor="riskAttitude">Risk preference</label>
                                <select id="riskAttitude" value={form.riskAttitude}
                                        onChange={(e) => setForm({riskAttitude: e.target.value})}>
                                    <option value="0">Conservative (low risk)</option>
                                    <option value="1">Moderate</option>
                                    <option value="2">Ambitious (high risk)</option>
                                </select>
                            </div>

                            <div className="pg-field">
                                <label htmlFor="cassavaProduceType">Cassava produce type</label>
                                <select id="cassavaProduceType" value={form.cassavaProduceType}
                                        onChange={(e) => setForm({cassavaProduceType: e.target.value})}>
                                    {CASSAVA_PRODUCE_TYPES.map((p) => (
                                        <option key={p.value} value={p.value}>{p.label}</option>
                                    ))}
                                </select>
                                <span className="pg-field-hint">Affects cassava price defaults</span>
                            </div>

                            {/* IC with Maize (NG) */}
                            {form.scenario === 'IC' && intercropCrop === 'MAIZE' && (
                                <div className="pg-field">
                                    <label htmlFor="maizeProduceType">Maize produce type</label>
                                    <select id="maizeProduceType" value={form.maizeProduceType}
                                            onChange={(e) => setForm({maizeProduceType: e.target.value})}>
                                        {MAIZE_PRODUCE_TYPES.map((p) => (
                                            <option key={p.value} value={p.value}>{p.label}</option>
                                        ))}
                                    </select>
                                </div>
                            )}

                            {/* IC with Sweet Potato (TZ) */}
                            {form.scenario === 'IC' && intercropCrop === 'POTATO' && (
                                <div className="pg-field">
                                    <label htmlFor="sweetPotatoProduceType">Sweet potato produce type</label>
                                    <select id="sweetPotatoProduceType" value={form.sweetPotatoProduceType}
                                            onChange={(e) => setForm({sweetPotatoProduceType: e.target.value})}>
                                        {SWEET_POTATO_PRODUCE_TYPES.map((p) => (
                                            <option key={p.value} value={p.value}>{p.label}</option>
                                        ))}
                                    </select>
                                </div>
                            )}

                            {/* IC with no country selected yet */}
                            {form.scenario === 'IC' && !intercropCrop && (
                                <div className="pg-field" style={{gridColumn: '1/-1'}}>
                                <span className="pg-field-hint" style={{color: 'var(--color-warning, #b45309)'}}>
                                    ⚠ Select a supported country in the previous step (Nigeria or Tanzania) to configure intercropping options.
                                </span>
                                </div>
                            )}
                        </div>

                        <div className="pg-actions">
                            <button className="btn btn-ghost" onClick={back}>← Back</button>
                            {isLastStep
                                ? <SubmitButton submitting={submitting} onClick={submit}/>
                                : <button className="btn btn-primary" onClick={advance}>
                                    Continue
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                                        <polyline points="9 18 15 12 9 6"/>
                                    </svg>
                                </button>
                            }
                        </div>
                    </div>
                )}

                {/* ── Step 4: Fertilizers (when needed) ── */}
                {step === 4 && needsFertilizers(form.scenario) && (
                    <div className="pg-card">
                        <h2 className="pg-card-title">Fertilizer selection</h2>
                        <p className="pg-card-sub">Select the fertilizers available to you and enter their local
                            prices.</p>

                        {loadingFerts && (
                            <div className="fert-loading">
                                <div className="fert-spinner"/>
                                Loading fertilizers for {form.country}…
                            </div>
                        )}

                        {!loadingFerts && fertilizers.length === 0 && (
                            <p style={{color: 'var(--text-muted)', fontSize: '0.875rem'}}>
                                No fertilizers found for this country. The recommendation will use model defaults.
                            </p>
                        )}

                        {!loadingFerts && fertilizers.length > 0 && (
                            <div style={{overflowX: 'auto'}}>
                                <table className="fert-table">
                                    <thead>
                                    <tr>
                                        <th style={{width: 36}}>Use</th>
                                        <th>Fertilizer</th>
                                        <th>Type</th>
                                        <th style={{width: 80}}>Wt (kg)</th>
                                        <th>Price (local currency / bag)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {fertilizers.map((f) => (
                                        <tr key={f.key}>
                                            <td>
                                                <input type="checkbox" checked={f.selected}
                                                       onChange={(e) => updateFert(f.key, {selected: e.target.checked})}/>
                                            </td>
                                            <td style={{fontWeight: f.selected ? 600 : 400}}>{f.name}</td>
                                            <td style={{
                                                fontSize: '0.8125rem',
                                                color: 'var(--text-muted)'
                                            }}>{f.fertilizer_type}</td>
                                            <td>
                                                <input type="number" min="0" value={f.weight}
                                                       onChange={(e) => updateFert(f.key, {weight: +e.target.value})}/>
                                            </td>
                                            <td>
                                                <input type="number" min="0" disabled={!f.selected}
                                                       placeholder={f.selected ? 'e.g. 12000' : '—'}
                                                       value={f.selected ? (f.price || '') : ''}
                                                       onChange={(e) => updateFert(f.key, {price: +e.target.value})}
                                                       style={{opacity: f.selected ? 1 : 0.4}}/>
                                                {validationErrors[`price_${f.key}`] && (
                                                    <div className="pg-field-error" style={{marginTop: '0.25rem'}}>
                                                        {validationErrors[`price_${f.key}`]}
                                                    </div>
                                                )}
                                            </td>
                                        </tr>
                                    ))}
                                    </tbody>
                                </table>
                                {validationErrors.fertilizers && (
                                    <p className="pg-field-error" style={{marginTop: '0.5rem'}}>
                                        {validationErrors.fertilizers}
                                    </p>
                                )}
                            </div>
                        )}

                        <div className="pg-actions">
                            <button className="btn btn-ghost" onClick={back}>← Back</button>
                            {isLastStep
                                ? <SubmitButton submitting={submitting} onClick={submit}/>
                                : <button className="btn btn-primary" onClick={advance}>
                                    Continue
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                                        <polyline points="9 18 15 12 9 6"/>
                                    </svg>
                                </button>
                            }
                        </div>
                    </div>
                )}

                {/* ── Step 4 or 5: Dates ── */}
                {((step === 4 && !needsFertilizers(form.scenario)) || step === 5) && (
                    <div className="pg-card">
                        <h2 className="pg-card-title">Planting dates</h2>
                        <p className="pg-card-sub">
                            {form.scenario === 'SPHS'
                                ? 'Set your target dates and a flexibility window — the model will find the optimal timing within that range.'
                                : 'When are you planning to plant and harvest?'}
                        </p>

                        <div className="pg-form-grid">
                            <div className="pg-field">
                                <label htmlFor="plantingDate">Planting date</label>
                                <input id="plantingDate" type="date" value={form.plantingDate}
                                       min={plantingMin}
                                       onChange={(e) => {
                                           const pd = e.target.value
                                           const minH = minHarvestDate(pd)
                                           const maxH = maxHarvestDate(pd)
                                           setForm({
                                               plantingDate: pd,
                                               ...(form.harvestDate && form.harvestDate < minH ? {harvestDate: minH} : {}),
                                               ...(form.harvestDate && form.harvestDate > maxH ? {harvestDate: maxH} : {}),
                                           })
                                       }}/>
                                {validationErrors.plantingDate &&
                                    <span className="pg-field-error">{validationErrors.plantingDate}</span>}
                                <span className="pg-field-hint">No earlier than {plantingMin}</span>
                            </div>
                            <div className="pg-field">
                                <label htmlFor="harvestDate">Expected harvest date</label>
                                <input id="harvestDate" type="date" value={form.harvestDate}
                                       min={minHarvestDate(form.plantingDate)}
                                       max={maxHarvestDate(form.plantingDate)}
                                       onChange={(e) => setForm({harvestDate: e.target.value})}/>
                                {validationErrors.harvestDate &&
                                    <span className="pg-field-error">{validationErrors.harvestDate}</span>}
                                <span className="pg-field-hint">~7.8–15 months after planting date</span>
                            </div>
                            <div className="pg-field">
                                <label htmlFor="plantingDateWindow">Planting flexibility (months)</label>
                                <select id="plantingDateWindow" value={form.plantingDateWindow}
                                        onChange={(e) => setForm({plantingDateWindow: e.target.value})}>
                                    <option value="0">0 — exact date</option>
                                    <option value="1">1 month</option>
                                    <option value="2">2 months</option>
                                </select>
                                {validationErrors.plantingDateWindow &&
                                    <span className="pg-field-error">{validationErrors.plantingDateWindow}</span>}
                                <span className="pg-field-hint">How far before/after the planting date to explore</span>
                            </div>
                            <div className="pg-field">
                                <label htmlFor="harvestDateWindow">Harvest flexibility (months)</label>
                                <select id="harvestDateWindow" value={form.harvestDateWindow}
                                        onChange={(e) => setForm({harvestDateWindow: e.target.value})}>
                                    <option value="0">0 — exact date</option>
                                    <option value="1">1 month</option>
                                    <option value="2">2 months</option>
                                </select>
                                {validationErrors.harvestDateWindow &&
                                    <span className="pg-field-error">{validationErrors.harvestDateWindow}</span>}
                                <span className="pg-field-hint">How far before/after the harvest date to explore</span>
                            </div>
                        </div>

                        <div className="pg-actions">
                            <button className="btn btn-ghost" onClick={back}>← Back</button>
                            <SubmitButton submitting={submitting} onClick={submit}/>
                        </div>
                    </div>
                )}

                {/* ── Results ── */}
                {(result || apiError) && (
                    <div ref={resultRef}>
                        {apiError && <div className="pg-error">✕ {apiError}</div>}

                        {result && (
                            <RecommendationResult result={result} country={form.country}/>
                        )}

                        <div style={{marginTop: '1.5rem', display: 'flex', gap: '0.75rem', flexWrap: 'wrap'}}>
                            <button className="btn btn-outline" onClick={() => {
                                setResult(null);
                                setApiError(null);
                                setStep(1)
                                setForm({
                                    scenario: 'FR', intercropCrop: 'MAIZE', country: '',
                                    fieldSize: '1', areaUnit: 'ha', mapLat: '', mapLong: '', lang: 'en',
                                    fieldYield: '10', soilQuality: '3', riskAttitude: '0',
                                    cassavaProduceType: 'roots', maizeProduceType: 'fresh_cob',
                                    sweetPotatoProduceType: 'tubers', plantingDate: '', harvestDate: '',
                                    plantingDateWindow: '0', harvestDateWindow: '0',
                                })
                                setFertilizers([])
                            }}>
                                ↺ Start over
                            </button>
                            <button className="btn btn-terra" onClick={submit} disabled={submitting}>
                                {submitting ? 'Sending…' : '↻ Run again'}
                            </button>
                        </div>
                    </div>
                )}

            </> /* end playground tab */}
        </div>
    )
}

function SubmitButton({submitting, onClick}: { submitting: boolean; onClick: () => void }) {
    return (
        <button className="btn btn-terra" onClick={onClick} disabled={submitting}>
            {submitting
                ? <>
                    <span style={{
                        width: 14,
                        height: 14,
                        border: '2px solid rgba(255,255,255,0.3)',
                        borderTopColor: '#fff',
                        borderRadius: '50%',
                        display: 'inline-block',
                        animation: 'spin 0.7s linear infinite'
                    }}/>
                    Computing…
                </>
                : <>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M5 3l14 9-14 9V3z"/>
                    </svg>
                    Get Recommendations
                </>
            }
        </button>
    )
}
