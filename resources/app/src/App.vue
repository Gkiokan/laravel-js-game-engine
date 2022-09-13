<template>
  <router-view />
</template>

<script>
import { defineComponent } from 'vue'
import { reactive } from 'vue'

import config from '~/config'
import store from '~/store'

import Darkmode from './mixins/darkmode'
import SocketHandler from './mixins/SocketHandler'

export default defineComponent({
  name: 'App',

  mixins: [Darkmode, SocketHandler],

  data(){ return {
      leftDrawerOpen : false,
      rightDrawerOpen: false,

      app: config.appTitle,
      version: config.version,
      config,
      layout: null,
      defaultLayout: 'default',

      toolbarClass: 'bg-dark text-grey-8',
      drawerClass: 'bg-white text-grey-8',
      layoutClass: 'text-grey-8',

      darkmode: store.get('app/getDarkmode'),

      fixedBackgroundImage: true,
      scrolled: false,
      scrollOffset: 100,
      stickToTop: true,
      isWeb: true,

      footerText: '',
      footerLinks: [],
      footerThirdParty: [],
  }},

  computed: {
      user(){ return this.$store.getters['auth/user'] },
      isMobile(){ return this.$q.screen.lt.md },
      isAdmin(){ 
          if(this.user)
            return this.user.role.includes('admin') ? true : false

          return false
      },
      showDashboard(){
          if(this.user)
            return ['admin', 'mod', 'upper'].includes(this.user.role) ? true : false

          return false
      },
      isMod(){
          if(this.user)
            return ['admin', 'mod'].includes(this.user.role) ? true : false

          return false
      },
      showDrawer(){
          return true
          return this.$q.screen.lt.md
      },    
      // darkmode(){ return this.$store.getters['app/getDarkmode'] },
      // darkmode: sync('app/darkmode'),
  },

  methods: {
      open(url){
          if(url)
            window.open(url)
      },

      log(msg){
          console.log(":: Game :: " + msg)
      },

  }
})
</script>