<template>
  <curd ref="curd" v-bind="curd" @load="handleLoad" @row-click="handleDetail">
    <!--搜索-->
    <template #search>
      <el-form-item label="路由">
        <el-input v-model="curd.search.url" placeholder="请输入路由地址"/>
      </el-form-item>

      <el-form-item label="ip地址">
        <el-input v-model="curd.search.ip" placeholder="请输入IP地址"/>
      </el-form-item>

      <el-form-item label="时间">
        <el-date-picker
          value-format="yyyy-MM-dd"
          style="width: 350px"
          v-model="curd.search.time"
          type="daterange"
          unlink-panels
          range-separator="至"
          start-placeholder="开始日期"
          end-placeholder="结束日期"
        >
        </el-date-picker>
      </el-form-item>
    </template>

    <!--工具-->
    <template #tools>
      <el-button v-permission="'debug@clear'" @click="handleClear" size="small" type="danger" icon="el-icon-delete">
        清空日志
      </el-button>
    </template>

    <!--表格-->
    <template #operation="{row}">
      <el-button v-permission="'debug@delete'" type="text" @click="handleDelete(row.id)">删除</el-button>
    </template>

    <!--详情-->
    <el-dialog :visible.sync="visible" top="10vh" title="详情">
      <el-scrollbar
        style="max-height:calc(80vh - 135px);width: 100%"
        wrap-style="max-height:calc(80vh - 135px);overflow-x:hidden;"
        view-style="padding:0 10px 17px 10px;"
      >
        <el-form label-position="top">
          <el-form-item label="路由">
            <span>[{{ detail.method }}] {{ detail.url }}</span>
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

          <el-form-item label="错误信息">
            <span>{{ detail.message }}</span>
          </el-form-item>

          <el-form-item label="文件">
            <span>{{ detail.file }}</span>
          </el-form-item>

          <el-form-item label="trace">
            <pre>{{ detail.trace }}</pre>
          </el-form-item>
        </el-form>
      </el-scrollbar>
    </el-dialog>
  </curd>
</template>

<script>
export default {
  name: "debugDatabaseLog",
  data() {
    return {
      visible: false,
      detail: {},
      curd: {
        search: {
          url: '',
          ip: '',
          time: ''
        },
        table: {
          columns: [
            {
              prop: 'message',
              label: '错误信息',
              'min-width': '300px',
              'show-overflow-tooltip': true,
            },
            {
              prop: 'file',
              label: '文件',
              'min-width': '300px',
              'show-overflow-tooltip': true,
            },
            {
              prop: 'method',
              label: '请求方法',
              width: '80px'
            },
            {
              prop: 'url',
              label: '路由',
              'min-width': '200px',
              'show-overflow-tooltip': true,
            },
            {
              prop: 'ip',
              label: 'IP',
              width: '150px'
            },
            {
              prop: 'create_time',
              label: '创建时间',
              align: 'center',
              headerAlign: 'center',
              width: '150px',
              sortable: 'custom'
            },
            {
              prop: 'operation',
              label: '操作',
              width: '100px',
              align: 'center',
              headerAlign: 'center',
              __slotName: 'operation',
              fixed: 'right',
            }
          ]
        }
      }
    }
  },
  methods: {
    // 加载
    handleLoad(search, success, error) {
      this.$http.post('system/debug/page', search)
        .then(data => {
          success(data.data)
        })
        .catch(() => {
          error()
        })
    },
    // 打开详情
    handleDetail(row, {property}) {
      if (property !== 'operation') {
        this.detail = row
        this.visible = true
      }
    },
    // 删除
    handleDelete(id) {
      this.$confirm('此操作将永久删除选中数据,是否继续?', '提示', {type: 'warning'})
        .then(() => {
          this.$http.post('system/debug/delete', {id})
            .then(data => {
              this.$message.success(data.message)
              this.$refs.curd.getData()
            })
        })
        .catch(() => {
        })
    },
    // 清除
    handleClear() {
      this.$confirm('此操作将永久清除所有数据,是否继续?', '提示', {type: 'warning'})
        .then(() => {
          this.$http.post('system/debug/clear')
            .then(data => {
              this.$message.success(data.message)
              this.$refs.curd.getData()
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
