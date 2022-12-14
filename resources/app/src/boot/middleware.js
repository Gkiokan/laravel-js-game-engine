// import something here
import router from '~/router'
import store from '~/store'

// "async" is optional;
// more info on params: https://quasar.dev/quasar-cli/boot-files
export default async ({ store, router, Vue }) => {
    router.beforeEach(beforeEach)
    router.afterEach(afterEach)

    if(store.get('app/debug'))
    console.log('Register Router middlewares')
}


// The middleware for every page of the application.
const globalMiddleware = ['check-auth']

// Load middleware modules dynamically.
const routeMiddleware = resolveMiddleware(
  require.context('~/middleware', false, /.*\.js$/)
)


/**
 * Global router guard.
 *
 * @param {Route} to
 * @param {Route} from
 * @param {Function} next
 */
async function beforeEach (to, from, next) {
    // Get the matched components and resolve them.
    // const components = await resolveComponents(
    //   router.getMatchedComponents({ ...to })
    // )
    //
    // if (components.length === 0) {
    //   return next()
    // }
    //
    // // Start the loading bar.
    // if (components[components.length - 1].loading !== false) {
    //   // router.app.$nextTick(() => router.app.$loading.start())
    // }
    //
    // Get the middleware for all the matched components.
    // const middleware = getMiddleware(components)

    if(store.get('app/debug')){
      console.log('- start beforeEach', to)
      console.log('- middleware list', middleware, routeMiddleware)
      console.log('- user auth ', store.get('auth/check'))
    }

    if(to.meta.guest){
        await routeMiddleware['guest'](to, from, next)
    }

    if(to.meta.auth){
        await routeMiddleware['auth'](to, from, next)
        // await routeMiddleware['verified'](to, from, next)
    }

    // callMiddleware(middleware, to, from, (...args) => {
    //   // Set the application layout only if "next()" was called with no args.
    //   if (args.length === 0) {
    //     // router.app.setLayout(components[0].layout || '')
    //   }
    //
    //   next(...args)
    // })
    if(!to.meta.auth && !to.meta.guest)
    next()
}

/**
 * Global after hook.
 *
 * @param {Route} to
 * @param {Route} from
 * @param {Function} next
 */
async function afterEach (to, from, next) {
    // await router.app.$nextTick()
    // router.app.$loading.finish()
}

/**
 * Call each middleware.
 *
 * @param {Array} middleware
 * @param {Route} to
 * @param {Route} from
 * @param {Function} next
 */
function callMiddleware (middleware, to, from, next) {
    // console.log('- call middleware', middleware, to)
    const stack = middleware.reverse()

    const _next = (...args) => {
      // Stop if "_next" was called with an argument or the stack is empty.
      if (args.length > 0 || stack.length === 0) {
        if (args.length > 0) {
          // router.app.$loading.finish()
        }

        return next(...args)
      }

      const middleware = stack.pop()

      if (typeof middleware === 'function') {
        middleware(to, from, _next)
      } else if (routeMiddleware[middleware]) {
        routeMiddleware[middleware](to, from, _next)
      } else {
        throw Error(`Undefined middleware [${middleware}]`)
      }
    }

    _next()
}

/**
 * Resolve async components.
 *
 * @param  {Array} components
 * @return {Array}
 */
function resolveComponents (components) {
    return Promise.all(components.map(component => {
      return typeof component === 'function' ? component() : component
    }))
}

/**
 * Merge the the global middleware with the components middleware.
 *
 * @param  {Array} components
 * @return {Array}
 */
function getMiddleware (components) {
    const middleware = [...globalMiddleware]

    components.filter(c => c.middleware).forEach(component => {
      if (Array.isArray(component.middleware)) {
        middleware.push(...component.middleware)
      } else {
        middleware.push(component.middleware)
      }
    })

    return middleware
}

/**
 * Scroll Behavior
 *
 * @link https://router.vuejs.org/en/advanced/scroll-behavior.html
 *
 * @param  {Route} to
 * @param  {Route} from
 * @param  {Object|undefined} savedPosition
 * @return {Object}
 */
function scrollBehavior (to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    }

    if (to.hash) {
      return { selector: to.hash }
    }

    const [component] = router.getMatchedComponents({ ...to }).slice(-1)

    if (component && component.scrollToTop === false) {
      return {}
    }

    return { x: 0, y: 0 }
}

/**
 * @param  {Object} requireContext
 * @return {Object}
 */
function resolveMiddleware (requireContext) {
    return requireContext.keys()
      .map(file =>
        [file.replace(/(^.\/)|(\.js$)/g, ''), requireContext(file)]
      )
      .reduce((guards, [name, guard]) => (
        { ...guards, [name]: guard.default }
      ), {})
}
