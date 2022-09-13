<template>
<div class="BaseLevel">


    <div class="map">
        <Tile :i="i" v-for="(i) in 200" :key="'tile_' + i" />
    </div>

    <Player />

    <div class='boundary' />
</div>
</template>

<script>
import { get } from 'vuex-pathify'

export default {
    name: 'BaseLevel',

    data(){ return {
        tilesWidth: 200,
        tilesHeight: 100,
    }},

    computed: {
        window: get('game/window', false),
    },

    mounted(){
        this.$root.log("Loading BaseLevel Component")
    },    
}
</script>

<style lang="scss" scoped>
.BaseLevel {    
    position: absolute; z-index: 100;
    border: 1px solid green;

    .map {
        padding: relative; z-index: 110;
        display: flex;
        flex-wrap: wrap;
        width: 800px; 
        height: 500px;
        border: 0px solid yellow;
    }

    .boundary {
        position: fixed; z-index: 2;
        top: 0px; left: 0px; right: 0px; bottom: 0px;
        border: 100px solid maroon;
        border-left-width: 200px;
        border-right-width: 200px;
        opacity: .2;
    }
}
</style>