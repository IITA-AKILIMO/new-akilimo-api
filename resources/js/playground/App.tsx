import {useEffect, useReducer, useRef, useState} from 'react'

// ── Types ─────────────────────────────────────────────────────────────────────

interface Country {
    id: number;
    code: string;
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
type Step = 1 | 2 | 3 | 4

interface FormState {
    scenario: Scenario
    intercropCrop: IntercropCrop
    country: string
    fieldSize: string
    areaUnit: string
    mapLat: string
    mapLong: string
    lang: string
    plantingDate: string
    harvestDate: string
}

// ── Constants ─────────────────────────────────────────────────────────────────

const SCENARIOS: { id: Scenario; icon: string; title: string; desc: string }[] = [
    {id: 'FR',       icon: '🌱', title: 'Fertilizer Recommendations', desc: 'Which fertilizers to apply and in what quantities'},
    {id: 'IC',       icon: '🌽', title: 'Intercropping',              desc: 'Should I grow cassava alongside maize or potato?'},
    {id: 'PP',       icon: '🌿', title: 'Planting Practices',         desc: 'Best practices for planting based on your location'},
    {id: 'SPHS',     icon: '📅', title: 'Planting Schedule',          desc: 'Optimal planting and harvest timing'},
    {id: 'COMPLETE', icon: '🗺️', title: 'Complete Farm Plan',         desc: 'All recommendations in one response'},
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

const COUNTRY_COORDS: Record<string, [number, number]> = {
    NG: [9.082, 8.675],
    KE: [-0.024, 37.906],
    TZ: [-6.369, 34.889],
    RW: [-1.940, 29.874],
    GH: [7.946, -1.023],
    BI: [-3.373, 29.919],
}

// Intercropping availability is country-specific
const COUNTRY_INTERCROP: Partial<Record<string, IntercropCrop>> = {
    NG: 'MAIZE',
    TZ: 'POTATO',
}

function scenarioToFlags(s: Scenario, intercropCrop: IntercropCrop) {
    return {
        fertilizer_rec:            s === 'FR'   || s === 'COMPLETE',
        planting_practices_rec:    s === 'PP'   || s === 'COMPLETE',
        scheduled_planting_rec:    s === 'SPHS' || s === 'COMPLETE',
        scheduled_harvest_rec:     s === 'SPHS' || s === 'COMPLETE',
        inter_cropping_maize_rec:  (s === 'IC' && intercropCrop === 'MAIZE')  || s === 'COMPLETE',
        inter_cropping_potato_rec: (s === 'IC' && intercropCrop === 'POTATO') || s === 'COMPLETE',
    }
}

function needsFertilizers(s: Scenario) {
    return s === 'FR' || s === 'COMPLETE'
}

function needsDates(s: Scenario) {
    return s === 'SPHS' || s === 'PP' || s === 'COMPLETE'
}

function totalSteps(s: Scenario): Step {
    const hasFert = needsFertilizers(s)
    const hasDates = needsDates(s)
    if (hasFert && hasDates) return 4
    if (hasFert || hasDates) return 3
    return 2
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
            plantingDate: '',
            harvestDate: '',
        },
    )

    const [countries, setCountries] = useState<Country[]>([])
    const [fertilizers, setFertilizers] = useState<FertilizerEntry[]>([])
    const [loadingFerts, setLoadingFerts] = useState(false)
    const [submitting, setSubmitting] = useState(false)
    const [result, setResult] = useState<unknown>(null)
    const [apiError, setApiError] = useState<string | null>(null)
    const [validationErrors, setValidationErrors] = useState<Record<string, string>>({})
    const resultRef = useRef<HTMLDivElement>(null)

    // Load countries once
    useEffect(() => {
        fetch('/api/v1/countries')
            .then((r) => r.json())
            .then((d) => setCountries(d.data ?? d))
            .catch(() => {
            })
    }, [])

    // Load fertilizers when country or crop changes (Step 3)
    useEffect(() => {
        if (!form.country || !needsFertilizers(form.scenario)) return
        setLoadingFerts(true)
        setFertilizers([])
        const url = `/api/v1/fertilizers/country/${form.country}?per_page=50`
        fetch(url)
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

    // Auto-fill coordinates from country default
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
        setFertilizers((prev) =>
            prev.map((f) => (f.key === key ? {...f, ...patch} : f)),
        )
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
        if (s === 3 && needsFertilizers(form.scenario)) {
            if (!fertilizers.some((f) => f.selected))
                errs.fertilizers = 'Select at least one fertilizer'
            fertilizers.filter((f) => f.selected).forEach((f) => {
                if (!f.price || f.price <= 0)
                    errs[`price_${f.key}`] = 'Enter a price for this fertilizer'
            })
        }
        if (needsDates(form.scenario)) {
            const dateStep = needsFertilizers(form.scenario) ? 4 : 3
            if (s === dateStep) {
                if (!form.plantingDate) errs.plantingDate = 'Enter a planting date'
                if (!form.harvestDate) errs.harvestDate = 'Enter a harvest date'
                if (form.plantingDate && form.harvestDate && form.harvestDate <= form.plantingDate)
                    errs.harvestDate = 'Harvest date must be after planting date'
            }
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
            planting_date: form.plantingDate || new Date().toISOString().slice(0, 10),
            harvest_date: form.harvestDate || new Date(Date.now() + 180 * 86400000).toISOString().slice(0, 10),
            fertilizer_list: fertList,
            ...flags,
        }

        const csrfToken = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? ''

        try {
            const res = await fetch('/playground/compute', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
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

    // ── Derived ────────────────────────────────────────────────────────────────
    const maxStep = totalSteps(form.scenario)
    const isLastStep = step === maxStep

    const STEP_LABELS: Record<number, string> = {
        1: 'Scenario',
        2: 'Farm Details',
        3: needsFertilizers(form.scenario) ? 'Fertilizers' : 'Dates',
        4: 'Dates',
    }

    // ── Render ─────────────────────────────────────────────────────────────────
    return (
        <div className="pg-wrap">
            {/* Header */}
            <div className="pg-header">
                <h1 className="pg-header-title">
                    Try the <em>recommendations API</em>
                </h1>
                <p className="pg-header-sub">
                    Select a scenario, fill in your farm details, and receive tailored agricultural
                    recommendations — no account required.
                </p>
            </div>

            {/* Rate-limit notice */}
            <div className="pg-notice">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"
                     strokeLinecap="round" strokeLinejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                This playground is limited to 5 requests per minute. For production use, obtain an API key from the
                admin panel.
            </div>

            {/* Step indicators */}
            <div className="pg-steps">
                {Array.from({length: maxStep}, (_, i) => i + 1).map((n, idx) => (
                    <div key={n} style={{display: 'flex', alignItems: 'center'}}>
                        {idx > 0 && <div className="pg-step-connector"/>}
                        <div className={`pg-step${step === n ? ' pg-step--active' : step > n ? ' pg-step--done' : ''}`}>
                            <div className="pg-step-num">
                                {step > n
                                    ? <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                           strokeWidth="3" strokeLinecap="round" strokeLinejoin="round">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                    : n
                                }
                            </div>
                            <span className="pg-step-label">{STEP_LABELS[n]}</span>
                        </div>
                    </div>
                ))}
            </div>

            {/* ── Step 1: Scenario + Crop ── */}
            {step === 1 && (
                <div className="pg-card">
                    <h2 className="pg-card-title">What would you like to know?</h2>
                    <p className="pg-card-sub">Choose a recommendation type for your farm.</p>

                    <div className="scenario-grid">
                        {SCENARIOS.map((s) => (
                            <button
                                key={s.id}
                                type="button"
                                className={`scenario-card${form.scenario === s.id ? ' scenario-card--active' : ''}`}
                                onClick={() => setForm({scenario: s.id})}
                            >
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
                            Intercropping availability is country-specific: 🌾 maize for Nigeria, 🥔 potato for Tanzania.
                            Select your country in the next step.
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
                            <select
                                id="country"
                                value={form.country}
                                onChange={(e) => handleCountryChange(e.target.value)}
                            >
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
                            <input
                                id="fieldSize"
                                type="number"
                                min="0.1"
                                step="0.1"
                                value={form.fieldSize}
                                onChange={(e) => setForm({fieldSize: e.target.value})}
                                placeholder="e.g. 2.5"
                            />
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

                        <div className="pg-field">
                            <label htmlFor="mapLat">Latitude</label>
                            <input
                                id="mapLat"
                                type="number"
                                step="0.0001"
                                value={form.mapLat}
                                onChange={(e) => setForm({mapLat: e.target.value})}
                                placeholder="e.g. 9.082"
                            />
                            {validationErrors.mapLat &&
                                <span className="pg-field-error">{validationErrors.mapLat}</span>}
                        </div>

                        <div className="pg-field">
                            <label htmlFor="mapLong">Longitude</label>
                            <input
                                id="mapLong"
                                type="number"
                                step="0.0001"
                                value={form.mapLong}
                                onChange={(e) => setForm({mapLong: e.target.value})}
                                placeholder="e.g. 8.675"
                            />
                            {validationErrors.mapLong &&
                                <span className="pg-field-error">{validationErrors.mapLong}</span>}
                            <span className="pg-field-hint">Auto-filled from country — adjust if needed</span>
                        </div>
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

            {/* ── Step 3a: Fertilizers (when needed) ── */}
            {step === 3 && needsFertilizers(form.scenario) && (
                <div className="pg-card">
                    <h2 className="pg-card-title">Fertilizer selection</h2>
                    <p className="pg-card-sub">Select the fertilizers available to you and enter their local prices.</p>

                    {loadingFerts && (
                        <div className="fert-loading">
                            <div className="fert-spinner"/>
                            Loading fertilizers for {form.country}…
                        </div>
                    )}

                    {!loadingFerts && fertilizers.length === 0 && (
                        <p style={{color: 'var(--text-muted)', fontSize: '0.875rem'}}>
                            No fertilizers found for this country and crop combination.
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
                                    <th style={{width: 80}}>Weight (kg)</th>
                                    <th>Price (local currency / bag)</th>
                                </tr>
                                </thead>
                                <tbody>
                                {fertilizers.map((f) => (
                                    <tr key={f.key}>
                                        <td>
                                            <input
                                                type="checkbox"
                                                checked={f.selected}
                                                onChange={(e) => updateFert(f.key, {selected: e.target.checked})}
                                            />
                                        </td>
                                        <td style={{fontWeight: f.selected ? 600 : 400}}>{f.name}</td>
                                        <td style={{
                                            fontSize: '0.8125rem',
                                            color: 'var(--text-muted)'
                                        }}>{f.fertilizer_type}</td>
                                        <td>
                                            <input
                                                type="number"
                                                min="0"
                                                value={f.weight}
                                                onChange={(e) => updateFert(f.key, {weight: +e.target.value})}
                                            />
                                        </td>
                                        <td>
                                            <input
                                                type="number"
                                                min="0"
                                                disabled={!f.selected}
                                                placeholder={f.selected ? 'e.g. 12000' : '—'}
                                                value={f.selected ? (f.price || '') : ''}
                                                onChange={(e) => updateFert(f.key, {price: +e.target.value})}
                                                style={{opacity: f.selected ? 1 : 0.4}}
                                            />
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
                                <p className="pg-field-error"
                                   style={{marginTop: '0.5rem'}}>{validationErrors.fertilizers}</p>
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

            {/* ── Step 3b or 4: Dates ── */}
            {((step === 3 && !needsFertilizers(form.scenario)) || step === 4) && needsDates(form.scenario) && (
                <div className="pg-card">
                    <h2 className="pg-card-title">Planting dates</h2>
                    <p className="pg-card-sub">When are you planning to plant and harvest?</p>

                    <div className="pg-form-grid">
                        <div className="pg-field">
                            <label htmlFor="plantingDate">Planting date</label>
                            <input
                                id="plantingDate"
                                type="date"
                                value={form.plantingDate}
                                onChange={(e) => setForm({plantingDate: e.target.value})}
                            />
                            {validationErrors.plantingDate &&
                                <span className="pg-field-error">{validationErrors.plantingDate}</span>}
                        </div>
                        <div className="pg-field">
                            <label htmlFor="harvestDate">Expected harvest date</label>
                            <input
                                id="harvestDate"
                                type="date"
                                value={form.harvestDate}
                                onChange={(e) => setForm({harvestDate: e.target.value})}
                            />
                            {validationErrors.harvestDate &&
                                <span className="pg-field-error">{validationErrors.harvestDate}</span>}
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
                    <div className="pg-result-header">
                        <div>
                            <h2 style={{
                                fontFamily: 'var(--font-display, serif)',
                                fontSize: '1.25rem',
                                color: 'var(--text-primary)',
                                marginBottom: '0.125rem'
                            }}>
                                Recommendation result
                            </h2>
                            {result && (
                                <span className="pg-result-meta">
                                    request_id: {(result as Record<string, unknown>).request_id as string}
                                </span>
                            )}
                        </div>
                        <div className={`pg-result-status pg-result-status--${apiError ? 'error' : 'success'}`}>
                            {apiError ? '✕ Error' : '✓ Success'}
                        </div>
                    </div>

                    {apiError && <div className="pg-error">{apiError}</div>}

                    {result && (
                        <div className="pg-json">
                            <div className="pg-json-header">
                                <div className="pg-json-dot" style={{background: '#ff5f57'}}/>
                                <div className="pg-json-dot" style={{background: '#febc2e'}}/>
                                <div className="pg-json-dot" style={{background: '#28c840'}}/>
                            </div>
                            <pre>{JSON.stringify(result, null, 2)}</pre>
                        </div>
                    )}

                    <div style={{marginTop: '1.25rem', display: 'flex', gap: '0.75rem', flexWrap: 'wrap'}}>
                        <button
                            className="btn btn-outline"
                            onClick={() => {
                                setResult(null);
                                setApiError(null);
                                setStep(1);
                                setForm({
                                    scenario: 'FR',
                                    intercropCrop: 'MAIZE',
                                    country: '',
                                    fieldSize: '1',
                                    areaUnit: 'ha',
                                    mapLat: '',
                                    mapLong: '',
                                    lang: 'en',
                                    plantingDate: '',
                                    harvestDate: ''
                                });
                                setFertilizers([])
                            }}
                        >
                            ↺ Start over
                        </button>
                        <button
                            className="btn btn-terra"
                            onClick={submit}
                            disabled={submitting}
                        >
                            {submitting ? 'Sending…' : '↻ Run again'}
                        </button>
                    </div>
                </div>
            )}
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
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"
                         strokeLinecap="round" strokeLinejoin="round">
                        <path d="M5 3l14 9-14 9V3z"/>
                    </svg>
                    Get Recommendations
                </>
            }
        </button>
    )
}
