import './bootstrap';
import '../css/app.css';
import 'sweetalert2/dist/sweetalert2.min.css';
import 'highlight.js/styles/codepen-embed.css'
// import "vue-toastification/dist/index.css";
import '@dafcoe/vue-notification/dist/vue-notification.css'

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

import VueSweetalert2 from 'vue-sweetalert2';
// import Toast from "vue-toastification";
import VueNotificationList from '@dafcoe/vue-notification'
import hljs from 'highlight.js/lib/core';
import javascript from 'highlight.js/lib/languages/javascript';
import hljsVuePlugin from "@highlightjs/vue-plugin";
import VueRecaptcha from "vue3-recaptcha-v2";

hljs.registerLanguage('javascript', javascript);

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'AceADA';
const recaptcha_site_key = import.meta.env.VITE_RECAPTCHA_SITE_KEY

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./App/Pages/${name}.vue`, import.meta.glob('./App/Pages/**/*.vue'), import.meta.glob('./App/Pages/**/**/*.vue')),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(VueSweetalert2)
            // .use(Toast)
            .use(VueNotificationList)
            .use(hljsVuePlugin)
            .use(VueRecaptcha, {
                siteKey: recaptcha_site_key,
                alterDomain: false
            })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
