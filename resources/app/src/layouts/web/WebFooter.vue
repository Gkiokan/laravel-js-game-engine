<template lang="html">
<div class='footer' :class="$root.darkmode ? 'bg-black' : 'bg-grey-10 text-white'">

  <Container class="inner">    
      <div class='row q-col-gutter-md'>
            <div class='col-xs-12 col-md-4 col-lg-4'>
                <div>
                    <Logo :light="$root.darkmode" width="100px" height="100px" />
                </div>              
                <p v-html="$root.footerText">                    
                </p>
            </div>
            

            <template v-if="$root.footerLinks">
                <div class='col-xs-12 col-sm-6 col-md-4 col-lg-2' :class="group.class" v-for="(group,g) in $root.footerLinks" :key="'footer_group_' + g">
                    <div class="q-gutter-y-sm">
                        <div class="text-h6 text-weight-light">{{ group.name }} </div>    
                    
                        <q-btn flat no-caps no-wrap v-for="(link,l) in group.links" :key="'footer_group_' + g + '_link_' + l"
                                class="full-width" @click="$root.open(link.link)" align="left" >
                            
                            <q-img :src="link.image" :fit="'contain'" style="height: 20px; width: 100px;" v-if="link.image" />
                            <div v-else>{{ link.name }}</div>
                        </q-btn>

                        <q-space style="height: 40px" />
                    </div>
                </div>       
            </template>


            <div class='col-xs-12 col-sm-6 col-md-4 col-lg-2'>
                    <q-btn no-wrap flat icon="admin_panel_settings" label="Terms of Service" @click="$router.push(links.tos)" /> <br>
                    <q-btn no-wrap flat icon="security" label="Privacy" @click="$router.push(links.privacy)" /> <br>
                    <q-btn no-wrap flat icon="vpn_lock" label="DMCA" @click="$router.push(links.privacy)" /> <br>
                    <q-space style="height: 40px" />
            </div>
            <div class='col-xs-12 col-md-4 col-lg-2'>
                <div class='social_media'>
                    <div v-for="(item,key) in $root.footerThirdParty" :key="'footerThirdParty_' + key">
                        <q-btn no-wrap flat :icon="item.icon" :label="$t('sm.' + item.name)" @click="$root.open(item.link)" v-if="item.link" />
                    </div>
                </div>
            </div>
      </div>

  </Container>

  <div class="legal_wrapper">
      <Container class="legal_notice">
          &copy; 2022 WCX | All rights reserved
      </Container>
  </div>

</div>
</template>

<script>
import links from '~/config/authLinks'
export default {
    name: 'WebFooter',

    data(){ return {
        links,
    }}
}
</script>

<style lang="scss" scoped>
.footer {
    position: relative;
    display: block;
    // background: #eee;
    // color: white;

    .inner {
        padding-top: 50px;
        padding-bottom: 50px;
    }

    &.bg-black {
      background: rgba(10,10,10,.9) !important;
    }
}

.legal_wrapper {
    padding: 5px 0px;
    background: #111;
    color: #fff;

    .legal_notice {
        font-size: 12px;
    }
}

</style>
