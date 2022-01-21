<template>
  <video class="video-thumb-preview" :src="data.url"/>
</template>

<script>
export default {
  name: "VideoThumbPreview",
  props: {
    data: Object
  }
}
</script>

<style scoped>
.video-thumb-preview {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
</style>
