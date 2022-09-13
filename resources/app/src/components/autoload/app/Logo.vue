<template lang="html">
    <div :style="style" class='app_logo' />
</template>

<script>
export default {
    name: 'Logo',

    props: {
        width: {
            type: String,
            default: '150px'
        },

        height: {
            type: String,
            default: '150px'
        },

        img: {
            type: String,
            default: '/img/logo.svg',
        },

        center: {
            type: Boolean,
            default: false,
        },

        light: {
            type: Boolean,
            default: false,
        },
        dark: {
            type: Boolean,
            default: false,
        },
    },

    data(){ return {

    }},

    computed: {
        backgroundImage(){
            let file = 'logo-dark'
            
            // let file = this.$root.darkmode ? 'logo-white' : 'logo-dark'
            if(this.light)
                file = 'logo-white'
            
            if(this.dark)
                file = 'logo-dark'

            return 'url(' + require('public/img/'+file+'.svg') + ')'
        },

        style(){
            let s = {
                backgroundImage: this.backgroundImage,
                width: this.width,
                height: this.height,
            }

            if(this.center)
              s.margin = "0px auto"

            return s
        }
    }

}
</script>

<style lang="scss" scoped>
.app_logo {
    background-position: center center;
    background-size: cover;
    background-repeat: no-repeat;
    border: 0px solid red;
    max-width: 100%;
    transition: all .3s;

    @media (max-width: 550px){
        max-width: 280px;
    }
}
</style>
