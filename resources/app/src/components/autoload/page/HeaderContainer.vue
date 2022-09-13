<template lang="html">
    <div class='fill-height pa0 bg HeaderContainer items-center row justify-center' :style="finalStyle">
        <div class='background' :style="backgroundStyle">
            <div class='backgroundImage bg' :style='{ backgroundImage: background }' />
            <div class='backgroundImage overlay' :class="overlay" :style="overlayStyle"/>
        </div>
        <div class='content'>
            <div v-if="title" class='text-h1 :font-cinzel font-title'>{{ title }}</div>
            <pre v-if="sub">{{ sub }}</pre>
        </div>
    </div>
</template>

<script>
export default {
    name: 'HeaderContainer',

    props: ['title', 'sub', 'image', 'css', 'opacity', 'blur', 'overlay'],

    data(){ return {
        imageSrc: '',
        background: this.image ? 'url(' + this.image + ')' : '',
        coreStyles: {
            // backgroundImage : this.image ? 'url(' + this.image + ')' : '',
            minHeight: "800px",
        },
    }},

    computed: {
        finalStyle(){ return this.css ? Object.assign({}, this.coreStyles, this.css) : this.coreStyles },
        overlayStyle(){ return { opacity: this.opacity ? this.opacity : '0.3' } },
        backgroundStyle(){ return { filter: this.blur ? 'blur(' + this.blur + 'px)' : '', transform: 'scale(1.1)' } }
    },

    mounted(){

    }
}
</script>

<style lang="scss" scoped>
.HeaderContainer {
    position: relative;
    overflow: hidden;
}

.background {
    position: absolute;
    top: 0; right: 0; bottom: 0; left: 0;
}

.backgroundImage {
    position: absolute;
    top: 0; right: 0; bottom: 0; left: 0;
    z-index: 3;

    &.overlay {
        z-index: 4;
    }
}

.content {
    position: relative;
    z-index: 5;
}

.bg {
    background: #222 no-repeat center center;
    // background-image: url('~/../images/polygon_mix.jpg');
    background-size: cover;
}
</style>
