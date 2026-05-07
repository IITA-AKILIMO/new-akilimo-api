import { createInertiaApp } from '@inertiajs/react'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createRoot } from 'react-dom/client'
import '../css/app.css'

// createInertiaApp({
//     resolve: (name) =>
//         resolvePageComponent(
//             `./pages/${name}.tsx`,
//             import.meta.glob('./pages/**/*.tsx')
//         ),
//     setup({ el, App, props }) {
//         return createRoot(el).render(<App {...props} />)
//     },
//     progress: {
//         color: '#16a34a',
//     },
// })

createInertiaApp({
    pages: {
        path: './pages',
        extension: '.tsx',
        lazy: true,
        transform: (name, page) => name.replace('/', '-'),
    },
})
