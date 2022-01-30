<template>
  <curd ref="curd" v-bind="curd" @load="handleLoad" @row-click="handleDetail">
    <!--搜索-->
    <template #search>
      <el-form-item label="操作信息">
        <el-input v-model="curd.search.message" placeholder="请输入操作信息"/>
      </el-form-item>
      <el-form-item label="操作路由">
        <el-input v-model="curd.search.route" placeholder="请输入操作路由"/>
      </el-form-item>
      <el-form-item label="ip">
        <el-input v-model="curd.search.ip" placeholder="请输入IP"/>
      </el-form-item>
      <el-form-item label="操作人">
        <el-input v-model="curd.search.operator" placeholder="请输入操作人"/>
      </el-form-item>
    </template>

    <!--表格工具-->
    <template #tools>
      <el-button icon="el-icon-delete" type="danger" size="small" @click="handleClear">全部清除</el-button>
    </template>

    <!--表格-->
    <template #op="{row}">
      <el-button type="text" @click="handleDelete(row.id)">删除</el-button>
    </template>

    <!--详情-->
    <el-dialog :visible.sync="visible" top="10vh" title="详情">
      <el-scrollbar
        style="max-height:calc(80vh - 135px);width: 100%"
        wrap-style="max-height:calc(80vh - 135px);overflow-x:hidden;"
        view-style="padding:0 10px 17px 10px;"
      >
        <el-form label-position="top">
          <el-form-item label="操作信息">
            <span>{{ detail.message }}</span>
          </el-form-item>

          <el-form-item label="操作人">
            <span>{{ detail.operator_name }}</span>
          </el-form-item>

          <el-form-item label="路由">
            <span>{{ detail.route }}</span>
          </el-form-item>

          <el-form-item label="IP">
            <span>{{ detail.ip }}</span>
          </el-form-item>

          <el-form-item label="参数">
            <pre>{{ detail.param }}</pre>
          </el-form-item>

          <el-form-item label="HEADER参数">
            <pre>{{ detail.header }}</pre>
          </el-form-item>
        </el-form>
      </el-scrollbar>
    </el-dialog>
  </curd>
</template>

<script>
export default {
  name: "operationLog",
  data() {
    return {
      curd: {
        table: {
          columns: [
            {
              prop: 'message',
              label: '操作信息',
              'min-width': '280px',
            },
            {
              prop: 'route',
              label: '操作路由',
              'min-width': '280px',
            },
            {
              prop: 'ip',
              label: 'IP',
              width: '150px',
            },
            {
              prop: 'operator_name',
              label: '操作人',
              width: '150px',
            },
            {
              prop: 'create_time',
              label: '创建时间',
              width: '150px',
            },
            {
              prop: 'op',
              label: '操作',
              width: '150px',
              __slotName: 'op',
              align: 'center',
              headerAlign: 'center',
              fixed: 'right'
            }
          ]
        },
        search: {
          route: '',
          message: '',
          ip: '',
          operator: ''
        },
      },
      detail: {},
      visible: false
    }
  },
  methods: {
    // 获取分页数据
    handleLoad(search, success, error) {
      this.$http.post('system/operation/log/page', search)
        .then(data => {
          success(data.data)
        })
        .catch(() => {
          error()
        })
    },
    // 获取详情
    handleDetail(row, {property}) {
      if (property !== 'op') {
        this.detail = row
        this.visible = true
      }
    },
    // 删除
    handleDelete(id) {
      this.$confirm('此操作将永久删除选中数据,是否继续?', '提示', {type: 'warning'})
        .then(() => {
          this.$http.post('system/operation/log/delete', {id})
            .then(data => {
              this.$message.success(data.message)
              this.$refs.curd.getData()
            })
            .catch(() => {
            })
        })
        .catch(() => {
        })
    },
    handleClear() {
      this.$confirm('此操作将永久清除所有数据,是否继续?', '提示', {type: 'warning'})
        .then(() => {
          this.$http.post('system/operation/log/clear')
            .then(data => {
              this.$message.success(data.message)
              this.$refs.curd.getData()
            })
            .catch(() => {
            })
        })
        .catch(() => {
        })
    }
  }
}
</script>

<style scoped>

</style>
