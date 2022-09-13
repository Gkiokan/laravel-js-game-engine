<template lang="html">
<div class='password_reset'>
    <h5 class='text-weight-regular' id='password_change'>Change password</h5>

    <div class='text-right'>
    <form class="q-gutter-md">
        <q-input outlined stack-label type='password' v-model="form.currentPassword" label="Current password" />

        <q-separator class="q-my-md" />

        <q-input outlined stack-label type='password' v-model="form.newPassword" label="New password" />
        <q-input outlined stack-label type='password' v-model="form.newPassword_repeat" label="New password confirmation" />

        <q-btn class='q-mt-md' outline :color="validatePassword ? 'green-5' : 'red-5'" label="change my password" :disable="!validatePassword" @click="update" />
    </form>
    </div>
</div>
</template>

<script>
export default {
    name: 'PasswordChange',

    data(){ return {
        form: {
            newPassword: '',
            newPassword_repeat: '',
            currentPassword: ''
        }
    }},

    computed: {
        validatePassword(){
            if( this.form.newPassword.length >= 4 &&
                this.form.newPassword_repeat.length >= 4 &&
                this.form.newPassword === this.form.newPassword_repeat)
                return true

            return false
        }
    },

    methods: {
        update(){
            let data = { current_password: this.form.currentPassword, password: this.form.newPassword, password_confirmation: this.form.newPassword_repeat }
            this.$axios.post('/auth/user/password/change', data)
                  .then( ({ data }) => {
                      this.$q.notify({
                          message: this.$t('auth.password_changed'),
                          color: 'positive',
                          icon: 'done'
                      })

                      this.form =
                        {
                            newPassword: '',
                            newPassword_repeat: '',
                        }
                  })
                  .catch( e => {
                      this.$q.notify({
                          message: this.$t('auth.password_change_failed'),
                          color: 'negative',
                          icon: 'warning',
                      })
                  })
        }
    }
}
</script>
