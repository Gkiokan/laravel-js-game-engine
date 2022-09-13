<template lang="html">
<div class='user_wallet'>

    <h4 class='q-mt-none text-weight-light'>Wallet</h4>


    <h5 class='q-mb-none text-weight-light'> PayPal </h5>

    Füge PayPal als deine Primäre Zahlungsmethode ein und erlaube uns<br>
    deine Bestellungen automatisch einzuziehen zu lassen. <br>
    Dabei gelten unsere AGB's und Richtlinien.<br>
    <div class='text-left q-my-md' v-if="enablePayPal">
        <q-btn outline @click="vaultPayPal" icon="add" label="Ok, ich bin einverstanden!" color="green-8" v-if="showPayPalVaultButton && !hasPayPal" />

        <q-btn-group unelevated v-if="hasPayPal">
            <q-btn outline icon="done" label="PayPal Account erfolgreich hinzugefügt" color="green-8" />
            <q-btn @click="removePayPal" icon="delete" label="Widerrufen" color="red-8" />
        </q-btn-group>
    </div>

    <div v-show="!showPayPalVaultButton && !hasPayPal" transition="slide">
        Bitte führe die nachstehende Authentifizierung durch
        <div id='paypal-button' />
    </div>

    <q-space style='height: 50px' />


    <h5 class='q-mb-none text-weight-light'> Bank IBAN </h5>
    Name, Vorname, IBAN, BIC
    <q-space style='height: 50px' />


    <h5 class='q-mb-none text-weight-light'> Kredit Karte <small>(Visa, Mastercard, Meastro)</small></h5>
    Karten-Nr, Exp
    <q-space style='height: 50px' />



    <q-space style='height: 50px' />
</div>
</template>

<script>
import { get, sync, set } from 'vuex-pathify'

export default {
    name: 'UserWallet',

    data(){ return {
        token: null,
        wallets: [],
        showPayPalVaultButton: true,
        enablePayPal: false,
    }},

    mounted(){
        this.load()
    },

    computed: {
        user: sync('auth/user', false),
        hasPayPal(){
            return false // this.wallets.find( x => x.key == 'paypal')
        }
    },

    methods: {
        load(){
            this.$axios.get('/api/trx/v1/wallet/load')
                .then( ({ data }) => {
                    this.wallets = data.wallets
                })
                .catch( e => console.log(e.response) )
        },

        save(type, payload){
          this.$axios.post('/api/trx/v1/wallet/save/' + type, payload)
                .then( ({ data }) => {
                    console.log(data)
                    this.load()
                })
                .catch( e => console.log(e.response) )
        },


        async loadClientToken(){
            const { data } = await this.$axios.get('/api/trx/v1/payment/getClientToken')
            this.token             = data.token
        },


        async vaultPayPal(){
            await this.loadClientToken()

            let that = this
            braintree.client.create({
              authorization: this.token
            })
            .then(function (clientInstance) {
              // Create a PayPal Checkout component.
              return braintree.paypalCheckout.create({
                client: clientInstance
              });
            })
            .then(function (paypalCheckoutInstance) {
              return paypalCheckoutInstance.loadPayPalSDK({
                vault: true
              });
            })
            .then(function (paypalCheckoutInstance) {
              that.showPayPalVaultButton = false

              return paypal.Buttons({
                fundingSource: paypal.FUNDING.PAYPAL,

                createBillingAgreement: function () {
                  return paypalCheckoutInstance.createPayment({
                    flow: 'vault', // Required

                    // The following are optional params
                    billingAgreementDescription:
                        'Füge PayPal als deine Primäre Zahlungsmethode ein und erlaube Remondo' +
                        'deine bestellen Dienstleistungen von Remondo automatisch einzuziehen. ' +
                        'Dabei gelten die AGB\'s und Richtlinien von Remondo.',
                    enableShippingAddress: false,
                    shippingAddressEditable: false,
                  });
                },

                onApprove: function (data, actions) {
                  return paypalCheckoutInstance.tokenizePayment(data).then(function (payload) {
                    // Submit `payload.nonce` to your server
                    console.log('Paypal approved', payload)
                    that.save('PayPal', payload)
                  });
                },

                onCancel: function (data) {
                  console.log('PayPal payment canceled', JSON.stringify(data, 0, 2));
                },

                onError: function (err) {
                  console.error('PayPal error', err);
                }
              }).render('#paypal-button');
            })
            .then(function () {
              // The PayPal button will be rendered in an html element with the ID
              // `paypal-button`. This function will be called when the PayPal button
              // is set up and ready to be used
            })
            .catch(function (err) {
              // Handle component creation error
            });
        },

        removePayPal(){
            this.$axios.post('/api/trx/v1/wallet/remove/PayPal')
                .then( () => this.load() )
                .catch( e => console.log(e.response) )
        }
    }
}
</script>

<style lang="css" scoped>
</style>
