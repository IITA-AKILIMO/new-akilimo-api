import MapPicker from './MapPicker'
import {AREA_UNITS, COUNTRY_INTERCROP, LANGUAGES} from './constants'
import type {Country, FormState} from './types'

interface Props {
    form: FormState
    countries: Country[]
    errors: Record<string, string>
    onCountryChange: (code: string) => void
    onChange: (patch: Partial<FormState>) => void
    onBack: () => void
    onNext: () => void
}

export default function StepFarm({form, countries, errors, onCountryChange, onChange, onBack, onNext}: Readonly<Props>) {
    return (
        <div className="pg-card">
            <h2 className="pg-card-title">Farm details</h2>
            <p className="pg-card-sub">Tell us about your farm location and size.</p>

            <div className="pg-form-grid pg-form-grid--3">
                <div className="pg-field pg-field--full">
                    <label htmlFor="country">Country</label>
                    <select id="country" value={form.country} onChange={(e) => onCountryChange(e.target.value)}>
                        <option value="">Select a country…</option>
                        {countries.map((c) => (
                            <option key={c.id} value={c.code}>{c.name}</option>
                        ))}
                    </select>
                    {errors.country && <span className="pg-field-error">{errors.country}</span>}
                    {form.scenario === 'IC' && form.country && COUNTRY_INTERCROP[form.country] && (
                        <span className="pg-field-hint">
                            {COUNTRY_INTERCROP[form.country] === 'MAIZE' ? '🌾 Maize' : '🥔 Potato'} intercropping will be computed
                        </span>
                    )}
                </div>

                <div className="pg-field">
                    <label htmlFor="fieldSize">Field size</label>
                    <input id="fieldSize" type="number" min="0.1" step="0.1" value={form.fieldSize}
                           onChange={(e) => onChange({fieldSize: e.target.value})} placeholder="e.g. 2.5"/>
                    {errors.fieldSize && <span className="pg-field-error">{errors.fieldSize}</span>}
                </div>

                <div className="pg-field">
                    <label htmlFor="areaUnit">Unit</label>
                    <select id="areaUnit" value={form.areaUnit} onChange={(e) => onChange({areaUnit: e.target.value})}>
                        {AREA_UNITS.map((u) => <option key={u.value} value={u.value}>{u.label}</option>)}
                    </select>
                </div>

                <div className="pg-field">
                    <label htmlFor="lang">Language</label>
                    <select id="lang" value={form.lang} onChange={(e) => onChange({lang: e.target.value})}>
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
                    onChange={(lat, lng) => onChange({mapLat: String(lat), mapLong: String(lng)})}
                />
                {(errors.mapLat || errors.mapLong) && (
                    <span className="pg-field-error">{errors.mapLat || errors.mapLong}</span>
                )}
            </div>

            <div className="pg-actions">
                <button className="btn btn-ghost" onClick={onBack}>← Back</button>
                <button className="btn btn-primary" onClick={onNext}>
                    Continue
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </button>
            </div>
        </div>
    )
}
