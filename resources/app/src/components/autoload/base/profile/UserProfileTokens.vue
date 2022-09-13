<template>
<div class="UserProfileTokens">

    <h5 class="text-weight-regular">Tokens</h5>
    
    <p>
        Create App Tokens to use with 3rd party apps. <br>
        This token is bind to your User Account, so <b>keep it secure</b>. <br>
        Once you create a new Token, you should save it as it will be not be shown anymore. <br>
    </p>

    <div class="q-mb-md">
        <q-btn unelevated class="q-mb-md" color="green" icon="add" label="Create new App Token" @click="create" />                

        <q-field standout label="New App Token (copy now, as it will be encrypted)" stack-label v-if="newToken">
            <template v-slot:control>
                <div class="self-center full-width no-outline" tabindex="0">{{ newToken }}</div>
            </template>
            <template v-slot:append>
                <q-btn unelevated round icon="content_copy" @click="copyNewToken" />
            </template>
        </q-field>
    </div>
    
    <q-markup-table unelevated flat wrap-cells class="bg-transparent">
        <thead>
            <tr>
                <th class="text-left">Name</th>
                <th class="text-right" style="width: 150px">Created</th>
                <th style="width: 50px">Clear</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(token,i) in tokens" :key="'token_'+i">
                <td class="">{{ token.name == 'upper' ? 'Generated Auth Token' : token.name }}</td>
                <td class="text-right">{{ $util.getDate(token.created_at, 'DD.MM.Y HH:mm') }}</td>
                <td> <q-btn flat round color="red" icon="clear" @click="clearToken(token)" /> </td>
            </tr>
        </tbody>
    </q-markup-table>
</div>
</template>

<script>
import { copyToClipboard } from 'quasar'
export default {
    name: 'UserProfileTokens',

    data(){ return {
        newToken: '',
        tokens: [],
    }},

    mounted(){
        this.load()
    },

    methods: {
        load(){
            this.$axios.post('/auth/user/tokens')
                .then( ({ data }) => {
                    this.tokens = data.items
                })
                .catch( e => console.log(e.response) )
        },

        create(){
            this.$axios.post('/auth/user/token/create')
                .then( ({ data }) => {
                    this.newToken = data.item
                    this.tokens = data.items
                })
                .catch( e => console.log(e.response) )
        },

        clearToken(token){
            this.$axios.post('/auth/user/token/clear', token)
                .then( ({ data }) => {
                    this.tokens = data.items
                })
                .catch( e => console.log(e.response) )            
        },

        copyNewToken(){
            copyToClipboard(this.newToken)
                .then( () => {
                    this.$q.notify({
                        message: "Token copied to your clipboard",
                        icon: "done",
                        color: "positive",
                    })
                })
                .catch( () => {
                    this.$q.notify({
                        message: "Error on copy. Copy the token yourself.",
                        icon: "warning",
                        color: "negative",
                    })
                })
        }
    },
}
</script>


