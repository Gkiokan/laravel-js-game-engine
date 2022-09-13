<template lang="html">
<div class='auth_support_links text-caption' :class="{ inverse }" >

    <a :href="home"> {{ $root.app }} </a> -
    <a href="" target="_blank"> Support </a>
    <br>

    <a :href="tos"> Terms of Services </a> -
    <a :href="privacy"> Privacy </a>

</div>
</template>

<script>
export default {
    name: 'AuthSupportLinks',

    props: {
        inverse: {
            type: Boolean,
            default: false,
        }
    },

    data(){ return {
        home: this.$router.resolve({ name: 'home' }).fullPath,
        tos: this.$router.resolve({ name: 'tos' }).fullPath,
        privacy: this.$router.resolve({ name: 'privacy' }).fullPath,
    }}
}
</script>
