// import something here
// import Vue from 'vue'
import store from '~/store'
import { Notify } from 'quasar'

let debug = store.get('app/debug')

let sw = {
    log(a){
        if(debug)
        console.log(a)
    },

    checkUpdateInstalled(){
        sw.log(':: SW check installed flag exists @boot')
        let isInstalled = localStorage.getItem('update.installed')

        sw.log(':: SW is update installed', isInstalled)
        // if we have installed flag, show the information
        if(isInstalled){
            let version = localStorage.getItem('version')
            Notify.create({
                message: "Update v" + version + " installed.",
                color: 'positive',
                timeout: 500,
            })
            sw.setVersionInformation()
        }
    },

    // Store the SW registration so we can send it a message
    // We use `updateExists` to control whatever alert, toast, dialog, etc we want to use
    // To alert the user there is an update they need to refresh for
    updateAvailable(event) {
        store.set('sw/registration', event.detail)
        store.set('sw/updateExists', true)
        sw.log(':: SW update available @boot', event.detail)
        // sw.refreshApp()
    },

    // Called when the user accepts the update
    refreshApp() {
        sw.log(':: SW refresh @boot')
        store.set('sw/updateExists', false)
        let registration = store.get('sw/registration')
        // Make sure we only send a 'skip waiting' message if the SW is waiting
        if (!registration || !registration.waiting) return
        // send message to SW to skip the waiting and activate the new SW
        registration.waiting.postMessage({ action: 'SKIP_WAITING' })
    },

    install(){
        sw.log(':: SW sw-install @boot')
        if(localStorage.getItem('version')) return;
        sw.setVersionInformation()
    },

    setVersionInformation(){
        localStorage.setItem('version', store.get('app/version'))
        localStorage.setItem('install.date', new Date().toLocaleDateString())
        localStorage.removeItem('update.installed')
    },

}


// "async" is optional;
// more info on params: https://quasar.dev/quasar-cli/boot-files
export default async ({ app, store, Vue }) => {


  // sw implementation
  document.addEventListener('sw-install', sw.install, { once: true })
  document.addEventListener('sw-update', sw.updateAvailable, { once: true })
  document.addEventListener('refreshApp', sw.refreshApp, { once: true })
  document.addEventListener('checkUpdateInstalled', sw.checkUpdateInstalled, { once: true })

  // Prevent multiple refreshes
  navigator.serviceWorker.addEventListener('controllerchange', (a, b) => {
      if (store.get('sw/refreshing')) return
      store.set('sw/refreshing', true)

      sw.log(':: SW update ready.. @boot', a)
      localStorage.setItem('version', store.get('app/version'))
      localStorage.setItem('update.installed', true)
      window.location.reload()
  })

  // Vue.prototype.$sw = sw
  app.config.globalProperties.$sw = sw

}
