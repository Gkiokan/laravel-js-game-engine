<template>
  <q-layout view="hHh lpr lfr">

    <q-page-container :class="$root.pageContainerClass">
        <router-view :key="$route.path" />
    </q-page-container>

  </q-layout>
</template>

<script>
import MainHeader from './MainHeader'
import MainDrawer from './MainDrawer'

export default {
  name: 'FullLayout',

  components: {
      MainDrawer,
  },

  data(){ return {

  }}
}
</script>


<style lang='scss'>

// h1 {
//   background: black;
//   color: white;
//   font-weight: bold;
//   margin: 0px;
//   margin-bottom: 40px;
//   padding: 10px;
// }
//
// h3 {
//     color: #333;
// }
//
// .logo_top_left {
//     position: absolute; z-index: 10;
//     top: 0; left: 35px;
//     width: 150px; height: 80px;
//     display: block; margin: 0 auto; margin-bottom: 40px;
// }

</style>
