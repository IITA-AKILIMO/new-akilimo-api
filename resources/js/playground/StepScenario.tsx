import {SCENARIOS} from './constants'
import type {Scenario} from './types'

interface Props {
    scenario: Scenario
    onSelect: (s: Scenario) => void
    onNext: () => void
}

export default function StepScenario({scenario, onSelect, onNext}: Readonly<Props>) {
    return (
        <div className="pg-card">
            <h2 className="pg-card-title">What would you like to know?</h2>
            <p className="pg-card-sub">Choose a recommendation type for your farm.</p>

            <div className="scenario-grid">
                {SCENARIOS.map((s) => (
                    <button key={s.id} type="button"
                            className={`scenario-card${scenario === s.id ? ' scenario-card--active' : ''}`}
                            onClick={() => onSelect(s.id)}>
                        <div className="scenario-card-icon">{s.icon}</div>
                        <div className="scenario-card-title">{s.title}</div>
                        <div className="scenario-card-desc">{s.desc}</div>
                    </button>
                ))}
            </div>

            {scenario === 'IC' && (
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
