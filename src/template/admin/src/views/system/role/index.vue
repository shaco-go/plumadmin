<template>
  <curd ref="curd" v-bind="curd" @row-click="handleDetail" @load="load">
    <template #tools>
      <el-button v-permission="'role@create'" type="primary" icon="el-icon-plus" size="small" @click="handleCreate">新增</el-button>
    </template>

    <template v-slot:operation="{row}">
      <el-button v-permission="'role@update'" @click="handleUpdate(row.id)" type="text">编辑</el-button>
      <el-divider direction="vertical"/>
      <el-button v-permission="'role@delete'" @click="handleDelete(row.id)" type="text">删除</el-button>
    </template>

    <dialog-form ref="form" :model="form" @submit="handleSubmit">
      <el-form-item label="角色名称" prop="name" :rules="{required:true,message:'请输入角色名称'}">
        <el-input placeholder="请输入角色名称" v-model="form.name"/>
      </el-form-item>

      <el-form-item label="描述" prop="remark">
        <el-input placeholder="请输入描述" v-model="form.remark"/>
      </el-form-item>

      <el-form-item label="菜单权限" prop="rule_ids" :rules="{required:true,message:'请选择菜单权限'}">
        <menus-select v-model="form.rule_ids"/>
      </el-form-item>
    </dialog-form>
  </curd>
</template>

<script>
import MenusSelect from './components/MenusSelect'

export default {
  name: "system-role",
  components: {MenusSelect},
  data() {
    return {
      form: {
        name: "",
        remark: '',
        rule_ids: []
      },
      curd: {
        table: {
          columns: [
            {
              prop: 'name',
              width: '300px',
              label: "角色名称"
            },
            {
              prop: 'remark',
              label: '描述',
              showOverflowTooltip: true
            },
            {
              prop: "create_time",
              width: '200px',
              label: "创建时间"
            },
            {
              prop: 'operation',
              width: '200px',
              headerAlign: 'center',
              align: 'center',
              label: '操作',
              __slotName: 'operation',
              fixed: 'right'
            }
          ]
        }
      }
    }
  },
  methods: {
    // 加载数据
    load(form, success, error) {
      this.$http.post('system/role/page', form)
          .then(data => {
            success(data.data)
          })
          .catch(err => {
            error()
          })
    },
    // 新建
    handleCreate() {
      this.$refs.form.open()
    },
    // 编辑
    handleUpdate(id) {
      this.$http.post('system/role/detail', {id})
          .then(data => {
            this.$refs.form.open(data.data)
          })
    },
    // 删除
    handleDelete(id) {
      this.$confirm('此操作会永久删除角色,确认继续?', '提示', {
        type: 'warning'
      }).then(() => {
        this.$http.post('system/role/delete', {id})
            .then(data => {
              this.$message.success(data.message)
              this.$refs.curd.getData()
            })
      }).catch(() => {

      })
    },
    // 详情
    handleDetail({id}, {property}) {
      if (property !== 'operation' && this.$tools.isPermission('role@detail')) {
        this.$http.post('system/role/detail', {id})
            .then(data => {
              this.$refs.form.read(data.data)
            })
      }
    },
    // 提交
    handleSubmit(form, success, error) {
      const url = form.id ? 'system/role/update' : 'system/role/create'
      this.$http.post(url, form)
          .then(data => {
            this.$message.success(data.message)
            this.$refs.curd.getData()
            success()
          })
          .catch(err => {
            error()
          })
    }
  }
}
</script>

<style scoped>

</style>
