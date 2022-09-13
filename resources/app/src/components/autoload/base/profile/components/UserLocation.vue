<template>
    <div class='user_location'>

        <h5 class='text-weight-light' id='user_center'>Meine Adresse</h5>

        <div class='row q-col-gutter-md'>
            <div class="col-12 col-md-5"> <!-- left side -->
                  <div class='row q-mb-md '> <!-- form row -->
                      <div class='col-12'>
                          <q-input v-model="location.address" dense flat label="Straße" stack-label />
                      </div>

                      <div class='col-2'>
                          <q-input v-model="location.postcode" dense flat label="PLZ" stack-label maxlength="5" />
                      </div>
                      <div class='col-10'>
                          <q-input v-model="location.city" dense flat label="Ort" stack-label />
                      </div>

                      <div class='col-6'>
                          <q-input v-model="location.lat" dense flat label="lat" stack-label disable />
                      </div>
                      <div class='col-6'>
                          <q-input v-model="location.lng" dense flat label="long" stack-label disable />
                      </div>
                  </div> <!-- end form row -->

                  <q-btn flat label="Such Adresse" icon="gps_not_fixed" align="left" class="full-width text-light-blue-13" @click="mapAdress" />
                  <q-btn flat label="Such meine Location" icon="my_location" align="left" class="full-width text-green-13" @click="findme" />
            </div> <!-- end left side -->

            <div class="col-12 col-md-7">
                <q-skeleton width="100%" height="300px" animation="fade" v-if="!location.mapURL" />

                <iframe width="100%" height="300px" :src="location.mapURL" v-if="location.mapURL && location.mapURL.length"
                        frameborder="0" scrolling="no" marginheight="0" marginwidth="0" />
            </div>
        </div>

    </div>
</template>

<script>
import axios from 'axios'
// import EventBus from '~/eventBus'
import { get, sync, commit, dispatch } from 'vuex-pathify'
import { Loading, QSpinnerPuff } from 'quasar'

export default {
    name: 'UserLocation',

    data(){ return {
        locationDefault: {
            address: '',
            city: '',
            formatted: '',
            lat: '',
            lng: '',
            nr: '',
            postcode: '',
            street: '',
            mapURL: '',
        },
    }},

    // mounted(){
    //     this.$root.$on('profile.update', this.save)
    // },
    //
    // beforeDestroy(){
    //     this.$root.$off('profile.update', this.save)
    // },

    computed: {
        user: get('auth/user', false),
        location(){
            return this.user && this.user.location ? this.user.location : this.locationDefault
        },
    },

    watch: {
        location: {
            deep: true,
            handler(){
                this.save()
            }
        },
        'user.settings.zoom'(){
            let adress = this.$util.generateMapAdress(this.location)
            let mapURL = this.$util.getMapURL(adress, { zoom : this.user.settings.zoom })

            if(mapURL)
            this.location.mapURL = mapURL
        }
    },

    methods: {
        save(){
            // this.$q.notify('Saving User Location')
            this.user.location = this.location
            // this.$store.set('auth/user', this.user)
            this.$store.dispatch('auth/updateSettings', this.user)
            // this.$store.set('profile/location', this.location)
        },

        findme(){
              Loading.show({
                  spinner: QSpinnerPuff,
                  message: 'Position wird abgerufen',
              })

              this.$getLocation({
                  enableHighAccuracy: true,
                  timeout: 10000,
                  maximumAge: 0,
              })
              .then(coords => {
                  this.location.lat = coords.lat
                  this.location.lng = coords.lng
                  // this.location.mapURL = this.$ccp.getMapURL(this.location.lat+','+this.location.lng)

                  axios.post('/api/trx/v1/geocoding/getGeoLocation', { latlng: this.location.lat+','+this.location.lng })
                      .then( ({ data }) =>  this.mapData(data) )
                      .catch( e => console.log(e) )
                  Loading.hide()
              })
              .catch( e => {
                  console.log(e)
                  Loading.hide()
                  if(e){
                      this.$q.notify({
                        message: 'Bitte aktiviere deine Positionsabfrage',
                        icon: 'announcement',
                        position: 'top',
                      })
                  }
              })
        },

        mapAdress(){
            let l = this.location
            l.lat = null
            l.lng = null

            if(!l.address){
              this.$q.notify({
                message: 'Bitte trage mindestend die Adresse ein',
                icon: 'announcement',
                position: 'top',
                color: 'negative',
              })
              return false
            }

            let a = l.address
            if(l.postcode)  a += ','+l.postcode
            if(l.city)      a += ' '+l.city

            axios.post('/api/trx/v1/geocoding/getGeoLocation', { q: a })
                 .then( ({ data }) =>  this.mapData(data) )
                 .catch( e => console.log(e) )
        },

        mapData(data){
            if(!data.location || !data.address){
                console.log('Data mapping error', data)
                this.$q.notify({
                  message: data.response.error_message || data.status,
                  icon: 'announcement',
                  position: 'top',
                  color: 'negative',
                })
                return
            }

            this.location.lat = data.location.lat
            this.location.lng = data.location.lng

            this.location.address   = data.address.address
            this.location.street    = data.address.street
            this.location.nr        = data.address.nr
            this.location.city      = data.address.city
            this.location.postcode  = data.address.postal_code
            this.location.formatted = data.address.formatted

            this.location.mapURL    = this.$util.getMapURL(data.address.formatted)
            // this.complaint.location.mapURL = this.$ccp.getMapURL(data.location.lat+','+data.location.lng)

        }
    }
}
</script>

<style>

</style>
