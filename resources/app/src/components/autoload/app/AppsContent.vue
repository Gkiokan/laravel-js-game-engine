<template>
<div class='apps_content'>

    <div class='bg-dark text-white' style='width: 230px;' v-if="!loaded">
      <div class="q-gutter-y-sm" style='opacity: .3;'>
        <q-skeleton bordered square height="40px" />
        <q-skeleton bordered square height="40px" />
        <q-skeleton bordered square height="40px" />
        <q-skeleton bordered square height="40px" />
      </div>
    </div>

    <div class='column' v-if="version == 1 && loaded == 1">
      <q-list bordered-off separator>

          <template v-for="(item,i) in apps">
          <q-slide-item class='bg-dark text-grey-3' @click="move(item)">
            <template v-slot:left>
              <q-icon name="done" />
              You've selected this one. Thanks!.
            </template>

            <q-item>
              <q-item-section avatar>
                <q-avatar>
                    <q-avatar dragable="false" square :icon="item.icon" class='q-ml-auto q-mr-auto' :class="'text-'+item.color"  v-if="!item.image"/>

                    <q-img :src="item.image" v-if="item.image" contain squared
                            class='q-ml-auto q-mr-auto' size="40px" :class="'text-'+item.color" />

                </q-avatar>
              </q-item-section>
              <q-item-section class='text-caption' style='font-size: 14px;'>{{ item.name }}</q-item-section>

            </q-item>
          </q-slide-item>
          </template>

      </q-list>
    </div>
    <q-separator insxet class='q-mt-xs q-mb-md bg-grey-7' v-if="version == 1 && version == 2" />

    <div class='row wrap align-center items-center q-gutter-none' v-if="version == 2">
        <template v-for="(item,i) in apps">
        <div class='col-4 text-center' style='border: 0px solid blue;'>

            <q-btn flat style='border: 0px solid red;' @click="move(item)">
            <div class='column'>

                <q-avatar square class='q-ml-auto q-mr-auto' size="sm" :icon="item.icon" :class="'text-'+item.color"  v-if="!item.image"/>
                <q-avatar square class='q-ml-auto q-mr-auto' size="lg" :class="'text-'+item.color" v-else>
                    <img :src="item.image" v-if="item.image" />
                </q-avatar>

                <q-tooltip>{{ item.name }}</q-tooltip>
                <div class="text-caption text-weight-regular q-mb-xs text-center text-grey-3" v-if="showName"> {{ item.short }}</div>
            </div>
            </q-btn>

        </div>
        </template>
    </div>

    <q-separator insxet class='q-mt-xs q-mb-md bg-grey-7' v-if="showButtonArea" />
    <div class='column q-px-md q-pb-md' v-if="showButtonArea">
        <q-btn color="primary" label="All Apps" push size="sm" v-close-popup />
    </div>

</div>
</template>

<script>
export default {
    name: 'AppsContent',

    props: ['apps', 'version', 'loaded'],

    data(){ return {
        showButtonArea: 0,
        showName: 1,
    }},

    methods: {
        move(item){
            let url = item.url

            if(url)
            window.location = url
        }
    }
}
</script>
