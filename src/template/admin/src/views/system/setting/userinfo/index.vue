<template>
  <curd-card>
    <el-tabs v-model="tabIndex" tab-position="left">
      <el-tab-pane name="userinfo" label="用户信息">
        <userinfo/>
      </el-tab-pane>
      <el-tab-pane name="password" label="修改密码">
        <password/>
      </el-tab-pane>
    </el-tabs>
  </curd-card>
</template>

<script>
import Userinfo from './components/Userinfo'
import Password from './components/Password'

export default {
  name: "personalSetting",
  components: {Userinfo, Password},
  data() {
    return {
      tabIndex: 'userinfo'
    }
  }
}
</script>

<style scoped>
.userinfo-form-container {
  width: 70%;
  margin: auto;
}
</style>
