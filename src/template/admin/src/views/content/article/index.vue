<template>
  <curd ref="curd" v-bind="curd" @load="handleLoad" @row-click="handleDetail">
    <!--搜索栏-->
    <template #search>
      <el-form-item label="文章标题">
        <el-input placeholder="请输入文章标题" v-model="curd.search.title"/>
      </el-form-item>

      <el-form-item label="文章分类">
        <category-select v-model="curd.search.category_id"/>
      </el-form-item>

      <el-form-item label="状态">
        <el-select v-model="curd.search.status" clearable>
          <el-option label="显示" :value="1"/>
          <el-option label="隐藏" :value="0"/>
        </el-select>
      </el-form-item>
    </template>

    <!--工具栏-->
    <template #tools>
      <el-button v-permission="'article@create'" type="primary" size="small" @click="handleInsert">新增</el-button>
    </template>

    <!--表格-->
    <template #operation="{row}">
      <el-button v-permission="'article@update'" type="text" @click="handleUpdate(row.id)">编辑</el-button>
      <el-divider direction="vertical"/>
      <el-button v-permission="'article@delete'" type="text" @click="handleDelete(row.id)">删除</el-button>
    </template>

    <template #status="{row}">
      <el-tag type="info" v-if="row.status===0">隐藏</el-tag>
      <el-tag type="success" v-if="row.status===1">显示</el-tag>
    </template>

    <template #cover="{row}">
      <div class="cover">
        <el-image fit="cover" :src="row.cover.url" :preview-src-list="[row.cover.url]"></el-image>
      </div>
    </template>

    <!--表单-->
    <dialog-form ref="form" :model="form" @submit="handleSubmit">
      <el-form-item label="文章标题" prop="title" :rules="{required:true,message:'请输入文章标题'}">
        <el-input v-model="form.title" placeholder="请输入文章标题"/>
      </el-form-item>

      <el-form-item label="分类" prop="category_id" :rules="{required:true,message:'请选择分类'}">
        <category-select v-model="form.category_id"/>
      </el-form-item>

      <el-form-item label="封面图" prop="cover" :rules="{required:true,message:'请上传封面图'}">
        <upload-image v-model="form.cover"/>
      </el-form-item>

      <el-form-item label="简介">
        <el-input type="textarea" v-model="form.intro" placeholder="请输入简介"></el-input>
      </el-form-item>

      <el-form-item label="内容">
        <editor v-model="form.content"/>
      </el-form-item>

      <el-form-item label="作者">
        <el-input v-model="form.author" placeholder="请输入作者"/>
      </el-form-item>

      <el-form-item label="排序">
        <el-input-number v-model="form.sort"/>
      </el-form-item>

      <el-form-item label="状态">
        <el-radio-group v-model="form.status">
          <el-radio :label="1">显示</el-radio>
          <el-radio :label="0">隐藏</el-radio>
        </el-radio-group>
      </el-form-item>
    </dialog-form>
  </curd>
</template>

<script>
import CategorySelect from './components/CategorySelect'

export default {
  name: "contentArticle",
  components: {CategorySelect},
  data() {
    return {
      curd: {
        table: {
          columns: [
            {
              prop: 'title',
              label: '文章标题',
              width: '150px',
              'show-overflow-tooltip': true,
            },
            {
              prop: "cover",
              label: '封面图',
              __slotName: 'cover',
              width: '180px',
            },
            {
              prop: "intro",
              label: '介绍',
              'min-width': '200px',
              'show-overflow-tooltip': true
            },
            {
              prop: "sort",
              label: '排序',
              width: '120px',
              sortable: 'custom'
            },
            {
              prop: "status",
              label: '状态',
              width: '100px',
              align: 'center',
              headerAlign: 'center',
              __slotName: 'status'
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
              fixed: 'right'
            }
          ]
        },
        search: {
          title: '',
          category_id: '',
          status: undefined
        }
      },
      form: {
        title: '',
        category_id: undefined,
        author: '',
        cover: undefined,
        intro: '',
        content: '',
        sort: 200,
        status: 1
      }
    }
  },
  methods: {
    //加载
    handleLoad(form, success, error) {
      this.$http.post('article/page', form)
        .then(data => {
          success(data.data)
        })
        .catch(() => {
          error()
        })
    },
    // 编辑
    handleUpdate(id) {
      this.$http.post('article/detail', {id})
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
          this.$http.post('article/delete', {id})
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
      const url = form.id ? 'article/update' : 'article/create'
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
      if (property !== 'operation' && this.$tools.isPermission('article@detail')) {
        this.$http.post('article/detail', {id})
          .then(data => {
            this.$refs.form.read(data.data)
          })
      }
    }
  }
}
</script>

<style scoped>
.cover {
  width: 150px;
  height: 80px;
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
