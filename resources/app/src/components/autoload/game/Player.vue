<template>
<div class="Player" id="me" ref="player" :style="playerCoordsStyle" @keyup="keydown">
    XXXXX
</div>
</template>

<script>
import { get, sync } from 'vuex-pathify'

export default {
    name: 'Player',

    props: {

    },

    data(){ return {
        registered: false,
        move: 0,
    }},

    computed: {
        acceleration: get('game/acceleration', false),
        dash: get('game/dash', false),

        coords: sync('game/coords', false),
        keys: sync('game/keys', false),
        window: get('game/window', false),
        playerCoordsStyle(){
            return {
                top: (this.coords.y * -1)+'px',
                left: this.coords.x+'px' ,
            }
        },
    },

    mounted(){
        this.$root.log("Loading Player Component")

        if(!this.registered){
            window.addEventListener('keydown', this.keyLogger);        
            window.addEventListener('keyup', this.keyLogger); 
            this.registered = true
            this.$root.log("Registered Player Event handlers")
        }

        this.handle()
    },

    beforeDestroy(){
        window.removeEventListener('keydown', this.keyLogger);
        window.addEventListener('keyup', this.keyLogger); 
    },

    methods: {
        keyLogger(e){
            this.keys[e.code] = e.type == 'keydown';
        },

        handle(){            
            // handle movement 
            this.keydown(this.keys)

            // handle skills
            this.skill(this.keys)

            // recall
            window.requestAnimationFrame(this.handle)
        },    

        keydown(keys){
            // apply dash
            if(keys['KeyD'])
                this.move = this.dash
            
            // movement
            if(keys['ArrowLeft'])
                this.left()

            if(keys['ArrowUp'])
                this.up()

            if(keys['ArrowRight'])
                this.right()

            if(keys['ArrowDown'])
                this.down()

            // reset dash
            if(!keys['KeyD'])
                this.move = this.acceleration

            this.calculateBoundaryCoords()
        },

        calculateBoundaryCoords(){
            let player = this.$refs.player 
            let coords = player.getBoundingClientRect()
            // console.log(coords)

            this.coords.left    = coords.left 
            this.coords.right   = coords.right 
            this.coords.top     = coords.top 
            this.coords.bottom  = coords.bottom 
        },


        skill(keys){
            if(!keys['Space']) return;

            
        },


        left(){            
            if(this.window.left > this.coords.left - this.move){
                this.$root.log("Out of boundary, correct that move")
                return
            }              

            this.$root.log("Move left")
            this.coords.x -= this.move                        
        },

        right(){
            if(this.window.right < this.coords.right + this.move){
                this.$root.log("Out of boundary, correct that move")
                return
            }

            this.$root.log("Move right")
            this.coords.x += this.move
        },

        up(){
            if(this.window.top > this.coords.top - this.move){
                this.$root.log("Out of boundary, correct that move")
                return
            }

            this.$root.log("Move up")
            this.coords.y += this.move
        },

        down(){
            if(this.window.bottom < this.coords.bottom + this.move){
                this.$root.log("Out of boundary, correct that move")
                return
            }

            this.$root.log("Move down")
            this.coords.y -= this.move
        },
        

    }
}
</script>

<style lang="scss" scoped>
.Player {
    position: absolute; z-index: 100;
    top: 0px; left: 0px;
    display: block;
    width: 50px; 
    height: 50px;
    border: 1px solid pink;

    transition: all .01s;
}
</style>