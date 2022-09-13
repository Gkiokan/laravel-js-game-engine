export default {
    watch: {
        '$root.darkmode'(val){
            this.setDarkmodeColors(val)
        }
    },

    mounted(){
        this.setDarkmodeColors(this.$root.darkmode)
    },

    methods: {
        setDarkmodeColors(val){
            console.log("switching lighting modes")

            let isDark = val
            let darkClass = 'bg-black- bg-dark- bg-transparent text-grey-2'
            let whiteClass = 'bg-grey-2x bg-white text-grey-9'

            this.$root.toolbarClass = !isDark ? whiteClass : darkClass;
            this.$root.drawerClass = !isDark ? 'bg-drawer-1 bg-grey-1 text-grey-9- text-white' : 'bg-dark text-grey-2';
            this.$root.layoutClass = !isDark ? whiteClass : 'bg-dark-page';
            this.$root.pageContainerClass = !isDark ? 'bg-white--' : ''

            this.$q.dark.set(val)
            this.$store.dispatch('app/setDarkmode', val)
        }
    }

}
