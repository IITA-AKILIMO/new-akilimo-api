import SubmitButton from './SubmitButton'
import {minHarvestDate, maxHarvestDate, plantingMin} from './usePlaygroundForm'
import type {FormState} from './types'

interface Props {
    form: FormState
    errors: Record<string, string>
    submitting: boolean
    onChange: (patch: Partial<FormState>) => void
    onBack: () => void
    onSubmit: () => void
}

export default function StepDates({form, errors, submitting, onChange, onBack, onSubmit}: Readonly<Props>) {
    function handlePlantingChange(pd: string) {
        const minH = minHarvestDate(pd)
        const maxH = maxHarvestDate(pd)
        onChange({
            plantingDate: pd,
            ...(form.harvestDate && form.harvestDate < minH ? {harvestDate: minH} : {}),
            ...(form.harvestDate && form.harvestDate > maxH ? {harvestDate: maxH} : {}),
        })
    }

    return (
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
                           onChange={(e) => handlePlantingChange(e.target.value)}/>
                    {errors.plantingDate && <span className="pg-field-error">{errors.plantingDate}</span>}
                    <span className="pg-field-hint">No earlier than {plantingMin}</span>
                </div>

                <div className="pg-field">
                    <label htmlFor="harvestDate">Expected harvest date</label>
                    <input id="harvestDate" type="date" value={form.harvestDate}
                           min={minHarvestDate(form.plantingDate)}
                           max={maxHarvestDate(form.plantingDate)}
                           onChange={(e) => onChange({harvestDate: e.target.value})}/>
                    {errors.harvestDate && <span className="pg-field-error">{errors.harvestDate}</span>}
                    <span className="pg-field-hint">~7.8–15 months after planting date</span>
                </div>

                <div className="pg-field">
                    <label htmlFor="plantingDateWindow">Planting flexibility (months)</label>
                    <select id="plantingDateWindow" value={form.plantingDateWindow}
                            onChange={(e) => onChange({plantingDateWindow: e.target.value})}>
                        <option value="0">0 — exact date</option>
                        <option value="1">1 month</option>
                        <option value="2">2 months</option>
                    </select>
                    {errors.plantingDateWindow && <span className="pg-field-error">{errors.plantingDateWindow}</span>}
                    <span className="pg-field-hint">How far before/after the planting date to explore</span>
                </div>

                <div className="pg-field">
                    <label htmlFor="harvestDateWindow">Harvest flexibility (months)</label>
                    <select id="harvestDateWindow" value={form.harvestDateWindow}
                            onChange={(e) => onChange({harvestDateWindow: e.target.value})}>
                        <option value="0">0 — exact date</option>
                        <option value="1">1 month</option>
                        <option value="2">2 months</option>
                    </select>
                    {errors.harvestDateWindow && <span className="pg-field-error">{errors.harvestDateWindow}</span>}
                    <span className="pg-field-hint">How far before/after the harvest date to explore</span>
                </div>
            </div>

            <div className="pg-actions">
                <button className="btn btn-ghost" onClick={onBack}>← Back</button>
                <SubmitButton submitting={submitting} onClick={onSubmit}/>
            </div>
        </div>
    )
}
