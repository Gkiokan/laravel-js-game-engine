<template lang="html">
  <q-header :elevated="false" :class="qHeaderClass">
    <q-toolbar :class="true ? 'bg-transparent' : $root.toolbarClass">
      <q-btn v-if="$root.showDrawer"
        square unelevated flat
        :class="$root.darkmode ? 'bg-transparent text-grey-1' : 'bg-transparent'"
        icon="menu" aria-label="Menu" style="margin-left: -10px"
        @click="toggleDrawer"
      />

      <Logo class="cursor-pointer" height="50px" width="50px" @click.prevent="$router.push({ name: 'home' })" />

      <q-space />

      <UserDropdown v-if="0" />

      <q-btn flat round v-if="$root.darkmode"
            :icon="$util.is(animationExists, 'motion_photos_paused', 'motion_photos_on')"
            @click="initBackgroundAnimation" />

      <q-btn flat round
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
import { get } from 'vuex-pathify'

export default {

  data(){ return {

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
          return { 
              "bg-transparent" : true,
              "text-grey-8"    : !this.$root.darkmode,
              "text-white"     : this.$root.darkmode,
          }
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

  }
}
</script>

<style lang="scss" scoped>
</style>
