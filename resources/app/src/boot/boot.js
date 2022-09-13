// import something here
import config from '~/config'
// import { sync } from 'vuex-router-sync'
import { Dark } from 'quasar'
// import vClickOutside from "click-outside-vue3"
 import LoadScript from "vue-plugin-load-script";

import { createMetaManager, plugin as metaPlugin } from 'vue-meta'

import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

import { createHead } from '@vueuse/head'
// import VueSmoothScroll from 'vue3-smooth-scroll' // not using for now

// "async" is optional;
// more info on params: https://quasar.dev/quasar-cli/boot-files
export default async ( { app, store, router, Vue } ) => {

  // add lcick outside directive
  // app.use(vClickOutside)
  app.use(LoadScript)

  // meta 
  const head = createHead()
  app.use(head)

  // // scroll 
  // app.use(VueSmoothScroll, {
  //   duration: 400,
  //   updateHistory: false
  // })  

  // #todo - meta to be done
  // const metaManager = createMetaManager()
  // app.use(metaManager)
  // app.use(metaPlugin)

  // Pusher
  // app.config.globalProperties.$pusher = new Pusher('wcx_ws_key', {
  //     wsHost: 'wcx.test',
  //     wsPort: 6001,
  // })

  // Echo
  app.config.globalProperties.$echo  = new Echo({
      // auth:{ headers: { 'Authorization': 'Bearer ' + user.token } },
      broadcaster: 'pusher',
      key: 'wcx_ws_key',
      wsHost: process.env.wsHost,
      wsPort: 6001,
      wssPort: 6001,
      disableStats: true,
      forceTLS: true,
      encrypted: true,
      enabledTransports: ['ws', 'wss'],
  })  

  // check-user
  try {
    await store.dispatch('auth/fetchUser')
  } catch (e) { }

  // app.config.warnHandler = (msg, vm, trace) => {
  //   console.log(`Warn: ${msg}\nTrace: ${trace}`);
  // }

  // sync(store, router)

  console.log('Booting up...')
}
