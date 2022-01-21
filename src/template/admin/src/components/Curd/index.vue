<template>
  <div>
    <!--搜索框-->
    <curd-card style="margin-bottom: 6px" v-if="search && searchVisible">
      <el-form ref="searchForm" :class="{'curd-search-form':true,'expand-all':searchExpand}" inline size="small">
        <!--搜索框表单项-->
        <slot name="search"></slot>
        <!--搜索框按钮-->
        <el-form-item class="search-operation">
          <el-button type="primary" icon="el-icon-search" @click="handleSearch">查询</el-button>
          <el-button icon="el-icon-refresh-right" @click="handleSearchReset">重置</el-button>
          <el-button v-if="showExpand" type="text" @click="searchExpand = !searchExpand">
            <span>{{ searchExpand ? '收起' : '展开' }}</span>
            <i :class="{'el-icon-arrow-down':!searchExpand,'el-icon-arrow-up':searchExpand}"></i>
          </el-button>
        </el-form-item>
      </el-form>
    </curd-card>

    <curd-card style="margin-bottom: 50px" ref="screenFull">
      <!--工具栏-->
      <div class="curd-tools-container">
        <div>
          <slot name="tools"></slot>
        </div>
        <div class="icon-container">
          <el-tooltip placement="top" content="刷新">
            <svg-icon class-name="icon-item" icon-class="reload" @click="getData(true)"/>
          </el-tooltip>
          <el-tooltip v-if="search" placement="top" :content="searchVisible?'收起查询':'展开查询'">
            <svg-icon class-name="icon-item" icon-class="search" @click="searchVisible = !searchVisible"/>
          </el-tooltip>
          <el-tooltip placement="top" content="密度">
            <el-dropdown placement="bottom" trigger="click" @command="handleTableSize">
              <div class="icon-item">
                <svg-icon icon-class="colum-height"></svg-icon>
              </div>
              <el-dropdown-menu>
                <el-dropdown-item command="small" :class="{active:table.size==='small'}">默认</el-dropdown-item>
                <el-dropdown-item command="medium" :class="{active:table.size==='medium'}">宽松</el-dropdown-item>
                <el-dropdown-item command="mini" :class="{active:table.size==='mini'}">紧凑</el-dropdown-item>
              </el-dropdown-menu>
            </el-dropdown>
          </el-tooltip>
          <el-tooltip placement="top" content="列设置">
            <svg-icon class-name="icon-item" icon-class="setting" @click="columSettingVisible = !columSettingVisible"/>
          </el-tooltip>
          <el-tooltip placement="top" content="全屏">
            <svg-icon class-name="icon-item" :icon-class="screenFull?'compress':'expend'" @click="handleScreenFull"/>
          </el-tooltip>
        </div>
      </div>

      <!--表格-->
      <el-table v-bind="table" v-on="$listeners" v-loading="tableLoading" @sort-change="sortChange">
        <template v-if="table && table.columns">
          <template v-for="(item,index) in table.columns">
            <el-table-column v-if="item.visible" v-bind="item" :key="item.prop">
              <template v-if="item.__slotName" v-slot="scope">
                <slot :name="item.__slotName" :row="scope.row" :$index="scope.$index" :column="scope.column"></slot>
              </template>
            </el-table-column>
          </template>
        </template>
      </el-table>

      <!--分页-->
      <el-pagination
        v-if="page && page.total>0"
        class="pagination-container"
        background
        layout="total,prev, pager, next,sizes,jumper"
        :current-page="page.page"
        :total="page.total"
        :page-sizes="[5,10,20,30,40,50]"
        :page-size="page.size"
        @current-change="changePage"
        @size-change="changePageSize"
      />
    </curd-card>

    <!--列设置-->
    <colum-setting v-model="table.columns" :original-data="originalData" :visible.sync="columSettingVisible"/>
    <slot name="default"></slot>
  </div>
</template>

<script>
import screenFull from 'screenfull'
import ColumSetting from '@/components/Curd/ColumSetting'
import _ from 'lodash'

export default {
  name: "curd",
  components: {ColumSetting},
  props: {
    search: Object,
    table: Object,
    pagination: Object | Boolean,
    load: Function | Object | Array,
  },
  data() {
    return {
      searchExpand: false,// 搜索展开
      searchVisible: true,// 展开搜索
      originalData: undefined,// 原数据
      columSettingVisible: false,//列设置是否显示
      screenFull: false,//是否全屏
      showExpand: false,//显示搜索更多
      page: false,
      tableLoading: false,
      sortby: undefined,
      order: undefined
    }
  },
  mounted() {
    //初始化
    this.initDefault()
    //原始数据,避免污染方便重置
    this.originalData = this.$tools.cloneDeep({
      search: this.search,
      table: this.table,
      pagination: this.pagination
    })
    //查看是否全屏
    this.checkIsScreenFull()
    //监听宽度
    this.changeSearchExpand()
    window.onresize = () => {
      this.changeSearchExpand()
    }
    //加载数据
    this.getData(true)
  },
  methods: {
    //默认配置
    initDefault() {
      //边框
      if (this.table.border === undefined) {
        this.table.border = true
      }
      //表头颜色
      if (this.table['header-cell-style'] === undefined) {
        this.table['header-cell-style'] = {
          background: '#f8f8f9',
          color: '#515A6E'
        }
      }
      //表格大小
      if (this.table.size === undefined) {
        this.$set(this.table, 'size', 'small')
      }
      //列设置添加hidden
      if (this?.table?.columns !== undefined) {
        this.table.columns = this.table.columns.map(e => {
          e.visible = e.visible === true || e.visible === undefined
          return e
        })
      }
      if (this.pagination === true || this.pagination === undefined) {
        this.page = {
          page: 1,
          size: 10,
          total: 0
        }
      }
      this.$forceUpdate()
    },
    // 搜索
    handleSearch: _.debounce(function () {
      this.getData(true)
    }, 500),
    // 搜索重置
    handleSearchReset() {
      const originalSearchData = this.$tools.cloneDeep(this.originalData.search)
      Object.assign(this.search, originalSearchData)
      //重置分页
      this.page.page = 1
      this.getData()
    },
    // 表格大小
    handleTableSize(size) {
      this.$set(this.table, 'size', size)
    },
    //全屏
    handleScreenFull() {
      screenFull.toggle(this.$refs.screenFull.$el)
      this.screenFull = !this.screenFull
      this.checkIsScreenFull()
    },
    // 检查全屏情况
    checkIsScreenFull() {
      this.$nextTick(() => {
        setTimeout(() => {
          this.screenFull = screenFull.isFullscreen
        }, 500)
      })
    },
    // 调整是否需要搜索展开按钮
    changeSearchExpand() {
      this.$nextTick(() => {
        const elWidth = document.documentElement.clientWidth
        const count = this.$refs?.searchForm?.$el?.querySelectorAll('.el-form-item')?.length
        if (count && elWidth) {
          if (elWidth > 1200) {
            this.showExpand = count > 4
          } else if (elWidth > 1000) {
            this.showExpand = count > 3
          } else {
            this.showExpand = count > 2
          }
        } else {
          this.showExpand = false
        }
      })
    },
    // 获取数据
    getData(refresh = false) {
      this.tableLoading = true
      //是否刷新
      if (this.page && refresh) {
        this.page.page = 1
      }
      let search = this.search ? this.$tools.cloneDeep(this.search) : {}
        , data;
      search.page = this.page?.page
      search.size = this.page?.size
      search.order = this.order
      search.sortby = this.sortby
      this.$emit('load', search, (response) => {
        //获取成功
        // 设置数据
        if (this.page) {
          //分页
          this.table.data = response.data
          this.page.size = response.per_page
          this.page.total = response.total
        } else {
          //列表
          this.table.data = response
        }
        this.tableLoading = false
      }, () => {
        //获取失败
        this.tableLoading = false
      })
    },
    // 分页改动
    changePage(page) {
      this.page.page = page
      this.getData()
    },
    // 分页数量改动
    changePageSize(size) {
      this.page.size = size
      this.getData()
    },
    // 排序
    sortChange({prop, order}) {
      if (prop && order === 'ascending') {
        this.order = 'asc'
        this.sortby = prop
      } else if (prop && order === 'descending') {
        this.sortby = prop
        this.order = 'desc'
      } else {
        this.order = undefined
        this.sortby = undefined
      }
      this.getData()
    }
  }
}
</script>

<style scoped lang="scss">
//搜索器
.curd-search-form {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  @media screen and (max-width: 1200px) {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
  @media screen and (max-width: 1000px) {
    grid-template-columns: repeat(1, minmax(0, 1fr));
  }
  //搜索器操作容器
  .search-operation {
    text-align: right;
    grid-column: 4 / span 1;
    @media screen and (max-width: 1200px) {
      grid-column: 3 / span 1;
    }
    @media screen and (max-width: 1000px) {
      grid-column: 2 / span 1;
    }
  }

  //表单展开收拢
  ::v-deep &:not(.expand-all) .el-form-item:not(:nth-of-type(-n+3),:last-of-type) {
    display: none !important;
  }

  @media screen and (max-width: 1200px) {
    ::v-deep &:not(.expand-all) .el-form-item:not(:nth-of-type(-n+2),:last-of-type) {
      display: none !important;
    }
  }
  @media screen and (max-width: 1000px) {
    ::v-deep &:not(.expand-all) .el-form-item:not(:nth-of-type(-n+1),:last-of-type) {
      display: none !important;
    }
  }

  //表单项label 字体粗细正常
  ::v-deep .el-form-item__label {
    font-weight: normal;
  }

  //表单项布局改成flex
  ::v-deep .el-form-item:not(.search-operation) {
    display: flex;

    .el-form-item__label {
      flex-shrink: 0;
      min-width: 80px;
    }

    .el-form-item__content {
      flex-grow: 1;
    }
  }
}

//工具栏
.curd-tools-container {
  display: flex;
  justify-content: space-between;
  margin-bottom: 15px;
  //修整图标
  .icon-container {
    display: flex;
    align-items: center;

    //下拉菜单
    .el-dropdown {
      display: flex;
      align-items: center;
    }

    .icon-item {
      display: flex;
      align-items: center;
      justify-content: center;
      margin: auto 5px;
      font-size: 16px;
      color: #303133;
    }
  }
}

//工具栏下拉菜单默认情况
.el-dropdown-menu__item.active {
  background: #f3f3f5;
}

//分页
.pagination-container {
  text-align: right;
  margin-top: 12px;
}
</style>
