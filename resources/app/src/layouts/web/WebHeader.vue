<template lang="html">
  <q-header :elevated="true" :class="qHeaderClass" :bordered="false">
    <q-toolbar :class="true ? 'bg-transparent' : $root.toolbarClass">
      <q-btn v-if="$root.showDrawer"
        square unelevated flat
        :class="$root.darkmode ? 'bg-transparent text-grey-1' : 'bg-transparent'"
        icon="menu" aria-label="Menu" style="margin-left: -10px"
        @click="toggleDrawer"
      />

      <Logo light class="cursor-pointer" height="50px" width="50px" @click.prevent="$router.push({ name: 'home' })" />
      
      <div class="q-ml-sm text-red text-overline">BETA</div>
      <q-space />

      <div class="menuLinks q-gutter-md" v-if="!$root.isMobile">
          <router-link custom :to="link.to" v-slot:default="props"
              v-for="(link,i) in menuLinks.search" :key="'menu_search_'+i" >
              <q-btn :squared="!props.isExactActive" flat :icon="link.icon" :label="$t(link.title)" :ripple="false" v-bind="buttonProps(props)" />
          </router-link>
      </div>

      <q-separator vertical inset class="q-mx-md" v-if="!$q.screen.lt.xl" />

      <div class="menuLinks q-gutter-md" v-if="!$q.screen.lt.xl">
          <router-link custom :to="link.to" v-slot:default="props"
              v-for="(link,i) in menuLinks.top" :key="'menu_top_'+i" >
              <q-btn :squared="!props.isExactActive" flat :label="$t(link.title)" :ripple="false" v-bind="buttonProps(props)" />
          </router-link>
      </div>

      <q-separator vertical inset class="q-mx-md" v-if="!$root.isMobile" />

      <div v-if="!$root.showDrawer && $root.showDashboard && !$root.isMobile">
          <q-btn color="grey-8" icon="dashboard" label="Dashboard" @click="$root.toDashboard()" v-if="$root.showDashboard" />
      </div>
      
      <q-btn flat rounded no-caps label="Dashboard" @click="$router.push({ name: 'dashboard' })" v-if="$root.showDashboard" />    
      <q-btn flat rounded no-caps :label="$root.isMobile ? '' : 'Login | Signup'" @click="$router.push({ name: 'login' })"  v-if="!$root.user" />
      
      <UserDropdown v-if="true" />
      
      <q-btn rounded flat class="q-mr-md"
            :icon="$util.is(!$root.darkmode, 'brightness_2', 'brightness_5')"
            @click="$root.darkmode = !$root.darkmode" />

      <q-btn flat rounded :label="'v'+$root.version" @click="triggerCustomEvent('refreshApp')" v-if="!$root.isMobile">
          <q-badge color="red" text-color="white" floating v-if="updateExists"> update avaible </q-badge>
          <q-badge color="posiive" text-color="white" floating v-if="refreshing" @click="reload"> install done <br>please refresh </q-badge>
      </q-btn>

    </q-toolbar>
  </q-header>
</template>

<script>
// import SWUpdate from '~/plugins/sw-update'
import { get, sync } from 'vuex-pathify'

import menuLinks from '~/config/menuLinks'

export default {

    data(){ return {
        menuLinks,
    }},

    created(){
        // this.$sw.checkUpdateInstalled() // depcreated
        this.triggerCustomEvent('checkUpdateInstalled')
    },

    computed: {
        updateExists: get('sw/updateExists', false),
        refreshing: get('sw/refreshing', false),
        registration: get('sw/registration', false),
        isMobile: get('app/isMobile', false),
        qHeaderClass(){
            let bzz = {
                "bg-top-header"  : true,
                "bg-transparent" : false,
                "text-white"     : true,
            }

            if(this.$root.scrolled)
                bzz[this.$root.darkmode ? 'bg-dark' : 'bg-black'] = true

            return bzz
        },
    },

    methods: {
        toggleDrawer(){
            // console.log('x.', this.$root.leftDrawerOpen)
            this.$root.leftDrawerOpen = !this.$root.leftDrawerOpen
        },

        triggerCustomEvent(name){
            document.dispatchEvent(new CustomEvent(name))
        },

        open(link){
            window.open(link)
        },

        logout(){
            // this.$store.dispatch('auth/logout')
            this.$store.dispatch('auth/logout', () => this.$router.push({ name: 'home' }) )
        },

        buttonProps ({ href, route, isActive, isExactActive }) {        
            const props = {
            outline: true,
            to: href
            }

            if (isActive === true && isExactActive) {
            props.color = isExactActive === true ? 'white' : ''
            props.class = "isActive"
            }

            return props
        },
    }
}
</script>

<style lang="scss" scoped>

</style>