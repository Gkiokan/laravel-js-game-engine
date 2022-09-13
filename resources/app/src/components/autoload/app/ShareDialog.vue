<template>
  <q-dialog ref="dialog" position="bottom" @hide="onDialogHide">
    <q-card class="q-dialog-plugin">
      <q-card-section>

          <div class='complaint_info q-mb-lg'>
              <div class="text-h3 text-weight-light q-mb-md">Share Complaint #{{ item.uid }}</div>
              <div>Erstellt {{ item.created_at | date }} </div>
              <div>Letze Aktion {{ item.updated_at | date }}</div>
              <div>{{ item.attachment.length }} Anhänge </div>
              <div>Status {{ $complaint.getStatus(item.status) }}</div>
              <div>Sichtbarkeit {{ item.public ? 'Öffentlich' : 'Privat' }}</div>
          </div>

          <ShareNetwork
            v-for="network in networks"
            :network="network.network"
            :key="network.network"
            :url="sharing.url"
            :title="sharing.title"
            :description="sharing.description"
            :quote="sharing.quote"
            :hashtags="sharing.hashtags"
          >
              <q-btn flat round :icon="network.icon" :style="{ color: network.color }">
                  <q-tooltip :style="{ color: network.color }">{{ network.network }}</q-tooltip>
              </q-btn>
          </ShareNetwork>


      </q-card-section>

      <!-- buttons example -->
      <q-card-actions align="right">
        <q-btn outline color="primary" label="OK" @click="onOKClick" v-if="0" />
        <q-btn outline color="primary" label="zurück" @click="onCancelClick" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
import moment from 'moment'

export default {
  name: 'ShareDialog',
  props: {
      item: {
          type: Object,
      },

      url: {
          type: String,
      },

      text: {
          type: String
      }
  },

  data(){ return {
      sharing: {
          url: this.url,
          title: 'Complaint ' + this.item.uid,
          description: this.text + ' | ' + this.item.user.displayname + '\n ' +
                        'Erstellt am ' + moment(this.item.created_at).format('DD.MM.yyyy') + '.\n ' +
                        'Letzte Aktion am ' + moment(this.item.updated_at).format('DD.MM.yyyy') + '.\n ' +
                        this.item.attachment.length + ' Anhänge vorhanden.\n ' +
                        'Status ' + this.$complaint.getStatus(this.item.status) + '.\n ' +
                        'Sichtbarkeit ' + (this.item.public ? 'Öffentlich' : 'Privat' ) + '.\n',

          quote: "Complaint #" + this.item.uid + ' | ' + this.item.user.displayname + ' | ' + moment(this.item.created_at).format('DD.MM.yyyy'),
          hashtags: 'complaint,ccp',
          // twitterUser: 'youyuxi'
      },

      networks: [
          { network: 'facebook', name: 'Facebook', icon: 'fab fah fa-lg fa-facebook-f', color: '#1877f2' },
          { network: 'whatsapp', name: 'Whatsapp', icon: 'fab fah fa-lg fa-whatsapp', color: '#25d366' },
          { network: 'twitter', name: 'Twitter', icon: 'fab fah fa-lg fa-twitter', color: '#1da1f2' },
          { network: 'skype', name: 'Skype', icon: 'fab fah fa-lg fa-skype', color: '#00aff0' },
          { network: 'telegram', name: 'Telegram', icon: 'fab fah fa-lg fa-telegram-plane', color: '#0088cc' },
          // { network: 'wordpress', name: 'Wordpress', icon: 'fab fah fa-lg fa-wordpress', color: '#21759b' },
          { network: 'linkedin', name: 'LinkedIn', icon: 'fab fah fa-lg fa-linkedin', color: '#007bb5' },
          { network: 'xing', name: 'Xing', icon: 'fab fah fa-lg fa-xing', color: '#026466' },
          { network: 'evernote', name: 'Evernote', icon: 'fab fah fa-lg fa-evernote', color: '#2dbe60' },
          { network: 'viber', name: 'Viber', icon: 'fab fah fa-lg fa-viber', color: '#59267c' },
          { network: 'email', name: 'Email', icon: 'far fah fa-lg fa-envelope', color: '#333333' },
          { network: 'sms', name: 'SMS', icon: 'far fah fa-lg fa-comment-dots', color: '#333333' },
          // { network: 'baidu', name: 'Baidu', icon: 'fas fah fa-lg fa-paw', color: '#2529d8' },
          // { network: 'buffer', name: 'Buffer', icon: 'fab fah fa-lg fa-buffer', color: '#323b43' },
          // { network: 'flipboard', name: 'Flipboard', icon: 'fab fah fa-lg fa-flipboard', color: '#e12828' },
          // { network: 'hackernews', name: 'HackerNews', icon: 'fab fah fa-lg fa-hacker-news', color: '#ff4000' },
          // { network: 'instapaper', name: 'Instapaper', icon: 'fas fah fa-lg fa-italic', color: '#428bca' },
          // { network: 'line', name: 'Line', icon: 'fab fah fa-lg fa-line', color: '#00c300' },
          // { network: 'odnoklassniki', name: 'Odnoklassniki', icon: 'fab fah fa-lg fa-odnoklassniki', color: '#ed812b' },
          // { network: 'pinterest', name: 'Pinterest', icon: 'fab fah fa-lg fa-pinterest', color: '#bd081c' },
          // { network: 'pocket', name: 'Pocket', icon: 'fab fah fa-lg fa-get-pocket', color: '#ef4056' },
          // { network: 'quora', name: 'Quora', icon: 'fab fah fa-lg fa-quora', color: '#a82400' },
          // { network: 'reddit', name: 'Reddit', icon: 'fab fah fa-lg fa-reddit-alien', color: '#ff4500' },
          // { network: 'stumbleupon', name: 'StumbleUpon', icon: 'fab fah fa-lg fa-stumbleupon', color: '#eb4924' },
          // { network: 'tumblr', name: 'Tumblr', icon: 'fab fah fa-lg fa-tumblr', color: '#35465c' },
          // { network: 'vk', name: 'Vk', icon: 'fab fah fa-lg fa-vk', color: '#4a76a8' },
          // { network: 'weibo', name: 'Weibo', icon: 'fab fah fa-lg fa-weibo', color: '#e9152d' },
          // { network: 'yammer', name: 'Yammer', icon: 'fab fah fa-lg fa-yammer', color: '#0072c6' },
          // { network: 'fakeblock', name: 'Custom Network', icon: 'fab fah fa-lg fa-vuejs', color: '#41b883' }
      ]
  }},

  filters: {
      date(val){
          return moment(val).format('DD.MM.yyyy')
      }
  },

  computed: {
      uid(){
          return this.item ? this.item.uid : ''
      },
  },

  methods: {
      show () {
        this.$refs.dialog.show()
      },

      hide () {
        this.$refs.dialog.hide()
      },

      onDialogHide () {
        this.$emit('hide')
      },

      onOKClick () {
        this.$emit('ok')
        this.hide()
      },

      onCancelClick () {
        this.hide()
      },

  }
}
</script>
