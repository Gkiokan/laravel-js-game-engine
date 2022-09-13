<template>
    <div class='user_settings'>

        <template v-if="false">
            <h5 class='q-mb-md text-weight-regular' id='favorite'>Favoriten Settings </h5>
            <div class='text-grey-8'>
              Noch nicht vorhanden.
            </div>
            <q-space style='height: 50px' />
        </template>


        <h5 class='q-mb-md text-weight-regular' id='notification'>Benachrichtigungseinstellungen </h5>
        <q-markup-table class='text-grey-8 bg-transparent' flat>
            <thead>
              <tr>
                  <th class='text-left' style='width: 80px'>Option</th>
                  <th class='text-left'>Beschreibung</th>
              </tr>
            </thead>
            <tbody>
                <tr @click="settings.show_notification_on_task = !settings.show_notification_on_task" class="cursor-pointer">
                    <td> <q-checkbox size="sm" v-model="settings.show_notification_on_task" /> </td>
                    <td> Benachrichtigung bei neuen Aufgaben </td>
                </tr>

                <tr @click="settings.show_notification_on_comment = !settings.show_notification_on_comment" class="cursor-pointer">
                    <td> <q-checkbox size="sm" v-model="settings.show_notification_on_comment" /> </td>
                    <td> Benachrichtigung bei neuen Kommentaren </td>
                </tr>

                <tr @click="settings.show_notification_on_task_start = !settings.show_notification_on_task_start" class="cursor-pointer">
                    <td> <q-checkbox size="sm" v-model="settings.show_notification_on_task_start" /> </td>
                    <td> Benachrichtigung bei Aufgaben Start </td>
                </tr>

                <tr @click="settings.show_notification_on_chat_message = !settings.show_notification_on_chat_message" class="cursor-pointer">
                    <td> <q-checkbox size="sm" v-model="settings.show_notification_on_chat_message" /> </td>
                    <td> Benachrichtigung bei neuen Chat Anfragen </td>
                </tr>


                <!-- <tr @click="settings.notifyOnNewRequest = !settings.notifyOnNewRequest" class='cursor-pointer'>
                    <td> <q-checkbox size="sm" color="orange-8" v-model="settings.notifyOnNewRequest" /> </td>
                    <td> Benachrichtigung [Email] bei neuen Anfragen </td>
                </tr>
                <tr _click="settings.notifyOnComplaintComment = !settings.notifyOnComplaintComment" class='cursor-not-allowed'>
                    <td> <q-checkbox size="sm" color="green-2" v-model="settings.notifyOnComplaintComment" disable /> </td>
                    <td> Benachrichtigung [Email] bei neuen Kommentaren </td>
                </tr>
                <tr @click="settings.notifyOnPaymenIncome = !settings.notifyOnPaymenIncome" class='cursor-pointer'>
                    <td> <q-checkbox size="sm" color="orange-8" v-model="settings.notifyOnPaymenIncome" /> </td>
                    <td> Benachrichtigung [Email] bei Bezahlungen </td>
                </tr>
                <tr _click="settings.notifyOnNewFavorit = !settings.notifyOnNewFavorit" class='cursor-pointer'>
                    <td> <q-checkbox size="sm" color="grey-4" v-model="settings.notifyOnNewFavorit" disable /> </td>
                    <td> Benachrichtigung [Email] bei neuen Favorit </td>
                </tr>
                <tr _click="settings.notifyOnComplaint = !settings.notifyOnComplaint" class='cursor-pointer'>
                    <td> <q-checkbox size="sm" color="grey-4" v-model="settings.notifyOnComplaint" /> </td>
                    <td> Benachrichtigung [Email] beim Abschluss einer Beschwerde </td>
                </tr> -->
            </tbody>
        </q-markup-table>

    </div>
</template>

<script>
import axios from 'axios'
// import EventBus from '~/eventBus'
import { get, sync, commit, dispatch } from 'vuex-pathify'
import { Loading, QSpinnerPuff } from 'quasar'

export default {
    name: 'UserSettings',

    data(){ return {
        settingsDefault: {
            show_notification_on_task: true,
            show_notification_on_comment: true,
            show_notification_on_task_start: true,
            show_notification_on_chat_message: true,

            // notifyOnComplaintAccept: false,
            // notifyOnNewRequest: true,
            // notifyOnComplaintComment: false,
            // notifyOnPaymenIncome: true,
            // notifyOnNewFavorit: null,
            // notifyOnComplaint: null,
        }
    }},

    props: ['user'],

    // mounted(){
    //     this.$root.$on('profile.update', this.save)
    // },
    //
    // beforeDestroy(){
    //     this.$root.$off('profile.update', this.save)
    // },

    computed: {
        // user: get('auth/user'),
        settings(){
            return this.user && this.user.settings ? this.user.settings : this.settingsDefault
        },
        zoomLevelInKM(l){
            if(!this.user || !this.user.location || !this.user.location.lat){
              return ' -Zentrum eingeben- '
            }

            let zl = this.settings.zoom ? this.settings.zoom : this.settingsDefault.zoom
            let m = 156543.03392 * Math.cos(parseFloat(this.user.location.lat) * Math.PI / 180) / Math.pow(2, zl)
            let round = parseFloat(m).toFixed(2)
            return round
        }
    },


    methods: {
        // save(){
        //     // this.$q.notify('Saving User Settings')
        //     this.user.settings = this.settings
        //     // this.$store.set('auth/user', this.user)
        //     this.$store.dispatch('auth/updateSettings', this.user)
        //     // this.$store.set('profile/settings', this.settings)
        // },
    }
}
</script>
