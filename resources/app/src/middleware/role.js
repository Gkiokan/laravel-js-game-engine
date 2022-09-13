// import routes from '~/config/routes'
import store from '~/store'

export default (to, from, next) => {

  let permission = routes.getRoles(to.name)
  let role  = store.getters['auth/user'].role
  let grant = permission.length ? permission.includes(role) : true

  // if (role in permission) {
  if(grant){
      next()
  } else {
      next({ name: '403' })
  }
}
