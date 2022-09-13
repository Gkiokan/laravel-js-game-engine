<template>
<div class="UserProfileKeys">

    <h5 class="text-weight-regular">Keys</h5>
    
    <p>
        Connect WCX with your 3rd party plattforms. <br>
        Provide your application keys to the respective applications. <br>                        
    </p>
    
    <q-markup-table unelevated flat wrap-cells class="bg-transparent">
        <thead>
            <tr>
                <th class="text-left">Key</th>
                <th class="text-left">Value</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(value,key) in settings" :key="'key_'+key">
                <td>{{ key }}</td>
                <td>
                    <q-input square filled standout v-model="settings[key]" />
                </td>
            </tr>
        </tbody>
    </q-markup-table>

    <q-space style="height: 40px" />

    <q-btn unelevated no-caps class="q-mt-md" color="green" icon="save" label="Save and Update" @click="save" />

</div>
</template>

<script>
import { copyToClipboard } from 'quasar'
import { get, sync } from 'vuex-pathify'

export default {
    name: 'UserProfileKeys',

    data(){ return {
        settings: {
            'fc_api_key': '',
            'imdb_api_key': '',
            'show_notification_on_new_entry': false,
        },
    }},

    computed: {
        user: sync('auth/user', false),
    },

    mounted(){
        // this.settings = { ...this.settings, ...this.user.settings }
        Object.keys(this.user.settings).map( key => {
            if( Object.keys(this.settings).includes(key) )
                this.settings[key] = this.user.settings[key]
        })
    },

    methods: {

        save(){
            this.user.settings = this.settings 

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
    },
}
</script>


