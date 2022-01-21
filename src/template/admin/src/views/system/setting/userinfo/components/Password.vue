<template>
  <el-form ref="form" label-width="150px" :model="form" size="small" style="width: 700px">
    <el-form-item label="旧密码" prop="old_password" :rules="{required:true,message:'请输入旧密码'}">
      <el-input clearable show-password placeholder="请输入旧密码" v-model="form.old_password"/>
    </el-form-item>
    <el-form-item label="新密码" prop="new_password" :rules="{required:true,message:'请输入新密码'}">
      <el-input clearable show-password placeholder="请输入新密码" v-model="form.new_password"/>
    </el-form-item>
    <el-form-item label="确认密码" prop="old_password" :rules="{required:true,message:'请输入确认密码'}">
      <el-input clearable show-password placeholder="请输入确认密码" v-model="form.confirm_password"/>
    </el-form-item>
    <el-form-item>
      <el-button type="primary" :loading="loading" @click="handleSubmit">保存</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
export default {
  name: "Password",
  data() {
    return {
      loading: false,
      form: {
        old_password: '',
        confirm_password: '',
        new_password: ''
      }
    }
  },
  methods: {
    handleSubmit() {
      this.loading = true
      this.$refs.form.validate(valid => {
        if (valid) {
          this.$http.post('userinfo/password/update', this.form)
              .then(data => {
                this.$message.success(data.message)
                //重置密码
                this.form = this.$options.data().form
              })
              .finally(() => {
                this.loading = false
              })
        }
      })
    }
  }
}
</script>

<style scoped>

</style>
