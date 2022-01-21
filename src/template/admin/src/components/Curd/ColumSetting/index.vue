<template>
  <!--列设置-->
  <el-dialog
      width="320px"
      top="0"
      custom-class="colum-dialog"
      :visible.sync="dialogVisible"
  >
    <el-scrollbar
        style="height:100%;"
        wrap-style="overflow-x:hidden;"
    >
      <div class="colum-title">
        <el-checkbox :indeterminate="indeterminate" v-model="checkoutAll" @change="handleCheckoutAll"/>
        <div>列设置</div>
      </div>
      <el-divider v-if="leftFixed">固定在左侧</el-divider>
      <vue-draggable v-model="columns" class="colum-list">
        <div :class="{'colum-item':true,visible:item.fixed===true || item.fixed === 'left'}"
             v-for="(item,index) in columns" :key="index">
          <el-checkbox v-model="item.visible" @change="handleCheckout"/>
          <div class="colum-item-label">{{ item.label }}</div>
          <div class="operation">
            <i @click="handleColumFixed(index,'left')"
               :class="{'el-icon-s-fold':true,active:item.fixed==='left' || item.fixed===true}"></i>
            <i @click="handleColumFixed(index,'right')"
               :class="{'el-icon-s-unfold':true,active:item.fixed==='right'}"></i>
          </div>
        </div>
      </vue-draggable>

      <el-divider v-if="notFixed && (rightFixed || leftFixed)">不固定</el-divider>
      <vue-draggable v-model="columns" class="colum-list">
        <div :class="{'colum-item':true,visible:!item.fixed}" v-for="(item,index) in columns" :key="index">
          <el-checkbox v-model="item.visible" @change="handleCheckout"/>
          <div class="colum-item-label">{{ item.label }}</div>
          <div class="operation">
            <i @click="handleColumFixed(index,'left')"
               :class="{'el-icon-s-fold':true,active:item.fixed==='left' || item.fixed===true}"></i>
            <i @click="handleColumFixed(index,'right')"
               :class="{'el-icon-s-unfold':true,active:item.fixed==='right'}"></i>
          </div>
        </div>
      </vue-draggable>

      <el-divider v-if="rightFixed">固定在右侧</el-divider>
      <vue-draggable v-model="columns" class="colum-list">
        <div :class="{'colum-item':true,visible: item.fixed === 'right'}" v-for="(item,index) in columns" :key="index">
          <el-checkbox v-model="item.visible" @change="handleCheckout"/>
          <div class="colum-item-label">{{ item.label }}</div>
          <div class="operation">
            <i @click="handleColumFixed(index,'left')"
               :class="{'el-icon-s-fold':true,active:item.fixed==='left' || item.fixed===true}"></i>
            <i @click="handleColumFixed(index,'right')"
               :class="{'el-icon-s-unfold':true,active:item.fixed==='right'}"></i>
          </div>
        </div>
      </vue-draggable>
    </el-scrollbar>
    <template #footer>
      <el-button size="small" @click="handleReset">重置</el-button>
      <el-button size="small" type="primary" @click="handleConfirm">确认</el-button>
    </template>
  </el-dialog>
</template>

<script>
import vueDraggable from 'vuedraggable'

export default {
  name: "index",
  components: {vueDraggable},
  props: ['value', 'visible', 'originalData'],
  data() {
    return {
      columns: [],
      dialogVisible: false,
      indeterminate: false,
      checkoutAll: false
    }
  },
  computed: {
    leftFixed() {
      return this.columns.filter(e => {
        return e.fixed === true || e.fixed === 'left'
      }).length !== 0
    },
    notFixed() {
      return this.columns.filter(e => {
        return !e.fixed
      }).length !== 0
    },
    rightFixed() {
      return this.columns.filter(e => {
        return e.fixed === 'right'
      }).length !== 0
    },
    // 选中数量
    checkCount() {
      return this.columns.filter(e => {
        return e.visible
      }).length
    },
    // 总数量
    count() {
      return this.columns.length
    },
  },
  mounted() {
    this.handleCheckout()
  },
  methods: {
    // 列设置固定
    handleColumFixed(index, arrow) {
      let fixed = this.columns[index].fixed
      if (arrow === 'right') {
        this.$set(this.columns[index], 'fixed', fixed === 'right' ? false : arrow)
      }
      if (arrow === 'left') {
        this.$set(this.columns[index], 'fixed', (fixed === 'left' || fixed === true) ? false : arrow)
      }
    },
    //确认
    handleConfirm() {
      this.$emit('input', this.$tools.cloneDeep(this.columns))
      this.dialogVisible = false
    },
    //重置
    handleReset() {
      this.columns = this.$tools.cloneDeep(this.originalData.table.columns)
    },
    //修改checkoutAll
    handleCheckoutAll(state) {
      this.columns = this.columns.map(e => {
        e.visible = state
        return e
      })
      this.indeterminate = this.checkCount > 0 && this.checkCount < this.count
    },
    //修改checkout
    handleCheckout() {
      this.indeterminate = this.checkCount > 0 && this.checkCount < this.count
      this.checkoutAll = this.checkCount === this.count
    }
  },
  watch: {
    value: {
      immediate: true,
      deep: true,
      handler(val) {
        this.columns = this.$tools.cloneDeep(val)
      }
    },
    visible(val) {
      this.dialogVisible = val
    },
    dialogVisible(val) {
      this.$emit('update:visible', val)
    }
  }
}
</script>

<style scoped lang="scss">
//列设置
::v-deep .colum-dialog {
  position: absolute;
  right: 0;
  height: 100vh;

  .el-dialog__body {
    height: calc(100% - 92px);
  }
}

.colum-title {
  display: flex;
  font-size: 16px;
  padding: 0 16px;
  margin-bottom: 30px;

  div {
    padding-left: 10px;
  }
}

.colum-list {
  .colum-item {
    &:hover {
      background: #f3f3f3;

      .operation {
        display: flex;
      }
    }

    &.visible {
      display: flex;
    }

    padding: 0 16px;
    display: none;
    align-items: center;
    height: 38px;

    .colum-item-label {
      flex-grow: 1;
      text-align: left;
      padding-left: 10px;
    }

    .operation {
      display: none;
      justify-content: space-between;
      width: 40px;

      .active {
        color: #409eff;
      }
    }
  }
}
</style>
