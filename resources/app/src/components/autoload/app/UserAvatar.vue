<template lang="html">
  <div class='avatar'>
      <q-avatar square size="210px">
          <q-img :src="src" :fit="'cover'" class='avatar_image' v-if="src" />
          <q-icon size="100px" name="far fa-user" color="white" v-else />
      </q-avatar>
  </div>
</template>

<script>
export default {
    name: 'UserAvatar',

    props: {
        src: {
          type: String,
          default: false,
        },
    }
}
</script>

<style lang="scss" scoped>
$avatar_white_border: 0px;
$avatar_colored_border: 0px;

.avatar {
    display: inline-block;
    height: 210px;
    width: 210px;
    margin: 0 auto;
    padding: #{$avatar_colored_border};
    border-radius: 10px;
    overflow: hidden;
    // background-image: linear-gradient(
    //     45deg,
    //     #ffa95f 5%,
    //     #f99c4a 15%,
    //     #f47838 30%,
    //     #e75157 45%,
    //     #d92d7a 70%,
    //     #cc2a92 80%,
    //     #c32e92 95%
    // );

    background-color: transparent; // #297bf7;
    background: -moz-linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
    background: -webkit-linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);
    background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f09433', endColorstr='#bc1888',GradientType=1 );

    .avatar_image {
        height: 205px;
        width: 205px;
        border: #{$avatar_white_border} solid white;
        border-radius: 10px;
        overflow: hidden;
        transform: scale(1);
    }
}

.body--dark {
  .avatar_image {
      border-color: #1d1d1d;
      border-radius: 10px;
      overflow: hidden;
  }
}
</style>
