<template lang="html">
  <q-drawer
    v-model="$root.leftDrawerOpen"
    :show-if-above="true"
    :bordered="!$root.darkmode && false"
    :elevated="false"
    :overlay="false"
    :width="350"
    :class="$root.drawerClass"
    class="bg-drawer-dashboard"
    :behavior="'default'"
  >

    <Logo light center width="100px" height="100px" />

    <q-space style="height: 20px" />

    <div class='q-mt-lg q-px-lg' v-if="false">
        <div class="text-center">
            <q-avatar round size="120px" color="teal" text-color="white" align="center">
                <img :src="user.avatar" :fit="'fill'" v-if="user.avatar" style="border: 2px solid white;" />
                <span  v-else>{{ user.initials }} </span>
            </q-avatar>
        </div>
        <div class='text-h6 text-weight-bold text-white q-my-md' v-if="user">{{ user.displayname }} </div>
    </div>


    <div class="q-pa-sm q-gutter-md">
        <q-list v-if="user">
            <EssentialLink
              v-for="link in essentialLinks"
              :key="link.title"
              v-bind="link"
              v-show="show(link)"
            />
        </q-list>

        <template v-if="user">
          <q-separator inset class='q-my-md'/>
          <q-list>
              <q-item-label header>User Menu</q-item-label>
              <EssentialLink v-for="link in userLinks" :key="link.title" v-bind="link" />
          </q-list>
        </template>

        <q-separator inset class='q-my-md' />
        <q-list>
            <q-item-label header>
              Settings
            </q-item-label>

            <EssentialLink
              v-for="link in adminLinks"
              :key="link.title"
              v-bind="link"
              v-show="show(link)"
            />
        </q-list>

        <q-separator inset class='q-my-md' />

        <AuthSupportLinks :inverse="true" />

    </div>
  </q-drawer>
</template>

<script>
import EssentialLink from 'components/EssentialLink'
import linksData from '~/config/drawerLinks'

export default {

  data(){ return {
      essentialLinks: linksData.menu,
      supportLinks: linksData.support,
      adminLinks: linksData.admin,
      userLinks: linksData.users,
      showLogo: 1,
      showEmployeeMenu: false,

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

  computed: {
      user(){ return this.$store.getters['auth/user'] },
      isAdmin(){
          if(this.user)
            if(this.user.role)
              if(this.user.role.includes('admin'))
                return true

          return false
      }
  },

  components: {
      EssentialLink,
  },

  methods: {
      show(link, admin=false){
          if(!link) return true

          if(admin){
              return this.user.role.includes('admin') ? true : false
          }

          if('role' in link && link.role.length && this.user){
              return link.role.includes(this.user.role)
          }

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

<style lang="scss">

</style>
