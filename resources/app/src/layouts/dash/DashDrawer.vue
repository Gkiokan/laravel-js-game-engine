<template lang="html">
  <q-drawer
    v-model="$root.leftDrawerOpen"
    :show-if-above="true"
    :bordered="!$root.darkmode && false"
    :elevated="false"
    :overlay="false"
    :x-breakpoint="800"
    :content-class="$root.drawerClass"
    :behavior="'default'"
  >
    <div class='q-mt-lg'>
        <Logo light width="200px" height="200px" :center="true" v-if="1" />

        <q-img :src="false ? 'img/tc-white.svg' : 'img/tc.svg'"
            style='width: 200px; display: block; margin: 0 auto;' v-if="0" />
    </div>

    <q-list>
        <q-item-label header>
          Dashboard Menü
        </q-item-label>

        <EssentialLink
          v-for="link in dashLinks"
          :key="link.title"
          v-bind="link"
          v-if="show(link)"
        />
    </q-list>

    <q-separator inset class='q-my-md' />

    <q-list>
        <q-item-label header> Help-Center </q-item-label>

        <EssentialLink
          v-for="link in supportLinks"
          :key="link.title"
          v-bind="link"
          v-if="show(link)"
        />
    </q-list>


  </q-drawer>
</template>

<script>
import linksData from '~/config/drawerLinks'

export default {

  data(){ return {
      essentialLinks: linksData.menu,
      dashLinks: linksData.dash,
      supportLinks: linksData.support,
  }},

  computed: {
      user(){ return this.$store.getters['auth/user'] },
  },

  components: {

  },

  methods: {
      show(link){
          if(link.guest){
              return this.user ? false : true
          }

          if(link.auth){
              return this.user ? true : false
          }

          return true
      }
  }
}
</script>

<style lang="scss" scoped>
</style>
