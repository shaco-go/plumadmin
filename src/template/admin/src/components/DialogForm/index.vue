<template>
  <el-dialog v-bind="$attrs" :visible.sync="visible" top="10vh" @close="dialogClose" @closed="dialogClosed">
    <el-scrollbar
      style="max-height:calc(80vh - 135px);width: 100%"
      wrap-style="max-height:calc(80vh - 135px);overflow-x:hidden;"
      view-style="padding:0 10px 17px 10px;"
    >
      <el-form ref="form" :model="form" label-width="100px" size="small" :disabled="isRead">
        <slot></slot>
      </el-form>
    </el-scrollbar>
    <template v-if="!isRead" #footer>
      <el-button size="small" @click="handleClose">关闭</el-button>
      <el-button :loading="loading" size="small" type="primary" @click="handleSubmit">保存</el-button>
    </template>
  </el-dialog>
</template>

<script>
export default {
  name: "index",
  props: {
    model: Object,
    submit: Function
  },
  data() {
    return {
      visible: false,
      isRead: false,
      form: {},
      originalForm: {},
      loading: false
    }
  },
  mounted() {
    this.originalForm = this.$tools.cloneDeep(this.model)
    this.form = this.model
  },
  methods: {
    read(form = {}) {
      Object.assign(this.form, form)
      this.isRead = true
      this.open()
    },
    // 打开
    open(form = {}) {
      Object.assign(this.form, form)
      this.visible = true
    },
    // 关闭
    handleClose() {
      this.visible = false
    },
    // 保存
    handleSubmit() {
      this.$refs.form.validate(valid => {
        if (valid) {
          this.loading = true
          this.$emit('submit', this.form, () => {
            this.visible = false
            this.loading = false
          }, () => {
            this.loading = false
          })
        }
      })
    },
    // 关闭弹窗
    dialogClosed() {
      //清除格式化
      this.$refs.form.clearValidate()
      //关闭阅读
      this.isRead = false
    },
    // 关闭弹窗
    dialogClose() {
      const deleteKeys = this.$tools.difference(Object.keys(this.form), Object.keys(this.originalForm))
      //删除多余的
      for (let val of deleteKeys) {
        delete this.form[val]
      }
      //覆盖最初数据
      Object.assign(this.form, this.$tools.cloneDeep(this.originalForm))
    }
  }
}
</script>

<style scoped>

</style>
