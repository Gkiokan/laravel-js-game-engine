<template>
    
    <div class="PlayerHUD">
        
        <div class="row q-col-gutter-xs">
            <div class="col">
                <div class="skill">
                    1
                </div>
            </div>
            <div class="col">
                <div class="skill">
                    2
                </div>
            </div>
            <div class="col">
                <div class="skill">
                    3
                </div>
            </div>
            <div class="col">
                <div class="skill">
                    4
                </div>
            </div>
            <div class="col">
                <div class="skill" :class="{ active: keys['KeyD'] }">
                    D
                </div>
            </div>                        
            <div class="col">
                <div class="skill" :class="{ active: keys['KeyF'] }">
                    F
                </div>
            </div>   
        </div>

        <div class="split" style="height: 10px; background: black" />

        <div class="column">
            <div class="">
                <div class="bar hp q-px-sm">
                    HP
                </div>
            </div>
            <div class="">
                <div class="bar mana q-px-sm">
                    Mana
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import { get } from 'vuex-pathify'

export default {
    name: 'PlayerHUD',

    props: {

    },

    data(){ return {
        
    }},

    computed: {
        keys: get('game/keys', false),
    },

    methods: {

    }
}
</script>

<style lang="scss" scoped>
.PlayerHUD {
    position: fixed; z-index: 1000;
    bottom: 0px; 
    width: auto;
    background: #000;
    border: 10px solid black;
    border-radius: 2px 2px 0 0;

    .bar {
        border: 1px solid #333;

        &.hp {
            background: #210000;
        }

        &.mana {
            border-top-width: 0px;
            background: #001821;
        }
    }

    .skill {
        display: block;
        width: 50px; height: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #111;
        border: 1px solid #333;

        &.active {
            border-color: cyan;
        }
    }
}
</style>