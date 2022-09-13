<template>
  <q-layout view="lHh lpr fFf" :class="$root.layoutClass" class="parent-page-bg">
    <div class="absolute-full bg-image" :class="{ 'fixed-image': $root.fixedBackgroundImage }" id="animation" style="z-index: -1;" />

    <WebHeader />

    <WebDrawer />

    <q-page-container :class="$root.pageContainerClass" class="page-web-bg" v-scroll="onScroll">
        <router-view />
    </q-page-container>

  </q-layout>
</template>

<script>
import WebHeader from './WebHeader'
import WebFooter from './WebFooter'
import WebDrawer from './WebDrawer'

import { get, sync } from 'vuex-pathify'

import { loadScript } from "vue-plugin-load-script";

export default {
    name: 'MainLayout',

    components: {
        WebHeader,
        WebFooter,
        WebDrawer,
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

        onScroll(pos){
            if(pos > this.$root.scrollOffset)
                this.$root.scrolled = true
            else 
                this.$root.scrolled = false
        },

    }
}
</script>

<style lang="scss">
body {
	// background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
	// background-size: 400% 400%;
	// animation: gradient 15s ease infinite;
	// height: 100vh;
  position: relative;

  &::before {
        content: '';
        position: absolute;
        top: 0px; left: 0; right: 0; bottom: 0;
        display: block;
        width: 100%;
        height: 100%;
        background: center center no-repeat;
        background-size: cover;
  }

  .bg-image {
        background-size: cover;

        &.fixed-image {
            position: fixed;
            top: 0px; left: 0px; right: 0px;
            height: 100vh;            
        }

        @media (max-width: 1024px) {
            position: fixed;
            top: 0px; left: 0px; right: 0px;
            height: 100vh;
        }
  }

  &.body--light {
        //   &::before, &.design-1::before {
        //       // background-image: url('/img/bg-gray-waves.webp');
        //   }

        //   &.design-2::before {
        //       // background-image: url('/img/bg-gray-triangles.jpg');
        //   }

    //   &.design-1 .bg-image,
    //   .bg-image {
    //     //   background-image: url('/img/bg-gray-waves.webp');
    //       background-image: url('/img/bg-gray-triangles.png');
    //   }

    //   &.design-2 .bg-image {
    //       background-image: url('/img/bg-gray-triangles.jpg');
    //   }
  }


  &.body--dark .bg-image {
    //   background: url('/img/bg-black-blue-dots.jpg');
    //   background: url('/img/bg-gray-stone.webp');
    //   background: url('/img/bg-gray-stone2.jpg');
      opacity: .6;
      background-position: center center;
      background-size: cover;
  }

}

.wrp {
  max-width: calc(100vw - 50px);
  margin: 0 auto;

  @media (min-width: 1200px){
      max-width: calc(100vw - 100px);
  }
}

.q-layout.bg-white {
  background: rgba(255,255,255,.1) !important;
}

@keyframes gradient {
	0% {
		background-position: 0% 50%;
	}
	50% {
		background-position: 100% 50%;
	}
	100% {
		background-position: 0% 50%;
	}
}

</style>
