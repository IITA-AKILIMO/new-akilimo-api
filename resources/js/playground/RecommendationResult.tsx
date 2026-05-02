import {useState} from 'react'

// ── Response types ────────────────────────────────────────────────────────────

export interface ApiResponse {
    request_id: string
    status: string
    version: string
    data: RecResult
}

interface RecResult {
    rec_type: string
    recommendation: string
    data: FRData | PPRow[] | SPRow[] | unknown
    fertilizer_rates: FertilizerRate[]
}

interface FRData {
    lat: number; lon: number; plDate: string
    N: number; P: number; K: number
    WLY: number; CurrentY: number; TargetY: number
    TC: number; NR: number
}

interface PPRow {
    ploughing: boolean; ridging: boolean; harrowing?: boolean
    method_ploughing: string; method_ridging: string
    TC: number; NR: number; dNR: number; CP: boolean
}

interface SPRow {
    PD: string; HD: string
    rPWnr: number; rHWnr: number
    GR: number; dGR: number; CP: boolean; rootUP: number
}

interface FertilizerRate {
    type: string
    rate: number
}

// ── Helpers ───────────────────────────────────────────────────────────────────

const COUNTRY_CURRENCY: Record<string, string> = {
    NG: 'NGN', TZ: 'TZS', KE: 'KES', RW: 'RWF', GH: 'GHS', BI: 'BIF',
}

function fmt(value: number, country: string): string {
    const currency = COUNTRY_CURRENCY[country]
    if (!currency) return value.toLocaleString(undefined, {maximumFractionDigits: 0})
    try {
        return new Intl.NumberFormat('en', {
            style: 'currency', currency,
            maximumFractionDigits: 0, minimumFractionDigits: 0,
        }).format(value)
    } catch {
        return value.toLocaleString(undefined, {maximumFractionDigits: 0})
    }
}

function fmtDate(d: string): string {
    try {
        return new Date(d + 'T00:00:00').toLocaleDateString('en', {
            day: 'numeric', month: 'short', year: 'numeric',
        })
    } catch {
        return d
    }
}

function pctChange(from: number, to: number): {value: string; positive: boolean} | null {
    if (!from || from === 0) return null
    const p = Math.round(((to - from) / from) * 100)
    return {value: p >= 0 ? `+${p}%` : `${p}%`, positive: p >= 0}
}

function weekOffset(w: number): string {
    if (w === 0) return 'as requested'
    return w > 0 ? `${w} wk later` : `${Math.abs(w)} wk earlier`
}

function methodLabel(m: string): string {
    if (!m || m === 'N/A' || m === 'NA') return '—'
    return m.charAt(0).toUpperCase() + m.slice(1).toLowerCase()
}

// ── Main export ───────────────────────────────────────────────────────────────

export default function RecommendationResult({
    result, country,
}: {result: ApiResponse; country: string}) {
    const {rec_type, recommendation, data, fertilizer_rates} = result.data
    const [showJson, setShowJson] = useState(false)

    const REC_META: Record<string, {icon: string; label: string; cls: string}> = {
        FR: {icon: '🌱', label: 'Fertilizer Recommendation', cls: 'rec-badge--fr'},
        IC: {icon: '🌽', label: 'Intercropping Recommendation', cls: 'rec-badge--ic'},
        PP: {icon: '🌿', label: 'Planting Practices', cls: 'rec-badge--pp'},
        SP: {icon: '📅', label: 'Planting Schedule', cls: 'rec-badge--sp'},
    }
    const meta = REC_META[rec_type] ?? {icon: '📊', label: rec_type, cls: ''}

    return (
        <div className="rec-result">
            {/* ── Header ── */}
            <div className="rec-header">
                <div>
                    <span className={`rec-badge ${meta.cls}`}>{meta.icon} {meta.label}</span>
                    <div className="rec-meta-row">
                        {result.version && <>Engine v{result.version}<span className="rec-meta-sep">·</span></>}
                        {result.request_id && <>ID: <code>{result.request_id.slice(0, 8)}…</code></>}
                    </div>
                </div>
                <div className="rec-status-badge">✓ Success</div>
            </div>

            {/* ── Recommendation text ── */}
            {recommendation && (
                <div className="rec-advice">
                    <div className="rec-advice-icon">💬</div>
                    <div className="rec-advice-body">
                        {recommendation.split('\n').filter(Boolean).map((line, i) => (
                            <p key={i}>{line}</p>
                        ))}
                    </div>
                </div>
            )}

            {/* ── Type-specific display ── */}
            {(rec_type === 'FR' || rec_type === 'IC') && !Array.isArray(data) && (
                <FRICDisplay data={data as FRData} rates={fertilizer_rates} country={country}/>
            )}
            {rec_type === 'PP' && Array.isArray(data) && (
                <PPDisplay rows={data as PPRow[]} country={country}/>
            )}
            {rec_type === 'SP' && Array.isArray(data) && (
                <SPDisplay rows={data as SPRow[]} country={country}/>
            )}

            {/* ── Raw JSON toggle ── */}
            <div className="rec-json-section">
                <button className="rec-json-toggle" onClick={() => setShowJson(v => !v)}>
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                        <polyline points={showJson ? '18 15 12 9 6 15' : '6 9 12 15 18 9'}/>
                    </svg>
                    {showJson ? 'Hide' : 'View'} raw JSON response
                </button>
                {showJson && (
                    <div className="pg-json" style={{marginTop: '0.75rem'}}>
                        <div className="pg-json-header">
                            <div className="pg-json-dot" style={{background: '#ff5f57'}}/>
                            <div className="pg-json-dot" style={{background: '#febc2e'}}/>
                            <div className="pg-json-dot" style={{background: '#28c840'}}/>
                        </div>
                        <pre>{JSON.stringify(result, null, 2)}</pre>
                    </div>
                )}
            </div>
        </div>
    )
}

// ── FR / IC ───────────────────────────────────────────────────────────────────

function FRICDisplay({data, rates, country}: {data: FRData; rates: FertilizerRate[]; country: string}) {
    const yieldChange = pctChange(data.CurrentY, data.TargetY)
    const nrPositive = data.NR >= 0

    return (
        <div>
            {/* Yield + financials */}
            <div className="rec-metrics">
                <MetricCard
                    icon="🌾"
                    label="Current Yield"
                    value={`${data.CurrentY?.toFixed(1) ?? '—'} t/ha`}
                    sub="estimated"
                    variant="neutral"
                />
                <MetricCard
                    icon="🎯"
                    label="Target Yield"
                    value={`${data.TargetY?.toFixed(1) ?? '—'} t/ha`}
                    sub={yieldChange
                        ? <span className={yieldChange.positive ? 'rec-change--pos' : 'rec-change--neg'}>
                            {yieldChange.value} vs current
                          </span>
                        : 'with recommendations'}
                    variant="highlight"
                />
                <MetricCard
                    icon="💰"
                    label="Total Input Cost"
                    value={fmt(data.TC ?? 0, country)}
                    sub="fertilizer cost"
                    variant="cost"
                />
                <MetricCard
                    icon={nrPositive ? '📈' : '📉'}
                    label="Net Revenue Gain"
                    value={fmt(data.NR ?? 0, country)}
                    sub="vs no fertilizer"
                    variant={nrPositive ? 'gain' : 'loss'}
                />
            </div>

            {/* NPK */}
            {(data.N || data.P || data.K) ? (
                <div className="rec-section">
                    <div className="rec-section-title">Nutrient application</div>
                    <div className="rec-npk">
                        <NpkPill label="N" value={data.N} color="var(--forest-mid)" bg="rgba(45,107,68,0.1)"/>
                        <NpkPill label="P" value={data.P} color="var(--terra)" bg="rgba(196,98,45,0.1)"/>
                        <NpkPill label="K" value={data.K} color="var(--harvest)" bg="rgba(200,151,58,0.1)"/>
                        {data.WLY ? (
                            <div className="rec-npk-wly">
                                Water-limited yield potential: <strong>{data.WLY.toFixed(1)} t/ha</strong>
                            </div>
                        ) : null}
                    </div>
                </div>
            ) : null}

            {/* Fertilizer rates */}
            {rates && rates.length > 0 && (
                <div className="rec-section">
                    <div className="rec-section-title">Recommended fertilizer application</div>
                    <div className="rec-fert-list">
                        {rates.map((r, i) => (
                            <FertBar key={i} type={r.type} rate={r.rate}
                                     max={Math.max(...rates.map(x => x.rate))}/>
                        ))}
                    </div>
                </div>
            )}

            {data.plDate && (
                <div className="rec-footnote">
                    Computed for planting date: <strong>{fmtDate(data.plDate)}</strong>
                    &nbsp;· Location: {data.lat?.toFixed(3)}, {data.lon?.toFixed(3)}
                </div>
            )}
        </div>
    )
}

// ── PP ────────────────────────────────────────────────────────────────────────

function PPDisplay({rows, country}: {rows: PPRow[]; country: string}) {
    const recommended = rows.find(r => !r.CP) ?? rows[0]
    const current     = rows.find(r => r.CP)

    return (
        <div>
            {/* Recommended option card */}
            {recommended && (
                <div className="rec-best-card">
                    <div className="rec-best-label">Recommended approach</div>
                    <div className="rec-best-ops">
                        {recommended.ploughing && (
                            <OpTag icon="🚜"
                                   label={`Ploughing (${methodLabel(recommended.method_ploughing)})`}/>
                        )}
                        {recommended.ridging && (
                            <OpTag icon="🔧"
                                   label={`Ridging (${methodLabel(recommended.method_ridging)})`}/>
                        )}
                        {!recommended.ploughing && !recommended.ridging && (
                            <OpTag icon="✋" label="No land preparation"/>
                        )}
                    </div>
                    <div className="rec-best-fin">
                        <span>Cost: <strong>{fmt(recommended.TC, country)}</strong></span>
                        <span>Net revenue: <strong
                            className={recommended.NR >= 0 ? 'rec-change--pos' : 'rec-change--neg'}>
                            {fmt(recommended.NR, country)}
                        </strong></span>
                        {recommended.dNR !== 0 && (
                            <span>Change vs current: <strong
                                className={recommended.dNR >= 0 ? 'rec-change--pos' : 'rec-change--neg'}>
                                {recommended.dNR >= 0 ? '+' : ''}{fmt(recommended.dNR, country)}
                            </strong></span>
                        )}
                    </div>
                </div>
            )}

            {/* Comparison table */}
            {rows.length > 1 && (
                <div className="rec-section">
                    <div className="rec-section-title">All options compared</div>
                    <div style={{overflowX: 'auto'}}>
                        <table className="rec-table">
                            <thead>
                            <tr>
                                <th>Operations</th>
                                <th>Method</th>
                                <th>Cost</th>
                                <th>Net Revenue</th>
                                <th>Change</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            {rows.map((r, i) => (
                                <tr key={i} className={r.CP ? 'rec-table-row--current' : ''}>
                                    <td>
                                        <div className="rec-ops-cell">
                                            {r.ploughing && <span className="rec-op-tag">Plough</span>}
                                            {r.ridging && <span className="rec-op-tag">Ridge</span>}
                                            {!r.ploughing && !r.ridging && (
                                                <span className="rec-op-tag rec-op-tag--none">None</span>
                                            )}
                                        </div>
                                    </td>
                                    <td style={{fontSize: '0.8125rem'}}>
                                        {r.ploughing ? methodLabel(r.method_ploughing) : '—'}&nbsp;/&nbsp;
                                        {r.ridging ? methodLabel(r.method_ridging) : '—'}
                                    </td>
                                    <td>{fmt(r.TC, country)}</td>
                                    <td className={r.NR >= 0 ? 'rec-change--pos' : 'rec-change--neg'}>
                                        {fmt(r.NR, country)}
                                    </td>
                                    <td className={r.dNR >= 0 ? 'rec-change--pos' : 'rec-change--neg'}>
                                        {r.dNR >= 0 ? '+' : ''}{fmt(r.dNR, country)}
                                    </td>
                                    <td>
                                        {r.CP && <span className="rec-pill rec-pill--current">Your current</span>}
                                        {!r.CP && i === rows.findIndex(x => !x.CP) &&
                                            <span className="rec-pill rec-pill--rec">Recommended</span>}
                                    </td>
                                </tr>
                            ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            )}

            {current && (
                <div className="rec-footnote">
                    Current practice: {current.ploughing ? `ploughing (${methodLabel(current.method_ploughing)})` : 'no ploughing'}
                    {current.ridging ? `, ridging (${methodLabel(current.method_ridging)})` : ', no ridging'}
                </div>
            )}
        </div>
    )
}

// ── SP ────────────────────────────────────────────────────────────────────────

function SPDisplay({rows, country}: {rows: SPRow[]; country: string}) {
    const best = rows[0]

    return (
        <div>
            {/* Best window */}
            {best && (
                <div className="rec-best-card rec-best-card--sp">
                    <div className="rec-best-label">Optimal planting window</div>
                    <div className="rec-schedule-dates">
                        <div className="rec-date-block">
                            <div className="rec-date-icon">🌱</div>
                            <div className="rec-date-label">Plant</div>
                            <div className="rec-date-value">{fmtDate(best.PD)}</div>
                        </div>
                        <div className="rec-schedule-arrow">→</div>
                        <div className="rec-date-block">
                            <div className="rec-date-icon">🌾</div>
                            <div className="rec-date-label">Harvest</div>
                            <div className="rec-date-value">{fmtDate(best.HD)}</div>
                        </div>
                    </div>
                    <div className="rec-best-fin">
                        <span>Estimated gross revenue: <strong
                            className="rec-change--pos">{fmt(best.GR, country)}</strong></span>
                        {best.dGR !== 0 && (
                            <span>Change vs current: <strong
                                className={best.dGR >= 0 ? 'rec-change--pos' : 'rec-change--neg'}>
                                {best.dGR >= 0 ? '+' : ''}{fmt(best.dGR, country)}
                            </strong></span>
                        )}
                        {best.rootUP > 0 && (
                            <span>Cassava price used: <strong>{fmt(best.rootUP, country)}/1000 kg</strong></span>
                        )}
                    </div>
                </div>
            )}

            {/* Alternatives */}
            {rows.length > 1 && (
                <div className="rec-section">
                    <div className="rec-section-title">All planting windows compared</div>
                    <div style={{overflowX: 'auto'}}>
                        <table className="rec-table">
                            <thead>
                            <tr>
                                <th>Plant date</th>
                                <th>Harvest date</th>
                                <th>Planting shift</th>
                                <th>Harvest shift</th>
                                <th>Gross revenue</th>
                                <th>Change</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            {rows.map((r, i) => (
                                <tr key={i} className={r.CP ? 'rec-table-row--current' : ''}>
                                    <td style={{whiteSpace: 'nowrap'}}>{fmtDate(r.PD)}</td>
                                    <td style={{whiteSpace: 'nowrap'}}>{fmtDate(r.HD)}</td>
                                    <td style={{fontSize: '0.8125rem', color: 'var(--text-muted)'}}>
                                        {weekOffset(r.rPWnr)}
                                    </td>
                                    <td style={{fontSize: '0.8125rem', color: 'var(--text-muted)'}}>
                                        {weekOffset(r.rHWnr)}
                                    </td>
                                    <td className="rec-change--pos">{fmt(r.GR, country)}</td>
                                    <td className={r.dGR >= 0 ? 'rec-change--pos' : 'rec-change--neg'}>
                                        {r.dGR >= 0 ? '+' : ''}{fmt(r.dGR, country)}
                                    </td>
                                    <td>
                                        {r.CP && <span className="rec-pill rec-pill--current">Your dates</span>}
                                        {!r.CP && i === 0 &&
                                            <span className="rec-pill rec-pill--rec">Best</span>}
                                    </td>
                                </tr>
                            ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            )}
        </div>
    )
}

// ── Sub-components ────────────────────────────────────────────────────────────

function MetricCard({icon, label, value, sub, variant}: {
    icon: string; label: string; value: string
    sub: React.ReactNode; variant: 'neutral' | 'highlight' | 'cost' | 'gain' | 'loss'
}) {
    return (
        <div className={`rec-metric rec-metric--${variant}`}>
            <div className="rec-metric-icon">{icon}</div>
            <div className="rec-metric-label">{label}</div>
            <div className="rec-metric-value">{value}</div>
            <div className="rec-metric-sub">{sub}</div>
        </div>
    )
}

function NpkPill({label, value, color, bg}: {label: string; value: number; color: string; bg: string}) {
    return (
        <div className="rec-npk-pill" style={{borderColor: color, background: bg}}>
            <span className="rec-npk-letter" style={{color}}>{label}</span>
            <span className="rec-npk-value">{value ?? 0} kg</span>
        </div>
    )
}

function FertBar({type, rate, max}: {type: string; rate: number; max: number}) {
    const pct = max > 0 ? Math.round((rate / max) * 100) : 0
    return (
        <div className="rec-fert-bar">
            <div className="rec-fert-name">{type}</div>
            <div className="rec-fert-track">
                <div className="rec-fert-fill" style={{width: `${pct}%`}}/>
            </div>
            <div className="rec-fert-rate">{rate} kg</div>
        </div>
    )
}

function OpTag({icon, label}: {icon: string; label: string}) {
    return (
        <span className="rec-op-badge">
            {icon} {label}
        </span>
    )
}
