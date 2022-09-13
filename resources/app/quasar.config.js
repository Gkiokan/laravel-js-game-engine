/* eslint-env node */

/*
 * This file runs in a Node context (it's NOT transpiled by Babel), so use only
 * the ES6 features that are supported by your Node version. https://node.green/
 */

// Configuration for your app
// https://v2.quasar.dev/quasar-cli-webpack/quasar-config-js


const { configure } = require('quasar/wrappers');
const path = require('path');
const fs = require('fs');
const webpack = require('webpack')

module.exports = configure(function (ctx) {
  console.log(process.argv)
  
  // check if we building in dev mode
  let isDevBuild = process.argv.includes('--dev')
  let isStagingBuild = process.argv.includes('--staging')
  let api = false 
  let cusotmWSHost = false

  let foundAPI = process.argv.find( a => a.includes('--api'))
  if(foundAPI){
      api = foundAPI.replace('--api', '').replace('=', '')
      console.log("Found fallback api to " + api)
  }

  let foundWSHost = process.argv.find( a => a.includes('--wsHost'))
  if(foundWSHost){
      cusotmWSHost = foundWSHost.replace('--wsHost', '').replace('=', '')
      console.log("Found fallback wsHost to " + cusotmWSHost)
  }  

  // force fix
  if(isDevBuild)
    ctx.dev = true

  // set axios base
  let axiosBaseURL = 'https://js-game-engine.test/'
  let wsHostDefault = 'js-game-engine.test'

  // patch axios base for staging
  if(ctx.mode.pwa || ctx.mode.spa){
      axiosBaseURL = ctx.dev ? axiosBaseURL : 'https://js-game-engine.com/'
      wsHost = ctx.dev ? wsHostDefault : 'js-game-engine.com'
  }

  // patch for api 
  if(api){
      axiosBaseURL = api
  }

  if(cusotmWSHost){
      wsHost = cusotmWSHost
  }


  console.log("Use DEV Endpoints in axiosBaseURL", isDevBuild)
  console.log("Use Staging Endpoints in axiosBaseURL", isStagingBuild)
  console.log("Final axiosBaseURL", axiosBaseURL)
  console.log("Final wsHost", wsHost)
  

  // CONFIG goes here
  return {
    // https://v2.quasar.dev/quasar-cli-webpack/supporting-ts
    supportTS: false,

    // https://v2.quasar.dev/quasar-cli-webpack/prefetch-feature
    // preFetch: true,

    // app boot file (/src/boot)
    // --> boot files are part of "main.js"
    // https://v2.quasar.dev/quasar-cli-webpack/boot-files
    boot: [
      (ctx.mode.pwa || ctx.mode.spa) ? 'sw' : '',
      'boot',
      'middleware',
      'components',
      'i18n',
      'axios',
      'util',
      'form',
      'helper',
    ],

    // https://v2.quasar.dev/quasar-cli-webpack/quasar-config-js#Property%3A-css
    css: [
      'app.scss'
    ],

    // https://github.com/quasarframework/quasar/tree/dev/extras
    extras: [
      // 'ionicons-v4',
      // 'mdi-v5',
      'fontawesome-v6',
      // 'eva-icons',
      // 'themify',
      // 'line-awesome',
      // 'roboto-font-latin-ext', // this or either 'roboto-font', NEVER both!

      'roboto-font', // optional, you are not bound to it
      'material-icons', // optional, you are not bound to it
    ],

    // Full list of options: https://v2.quasar.dev/quasar-cli-webpack/quasar-config-js#Property%3A-build
    build: {
      vueRouterMode: 'history', // available values: 'hash', 'history'

      // transpile: false,
      // publicPath: '/',

      // Add dependencies for transpiling with Babel (Array of string/regex)
      // (from node_modules, which are by default not transpiled).
      // Applies only if "transpile" is set to true.
      // transpileDependencies: [],

      // rtl: true, // https://quasar.dev/options/rtl-support
      // preloadChunks: true,
      // showProgress: false,
      // gzip: true,
      // analyze: true,
      env: {
          axiosBaseURL,
          isDevBuild,
          isStagingBuild,
          wsHost,
      },

      distDir: '../../public/app',
      publicPath: ctx.dev ? '/' : '/app',
      vueRouterBase: '/',
      appBase: '/',


      // Options below are automatically set depending on the env, set them if you want to override
      // extractCSS: false,

      // https://v2.quasar.dev/quasar-cli-webpack/handling-webpack
      // "chain" is a webpack-chain object https://github.com/neutrinojs/webpack-chain

      chainWebpack (/* chain */) {},

      extendWebpack (cfg) {
          cfg.resolve.alias = {
            ...cfg.resolve.alias,
            '~': path.resolve(__dirname, './src'),
            'public': path.resolve(__dirname, './public'),
          }

          cfg.plugins.push(
              // new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),
          )

          // console.log(cfg.optimization)
          // console.log(cfg.optimization.splitChunks.cacheGroups)
      },

    },

    // Full list of options: https://v2.quasar.dev/quasar-cli-webpack/quasar-config-js#Property%3A-devServer
    devServer: {
      server: {
        type: 'http'
      },
      port: 8080,
      open: true // opens browser window automatically
    },

    // https://v2.quasar.dev/quasar-cli-webpack/quasar-config-js#Property%3A-framework
    framework: {
      config: {},

      // iconSet: 'material-icons', // Quasar icon set
      // lang: 'en-US', // Quasar language pack

      // For special cases outside of where the auto-import strategy can have an impact
      // (like functional components as one of the examples),
      // you can manually specify Quasar components/directives to be available everywhere:
      //
      // components: [],
      // directives: [],

      // Quasar plugins
      plugins: [
        'Meta',
        'Notify',
        'Dialog',
      ]
    },

    // animations: 'all', // --- includes all animations
    // https://quasar.dev/options/animations
    animations: [
      'fadeIn',
      'fadeOut',
    ],

    // https://v2.quasar.dev/quasar-cli-webpack/developing-ssr/configuring-ssr
    ssr: {
      pwa: false,

      // manualStoreHydration: true,
      // manualPostHydrationTrigger: true,

      prodPort: 3000, // The default port that the production server should use
                      // (gets superseded if process.env.PORT is specified at runtime)

      maxAge: 1000 * 60 * 60 * 24 * 30,
        // Tell browser when a file from the server should expire from cache (in ms)


      chainWebpackWebserver (/* chain */) {},


      middlewares: [
        ctx.prod ? 'compression' : '',
        'render' // keep this as last one
      ]
    },

    // https://v2.quasar.dev/quasar-cli-webpack/developing-pwa/configuring-pwa
    pwa: {
      workboxPluginMode: 'InjectManifest', // 'GenerateSW' or 'InjectManifest'
      workboxOptions: {}, // only for GenerateSW

      // for the custom service worker ONLY (/src-pwa/custom-service-worker.[js|ts])
      // if using workbox in InjectManifest mode

      chainWebpackCustomSW (/* chain */) {},

      manifestOptions: {
          install: true,
          start_url: '/?app_mode=true', // '?app_mode=true',
          scope: '/',
          short_name: "WCX",
          description: "WCX",
          display: 'standalone',
      },

      manifest: {
        name: `JS Game Engine`,
        short_name: `JGE`,
        description: `JGE Plattform`,
        display: 'standalone',
        orientation: 'portrait',
        background_color: '#ffffff',
        theme_color: '#027be3',
        icons: [
          {
            src: 'icons/icon-128x128.png',
            sizes: '128x128',
            type: 'image/png'
          },
          {
            src: 'icons/icon-192x192.png',
            sizes: '192x192',
            type: 'image/png'
          },
          {
            src: 'icons/icon-256x256.png',
            sizes: '256x256',
            type: 'image/png'
          },
          {
            src: 'icons/icon-384x384.png',
            sizes: '384x384',
            type: 'image/png'
          },
          {
            src: 'icons/icon-512x512.png',
            sizes: '512x512',
            type: 'image/png'
          }
        ]
      }
    },

    // Full list of options: https://v2.quasar.dev/quasar-cli-webpack/developing-cordova-apps/configuring-cordova
    cordova: {
      // noIosLegacyBuildFlag: true, // uncomment only if you know what you are doing
    },

    // Full list of options: https://v2.quasar.dev/quasar-cli-webpack/developing-capacitor-apps/configuring-capacitor
    capacitor: {
      hideSplashscreen: true
    },

    // Full list of options: https://v2.quasar.dev/quasar-cli-webpack/developing-electron-apps/configuring-electron
    electron: {
      bundler: 'builder', // 'packager' or 'builder'

      packager: {
        // https://github.com/electron-userland/electron-packager/blob/master/docs/api.md#options

        // OS X / Mac App Store
        // appBundleId: '',
        // appCategoryType: '',
        // osxSign: '',
        // protocol: 'myapp://path',

        // Windows only
        // win32metadata: { ... }
      },

      builder: {
        // https://www.electron.build/configuration/configuration

        appId: 'js-game-engine'
      },

      // "chain" is a webpack-chain object https://github.com/neutrinojs/webpack-chain

      chainWebpackMain (/* chain */) {},



      chainWebpackPreload (/* chain */) {},

    }
  }
});