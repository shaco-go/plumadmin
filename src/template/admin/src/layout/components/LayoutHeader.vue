<template>
  <div class="header-container">
    <!--面包屑-->
    <div class="left">
      <i class="el-icon-refresh-right" @click="viewReload"/>
      <el-breadcrumb separator="/">
        <el-breadcrumb-item v-for="(item,index) in breadcrumb" :key="index">
          {{ item.title }}
        </el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <!--用户-->
    <div class="right">
      <el-dropdown class="userinfo" @command="handleUserinfoDropdown">
        <div>
          <el-avatar fit="cover" size="small" :src="avatar" @error="()=>true">
            <i class="el-icon-user-solid"></i>
          </el-avatar>
          <span style="padding-left: 12px">{{ nickname }}</span>
        </div>
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item command="updateUserinfo">
            <svg-icon icon-class="userinfo"></svg-icon>
            <span>个人设置</span>
          </el-dropdown-item>
          <el-dropdown-item command="logout">
            <svg-icon icon-class="logout"></svg-icon>
            <span>退出登录</span>
          </el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
    </div>
  </div>
</template>

<script>
import {findLinks} from '@/utils/tools'
import {mapActions, mapGetters} from 'vuex'

export default {
  name: "LayoutHeader",
  inject: ['viewReload'],
  data() {
    return {
      breadcrumb: []
    }
  },
  computed: {
    ...mapGetters(['nickname', 'avatar'])
  },
  methods: {
    ...mapActions('user', ['logout']),
    handleUserinfoDropdown(command) {
      if (command === 'updateUserinfo') {
        this.$router.push('/admin/system/userinfo/setting')
      }
      if (command === 'logout') {
        this.logout().then(() => {
          this.$router.push('/admin/login')
        })
      }
    }
  },
  watch: {
    '$route.path': {
      immediate: true,
      handler() {
        //获取面包屑
        const menus = this.$store.state.permission.menus
        this.breadcrumb = findLinks(menus, e => e.path === this.$route.path)
      }
    }
  }
}
</script>

<style scoped lang="scss">
//头像
::v-deep .el-avatar img {
  width: 100%;
  height: 100%;
}

.header-container {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;

  //头部左侧
  .left {
    display: flex;
    align-items: center;

    i {
      margin-right: 12px;
      cursor: pointer;
    }
  }

  //头部右侧
  .right {
    height: 100%;

    //用户
    .userinfo {
      height: 100%;
      display: flex;
      align-items: center;
      cursor: default;

      &:hover {
        background: rgba(0, 0, 0, .025);
      }

      & > div {
        display: flex;
        align-items: center;
        font-size: 15px;
        color: #000;
        padding: 0 12px;
      }
    }
  }
}

//下拉菜单
.el-dropdown-menu .el-dropdown-menu__item {
  font-size: 14px;
  color: #515a6e;

  .svg-icon {
    margin-right: 6px;
  }
}
</style>
