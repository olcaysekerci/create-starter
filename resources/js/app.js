import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        // Önce normal pages klasöründe ara
        const normalPages = import.meta.glob('./Pages/**/*.vue');
        const normalPath = `./Pages/${name}.vue`;
        
        if (normalPages[normalPath]) {
            return normalPages[normalPath]();
        }
        
        // Sonra modül klasörlerinde ara
        const modulePages = import.meta.glob('../../Modules/*/Resources/Views/**/*.vue');
        
        // Admin/Log/ActivityIndex -> Admin/Log/Pages/ActivityIndex.vue formatına çevir
        const parts = name.split('/');
        if (parts.length >= 3) {
            const [area, module, page] = parts;
            const modulePath = `../../Modules/${module}/Resources/Views/${area}/Pages/${page}.vue`;
            if (modulePages[modulePath]) {
                return modulePages[modulePath]();
            }
        }
        
        // Fallback olarak resolvePageComponent kullan
        return resolvePageComponent(
            normalPath,
            { ...normalPages, ...modulePages }
        );
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
