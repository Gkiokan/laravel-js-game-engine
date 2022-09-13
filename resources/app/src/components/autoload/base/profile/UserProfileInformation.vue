<template lang="html">
<div class='user_profile_settings'>

    <UserProfile :user="user" />
    <q-space style='height: 30px' />

    <div class='text-left'>
        <q-btn unelevated no-caps class="q-mt-md" color="green" icon="save" label="Save and Update" @click="save" />
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
    name: 'UserProfileInformation',

    components: {
        // UserLocation,
        // UserSettings,
        // UserAvatarUpload,
        // UserProfile,
    },

    data(){ return {
        // user: null,
    }},

    mounted(){
        this.load()
    },

    computed: {
        user: get('auth/user', false),
    },

    methods: {
        load(){
            // this.$axios.get('/auth/user').then( ({ data }) => this.user = data.user )
            this.$store.dispatch('auth/fetchUser')
        },

        save(){
            this.$axios.post('/auth/user/update', this.user)
                .then( ({ data }) => {
                    this.$q.notify({
                        message: this.$t('user.profile_updated'),
                        color: 'positive',
                        icon: 'done'
                    })

                    this.$store.dispatch('auth/updateUser', { user: data.user })
                })
                .catch( e => {
                    let data = e.response.data

                    if(data.errors && data.errors.mobile)
                        this.$q.notify({
                            message: data.errors.mobile[0],
                            color: 'negative',
                            icon: 'warning'
                        })
                })
        }
    }
}
</script>

<style lang="css" scoped>
</style>
