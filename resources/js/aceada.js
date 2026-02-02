/**
 * Note: We're not gonna use tailwindcss for the icon and its modules since it affects the client's site interface
 *
 */
import '../scss/icon.scss'
import '../scss/global.scss'

import { createApp } from 'vue';
import { createPinia } from 'pinia'
import { createPersistedState  } from 'pinia-plugin-persistedstate'
import axios from 'axios'

import Icon from './FloatingActionButton/Icon.vue'
import Widget from './widget'

// for storing user accessibility settings on session
// changed the storage to local

const pinia = createPinia()
pinia.use(createPersistedState({
    key: id => `aceada-${id}`,
    storage: localStorage
}))

const env = import.meta.env.VITE_APP_ENV
const url = import.meta.env.VITE_APP_URL
const name = import.meta.env.VITE_APP_NAME

var token = null
var config = null

if (env === 'local') {
    injectScript()
} else {
    const aceadaScript = document.querySelector(`script[aceada-script='']`)

    if (aceadaScript) {
        token = aceadaScript.getAttribute('data-token')
        const host = window.location.hostname

        /** TODO: maybe try to use try catch for cleaner code */
        axios.post(`${url}/api/verify`, { token: token, host: host })
            .then((response) => {
                handleTokenVerificationResponse(response)
            })
            .catch((err) => {
                console.log(err.response.data.message)
            })
    } else {
        console.log(`${name}: Script not found for installing web accessibility. Please contact support for assistance.`)
    }
}

function handleTokenVerificationResponse(response) {
    console.log(response.data.message)

    /** if user has not saved widget configuration and has used aceAdaWidget on their script, use that instead */
    if (config != null && response.data.widget_configuration.triggerIcon === null) {
        injectScript(config)
        return;
    }

    config = response.data.widget_configuration
    injectScript(config)
}

function injectScript(config) {
    const app = createApp(Icon)

    /** append element the icon will be injected */
    const container = document.createElement('div')
    container.setAttribute('id', 'aceada')
    document.body.appendChild(container)

    /** inject css */
    if (env !== 'local') {
        const filename = import.meta.env.VITE_CSS_FILENAME ?? 'aceada'
        const styles = document.createElement('link')

        styles.setAttribute('rel', 'stylesheet')
        styles.setAttribute('href', `${url}/dist/${filename}.css`)
        document.head.appendChild(styles)

        // initialize widget configuration
        new Widget(config)
    }

    // mount the icon
    app.use(pinia)
    app.mount('#aceada')
}

/**
 * called first before token verification request;
 * sets initial value of config if aceAdaWidget is present on client script
 */
if (! window.aceAdaWidget) {
    window.aceAdaWidget = (params) => {
        if (config === null) config = params
    }
}
