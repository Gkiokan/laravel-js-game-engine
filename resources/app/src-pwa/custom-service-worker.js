// console.log('Loading custom service worker')
// import * as googleAnalytics from 'workbox-google-analytics';

import {
  pageCache,
  imageCache,
  staticResourceCache,
  googleFontsCache,
  offlineFallback,
} from 'workbox-recipes';
import { precacheAndRoute } from 'workbox-precaching';
import {registerRoute} from 'workbox-routing';
import {CacheFirst} from 'workbox-strategies';
import {ExpirationPlugin} from 'workbox-expiration';

// Precache
self.__precacheManifest = [].concat(self.__WB_MANIFEST || []);
// console.log('Manifest', self.__precacheManifest)

// workbox.setConfig({ debug: false })

// Google roules
// googleAnalytics.initialize();


// Include offline.html in the manifest
precacheAndRoute(self.__precacheManifest)

offlineFallback()

pageCache()

googleFontsCache()

staticResourceCache()

imageCache()



// Events
self.addEventListener("message", (event) => {
  if (event.data.action == "SKIP_WAITING"){
      console.log(':: SKIP WAITING - csw')
      self.skipWaiting()
  }

  console.log(':: message | ', event)
});
