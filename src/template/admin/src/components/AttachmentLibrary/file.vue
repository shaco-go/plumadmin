<template>
  <attachment-library type="file" v-model="insideValue" v-bind="$attrs"/>
</template>

<script>
import AttachmentLibrary from './index'

export default {
  name: "upload-file",
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
        this.$emit('input', val)
      }
    },
    value: {
      immediate: true,
      handler(val) {
        this.insideValue = val
      }
    }
  }
}
</script>

<style scoped>

</style>
