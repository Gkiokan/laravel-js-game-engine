<template>
    <div class="q-px-lg q-pb-md">
        <q-timeline :layout="layout">
          <q-timeline-entry heading class='font-weight-light' v-if="title">
            <div class='text-weight-light text-h2'>{{ title }}</div>
            <br>
          </q-timeline-entry>


          <template v-for="(entry, i) in entries">

              <template v-if="entry.headline">
                  <q-timeline-entry heading><div class='text-weight-light' :class="{ 'text-white' : $root.darkmode }">{{ entry.headline }}</div></q-timeline-entry>
              </template>

              <template v-if="entry.icon">
                  <q-timeline-entry
                    :color="color"
                    :title="entry.title"
                    :subtitle="entry.subtitle"
                    :side="entry.side"
                    :icon="entry.icon"
                  >
                    <div v-html="entry.content"></div>
                  </q-timeline-entry>
              </template>

              <template v-else>
                <q-timeline-entry
                  :color="color"
                  :title="entry.title"
                  :subtitle="entry.subtitle"
                  :side="entry.side"
                >
                  <div v-html="entry.content"></div>
                </q-timeline-entry>
              </template>

          </template>
        </q-timeline>
      </div>
</template>

<script>
export default {
    name: 'Changelog',

    props: {
      title: {
          type: String,
          default: ''
      },
      entries: {
          type: Array,
          default: []
      },
      color: {
          type: String,
          default: 'red-7'
      }
    },

    computed: {
        layout () {
            // return 'dense'
            return this.$q.screen.lt.sm ? 'dense' : (this.$q.screen.lt.md ? 'comfortable' : 'loose')
        }
    }
}
</script>
