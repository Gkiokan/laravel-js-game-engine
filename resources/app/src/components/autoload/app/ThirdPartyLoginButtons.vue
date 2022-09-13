<template>
  <div class='ThirdPartyLoginButtons'>
      <q-btn round flat color='blue' @click.native="oauth('facebook')" icon="fab fa-facebook"></q-btn>
      <q-btn round flat color='orange-9' @click.native="oauth('insta')" icon="fab fa-instagram"></q-btn>
      <q-btn round flat color='gray-10' @click.native="oauth('github')" icon="fab fa-github"></q-btn>
  </div>
</template>

<script>
import axios from 'axios'

export default {
    name: 'ThirdPartyLoginButtons',

    methods: {

        // handle oauth click
        oauth(provider){
            if(provider == 'insta' || provider == 'github'){
                alert(provider + ' not ready yet')
                return
            }

            axios.post('/api/auth/provider/' + provider)
                  .then( r => {
                      let url = r.data.url

                      if(url){
                          // const newWindow = openWindow('', 'Gkiokan.NET OAuth Login')
                          // newWindow.location.href = url
                          window.open(url)
                      }

                  })
                  .catch(e => console.log(e.response) )
        }
    }
}
</script>
