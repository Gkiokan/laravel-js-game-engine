<template lang="html">
    <q-btn flat :label="$t('auth.back_to_login')" color="grey-7" class="q-pr-md q-my-lg" icon="chevron_left" @click="$router.push({ name: 'login' })" />
</template>

<script>
export default {
    name: 'BackToLoginButton'
}
</script>
