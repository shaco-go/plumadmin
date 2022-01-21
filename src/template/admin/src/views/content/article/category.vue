<template>
  <curd ref="curd" v-bind="curd" @load="handleLoad" @row-click="handleDetail">
    <!--搜索栏-->
    <template #search>
      <el-form-item label="分类名称">
        <el-input placeholder="请输入分类名称" v-model="curd.search.name"/>
      </el-form-item>
    </template>

    <!--工具栏-->
    <template #tools>
      <el-button v-permission="'articleCategory@create'" type="primary" size="small" @click="handleInsert">新增</el-button>
    </template>

    <!--表格-->
    <template #operation="{row}">
      <el-button v-permission="'articleCategory@update'" type="text" @click="handleUpdate(row.id)">编辑</el-button>
      <el-divider direction="vertical"/>
      <el-button v-permission="'articleCategory@delete'" type="text" @click="handleDelete(row.id)">删除</el-button>
    </template>

    <!--表单-->
    <dialog-form ref="form" :model="form" @submit="handleSubmit">
      <el-form-item label="分类名称" prop="name" :rules="{required:true,message:'请输入分类名称'}">
        <el-input v-model="form.name" placeholder="请输入分类名称"/>
      </el-form-item>
      <el-form-item label="排序">
        <el-input-number v-model="form.sort"/>
      </el-form-item>
    </dialog-form>
  </curd>
</template>

<script>
export default {
  name: "articleCategory",
  data() {
    return {
      curd: {
        table: {
          columns: [
            {
              prop: 'name',
              label: '分类名称'
            },
            {
              prop: "sort",
              label: '排序',
              width: '120px',
              sortable: 'custom',
              align: 'center',
              headerAlign: 'center',
            },
            {
              prop: 'create_time',
              label: '创建时间',
              width: '150px',
              align: 'center',
              headerAlign: 'center',
              sortable: 'custom'
            },
            {
              prop: 'operation',
              label: '操作',
              width: '200px',
              __slotName: 'operation',
              align: 'center',
              headerAlign: 'center',
            }
          ]
        },
        search: {
          name: ""
        }
      },
      form: {
        name: '',
        sort: 200
      }
    }
  },
  methods: {
    //加载
    handleLoad(form, success, error) {
      this.$http.post('article/category/page', form)
        .then(data => {
          success(data.data)
        })
        .catch(() => {
          error()
        })
    },
    // 编辑
    handleUpdate(id) {
      this.$http.post('article/category/detail', {id})
        .then(data => {
          this.$refs.form.open(data.data)
        })
    },
    // 新增
    handleInsert() {
      this.$refs.form.open()
    },
    // 删除
    handleDelete(id) {
      this.$confirm('此操作将永久删除选中数据,是否继续?', '提示', {type: 'warning'})
        .then(() => {
          this.$http.post('article/category/delete', {id})
            .then(data => {
              this.$message.success(data.message)
              this.$refs.curd.getData()
            })
            .catch()
        })
        .catch(() => {
        })
    },
    // 保存
    handleSubmit(form, success, error) {
      const url = form.id ? 'article/category/update' : 'article/category/create'
      this.$http.post(url, form)
        .then(data => {
          this.$message.success(data.message)
          this.$refs.curd.getData()
          success()
        })
        .catch(() => {
          error()
        })
    },
    // 详情
    handleDetail({id}, {property}) {
      if (property !== 'operation' && this.$tools.isPermission('articleCategory@detail')) {
        this.$http.post('article/category/detail', {id})
          .then(data => {
            this.$refs.form.read(data.data)
          })
      }
    }
  }
}
</script>

<style scoped>

</style>
