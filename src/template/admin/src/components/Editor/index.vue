<template>
  <div ref="editor"/>
</template>

<script>
import E from 'wangeditor'

export default {
  name: "editor",
  props: {
    width: {
      type: String,
      default: '100%'
    },
    height: {
      type: Number,
      default: 500
    },
    value: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      editor: undefined,
      insideHtml: ''
    }
  },
  mounted() {
    this.$nextTick(() => {
      this.editor = new E(this.$refs.editor)
      //设置高度
      this.editor.config.height = this.height
      //修改内容回调
      this.editor.config.onchange = (html) => {
        this.$emit('input', html)
      }
      this.editor.create()
      //初始化内容
      this.editor.txt.html(this.value)
    })
  },
  watch: {
    value: {
      immediate: true,
      handler(val) {
        this.$nextTick(() => {
          if (this.editor && val !== this.editor.txt.html()) {
            this.editor.txt.html(val)
          }
        })
      }
    }
  }
}
</script>

<style scoped>

</style>
