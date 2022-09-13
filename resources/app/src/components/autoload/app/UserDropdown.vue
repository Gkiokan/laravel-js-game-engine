<template>
  <div class='user_dropdown'>

      <q-btn round flat icon="fingerprint" @click="login" v-if="!user" aria-label="User Dropdown">
          <q-tooltip>Login</q-tooltip>
      </q-btn>

      <q-btn rounded flat no-caps v-if="user"  aria-label="User Dropdown">
          <q-avatar round size="40px" v-if="false">
              <q-img :src="user.avatar" :fit="'cover'" style="width: 40px; height: 40px" v-if="user.avatar" />
              <q-avatar color="teal" text-color="white" v-else> {{ user.initials }} </q-avatar>
          </q-avatar>

          {{ $root.user.username }}


          <q-tooltip v-if="0">{{ userName }}</q-tooltip>

          <q-popup-proxy content-class="no-shadow custom-shadow q-card-light-shadow">
            <div class="no-wrap q-pa-md text-left" :class="dropDownClass" style='width: 400px;'>

                <div class="column items-center q-pa-md">
                    <q-avatar size="120px">
                        <q-img :src="user.avatar" :fit="'cover'" style="height: 120px; height: 120px;" v-if="user.avatar" />
                        <q-avatar color="black" text-color="white" v-else> {{ user.initials }} </q-avatar>                        
                    </q-avatar>
                    
                    <div class="text-subtitle1 q-mt-md q-mb-xs">{{ user.displayname }}</div>
                    <div class='q-mb-lg'> <small> @{{ userName }} </small> </div>

                    <q-separator inset class="q-my-md q-my-xs bg-grey-8" />

                    <div>
                        <q-btn flat class='full-width' size="md" align="left"
                              :icon="$util.is(!$root.darkmode, 'brightness_2', 'brightness_5')"
                              :label="$util.is($root.darkmode, 'Change to Light Mode', 'Change to Dark Mode')"
                              @click="$root.darkmode = !$root.darkmode" />

                        <q-btn flat class='full-width' size="md" align="left" v-if="false"
                              :icon="$util.is(!privacy, 'fa fa-cookie', 'fa fa-cookie-bite')"
                              :label="$util.is(privacy, 'Disable analytics', 'Enable analytics')"
                              @click="$root.config.accept_privacy = !$root.config.accept_privacy" />

                        <q-separator inset class="q-my-md q-my-xs bg-grey-8" />
                    </div>

                    <div>
                        <q-btn size="md" color='primary text-blue-6' class='full-width' align='left' icon="person" flat label="My Profile" @click="$router.push({ name: 'settings.profile' })" v-if="!$root.isWeb" />
                        <q-btn size="md" color='primary text-blue-6' class='full-width' align='left' icon="person" flat label="My Web Profile" @click="$router.push({ name: 'web.profile' })" v-if="$root.isWeb" />

                        <q-btn size="md" color='primary text-blue-6' class='full-width' align='left' icon="settings" flat label="App Settings" @click="$router.push({ name: 'settings.app' })" v-if="$root.isAdmin" />
                        <q-separator inset class="q-my-md q-my-xs bg-grey-8" />
                    </div>

                    <q-btn outline push size="sm" color="primary" icon="power_settings_new" label="Logout" v-close-popup @click="logout" />

                    <br>
                    <q-checkbox disable keep-color dense size="xs" v-model="showAppBar" color="blue-8 text-blue-8" label="Application Bar" style='font-size: 12px;' v-if="0" />
                    <small class='text-grey-5' v-if="false">{{ $root.config.appTitle }} v{{ $root.config.version }}</small>
                </div>

            </div>
          </q-popup-proxy>

      </q-btn>

  </div>
</template>

<script>
import axios from 'axios'

export default {
    name: 'UserDropdown',

    data(){ return {
        showAppBar: false,
        horizontalFlow: true,
    }},

    computed: {
        user(){ return this.$store.getters['auth/user'] },
        userImage(){ return this.user.avatar ? this.user.avatar : 'https://cdn.quasar.dev/img/boy-avatar.png' },
        userName(){ return this.user.displayname ? this.user.displayname : this.user.initials },
        horizontal(){ return this.horizontalFlow ? 'column' : 'row reverse' },
        dropDownClass(){
            let items = [
                this.horizontal,
                this.$root.darkmode ? 'bg-dark text-grey-2' : 'bg-white text-grey-8 '
            ]
            return items.join(' ')
        },
        privacy(){ return this.$store.get('app/getPrivacy') },
    },

    methods: {
        logout(){
            // this.$store.dispatch('auth/logout')
            this.$store.dispatch('auth/logout', () => this.$router.push({ name: 'login' }) )
        },

        login(){
            this.move({ name: 'login' })
            return;

            let href = window.location.href
            let auth = this.$root.config.api.login
            let go = auth + '?cb=' + href

            window.location = go
        },

        move(params){
            // get comparable fullPaths
            let from  = this.$route.fullPath
            let to    = this.$router.resolve(params).fullPath

            if(from === to) {
              // handle any error due the redundant navigation here
              // or handle any other param modification and route afterwards
              return
            }

            // route as expected
            this.$router.push(params)
        }

    }
}
</script>
