<template>
<div class="FooterSettings">    

    <q-btn unelevated no-caps icon="save" color="green" label="Save Footer Settings" @click="save" />

    <h5 class="text-weight-regular q-mb-sm">Footer Area Text below the Logo </h5>
    <div class="q-mb-lg">
        <q-input filled square v-model="footerText" type="textarea" label="Textare in Footer below the Logo" stack-label />
    </div>


    <h5 class="text-weight-regular q-mb-sm">Footer 3rd Prty </h5>
    <div class="row q-col-gutter-sm">
        <div class="col-xs-12" v-for="(item,key) in footerThirdParty" :key="'sm_' +  key">
            <div class="row">
                <div class="col-8">
                    <q-input filled standout square  class="q-mb-md" v-model="item.link" :label="$t('sm.' + key)" stack-label />
                </div>
                <div class="col-2">
                    <q-input filled standout square  class="q-mb-md" v-model="item.icon"  label="Icon" stack-label />
                </div>           
                <div class="col-2">
                    <q-input filled standout square  class="q-mb-md" v-model="item.name"  label="Name" stack-label disable />
                </div>              
            </div>                  
        </div>
    </div>


    <h5 class="text-weight-regular q-mb-sm">Footer Links </h5>
    <div v-if="items.length">
        <q-card class="link_group q-pa-md q-mb-lg" v-for="(group,i) in items" :key="'link_group_' + i">
            <div class="row q-mb-sm q-col-gutter-sm">
                <div class="col">            
                    <q-input filled standout square dense class="q-mb-md" v-model="group.name" label="Group Name" stack-label />
                </div>
                <div class="col">            
                    <q-input filled standout square dense class="q-mb-md" v-model="group.class" label="Group css class" stack-label />
                </div>                
                <div>
                    <q-btn flat color="red" icon="delete" @click="removeLink(items, i)" />
                </div>                
            </div>

            <div class="links"  v-for="(link,l) in group.links" :key="'link_group_' + i + '_link_' + l">
                <div class="row q-mb-sm q-col-gutter-sm">
                    <div class="col-2">
                        <q-input filled square dense v-model="link.name" label="Name" stack-label />
                    </div>
                    <div class="col">
                        <q-input filled square dense v-model="link.link" label="Link" stack-label />
                    </div>
                    <div class="col">
                        <q-input filled square dense v-model="link.image" label="Image (instead of name)" stack-label />
                    </div>
                    <div>
                        <q-btn flat color="red" icon="clear" @click="removeLink(group.links, l)" />
                    </div>
                </div>
            </div>
            <q-btn unelevated no-caps icon="add" label="Add Link to Group" @click="addLinkToGroup(group)" />
        </q-card>
    </div>


    <q-btn no-caps outline class="q-mb-md" label="Create Link Group" @click="createLinkGroup" />
    <q-space style="height: 40px" />

    <q-btn unelevated no-caps icon="save" color="green" label="Save Footer Settings" @click="save" />

</div>
</template>

<script>
export default {
    name: 'FooterSettings',

    data(){ return {
        footerText: '',
        items: [],
        footerThirdParty: [],
    }},

    computed: {

    },

    mounted(){
        this.load()
    },

    methods: {
        load(){
            this.$axios.get('/app/footer/get')
                .then( ({ data }) => {
                    this.items = data.footerLinks
                    this.footerText = data.footerText
                    this.footerThirdParty = data.footerThirdParty
                })            
                .catch( e => console.log(e.response) )
        },

        save(){
            this.$axios.post('/app/footer/save', {  
                    footerText: this.footerText, 
                    footerThirdParty: this.footerThirdParty,
                    items: this.items,                    
                })
                .then( ({ data }) => {
                    this.load()

                    this.$q.notify({
                        message: this.$t(data.message),
                        icon: "done",
                        color: "positive",
                    })
                })
                .catch( e => console.log(e.response) )
        },

        createLinkGroup(){
            this.items.push({ 
                name: "",
                class: "",
                links: [],
            })
        },

        addLinkToGroup(group){
            group.links.push({
                name: '',
                link: '',
                image: null,
            })
        },

        removeLink(list, i){
            list.splice(i,1)
        },

    }
}
</script>