<template lang="html">
<div class='user_avatar_upload'>
    <h5 class='text-weight-regular' id='user_avatar'>Profile Image</h5>

    <div class='row' :class="{ 'text-center' : $root.isMobile }" v-if="user">
        <div class='col-xs-12 col-sm-12'>
            <div>
              <UserAvatar :src="user.avatar" />
            </div>

            <div v-if="!newAvatar">
                <q-btn flat unelevated color="grey-8" icon="add" label="select new avatar" @click="$refs.avatar_upload_field.click()" style="width: 210px" />
            </div>

            <q-space style='height: 50px' v-if="$root.isMobile" />

            <q-btn-group unelevated v-if="newAvatar">
                <q-btn flat color="green-6" icon="upload" label="Upload new Avatar" @click="uploadAvatar" />
                <q-btn flat color="red" icon="delete" @click="newAvatar = null"/>
            </q-btn-group>

            <input type='file' class='hidden' ref='avatar_upload_field' @change="updateAvatarInputField">
        </div>
    </div>
</div>
</template>

<script>
import { get } from 'vuex-pathify'

export default {
    name: 'UserAvatarUpload',

    data(){ return {
        loading: false,
        newAvatar: null,
        progressDataAvatarUpload: 0,
    }},

    props: ['user'],

    computed: {
        // user: get('auth/user'),
    },

    methods: {
        updateAvatarInputField(val){
            let file = this.$refs.avatar_upload_field.files[0]
            this.newAvatar = file
            this.user.avatar = URL.createObjectURL(file)
        },

        uploadAvatar(bypass=false){
            if( !this.newAvatar ){
                if(bypass == false)
                this.$q.notify({
                    message: 'Kein Bild ausgewählt',
                    color: 'negative'
                })
                return
            }

            // Prepare
            let fd = new FormData()
            fd.append('image', this.newAvatar)

            // post it
            this.$axios.post('/auth/user/avatar', fd, {
                    headers : { 'Content-Type' : 'multipart/form-data' },
                    onUploadProgress: (progressEvent) => {
                        const totalLength = progressEvent.lengthComputable ? progressEvent.total : progressEvent.target.getResponseHeader('content-length') || progressEvent.target.getResponseHeader('x-decompressed-content-length');
                        if (totalLength !== null) {
                          this.progressDataAvatarUpload = Math.round( (progressEvent.loaded * 100) / totalLength );
                        }
                    }
                })
                .then( r => {
                    let data = r.data

                    // reset
                    this.progressDataAvatarUpload = 0
                    this.newAvatar = ''

                    // Update
                    this.$q.notify({
                        message: this.$t(data.message),
                        icon: 'done',
                        color: 'positive',
                    })

                    this.$store.dispatch('auth/updateUser', { user: data.user })
                })
                .catch( e => {
                    // console.log(e.response.data)
                    if(e.response.status == 413){
                        this.$q.notify({
                            message: "Bild zu groß.",
                            icon: 'error',
                            color: 'negative',
                        })
                    }

                    this.errors.errors   = e.response.data.errors ? e.response.data.errors : []
                    this.loading  = false
                })
        },

    }
}
</script>

<style lang="css" scoped>

</style>
