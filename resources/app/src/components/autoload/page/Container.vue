<template>
    <div class='row justify-center'>
      <div class='container' :class="full ? 'full': ''" :style="style">
          <slot />
      </div>
    </div>
</template>

<script>
export default {
    name: 'Container',

    props: {
        width: {
            type: String,
            default: ''
        },
        full: {
            type: Boolean,
            default: false,
        }
    },

    computed: {
        style(){
            if(this.full) return {};

            if(this.width){
                return { 'max-width': this.width }
            }

            return {}
        }

    }
}
</script>

<style lang="scss" scoped>
.container {
    width: 1920px;
    max-width: 80vw;
    margin: 0 auto;

    @media (max-width: 1023px){
        max-width: 95vw;
    }

    &.full {
        width: 100%;
        max-width: none !important;
    }
}
</style>
