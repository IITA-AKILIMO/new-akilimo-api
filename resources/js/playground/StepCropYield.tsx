import SubmitButton from './SubmitButton'
import {CASSAVA_PRODUCE_TYPES, COUNTRY_INTERCROP, MAIZE_PRODUCE_TYPES, SWEET_POTATO_PRODUCE_TYPES} from './constants'
import type {FormState} from './types'

interface Props {
    form: FormState
    errors: Record<string, string>
    isLastStep: boolean
    submitting: boolean
    onChange: (patch: Partial<FormState>) => void
    onBack: () => void
    onNext: () => void
    onSubmit: () => void
}

export default function StepCropYield({form, errors, isLastStep, submitting, onChange, onBack, onNext, onSubmit}: Readonly<Props>) {
    const intercropCrop = COUNTRY_INTERCROP[form.country]

    return (
        <div className="pg-card">
            <h2 className="pg-card-title">Crop &amp; yield details</h2>
            <p className="pg-card-sub">Help the model calibrate recommendations to your actual farm conditions.</p>

            <div className="pg-form-grid pg-form-grid--3">
                <div className="pg-field">
                    <label htmlFor="fieldYield">Current cassava yield (t/ha)</label>
                    <input id="fieldYield" type="number" min="0" max="100" step="1"
                           value={form.fieldYield}
                           onChange={(e) => onChange({fieldYield: e.target.value})}
                           placeholder="e.g. 10"/>
                    {errors.fieldYield && <span className="pg-field-error">{errors.fieldYield}</span>}
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
                           onChange={(e) => onChange({soilQuality: e.target.value})}
                           placeholder="0 = poor · 5 = excellent"/>
                    {errors.soilQuality && <span className="pg-field-error">{errors.soilQuality}</span>}
                </div>

                <div className="pg-field">
                    <label htmlFor="riskAttitude">Risk preference</label>
                    <select id="riskAttitude" value={form.riskAttitude}
                            onChange={(e) => onChange({riskAttitude: e.target.value})}>
                        <option value="0">Conservative (low risk)</option>
                        <option value="1">Moderate</option>
                        <option value="2">Ambitious (high risk)</option>
                    </select>
                </div>

                <div className="pg-field">
                    <label htmlFor="cassavaProduceType">Cassava produce type</label>
                    <select id="cassavaProduceType" value={form.cassavaProduceType}
                            onChange={(e) => onChange({cassavaProduceType: e.target.value})}>
                        {CASSAVA_PRODUCE_TYPES.map((p) => (
                            <option key={p.value} value={p.value}>{p.label}</option>
                        ))}
                    </select>
                    <span className="pg-field-hint">Affects cassava price defaults</span>
                </div>

                {form.scenario === 'IC' && intercropCrop === 'MAIZE' && (
                    <div className="pg-field">
                        <label htmlFor="maizeProduceType">Maize produce type</label>
                        <select id="maizeProduceType" value={form.maizeProduceType}
                                onChange={(e) => onChange({maizeProduceType: e.target.value})}>
                            {MAIZE_PRODUCE_TYPES.map((p) => (
                                <option key={p.value} value={p.value}>{p.label}</option>
                            ))}
                        </select>
                    </div>
                )}

                {form.scenario === 'IC' && intercropCrop === 'POTATO' && (
                    <div className="pg-field">
                        <label htmlFor="sweetPotatoProduceType">Sweet potato produce type</label>
                        <select id="sweetPotatoProduceType" value={form.sweetPotatoProduceType}
                                onChange={(e) => onChange({sweetPotatoProduceType: e.target.value})}>
                            {SWEET_POTATO_PRODUCE_TYPES.map((p) => (
                                <option key={p.value} value={p.value}>{p.label}</option>
                            ))}
                        </select>
                    </div>
                )}

                {form.scenario === 'IC' && !intercropCrop && (
                    <div className="pg-field" style={{gridColumn: '1/-1'}}>
                        <span className="pg-field-hint" style={{color: 'var(--color-warning, #b45309)'}}>
                            ⚠ Select a supported country in the previous step (Nigeria or Tanzania) to configure intercropping options.
                        </span>
                    </div>
                )}
            </div>

            <div className="pg-actions">
                <button className="btn btn-ghost" onClick={onBack}>← Back</button>
                {isLastStep
                    ? <SubmitButton submitting={submitting} onClick={onSubmit}/>
                    : <button className="btn btn-primary" onClick={onNext}>
                        Continue
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </button>
                }
            </div>
        </div>
    )
}
