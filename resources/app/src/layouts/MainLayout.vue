<template>
  <q-layout view="lHh lpr fFf" :class="$root.layoutClass" class="parent-page-bg">
    <div class="absolute-full" id="animation" style="z-index: -1;" />

    <MainHeader v-if="!showCleanHeader" />
    <CleanHeader v-if="showCleanHeader" />

    <MainDrawer />

    <q-page-container :class="$root.pageContainerClass" class="page-bg">
        <router-view :key="$route.path" />
    </q-page-container>


    <!--
    <FooterElement />
    <AppFooter v-if="$q.platform.is.mobile && showFooter" />
    -->

  </q-layout>
</template>

<script>
import MainHeader from './MainHeader'
import MainDrawer from './MainDrawer'
import CleanHeader from './CleanHeader'
import AppFooter from './app/AppFooter'

import { get, sync, commit, dispatch } from 'vuex-pathify'

export default {
    name: 'MainLayout',

    components: {
        MainHeader,
        MainDrawer,
        CleanHeader,
        AppFooter,
    },

    data(){ return {
        showFooter: false,
        showCleanHeader: false
    }},

    created(){
        // this.loadScripts()
        this.$root.isWeb = false

        // var supportsPassive = false;
        // try {
        // var opts = Object.defineProperty({}, 'passive', {
        //     get: function() {
        //     supportsPassive = true;
        //     }
        // });
        // window.addEventListener("testPassive", null, opts);
        // window.removeEventListener("testPassive", null, opts);
        // } catch (e) {}

        // // Use our detect's results. passive applied if supported, capture will be false either way.
        // window.addEventListener('touchstart', () => {}, supportsPassive ? { passive: true } : false);         
    },

    mounted(){
        // check user auth
        if(this.$root.user && !['admin', 'mod', 'dev', 'upper'].includes(this.$root.user.role) ){
            alert('No rights to access the Dashboard')
            this.$store.dispatch('auth/logout', () => this.$router.push({ name: 'login' }) )
        }
    },

    computed: {
        isMobile: get('app/isMobile', false),
    },

    methods: {

    }
}
</script>
