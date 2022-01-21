<template>
  <div>
    {{list}}
    <Dialog v-model="list" :visible.sync="visible"></Dialog>
    <el-button @click="visible=!visible">测试</el-button>
  </div>
</template>
<script>
import Dialog from '@/components/AttachmentLibrary'

export default {
  name:'dashboard',
  components: {Dialog},
  data() {
    return {
      visible: true,
      list:undefined
    }
  },
  methods: {}
}
</script>
<style scoped lang="scss">

</style>
