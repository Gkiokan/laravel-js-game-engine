<template>
    <div class='UserQuickInfo'>

        <q-img class="absolute-top bg-dark" _src="require('~/../images/bg/hexa_black_turkis.png')"
                style="height: 150px" v-if="user"
                transition="jump-down"
                >
            <div class="absolute-bottom bg-transparent">
                <q-avatar size="72px" class="q-mb-sm">
                  <img :src="this.user.avatar ? this.user.avatar : '' " v-if="this.user.avatar">
                  <q-icon name='account_circle' v-else />
                </q-avatar>
                <div class="text-weight-bold">{{ user.name }}</div>
                <div>@{{ username }}</div>
            </div>
        </q-img>

        <q-img class="absolute-top bg-dark" _src="require('~/../images/bg/hexa_red.png')"
                style="height: 150px;" :img-style="{ 'background-size': 'cover' }" v-if="!user"
                transition="jump-down"
                >
            <div class="absolute-bottom bg-transparent">
                <q-avatar size="72px" class="q-mb-sm">
                  <img src="https://cdn.quasar.dev/img/boy-avatar.png" v-if="false">
                  <q-icon name='account_circle' v-else />
                </q-avatar>
                <div class="text-weight-bold">Guest User</div>
                <div>@guest</div>
            </div>
        </q-img>

    </div>
</template>

<script>
export default {
    name: 'UserQuickInfo',

    data(){ return {

    }},

    mounted(){

    },

    computed: {
        user(){ return this.$store.getters['auth/user'] },

        username(){ return this.user.username ? this.user.username : this.user.name },

        userImage(){ return this.user.avatar ? this.user.avatar : 'https://cdn.quasar.dev/img/boy-avatar.png' }


    },

    methods: {

    }

}
</script>

<style>

</style>
