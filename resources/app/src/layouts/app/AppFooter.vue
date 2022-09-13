<template lang="html">
  <q-footer :class="qFooterClass" class="app-shadow shadow-up" >
      <q-tabs no-caps v-model="tab"
              active-color="primary"
              indicator-color="transparent"
              class="text-grey bg-transparent"
              >

          <q-route-tab class="bg-transparent" :ripple="true" flat exact name="home" icon="home" :to="{ name: 'home' }" />
          <q-route-tab class="bg-transparent" :ripple="true" flat exact name="context" icon="account_balance_wallet" :to="{ name: 'context' } "/>

          <div style="width: 80px;">
          <q-btn flat round class="absolute-center app-shadow shadow-up" :class="bgColor"
                  style="width: 60px; height: 60px; margin-top: -10px;"
                  @click="appButtonClick"
                  v-touch-hold:2000="hold">

              <q-img :src="$root.darkmode ? 'img/logo-white.png' : 'img/logo-dark.png'"
                    style="height: 60px; border-radius: 50%; overflow: hidden;"
                    class='absolute-center' :class="bgColor" />
          </q-btn>
          </div>

          <q-route-tab class="bg-transparent" :ripple="true" flat exact name="notification" icon="notifications" :to="{ name: 'notifications' }" />
          <q-tab class="bg-transparent" :ripple="true" flat exact name="profile" icon="person_pin" @click="profile" />

      </q-tabs>
  </q-footer>
</template>

<script>
import { get, sync, commit, dispatch } from 'vuex-pathify'

export default {
    name: 'AppFooter',

    data(){ return {
        ripple: true,
        tab: 'home'
    }},

    computed: {
        user: get('auth/user'),
        isMobile: get('app/isMobile'),
        bgColor(){
            return this.$root.darkmode ? 'bg-black' : 'bg-white'
        },
        qFooterClass(){
            return { 
                "text-grey-8"   : !this.$root.darkmode,
                "text-white"    : this.$root.darkmode,
                "bg-black"      : this.$root.darkmode,
                "bg-white"      : !this.$root.darkmode,
            }
        }
    },

    methods: {
        appButtonClick(){
            this.$root.leftDrawerOpen = !this.$root.leftDrawerOpen

            if(window.navigator)
              window.navigator.vibrate(0)
        },

        hold(){
            if(window.navigator)
              window.navigator.vibrate([100,10,200,30,500,10,1000])
        },

        profile(){
            if(!this.user){
                if(this.$route.name != 'login.req')
                  this.$router.push({ name: 'login.req' })

                return
            }

            if(this.$route.name != 'profile'){
              this.$router.push({ name: 'profile' })
            }
        }
    }
}
</script>

<style lang="css" scoped>
</style>
