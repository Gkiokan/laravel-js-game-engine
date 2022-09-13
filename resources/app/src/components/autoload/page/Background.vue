<template>
  <div class='background z-1' :class="className">
      <HeaderContainer :image="image" :overlay="overlay" :opacity="opacity" :blur="blur" :css="css" />
  </div>
</template>

<script>
export default {
    name: 'Background',

    // props: ['image', 'overlay', 'opacity', 'blur'],
    props: {
        image: String,
        className: String,
        overlay: {

        },
        opacity: {},
        blur: {},
    },

    data(){ return {
        css: {
            minHeight : '100%'
        }
    }}
}
</script>

<style lang='scss' scoped>

.background {
    // position: absolute; z-index: 1;
    // top: 0; left: 0; right: 0; bottom: 0;

    display: block;
    width: 100%;
    height: 100%;
    background-size: 120%;
}

</style>
