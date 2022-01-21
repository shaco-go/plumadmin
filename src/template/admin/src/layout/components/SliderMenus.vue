<template>
  <div>
    <div v-for="item in menus">
      <template v-if="item.hidden===false">
        <!--子菜单-->
        <el-submenu
          v-if="item.children && item.children.length"
          :index="item.id+''"
          :key="item.id"
        >
          <template slot="title">
            <div class="menu-title">
              <div class="icon-box">
                <svg-icon v-if="item.icon" class-name="title-icon" :icon-class="item.icon"/>
              </div>
              <span>{{ item.title }}</span>
            </div>
          </template>
          <!--递归-->
          <slider-menus :menus="item.children"></slider-menus>
        </el-submenu>
        <!--无子菜单,直接显示-->
        <el-menu-item
          v-else
          :index="item.url?item.url:item.path"
          :key="item.id"
        >
          <div class="menu-title">
            <div class="icon-box">
              <svg-icon v-if="item.icon" class-name="title-icon" :icon-class="item.icon"/>
            </div>
            <span>{{ item.title }}</span>
          </div>
        </el-menu-item>
      </template>
    </div>
  </div>


</template>

<script>
export default {
  name: 'SliderMenus',
  props: ['menus']
}
</script>

<style lang="scss">
.el-menu .el-menu-item, .el-menu .el-submenu__title {
  &.is-active {
    background: #2d8cf0 !important;
  }

  height: 42px;
  line-height: 42px;
  border-radius: 4px;
  box-sizing: border-box;
  width: calc(100% - 18px);
  min-width: auto !important;
  margin: 0 auto 10px;
}
</style>


<style lang="scss" scoped>
.menu-title {
  display: flex;
  align-items: center;

  .icon-box {
    height: 100%;
    width: 26px;
  }

  .title-icon {
    font-size: 18px;
    line-height: 20px;
    margin-right: 8px;
  }
}

</style>
