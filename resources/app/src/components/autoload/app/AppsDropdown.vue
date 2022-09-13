<template>
  <div class='app_dropdown'>

      <q-btn flat dense squared icon="apps" @click="loadInnerContent" aria-label="Apps Dropdown">
          <q-tooltip>Apps</q-tooltip>

          <q-popup-proxy content-class="bg-dark">
          <div class='bg-dark text-grey-3 q-border-none theme-dark' style='width: 230px; border:0px solid green;'>

              <transition appear enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" >
                  <AppsContent :apps="apps" :version="version" :loaded="loaded" />
              </transition>

              <q-inner-loading :showing="!loaded">
                  <q-spinner-gears size="50px" color="bg-dark" />
              </q-inner-loading>

          </div>
          </q-popup-proxy>
      </q-btn>

  </div>
</template>

<script>
import axios from 'axios'

export default {
    name: 'AppsDropdown',

    data(){ return {
        version: 1,
        loaded: false,
        apps: [

        ],

    }},

    mounted(){
    },

    computed: {
        user(){ return this.$store.getters['auth/user'] },
    },

    methods: {
        loadInnerContent(){
            if(!this.loaded)
            this.loadApps()
        },

        loadApps(){
            axios.get('/api/core/apps')
                 .then( r => {

                    if(r.data.apps)
                    this.apps = r.data.apps

                    setTimeout( () => {
                        this.loaded = true
                    }, 1)
                 })
                 .catch(e => console.log(e.response))
        }
    }
}
</script>

<style lang='scss' scoped>
.q-inner-loading {
    background: rgba(30,30,30,.5);
}
</style>
