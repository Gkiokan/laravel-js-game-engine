<template>
  <q-layout view="lHh LpR fFf" x-view="lHr lpr lFr" :class="$root.layoutClass" class="parent-page-bg">
    <div class="absolute-full" id="animation" style="z-index: -1;" />

    <MainHeader v-if="!showCleanHeader" />
    <CleanHeader v-if="showCleanHeader" />

    <DashDrawer />

    <q-page-container :class="$root.pageContainerClass" class="page-bg">
        <router-view :key="$route.path" />
    </q-page-container>

    <AppFooter v-if="$q.platform.is.mobile && showFooter" />

  </q-layout>
</template>

<script>
import MainHeader from './../MainHeader'
import MainDrawer from './../MainDrawer'
import DashDrawer from './DashDrawer'
import CleanHeader from './../CleanHeader'
import AppFooter from './../app/AppFooter'

import { get, sync, commit, dispatch } from 'vuex-pathify'

export default {
    name: 'DashLayout',

    components: {
        MainHeader,
        MainDrawer,
        DashDrawer,
        CleanHeader,
        AppFooter,
    },

    data(){ return {
        showFooter: false,
        showCleanHeader: false
    }},

    computed: {
        isMobile: get('app/isMobile', false),
    }
}
</script>
