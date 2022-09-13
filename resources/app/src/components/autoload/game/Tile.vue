<template>
    
    <div class="Tile" :class="tileClass">
        <div class="info" v-if="showTileInfo">
            Tile <br>
            {{ i }}
        </div>
    </div>

</template>

<script>
export default {
    name: 'Tile',

    props: {
        i: {},
    },

    data(){ return {
        showTileInfo: false,
    }},

    computed: {
        tileClass(){
            return {
                width: '50px',
                height: '50px',
                'texture-default': true,
            }
        }
    },

    methods: {

    }
}
</script>

<style lang="scss" scoped>
.Tile {

    width: 50px;
    height: 50px;
    border: 0px solid rgb(167, 69, 3);

    &.texture-default {
        // border: 1px solid cyan;
        // background-image: url('assets/trees-and-bushes.png');
        background-image: url('/set/trees-and-bushes.png');
        background-position: 0px 0px;
        background-size: calc(288px * 1.8) calc(160px * 1.8);
        background-repeat: no-repeat;
        // transform: scale(2);
    }
}
</style>