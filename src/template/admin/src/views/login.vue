<template>
  <div class="login-container">
    <div class="panel left-panel">
      <img class="image" src="@/assets/login/image.svg" alt="">
    </div>
    <div class="panel right-panel">
      <div class="title">PLUM ADMIN</div>
      <div class="form">
        <div class="form-item">
          <div class="label">账号</div>
          <input v-model="form.username" type="text" placeholder="请输入账号" @keyup.enter="submit">
        </div>
        <div class="form-item">
          <div class="label">密码</div>
          <input v-model="form.password" type="password" placeholder="请输入密码" @keyup.enter="submit">
        </div>
        <div class="login-button" @click="submit">登录</div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapActions} from 'vuex'

export default {
  data() {
    return {
      form: {
        username: '',
        password: ''
      }
    }
  },
  methods: {
    ...mapActions({login: 'user/login', getUserInfo: 'user/getUserInfo'}),
    submit() {
      if (this.validate()) {
        const message = this.$message({
          duration: 0,
          message: '登陆中',
          iconClass: 'el-icon-loading',
          customClass: 'el-message-loading'
        })
        //登录
        this.login(this.form)
            .then(() => {
              //获取用户信息
              return this.getUserInfo()
            })
            .then(() => {
              this.$message.success('登录成功!')
              this.$router.push(this.$route.params.redirect??'/admin')
            })
            .finally(() => {
              message.close()
            })
      }
    },
    validate() {
      if (!this.form.username) {
        this.$message.error('请输入账号')
        return false
      }
      if (!this.form.password) {
        this.$message.error('请输入密码')
        return false
      }
      return true
    }

  }
}
</script>


<style lang="scss" scoped>
.login-container {
  display: flex;
  height: 100vh;

  .panel {
    flex: 1;
    z-index: 10;
    height: 100%;
  }


  .left-panel {
    position: relative;
    box-sizing: border-box;

    .image {
      width: 60%;
      z-index: 20;
      position: absolute;
      bottom: 10%;
      right: 17%;
    }
  }

  .right-panel {
    .title {
      margin-bottom: 50px;
    }

    color: #07003b;
    font-size: 60px;
    font-weight: 550;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

    .form {
      font-weight: 500;

      .login-button {
        width: 400px;
        height: 60px;
        background: linear-gradient(#5566e4, #3c50e0);
        border-radius: 10px;
        color: #ffffff;
        font-size: 18px;
        font-weight: 600;
        line-height: 60px;
        text-align: center;
        margin-top: 50px;
        cursor: default;
        user-select: none;
      }

      .form-item {
        margin-bottom: 30px;

        .label {
          color: #4d5a7e;
          font-size: 18px;
          font-weight: 600;
          text-align: left;
        }

        input {
          width: 400px;
          height: 60px;
          line-height: 60px;
          color: #4d5a7e;
          font-size: 18px;
          border: 1px solid #f3f3f3;
          box-shadow: 2px 2px 6px 0 rgba(#8c8c8c, .2);
          border-radius: 10px;
          padding: 0 30px;

          &:focus {
            border: 1px solid #4d5a7e;
            outline: 0;
          }

          &::-webkit-input-placeholder {
            color: #c3c9da;
            font-size: 18px;
          }
        }
      }
    }
  }

  &:before {
    content: "";
    position: absolute;
    height: 100vw;
    width: 100vw;
    top: -10%;
    right: 48%;
    transform: translateY(-50%);
    background-image: linear-gradient(-45deg, #3c50e0 0%, #707ee8 100%);
    transition: 1.8s ease-in-out;
    border-radius: 50%;
    z-index: 6;
  }
}


</style>
