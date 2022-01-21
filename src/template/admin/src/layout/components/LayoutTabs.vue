<template>
  <div class="tabs-container">
    <div class="tabs-wrap">
      <div class="tabs-prev" @click="adjustSlider(sliderContainerWidth)">
        <i class="el-icon-arrow-left" />
      </div>
      <!--选项卡容器-->
      <div class="tabs-scroll-container" ref="tabContainer">
        <div class="tabs-scroll" :style="{transform: `translateX(${tabLeft}px)`}" ref="tabSlider">
          <div @click="$router.push(item.path)" :class="{tab:true,active:currentTabs.path === item.path}" v-for="item in tabs" :key="item.path">
            <span>{{ item.title }}</span>
            <i style="padding-left: 5px" v-if="tabs.length>1" class="el-icon-close" @click.stop="closeTab(item.path)" />
          </div>
        </div>
      </div>
      <div class="tabs-next" @click="adjustSlider(-sliderContainerWidth)">
        <i class="el-icon-arrow-right" />
      </div>
    </div>
    <el-dropdown @command="handleTabClose">
      <div class="tabs-close">
        <i class="el-icon-arrow-down" />
      </div>
      <el-dropdown-menu slot="dropdown">
        <el-dropdown-item command="close">
          <i class="el-icon-close"></i>
          <span>关闭当前</span>
        </el-dropdown-item>
        <el-dropdown-item command="closeOther">
          <i class="el-icon-error"></i>
          <span>关闭其他</span>
        </el-dropdown-item>
      </el-dropdown-menu>
    </el-dropdown>
  </div>
</template>

<script>
import {mapGetters, mapMutations,mapState} from 'vuex'

export default {
  data() {
    return {
      sliderContainerWidth: 0,
      sliderWidth: 0,
      tabLeft: 0
    }
  },
  computed: {
    ...mapGetters(['tabs']),
    ...mapState('tabs',['currentTabs'])
  },
  methods: {
    ...mapMutations('tabs', {
      closeTab: 'CLOSE_TAB',
      closeOtherTab: 'CLOSE_OTHER_TAB'
    }),
    //调整滑块
    adjustSlider(changeWidth = 0) {
      this.$nextTick(() => {
        let tabLeft = changeWidth + this.tabLeft
        if (tabLeft > 0 || this.tabLeft > 0) {
          //如果边距和当前边距,大于0,则重新置为0
          tabLeft = 0
        } else if (Math.abs(this.tabLeft) >= this.sliderWidth) {
          //如果当前的边距绝对值,大于容器,则容器宽度-滑块宽度
          tabLeft = this.sliderContainerWidth - this.sliderWidth
        } else if (Math.abs(tabLeft) >= this.sliderWidth) {
          //如果边距绝对值,大于容器,则返回原值
          tabLeft = this.tabLeft
        }
        this.tabLeft = tabLeft
      })
    },
    //关闭选项卡
    handleTabClose(command){
      if(command === 'close'){
        this.closeTab(this.currentTabs.path)
      }
      if(command === 'closeOther'){
        this.closeOtherTab(this.currentTabs.path)
      }
    }
  },
  watch: {
    //监控选项卡
    tabs: {
      immediate: true,
      handler() {
        this.$nextTick(() => {
          //设置滑块宽度
          this.sliderContainerWidth = this.$refs.tabContainer.offsetWidth
          this.sliderWidth = this.$refs.tabSlider.scrollWidth
          //校验滑块位置
          this.adjustSlider()
        })
      }
    }
  }
}
</script>

<style scoped lang="scss">
.tabs-container {
  display: flex;
  align-items: center;
  overflow: hidden;
  color: #808695;
  font-size: 14px;
  cursor: pointer;

  //标签卡关闭按钮
  .tabs-close {
    background-color: #ffffff;
    border-radius: 2px;
  }

  //标签卡左右关闭按钮
  .tabs-prev, .tabs-next, .tabs-close {
    flex-shrink: 0;
    width: 32px;
    height: 32px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #515a6e;
    font-size: 16px;

    i {
      font-weight: 600;
    }
  }

  //标签卡容器
  .tabs-wrap {
    display: flex;
    flex-grow: 1;
    overflow: hidden;
    //滑块容器
    .tabs-scroll-container {
      display: flex;
      overflow: hidden;
      flex-grow: 1;
      //滑块
      .tabs-scroll {
        display: flex;
        transition: transform 0.5s ease-in-out;

        .tab {
          flex-shrink: 0;
          padding: 0 16px;
          line-height: 32px;
          background: #ffffff;
          border-radius: 2px;
          margin-right: 6px;
          display: flex;
          align-items: center;

          i {
            width: 22px;
            height: 22px;
            line-height: 22px;
          }

          //当被选中的时候
          &:hover i {
            font-weight: 600;
          }

          //当tab被选中的时候
          &.active span {
            color: #2d8cf0;
          }
        }
      }
    }
  }
}
</style>
