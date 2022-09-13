import { sync } from 'vuex-pathify'

export default {

    computed: {
        live: sync('socket/live', false),
    },

    mounted(){
        this.registerWS()
    },    

    methods: {
        registerWS(){
            this.$echo.channel('live')
                .listen('LiveEvent', (e) => {
                    console.log('WS Event', e)

                    if(e.type == 'entry.new'){
                        let entry = e.entry 

                        this.live.unshift(entry)

                        this.$q.notify({
                            message: entry.title + ' added',
                            position: 'top-right',
                            color: 'info',
                            icon: 'info',
                        })

                        let findEmpty = this.live.filter( i => i.empty == true )
                        if(findEmpty.length){
                            this.live.pop()
                        }
                    }
                    else {
                        console.log("WS payload", e)
                    }
                })
        },        
    }
}