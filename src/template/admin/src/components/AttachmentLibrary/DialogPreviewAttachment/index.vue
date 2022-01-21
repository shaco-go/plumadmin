<template>
  <el-dialog
      :visible.sync="visible"
      :title="title"
      width="70%"
      top="10vh"
      @closed="handleClosed"
      :append-to-body="true"
  >
    <div class="preview-container">
      <img v-if="attachmentData && type==='image'" :src="attachmentData.url" :alt="attachmentData.name"/>
      <video v-if="attachmentData && type==='video'" :src="attachmentData.url" controls/>
    </div>
  </el-dialog>
</template>

<script>
export default {
  name: "DialogPreviewAttachment",
  data() {
    return {
      attachmentData: undefined,
      visible: false,
      type: undefined
    }
  },
  computed: {
    title() {
      return this.attachmentData?.name
    }
  },
  methods: {
    // 打开
    open(data, type) {
      this.attachmentData = data
      this.type = type
      this.visible = true
    },
    // 关闭
    handleClosed() {
      this.attachmentData = undefined
      this.type = undefined
    }
  }
}
</script>

<style scoped lang="scss">
.preview-container {
  width: 100%;
  height: calc(100vh - 20vh - 104px);

  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  video {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
}
</style>
