<template lang="html">
  <q-header :elevated="false" :class="qHeaderClass" xstyle="border-bottom: 1px solid #ddd;" v-if="user">
    <q-toolbar :class="false ? 'bg-transparent' : $root.toolbarClass" :isMobile="$q.platform.is.mobile">
      <q-btn v-if="$root.showDrawer"
        square unelevated flat
        :class="$root.darkmode ? 'bg-transparent text-grey-1' : 'bg-transparent'"
        icon="menu" aria-label="Menu" style="margin-left: -10px"
        @click="toggleDrawer"
      />

      <Logo class="cursor-pointer" height="50px" width="50px" @click.prevent="$router.push({ name: 'home' })" />

      <q-space style="max-width: 20px" />

      <div>{{ title }}</div>

      <q-space />

      <div flat round class="q-gutter-xs q-mr-xs" v-if="false">
          <q-btn flat padding="sm" icon="question_answer" :ripple="false" @click="$router.push({ name: 'chat' })" v-if="false">
              <q-badge color="red-6" floating v-if="0">1</q-badge>
          </q-btn>

          <q-btn flat padding="sm" icon="notifications" :ripple="false" @click="$router.push({ name: 'settings.notification' })" v-if="false">
              <q-badge color="red-6" floating v-if="user.unread_notifications_count">{{ user.unread_notifications_count }}</q-badge>
          </q-btn>
      </div>

      <UserDropdown v-if="true" />

      <q-btn rounded flat class="q-mr-md"
            :icon="$util.is(!$root.darkmode, 'brightness_2', 'brightness_5')"
            @click="$root.darkmode = !$root.darkmode" />      

      <q-btn flat rounded :label="'v'+$root.version" @click="triggerCustomEvent('refreshApp')">
          <q-badge color="red" text-color="white" floating v-if="updateExists"> update avaible </q-badge>
          <q-badge color="posiive" text-color="white" floating v-if="refreshing" @click="reload"> install done <br>please refresh </q-badge>
      </q-btn>

    </q-toolbar>
  </q-header>
</template>

<script>
// import SWUpdate from '~/plugins/sw-update'
import { get, sync } from 'vuex-pathify'

export default {

  data(){ return {
      vantaActivated: false,
  }},

  created(){
      // this.$sw.checkUpdateInstalled() // depcreated
      this.triggerCustomEvent('checkUpdateInstalled')
  },

  computed: {
      animationStopped: sync('app/animationStopped', false),
      updateExists: get('sw/updateExists', false),
      refreshing: get('sw/refreshing', false),
      registration: get('sw/registration', false),
      isMobile: get('app/isMobile', false),
      qHeaderClass(){
          return { 
              "bg-transparent" : true,
              "text-grey-8"    : !this.$root.darkmode,
              "text-grey-1"    : this.$root.darkmode,
              "border-bottom"  : !this.$root.darkmode,
          }
      },

      user: get('auth/user', false),
      title(){
          if(this.$route.name.includes('dashboard'))
            return "Hi, " + this.user.username

          return this.$t(this.$route.name)
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

      logout(){
          // this.$store.dispatch('auth/logout')
          this.$store.dispatch('auth/logout', () => this.$router.push({ name: 'login' }) )
      },

  }
}
</script>

<style lang="scss" scoped>
</style>
