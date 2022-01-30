<template>
  <el-container class="layout-container">
    <!--侧边栏-->
    <el-aside class="layout-aside" width="200px">
      <!--LOGO-->
      <div class="layout-aside-logo">
        <el-image class="logo" fit="cover" :src="sliderLogo"></el-image>
        <span>{{ sliderTitle }}</span>
      </div>
      <el-scrollbar
        style="height: calc(100vh - 64px)"
        wrap-style="overflow-x:hidden;"
      >
        <!--菜单-->
        <el-menu
          background-color="#001428"
          text-color="#bbb"
          active-text-color="#fff"
          :default-active="defaultActive"
          @select="selectMenus"
        >
          <slider-menus :menus="menus"></slider-menus>
        </el-menu>
      </el-scrollbar>
    </el-aside>

    <el-container class="layout-main">
      <!--头部-->
      <el-header class="layout-header">
        <layout-header/>
      </el-header>

      <el-main class="layout-content">
        <!--选项卡-->
        <layout-tabs class="layout-tabs"/>
        <!--内容-->
        <el-scrollbar
            style="height: calc(100vh - 104px);width:100%"
            wrap-style="overflow-x:hidden;"
        >
          <keep-alive :include="keepAliceInclude" :exclude="keepAliceExclude">
            <router-view v-if="routerViewVisible"></router-view>
          </keep-alive>
          <!--返回顶部-->
          <el-backtop target=".el-scrollbar__wrap"></el-backtop>
        </el-scrollbar>
      </el-main>
    </el-container>
  </el-container>
</template>

<script>
import SliderMenus from '@/layout/components/SliderMenus'
import LayoutHeader from '@/layout/components/LayoutHeader'
import LayoutTabs from '@/layout/components/LayoutTabs'
import {sliderLogo, sliderTitle} from '@/config'
import {mapGetters} from 'vuex'
import {isExternal} from '@/utils/tools'

export default {
  components: {
    SliderMenus,
    LayoutHeader,
    LayoutTabs
  },
  data() {
    return {
      sliderLogo,
      sliderTitle,
      routerViewVisible: true,
      keepAliceExclude:  [],
      menusActive:undefined,
    }
  },
  provide() {
    return {
      viewReload: this.viewReload
    }
  },
  computed: {
    ...mapGetters(['menus']),
    //需要缓存的路由tabs
    keepAliceInclude() {
      let keepAlive = this.$store.state.permission.routes.list.filter(e => e.keep_alive)
      keepAlive = keepAlive.map(e => e.name)
      let tabs = this.$store.state.tabs.activeTabs.map(e => e.name)
      //交集
      return this.$tools.intersection(keepAlive, tabs)
    },
    //当前激活路由
    defaultActive(){
      return this.menusActive || this.$route.path
    }
  },
  methods:  {
    //选择菜单
    selectMenus(path) {
      if (isExternal(path)) {
        window.open(path, '_blank')
        this.menusActive = 'defaultActive'
        this.$nextTick(()=>{
          //切换一下激活菜单
          this.menusActive = undefined
        })
      } else {
        this.$router.push(path)
      }
    },
    //刷新视图
    viewReload() {
      this.keepAliceExclude = [this.$route.name]
      this.routerViewVisible = false
      this.$nextTick(() => {
        this.routerViewVisible = true
        this.keepAliceExclude = []
      })
    }
  }
}
</script>

<style scoped lang="scss">
//主体
.layout-container {
  height: 100%;

  //侧边栏
  .layout-aside {
    background: #001428;
    //取消菜单边框
    ::v-deep .el-menu {
      border-right: none;
    }

    //LOGO
    .layout-aside-logo {
      height: 64px;
      overflow: hidden;
      display: flex;
      color: #fff;
      align-items: center;
      justify-content: center;
      font-size: 16px;

      .logo {
        width: 32px;
        height: 32px;
        margin-right: 10px
      }
    }
  }

  //左侧内容
  .layout-main {
    background: #f5f7f9;
    //内容
    .layout-content {
      padding: 0;

      .layout-tabs {
        width: 100%;
        box-sizing: border-box;
        padding: 6px 10px;
      }
    }
  }

  .layout-header {
    background: #ffffff;
    box-shadow: 0 1px 4px rgb(0 21 41 / 8%);
  }
}
</style>
