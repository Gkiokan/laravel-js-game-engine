<template>
  <q-item
    clickable exact
    tag="a"
    active-class="text-red"
    :link="link"
  >
    <q-item-section v-if="icon" avatar>
      <q-icon :name="icon" />
    </q-item-section>

    <q-item-section>
      <q-item-label> {{ $t(title) }} </q-item-label>
      <q-item-label caption>
        {{ caption }}
      </q-item-label>
    </q-item-section>
  </q-item>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
    name: 'EssentialLinkTranslated',
    props: {
        title: {
          type: String,
          required: true
        },

        caption: {
          type: String,
          default: ''
        },

        link: {
          type: String,
          default: '#'
        },

        icon: {
          type: String,
          default: ''
        }
    },

    methods: {
        isActive(link){
            // todo: active state for deep links
            return this.$route.name
        }
    }
})
</script>
