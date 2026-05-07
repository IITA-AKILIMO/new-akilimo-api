import {createInertiaApp} from '@inertiajs/react'
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers'
import {createRoot} from 'react-dom/client'
import {type ComponentType} from 'react'
import '../css/app.css'

void createInertiaApp({
    resolve: name => resolvePageComponent<ComponentType>(`./pages/${name}.tsx`,
        import.meta.glob<ComponentType>('./pages/**/*.tsx')),
    setup({el, App, props}) {
        createRoot(el).render(<App {...props} />)
    },
    progress: {
        color: '#16a34a',
    },
})
