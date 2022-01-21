<template>
  <curd-card v-loading="pageLoading">
    <!--测试-->
    <el-dialog
      :visible.sync="visible"
      title="验证邮件"
      @close="dialogClose"
    >
      <el-alert v-if="errMessage" style="margin-bottom: 10px" :title="errMessage" type="error"></el-alert>
      <el-input v-model="address" size="small" placeholder="请输入接受邮件账号"/>
      <template #footer>
        <el-button size="small" plain @click="visible = false">关闭</el-button>
        <el-button :loading="emailLoading" size="small" type="primary" @click="handleTestEmail">验证</el-button>
      </template>
    </el-dialog>

    <!--表单-->
    <el-form style="width: 60%;margin:auto;" ref="form" size="small" :model="form" label-width="150px">
      <el-form-item label="邮件通知" prop="switch">
        <template #label>
          <div style="display: flex;align-items: center;justify-content: flex-end">
            <span>邮件通知</span>
            <el-tooltip content="当有内部错误时,通过邮件的方式通知" placement="right">
              <i class="el-icon-question" style="margin-left: 5px"/>
            </el-tooltip>
          </div>
        </template>
        <el-switch v-model="form.switch"/>
      </el-form-item>
      <el-form-item label="SMTP服务器" prop="smtp_host" :rules="{required:true,message:'请输入SMTP服务器'}">
        <el-input clearable placeholder="请输入SMTP服务器" v-model="form.smtp_host"/>
      </el-form-item>
      <el-form-item label="用户名" prop="username" :rules="{required:true,message:'请输入SMTP用户名'}">
        <el-input clearable placeholder="请输入SMTP用户名" v-model="form.username"/>
      </el-form-item>
      <el-form-item label="密码" prop="password" :rules="{required:true,message:'请输入SMTP密码'}">
        <el-input clearable show-password placeholder="请输入SMTP密码" v-model="form.password"/>
      </el-form-item>
      <el-form-item label="端口" prop="port" :rules="{required:true,message:'请输入端口'}">
        <el-input clearable placeholder="请输入端口" v-model="form.port"/>
      </el-form-item>
      <el-form-item label="发件人邮箱" prop="from_email" :rules="{required:true,message:'请输入发件人邮箱'}">
        <el-input clearable placeholder="请输入发件人邮箱" v-model="form.from_email"/>
      </el-form-item>
      <el-form-item label="发件人名称">
        <el-input clearable placeholder="请输入发件人名称" v-model="form.form_email_name"/>
      </el-form-item>
      <el-form-item label="收件人邮箱">
        <template #label>
          <div style="display: flex;align-items: center;justify-content: flex-end">
            <span>邮件通知</span>
            <i @click="handleInsertAddress" class="el-icon-circle-plus" style="margin-left: 5px;color: #409eff"/>
          </div>
        </template>
        <div class="to-email-item" v-for="(item,index) in form.to_email">
          <el-input v-model="form.to_email[index]" placeholder="请输入收件人邮箱"/>
          <div class="close-icon">
            <i class="el-icon-circle-close" @click="form.to_email.splice(index,1)"></i>
          </div>
        </div>
      </el-form-item>
      <el-form-item>
        <el-button :loading="loading" type="primary" @click="handleSubmit">保存</el-button>
        <el-button v-permission="'emailDebug@testSend'" type="primary" @click="visible=true">验证邮件</el-button>
      </el-form-item>
    </el-form>
  </curd-card>
</template>

<script>
export default {
  name: "debugEmailLog",
  data() {
    return {
      pageLoading: false,
      collapseIndex: 'base',
      loading: false,
      visible: false,
      address: '',
      errMessage: '',
      emailLoading: false,
      form: {
        switch: false,
        smtp_host: '',
        username: '',
        password: '',
        port: '',
        from_email: '',
        form_email_name: '',
        to_email: []
      }
    }
  },
  mounted() {
    this.pageLoading = true
    this.getData()
  },
  methods: {
    //获取配置数据
    getData() {
      this.$http.get('system/email/config')
        .then(data => {
          this.form = data.data
        })
        .finally(() => {
          this.pageLoading = false
        })
    },
    // 保存
    handleSubmit() {
      this.$refs.form.validate(valid => {
        if (valid) {
          this.loading = true
          this.$http.post('system/email/config', {data: this.form})
            .then(data => {
              this.$message.success(data.message)
            })
            .finally(() => {
              this.loading = false
            })
        }
      })
    },
    // 测试邮件
    handleTestEmail() {
      this.emailLoading = true
      this.$http.post('system/email/test/send', {address: this.address}, {toast: false})
        .then(data => {
          this.$message.success(data.message)
          this.visible = false
        })
        .catch(data => {
          this.errMessage = data.message
        })
        .finally(() => {
          this.emailLoading = false
        })

    },
    // 关闭dialog
    dialogClose() {
      this.address = ''
      this.errMessage = ''
    },
    // 新增邮件
    handleInsertAddress(){
      this.form.to_email.push('')
    }
  }
}
</script>

<style scoped lang="scss">
.to-email-list {
  display: flex;
  flex-direction: column;
}

.to-email-item {
  display: flex;
  align-items: center;
  padding: 5px 10px;
  border-radius: 5px;
  margin-bottom: 5px;

  &:hover {
    background: #f0f0f0;

    .el-icon-circle-close {
      display: inline-block;
    }
  }

  .el-icon-circle-close {
    display: none;
  }

  .el-input {
    flex-shrink: 1;
    flex-grow: 1;
  }

  .close-icon {
    flex-shrink: 0;
    width: 28px;
    height: 28px;
    text-align: right;
    display: flex;
    align-items: center;
    justify-content: flex-end;
  }
}
</style>
