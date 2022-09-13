import { register } from 'register-service-worker'

// The ready(), registered(), cached(), updatefound() and updated()
// events passes a ServiceWorkerRegistration instance in their arguments.
// ServiceWorkerRegistration: https://developer.mozilla.org/en-US/docs/Web/API/ServiceWorkerRegistration

// register(process.env.SERVICE_WORKER_FILE,

register('/service-worker.js', {
// register(process.env.SERVICE_WORKER_FILE, {
    // The registrationOptions object will be passed as the second argument
    // to ServiceWorkerContainer.register()
    // https://developer.mozilla.org/en-US/docs/Web/API/ServiceWorkerContainer/register#Parameter

    registrationOptions: { scope: '/' },

    ready (sw) {
        // console.log(
        //   'App is being served from cache by a service worker.\n' +
        //   'For more details, visit https://goo.gl/AFskqB'
        // )
        // console.log('Service Worker ready', 'call custom event sw-install')
        document.dispatchEvent( new CustomEvent('sw-install', { detail: sw }) )
    },
    registered (r) {
        // console.log('Service worker has been registered.', r)
    },
    cached () {
        // console.log('Content has been cached for offline use.')
    },
    updatefound (sw) {
        // console.log('New content is downloading.')
        document.dispatchEvent( new CustomEvent('sw-download', { detail: sw }) )
    },
    updated (sw) {
        // console.log('New content is available; please refresh.')
        document.dispatchEvent( new CustomEvent('sw-update', { detail: sw }) )
    },
    offline (sw) {
        // console.log('No internet connection found. App is running in offline mode.')
        document.dispatchEvent( new CustomEvent('sw-offline', { detail: sw }) )
    },
    error (error) {
        console.error('Error during service worker registration:', error)
    }
})
