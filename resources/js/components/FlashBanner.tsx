import { usePage } from '@inertiajs/react'
import type { PageProps } from '../types'

export default function FlashBanner() {
    const { flash } = usePage<PageProps>().props

    if (!flash.success && !flash.error) return null

    return (
        <div className={flash.success ? 'flash-success' : 'flash-error'}>
            {flash.success ?? flash.error}
        </div>
    )
}
