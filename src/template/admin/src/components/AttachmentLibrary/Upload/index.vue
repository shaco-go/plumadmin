<template>
    <el-upload style="display: inline-block" multiple action="" :show-file-list="false" :http-request="upload">
      <slot>
        <el-button  type="primary" size="small" icon="el-icon-upload" :loading="loading">上传</el-button>
      </slot>
    </el-upload>
</template>

<script>
export default {
  name: 'Upload',
  data() {
    return { loading: false }
  },
  methods: {
    upload({ file }) {
      let form = new FormData()
      form.append('file', file)
      this.loading = true
      this.$http.post('system/attachment/upload', form)
          .then(({ data }) => {
            this.$emit('upload', data)
          })
          .finally(() => {
            this.loading = false
          })
    }
  }
}
</script>

<style scoped>

</style>
