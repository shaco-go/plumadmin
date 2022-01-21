<template>
  <attachment-library type="video" v-model="insideValue" v-bind="$attrs"/>
</template>

<script>
import AttachmentLibrary from './index'

export default {
  name: "upload-video",
  components: {AttachmentLibrary},
  props: {
    value: Object | Array
  },
  data() {
    return {
      insideValue: undefined
    }
  },
  watch: {
    insideValue: {
      immediate: true,
      handler(val) {
        console.log('cehsi')
        this.$emit('input', val)
      }
    },
    value: {
      immediate: true,
      handler(val) {
        console.log('cehsi')
        this.insideValue = val
      }
    }
  }
}
</script>

<style scoped>

</style>
