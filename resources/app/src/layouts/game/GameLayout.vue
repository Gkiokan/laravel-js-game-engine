<template>
  <q-layout view="lHh lpr fFf" :class="$root.layoutClass" class="parent-page-bg">

    <q-page-container :class="$root.pageContainerClass" class="game-main">
        <router-view />
    </q-page-container>

  </q-layout>
</template>

<script>

import { get, sync } from 'vuex-pathify'

import { loadScript } from "vue-plugin-load-script";

export default {
    name: 'GameLayout',

    components: {
        
    },

    data(){ return {

    }},

    created(){
        this.$root.darkmode = true    
        this.$root.leftDrawerOpen = false            
        this.loadFooterConfig()
    },

    mounted(){
        // this.loadAds()
        this.$root.isWeb = true
    },


    watch:{
        '$route.name'(name){
            console.log("Route changed", name)
            this.checkCurrentRouteAndPatchSearch(name)
        },
    },      

    computed: {
        isMobile: get('app/isMobile', false),
        design: get('hb/design', false),
        params: sync('search/params', false),
    },

    methods: {

        loadAds(){
            this.$loadScript("https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=")
                .then(() => {
                    // Script is loaded, do something
                    console.log("ads loaded")
                })
                .catch(() => {
                    // Failed to fetch script
                });
        },

        loadFooterConfig(){
            this.$axios.get('/app/config/get')
                .then( ({ data }) => {
                    this.$root.footerText  = data.footerText
                    this.$root.footerLinks = data.footerLinks
                    this.$root.footerThirdParty = data.footerThirdParty
                })
                .catch( e => console.log(e.response) )
        },

    }
}
</script>