<template>
<q-page tabindex=0>
    Game index

    <BaseLevel />

    <PlayerHUD />

    <Debug />

</q-page>
</template>

<script>
/*** 
 * Game INDEXES 
 * ---------------------------
 * Key                  Index
 * ---------------------------
 * Level Boundary       2
 * BaseLevel            20
 * 
 * Player               100
 * 
 * Player HUD           1000
 * DebugBar             8080
 * ---------------------------
**/

import { get, sync } from 'vuex-pathify'

export default {
    name: 'GameIndex',

    mounted(){
        this.$root.log("Loading Game Index View")
        this.setAttributes()
    },

    computed: {
        window: sync('game/window', false),
    },    

    methods: {
        setAttributes(){
            this.$root.log("Set Window Attributes")
            this.window.width   = window.innerWidth
            this.window.height  = window.innerHeight
    
            this.window.right   = this.window.width - this.window.offsetX
            this.window.bottom  = this.window.height - this.window.offsetY
            this.window.left    = this.window.offsetX
            this.window.top     = this.window.offsetY
        },        
    }
}
</script>