<template>
<div class="Debug">
    <div class='bg-orange-10 text-bold q-px-sm text-center cursor-pointer' @click="show = !show">Debug </div>

    <div v-if="show">
        <div> Player: 0 </div>
        <div> Enemies: 0 </div>
        <div> Level: {{ level }} </div>
        <div> Acceleration: {{ acceleration }} / {{ dash }} </div>
        <div> Resolution: 
            <pre>{{ window }}</pre>
        </div>
        <div> Coords: 
            <pre>{{ coords }}</pre>
        </div>
        <div> Keys: 
            <pre>{{ keys }}</pre> 
        </div>
    </div>

</div>
</template>

<script>
import { get } from 'vuex-pathify'

export default {
    name: 'Debug',

    data(){ return {
        show: true,
    }},

    computed: {
        coords: get('game/coords', false),
        acceleration: get('game/acceleration', false),
        dash: get('game/dash', false),
        keys: get('game/keys', false),
        level: get('game/level', false),
        window: get('game/window', false),
    },

    mounted(){
        this.$root.log("Loading Debug Component")
    },    
}
</script>

<style lang="scss" scoped>
.Debug {
    position: fixed; z-index: 8080;
    top: 0px; right: 0px;
    overflow-y: scroll;

    width: 300px;
    height: auto;
    padding: 5px;

    border: 5px solid orange;
    background: #00000051;
}
</style>