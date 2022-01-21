<template>
  <curd-card>
    <el-tabs v-model="tabIndex" tab-position="left">
      <el-tab-pane v-permission="'config@attachment'" name="attachment" label="基本设置">
        <attachment/>
      </el-tab-pane>
      <el-tab-pane v-permission="'config@attachmentValidate'" name="validate" label="上传限制">
        <validate/>
      </el-tab-pane>
    </el-tabs>
  </curd-card>
</template>

<script>
import Attachment from './components/Attachment'
import Validate from './components/Validate'

export default {
  name: "attachmentSetting",
  components: {Attachment, Validate},
  data() {
    return {
      tabIndex: 'attachment'
    }
  }
}
</script>

<style scoped>

</style>
