<template lang="html">
<div class='google2fa' v-if="user">
    <h5 class='text-weight-regular q-mb-sm' id='google2fa'>Two Factor Authentication </h5>

    <div v-if="!user.g2fa">
        Your 2FA with Google is not activated. Please activate first. <br>

        <q-btn unelevated class="q-mt-md" color="green" label="Activate 2FA" @click="activate" />
    </div>


    <div v-if="user.g2fa">
        Two Factor is activated. Here is your QR Code for your Google Auth app. <br>
        Instruction: <br>
        Scan this QR Code on your mobile phone with the Google Authenticator App. <br>
        You can generate a time based TOTP Token for the login with it. <br>
        While 2FA is enabled, you will be asked on login for the TOTP Token. <br>
        <br>

        <div class='row'>
            <div class=''>
                <q-skeleton type="rect" style="width: 200px; height: 200px" v-if="!qr" />
                <div v-html="qr" style="width: 200px; height: 200px;" v-if="qr" />
            </div>
            <div class='col' v-if="qr">
                <q-input outlined dense v-model="key" label="Test TOTP Token" style="margin-top: 15px;">
                    <template v-slot:append>
                        <q-btn flat icon="send" @click="verify" />
                    </template>
                </q-input>
            </div>
        </div>
        <q-space style="height: 30px" />

        <q-btn unelevated color="negative" label="Disable 2FA" @click="disable" />
    </div>

</div>
</template>

<script>
import { get } from 'vuex-pathify'

export default {
    name: 'Google2FA',

    data(){ return {
        qr: '',
        key: '',
        busy: false,
    }},

    mounted(){
        this.load()
    },

    computed: {
        user: get('auth/user', false),
    },

    methods: {
        load(){
            this.$axios.get('/auth/user/2fa/get')
                .then( ({ data }) => {
                    this.qr = data.qr
                })
                .catch( e => console.log(e) )
        },

        verify(){
            this.$axios.post('/auth/user/2fa/verify', { key: this.key })
                .then( ({ data }) => {
                    this.$q.notify({
                        message: "Token valid",
                        color: 'green',
                        icon: 'done',
                    })
                })
                .catch( e => {
                    this.$q.notify({
                        message: "Token error",
                        color: 'negative',
                        icon: 'error',
                    })
                })
        },

        activate(){
            this.$axios.post('/auth/user/2fa/activate')
                .then( ({ data }) => {
                    this.$q.notify({
                        message: "Two Factor Authentication activated",
                        color: 'green',
                        icon: 'done'
                    })
                    this.qr = data.qr
                    this.$store.dispatch('auth/fetchUser')
                })
                .catch( e => {
                    this.$q.notify({
                        message: "Error on activation",
                        color: 'negative'
                    })
                })
        },

        disable(){
            this.$axios.post('/auth/user/2fa/disable')
                .then( ({ data }) => {
                    this.$q.notify({
                        message: "Two Factor Authentication disabled",
                        color: 'green',
                        icon: 'done'
                    })
                    this.qr = null
                    this.$store.dispatch('auth/fetchUser')
                })
                .catch( e => {
                    this.$q.notify({
                        message: "Error on 2fa disable action",
                        color: 'negative'
                    })
                })
        }
    }
}
</script>

<style lang="css" scoped>
</style>
