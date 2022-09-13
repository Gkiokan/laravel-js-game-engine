<template lang="html">
  <q-avatar square :size="size + 'px'" class="q-mr-md UserAvatarImage" v-if="user">
      <q-img square class="img" :fit="'cover'" :src="user.avatar" v-if="user.avatar" />
      <q-icon size="20px" name="far fa-user" color="grey-4" v-else />
  </q-avatar>
  <q-icon size="20px" name="far fa-user" color="grey-4" v-else />
</template>

<script>
export default {
    name: 'UserAvatarImage',

    props: {
        user: {
            type: Object,
            default: null
        },

        size: {
            type: Number,
            default: 40,
        }
    }
}
</script>

<style lang="scss" scoped>
.UserAvatarImage {
    display: block;
    margin: 0 auto;

    background-color: transparent; // #297bf7;
    background: -moz-linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
    background: -webkit-linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);
    background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f09433', endColorstr='#bc1888',GradientType=1 );

    border-radius: 4px;
    overflow: hidden;

    .img {
        width: 95%;
        height: 95%;

        border-radius: 4px;
        overflow: hidden;
    }
}
</style>
