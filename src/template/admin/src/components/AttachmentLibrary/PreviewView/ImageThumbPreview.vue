<template>
  <img class="image-thumb-preview" :src="data.url" :alt="data.name">
</template>

<script>
export default {
  name: "ImageThumbPreview",
  props: {
    data: Object
  }
}
</script>

<style scoped>
.image-thumb-preview {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
</style>
