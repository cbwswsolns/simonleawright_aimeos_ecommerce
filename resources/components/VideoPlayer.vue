<template>
  <div class="player-container">
    <vue-core-video-player :src="videoSrc"></vue-core-video-player>
  </div>
</template>

<script>
import VueCoreVideoPlayer from 'vue-core-video-player'

Vue.use(VueCoreVideoPlayer)

export default {

props: ['videoSrc'],

}

</script>