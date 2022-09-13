<template>
    <div class='user_profile'>

        <h5 class='text-weight-regular' id='user_profile'>Account Information</h5>

        <div class='q-gutter-md' v-if="user">>
            <q-input outlined stack-label label="Username" v-model="user.username" disable />
            <q-input outlined stack-label label="E-Mail" v-model="user.email" disable />

            <q-separator class='q-my-md' >Test </q-separator>

            <q-input outlined stack-label label="Role" v-model="user.role"  disable />
        </div>

    </div>
</template>

<script>
import axios from 'axios'
// import EventBus from '~/eventBus'
import { get, sync, commit, dispatch } from 'vuex-pathify'
import { Loading, QSpinnerPuff } from 'quasar'

export default {
    name: 'UserProfile',

    data(){ return {
        profileDefault: {

        }
    }},

    props: ['user'],
    emits: ['save'],

    // mounted(){
    //     this.$root.$on('profile.update', this.save)
    // },
    //
    // beforeDestroy(){
    //     this.$root.$off('profile.update', this.save)
    // },

    computed: {
        // user: sync('auth/user'),
        profile(){
            return this.user && this.user.profile ? this.user.profile : this.profileDefault
        },
    },

    // watch: {
    //     profile: {
    //         deep: true,
    //         handler(){
    //             this.save()
    //         }
    //     }
    // },

    methods: {
        save(){
            console.log(this.user)
            // this.$q.notify('Saving User Settings')
            // this.user.profile = this.profile
            // this.$store.set('auth/user', this.user)
            // this.$store.dispatch('auth/updateSettings', this.user)
            // this.$store.set('profile/profile', this.profile)
        },
    }
}
</script>
