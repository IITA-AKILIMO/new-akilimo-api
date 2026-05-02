import {needsFertilizers, totalSteps} from './usePlaygroundForm'
import type {Step} from './types'

interface Props {
    step: Step
    scenario: string
}

export default function Stepper({step, scenario}: Readonly<Props>) {
    const maxStep = totalSteps(scenario)

    const STEP_LABELS: Record<number, string> = {
        1: 'Scenario',
        2: 'Farm Details',
        3: 'Crop & Yield',
        4: needsFertilizers(scenario) ? 'Fertilizers' : 'Dates',
        5: 'Dates',
    }

    return (
        <div className="pg-steps">
            {Array.from({length: maxStep}, (_, i) => i + 1).map((n, idx) => (
                <div key={n} style={{display: 'flex', alignItems: 'center'}}>
                    {idx > 0 && <div className="pg-step-connector"/>}
                    <div className={`pg-step${step === n ? ' pg-step--active' : step > n ? ' pg-step--done' : ''}`}>
                        <div className="pg-step-num">
                            {step > n
                                ? <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                       stroke="currentColor" strokeWidth="3" strokeLinecap="round"
                                       strokeLinejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                : n}
                        </div>
                        <span className="pg-step-label">{STEP_LABELS[n]}</span>
                    </div>
                </div>
            ))}
        </div>
    )
}
