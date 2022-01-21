<template>
  <curd ref="curd" v-bind="curd" @load="load" @row-click="handleDetail">
    <!--搜索-->
    <template #search>
      <el-form-item label="用户名">
        <el-input placeholder="请输入用户名" v-model="curd.search.username"/>
      </el-form-item>

      <el-form-item label="昵称">
        <el-input placeholder="请输入用户名" v-model="curd.search.nickname"/>
      </el-form-item>

      <el-form-item label="超级管理员">
        <el-select v-model="curd.search.is_super" placeholder="请选择是否是超级管理员" clearable>
          <el-option label="超级管理员" :value="1"></el-option>
          <el-option label="非超级管理员" :value="0"></el-option>
        </el-select>
      </el-form-item>
    </template>

    <!--工具栏-->
    <template #tools>
      <el-button v-permission="'admin@create'" type="primary" size="small" icon="el-icon-plus" @click="handleCreate">新增</el-button>
    </template>

    <!--表格-->
    <template #userinfo="{row}">
      <div style="display: flex;align-items: center">
        <el-avatar fit="cover" :src="row | avatar" @error="false" size="small">
          <i class="el-icon-user-solid"></i>
        </el-avatar>
        <span style="padding-left: 10px">{{ row.nickname }}</span>
      </div>
    </template>

    <template #isSuper="{row}">
      <el-tag v-if="row.is_super">超级管理员</el-tag>
      <el-tag v-else type="danger">非超级管理员</el-tag>
    </template>

    <template #operation="{row}">
      <el-button v-permission="'admin@update'" size="small" type="text" @click="handleUpdate(row.id)">编辑</el-button>
      <el-divider direction="vertical"></el-divider>
      <el-button v-permission="'admin@delete'" size="small" type="text" @click="handleDelete(row.id)">删除</el-button>
    </template>

    <!--表单-->
    <dialog-form ref="form" :model="form" @submit="handleSubmit">
      <el-form-item label="账号" prop="username" :rules="{required:true,message:'请输入账号'}">
        <el-input placeholder="请输入账号" v-model="form.username" clearable/>
      </el-form-item>

      <el-form-item label="密码" prop="password" :rules="{required:!this.form.id,message:'请输入密码'}">
        <el-input placeholder="请输入密码" v-model="form.password" clearable/>
      </el-form-item>

      <el-form-item label="昵称" prop="nickname" :rules="{required:true,message:'请输入昵称'}">
        <el-input placeholder="请输入昵称" v-model="form.nickname" clearable/>
      </el-form-item>

      <el-form-item label="头像" prop="avatar" :rules="{required:true,message:'请上传头像'}">
        <upload-image v-model="form.avatar"/>
      </el-form-item>

      <el-form-item label="管理员" :rules="{required:true,message:'请选择管理员'}">
        <role-select v-model="form.role_ids"/>
      </el-form-item>

      <el-form-item label="超级管理员">
        <el-switch v-model="form.is_super"/>
      </el-form-item>
    </dialog-form>
  </curd>
</template>

<script>
import RoleSelect from '@/views/system/admin/components/RoleSelect'

export default {
  name: "system-admin",
  components: {RoleSelect},
  data() {
    return {
      curd: {
        table: {
          columns: [
            {
              prop: 'userinfo',
              label: '用户',
              __slotName: 'userinfo',
              width: '150px'
            },
            {
              prop: 'username',
              label: '账号'
            },
            {
              prop: 'is_super',
              label: '超级管理员',
              width: '120px',
              __slotName: 'isSuper',
              align: 'center',
              headerAlign: 'center'
            },
            {
              prop: 'create_time',
              label: '创建时间',
              width: '150px',
              sortAble: 'custom',
              headerAlign: 'center',
              align: 'center',
            },
            {
              prop: 'operation',
              label: '操作',
              fixed: 'right',
              headerAlign: 'center',
              align: 'center',
              width: '150px',
              __slotName: 'operation'
            }
          ]
        },
        search: {
          username: '',
          nickname: '',
          is_super: undefined
        }
      },
      form: {
        username: '',
        password: '123456',
        nickname: '',
        avatar: [],
        is_super: false,
        role_ids: []
      }
    }
  },
  filters: {
    avatar(row) {
      return row?.avatar?.url
    }
  },
  methods: {
    // 获取数据
    load(search, success, error) {
      this.$http.post('system/admin/page', search)
          .then(data => {
            success(data.data)
          })
          .catch(() => {
            error()
          })
    },
    // 新增
    handleCreate() {
      this.$refs.form.open()
    },
    // 修改
    handleUpdate(id) {
      this.$http.post('system/admin/detail', {id})
          .then(data => {
            data.data.password = undefined
            this.$refs.form.open(data.data)
          })
          .catch()
    },
    // 详情
    handleDetail({id}, {property}) {
      if (property !== 'operation' && this.$tools.isPermission('admin@detial')) {
        this.$http.post('system/admin/detail', {id})
            .then(data => {
              data.data.password = undefined
              this.$refs.form.read(data.data)
            })
            .catch()
      }
    },
    //删除
    handleDelete(id) {
      this.$confirm('此操作将永久删除选中数据,是否继续?', '提示', {type: 'warning'})
          .then(() => {
            this.$http.post('system/admin/delete', {id})
                .then(data => {
                  this.$message.success(data.message)
                  this.$refs.curd.getData()
                }).catch()
          })
          .catch(() => {
          })
    },
    // 保存
    handleSubmit(form, success, error) {
      const url = form.id ? 'system/admin/update' : 'system/admin/create'
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

<style scoped lang="scss">
::v-deep .el-avatar img {
  width: 100%;
  height: 100%;
}

</style>
