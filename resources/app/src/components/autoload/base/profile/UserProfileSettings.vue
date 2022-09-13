<template lang="html">
<div class='user_profile_settings'>

    <UserSettings :user="user" />
    <q-space style='height: 50px' v-if="!$root.isMobile" />

    <div class='text-left'>
        <q-btn rounded no-caps class="q-mt-md" color="primary" label="Speichern"  @click="save" />
    </div>

    <q-space style='height: 30px' />
</div>
</template>

<script>
import { get, sync, set } from 'vuex-pathify'

// import UserLocation from './components/UserLocation'
// import UserSettings from './components/UserSettings'
// import UserAvatarUpload from './components/UserAvatarUpload'
// import UserProfile from './components/UserProfile'

export default {
    name: 'UserProfileSettings',

    components: {
        // UserLocation,
        // UserSettings,
        // UserAvatarUpload,
        // UserProfile,
    },

    data(){ return {

    }},

    mounted(){
        this.load()
    },

    computed: {
        user: get('auth/user'),
    },

    methods: {
        load(){
            // this.$axios.get('/api/auth/user').then( ({ data }) => this.user = data.user )
            this.$store.dispatch('auth/fetchUser')
        },

        save(){
            this.$axios.post('/api/auth/user/update', this.user)
                .then( ({ data }) => {
                    console.log(data)
                    this.$q.notify({
                        message: this.$t('user.profile_updated'),
                        color: 'positive',
                        icon: 'done'
                    })

                    this.$store.dispatch('auth/updateUser', { user: data.user })
                })
                .catch( e => console.log(e.response) )
        }
    }
}
</script>

<style lang="css" scoped>
</style>
