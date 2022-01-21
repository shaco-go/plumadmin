<template>
  <img  class="file-thumb-preview" :src="require('@/assets/file.png')" :alt="data.name">
</template>

<script>
export default {
  name: "FileThumbPreview",
  props: {
    data: Object
  }
}
</script>

<style scoped>
.file-thumb-preview {
  width: 100%;
  height: 100%;
  object-fit: contain;
}
</style>
