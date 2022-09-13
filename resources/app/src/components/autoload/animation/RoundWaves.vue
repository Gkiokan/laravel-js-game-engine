<template lang="html">

    <div class='fullscreen' style='background: blue; z-index: 4;'>
        <div class='box'>
          <div class='wave -one'></div>
          <div class='wave -two'></div>
          <div class='wave -three'></div>
        </div>
    </div>

</template>

<script>
export default {
    name: 'RoundWaves'
}
</script>

<style lang='scss'>
.box {
  width: 100%;
  height: 100%;
  background: #fff; // lighten(#f0f4c3, 10%);
  position: relative;
  overflow: hidden;
  transform: translate3d(0, 0, 0);
}

.wave {
  opacity: .4;
  position: absolute;
  top: 3%;
  left: 50%;
  background: #0af;
  width: 140vw;
  height: 160vw;
  margin-left: -60vh;
  margin-top: -30vh;
  transform-origin: 50% 48%;
  border-radius: 43%;
  animation: drift 3000ms infinite linear;
}

.wave.-three {
  animation: drift 5000ms infinite linear;
}

.wave.-two {
  animation: drift 7000ms infinite linear;
  opacity: .1;
  background: yellow;
}

.box:after {
  content: '';
  display: block;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(to bottom, rgba(#fff, 1), rgba(#fefefe, .8) 80%, rgba(white, .5));
  z-index: 11;
  transform: translate3d(0, 0, 0);
}

@keyframes drift {
  from { transform: rotate(0deg); }
  from { transform: rotate(360deg); }
}

</style>
