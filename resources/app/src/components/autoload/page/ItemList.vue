<template lang="html">
    <v-card :elevation="elevation">
        <v-card-title v-if="title">{{ title }}</v-card-title>

        <v-list two-line>
        <template v-for="(item,i) in items">
            <v-list-item>
            <template v-slot:default="{ active, toggle }">
                <v-list-item-content>
                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                    <v-list-item-subtitle class='deep-orange--text darken-3' v-if="0">
                        <v-chip-group>
                        <v-chip v-for="(f,i) in item.for" :key="'_for_'+i" :input-value="true"
                               label_ outlined multiple>
                            {{ f }}
                        </v-chip>
                        </v-chip-group>
                    </v-list-item-subtitle>
                    <v-list-item-subtitle>
                        {{ item.description }}
                    </v-list-item-subtitle>
                </v-list-item-content>
                <v-list-item-action>
                    <v-list-item-action-text v-text="item.percent"></v-list-item-action-text>
                    <v-layout>
                        <v-icon v-if="0" color="grey lighten-1" > star_border </v-icon>
                        <v-icon v-if="0" color="yellow" > star </v-icon>

                        <v-icon color="blue" > av_timer </v-icon>
                        <v-icon color="gray" outline> done_all </v-icon>
                    </v-layout>
                </v-list-item-action>
            </template>
            </v-list-item>
            <v-divider v-if="i + 1 < items.length" :key="i" />
        </template>
        </v-list>
    </v-card>
</template>

<script>
export default {
    name: 'ItemList',

    props: ['items', 'title'],

    data(){ return {
        elevation: 1,
    }},

    mounted(){

    }
}
</script>
