<template>
  <transition name="component-fade" mode="out-in">
    <slot>
        <router-view _key="$route.fullPath"/>
    </slot>
  </transition>
</template>

<script>
export default {
  name: 'Child'
}
</script>
