<template>
    <div class='row justify-center' :style='style'>
    <div class='container-fluid'>
        <slot />
    </div>
    </div>
</template>

<script>
export default {
    name: 'ContainerFluid',

    props: ['fullHheight'],

    data(){ return {
        style: '',
    }},

    mounted(){
        if(this.fullHeight)
          this.style = this.style + 'height: 100%;'
    }

}
</script>

<style lang="scss">
.container-fluid {

}
</style>
