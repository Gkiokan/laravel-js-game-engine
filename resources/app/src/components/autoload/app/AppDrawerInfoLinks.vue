<template>
    <div v-if="$root.isMobile">
        <div class="q-mt-md text-grey-8">
          <div class="flex flex-center q-gutter-xs">
            <router-link class="GNL__drawer-footer-link" :to="{ name: 'privacy' }" aria-label="Privacy">Privacy</router-link>
            <span> · </span>
            <router-link class="GNL__drawer-footer-link" :to="{ name: 'tos' }" aria-label="Terms">Terms</router-link>
            <span> · </span>
            <a class="GNL__drawer-footer-link" href="mailto:support@gkiokan.net" target="_blank" rel="nofollow" aria-label="Support">Support</a>
            <span> · </span>
            <router-link class="GNL__drawer-footer-link" :to="{ name: 'about' }" aria-label="About">About</router-link>
          </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AppDrawerInfoLinks',
}
</script>

<style lang="sass" scoped>
.GNL
  &__toolbar
    height: 64px

  &__toolbar-input
    width: 55%

  &__drawer-item
    line-height: 24px
    border-radius: 0 24px 24px 0
    margin-right: 12px
    .q-item__section--avatar
      .q-icon
        color: #5f6368

    .q-item__label
      color: #3c4043
      letter-spacing: .01785714em
      font-size: .875rem
      font-weight: 500
      line-height: 1.25rem

  &__drawer-footer-link
    color: inherit
    text-decoration: none
    font-weight: 500
    font-size: .75rem

    &:hover
      color: #000
</style>
