<template>
  <el-form ref="form" label-width="150px" :model="form" size="small" style="width: 700px">
    <el-form-item label="昵称" prop="nickname" :rules="{required:true,message:'请输入昵称'}">
      <el-input placeholder="请输入昵称" v-model="form.nickname"/>
    </el-form-item>

    <el-form-item label="头像" prop="avatar" :rules="{required:true,message:'请上传头像'}">
      <upload-image v-model="form.avatar"/>
    </el-form-item>

    <el-form-item>
      <el-button :loading="loading" type="primary" @click="handleSubmit">保存</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
import {mapGetters,mapActions} from 'vuex'

export default {
  name: "Userinfo",
  data() {
    return {
      loading: false,
      form: {
        nickname: '',
        avatar: undefined,
      }
    }
  },
  computed: {
    ...mapGetters(['user'])
  },
  mounted() {
    this.setUserinfo()
  },
  methods: {
    ...mapActions('user',['getUserInfo']),
    // 保存
    handleSubmit() {
      this.loading = true
      this.$refs.form.validate(valid => {
        if (valid) {
          this.$http.post('userinfo/update',this.form)
              .then(data => {
                this.$message.success(data.message)
                //重新获取用户信息
                this.getUserInfo()
              })
              .finally(() => {
                this.loading = false
              })
        }
      })
    },
    // 获取用户信息
    setUserinfo() {
      this.form = {
        nickname: this.user.nickname,
        avatar: this.user.avatar
      }
    }
  }
}
</script>

<style scoped>

</style>
