
const routes = [
  {
    path: '/',
    component: () => import('layouts/game/GameLayout.vue'),
    redirect: { name: 'home' },
    children: [
      {
        path: '', name: 'home', component: () => import('pages/Game/Index.vue'),
        meta: {}
      }
    ]
  },

  {
    path: '/privacy', name: 'privacy', component: () => import('pages/Privacy.vue'),
    meta: {}
  },

  {
    path: '/term-of-service', name: 'tos', component: () => import('pages/ToS.vue'),
    meta: {}
  },  

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
