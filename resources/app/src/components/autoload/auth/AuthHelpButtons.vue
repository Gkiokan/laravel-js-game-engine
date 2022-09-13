<template lang="html">
  <div class='column q-mb-lg text-center text-grey-7'>
      <q-btn flat size='sm' class='full-width' :label="$t('auth.forgott_my_password')" @click="$router.push({ name: 'password.email' })" />
      <q-btn flat size='sm' class='full-width' :label="$t('auth.resend_verify_email')" @click="$router.push({ name: 'email.verify.resend' })" />
      <q-btn flat size='sm' class='full-width' :label="$t('auth.more_info')" @click="$router.push({ name: 'privacy' })" />
  </div>
</template>

<script>
export default {
    name: 'AuthHelpButtons'
}
</script>
