<template lang="html">
  <q-btn flat size='sm' class='q-mt-md' color="grey-8"
        :icon="!$root.darkmode ? 'brightness_2' : 'brightness_5' "
        :label="$root.darkmode ? $t('style_to_light') : $t('style_to_dark') "
        @click="$root.darkmode = !$root.darkmode" />
</template>

<script>
export default {
    name: 'DarkmodeButton'
}
</script>
