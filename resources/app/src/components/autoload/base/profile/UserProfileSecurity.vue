<template lang="html">
<div class='UserProfileSecurity'>

    <PasswordChange />
    <q-space style='height: 30px' />

    <i>Info:</i> <br>
    Take a secure password as you wish. <br>
    It should have at least 8 characters. <br>
    Use special character like §$!. <br>
    <q-space style='height: 30px' />

    <Google2FA />
    <q-space style='height: 100px' />
</div>
</template>

<script>
import PasswordChange from './components/PasswordChange'
import { get, sync } from 'vuex-pathify'

export default {
    name: 'UserProfileSecurity',

    components: {
        // PasswordChange
    },

    data(){ return {

    }},

    computed: {
        user: get('auth/user', false)
    },

    methods: {
        update(){

        }
    }
}
</script>
