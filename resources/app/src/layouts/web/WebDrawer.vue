<template lang="html">
  <q-drawer
    v-model="$root.leftDrawerOpen"
    :show-if-above="false"
    :bordered="!$root.darkmode && false"
    :elevated="false"
    :overlay="false"
    :width="300"    
    class="bg-drawer"
    _class="$root.drawerClass"
    :behavior="'mobile'"
    v-if="$root.showDrawer"
  >

  <Logo light center width="100px" height="100px" />

  <div class="q-pa-sm q-gutter-md">
        <q-list>
            <q-item clickable tag="a" v-if="$root.user">
                <q-item-section avatar v-if="false">
                    <q-avatar>
                        {{ $root.user.username[0] }}
                    </q-avatar>
                </q-item-section>
                <q-item-section>
                    <q-item-label> Hi, {{ $root.user.displayname }} </q-item-label>
                </q-item-section>
                <q-item-section avatar>
                    <q-btn round icon="settings" @click="$router.push({ name: 'web.profile' })" />
                </q-item-section>                
            </q-item>            
            <q-separator class="q-my-sm" v-if="$root.user" />

            <EssentialLink key="login" v-bind="{ title: 'Login | Signup', to: { name: 'login' }, icon: 'fingerprint' }" v-show="!$root.user" />
            <q-separator class="q-my-sm" v-if="!$root.user" />
            
            <q-item clickable tag="a" @click="$router.push({ name: 'dashboard' })" v-if="$root.showDashboard">
                <q-item-section avatar>
                    <q-icon name="dashboard" />
                </q-item-section>
                <q-item-section>
                    <q-item-label> Dashboard </q-item-label>
                </q-item-section>
            </q-item>
            <q-separator class="q-my-sm" v-if="$root.showDashboard" />


            <EssentialLinkTranslated v-for="(link,i) in menuLinks.search" :key="'drawer_link_search' + i" v-bind="link" />            
            <q-separator class="q-my-sm" />

            <EssentialLinkTranslated v-for="(link,i) in menuLinks.top" :key="'drawer_link_top' + i" v-bind="link" />            

            
            <q-separator class="q-my-sm" v-if="$root.user" />
            <q-item clickable tag="a" @click="logout" v-if="$root.user">
                <q-item-section avatar>
                    <q-icon name="power_settings_new" />
                </q-item-section>
                <q-item-section>
                    <q-item-label> Logout </q-item-label>
                </q-item-section>
            </q-item>
        </q-list>

        <q-separator class="q-my-sm" />

        <q-space style="height: 40px" />

        <AuthSupportLinks />

        <q-space style="height: 40px" />
  </div>



  </q-drawer>
</template>

<script>
import EssentialLink from 'components/EssentialLink'
import EssentialLinkTranslated from 'components/EssentialLinkTranslated'
import menuLinks from '~/config/menuLinks'

export default {

  components: {
      EssentialLink,      
      EssentialLinkTranslated,
  },

  data(){ return {
      showLogo: 1,
      showEmployeeMenu: false,
      menuLinks,

      contentStyle: {
        // backgroundColor: 'rgba(0,0,0,0.02)',
        // background-color: #4158D0;
        // "background-image": this.$root.darkmode ? '' : "linear-gradient(to top, #6a85b6 0%, #bac8e0 100%)"

        // color: '#555'
      },

      contentActiveStyle: {
        backgroundColor: '#eee',
        // color: 'black'
      },

      thumbStyle: {
        right: '2px',
        borderRadius: '5px',
        backgroundColor: '#027be3',
        width: '5px',
        opacity: 0.75
      }
  }},

  methods: {
      logout(){
          // this.$store.dispatch('auth/logout')
          this.$store.dispatch('auth/logout', () => this.$router.push({ name: 'home' }) )
      },

      open(link){
          window.open(link)
      },

  }
}
</script>

<style lang="scss" scoped>

</style>
