interface Props {
    submitting: boolean
    onClick: () => void
}

export default function SubmitButton({submitting, onClick}: Readonly<Props>) {
    return (
        <button className="btn btn-terra" onClick={onClick} disabled={submitting}>
            {submitting ? (
                <>
                    <span style={{
                        width: 14, height: 14,
                        border: '2px solid rgba(255,255,255,0.3)',
                        borderTopColor: '#fff',
                        borderRadius: '50%',
                        display: 'inline-block',
                        animation: 'spin 0.7s linear infinite',
                    }}/>
                    Computing…
                </>
            ) : (
                <>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M5 3l14 9-14 9V3z"/>
                    </svg>
                    Get Recommendations
                </>
            )}
        </button>
    )
}
