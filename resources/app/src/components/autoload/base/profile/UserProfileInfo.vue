<template lang="html">
<div class='user_profile_info'>

    <h5 class='q-mb-lg text-weight-regular'>User Parameters</h5>

    <div class='user_info q-mb-lg'>
        <q-markup-table class="text-grey- bg-transparent no-shadow" unelevated>
            <thead>
              <tr class='text-grey-'>
                <th class="text-weight-bold text-left">Key</th>
                <th class="text-weight-bold text-left">Value</th>
                <th class="text-weight-bold text-right">Option</th>
              </tr>
            </thead>
            <tbody>
                <tr v-for="(item,i) in fields" :key="'fieldd_' + i">
                    <td>{{ item.key }}</td>
                    <td>{{ item.value }}</td>
                    <td> &nbsp; </td>
                </tr>
            </tbody>
        </q-markup-table>
    </div>

</div>
</template>

<script>
import moment from 'moment'

export default {
    name: 'UserProfileInfo',

    computed: {
        user(){ return this.$store.getters['auth/user'] },
        fields(){
            return [
                { key: 'Username', value: this.user.username },
                // { key: 'E-Mail', value: this.user.email },
                // { key: 'Handy', value: this.user.mobile },
                { key: 'Displayname', value: this.user.displayname },
                { key: 'User active', value: (this.user.active) ? 'Yes' : 'No' },
                { key: 'Activation send', value: this.user.activate_send },
                { key: 'Account verified', value: (this.user.email_verified_at && this.user.active) ? 'Aktiviert am ' + this.$util.getDate(this.user.email_verified_at) : '-' },
                { key: 'Info sent', value: this.user.info_send_at ? this.user.info_send_at : '-' },
                { key: '2 Faktor Auth Aktiv', value: this.user['2fa_enabled'] ? 'Active' : 'Disabled' },
                { key: '2 Faktor Auth Verifiziert', value: this.user['2fa_verified'] ? 'Yes' : 'No' },
            ]
        }
    },

}
</script>
