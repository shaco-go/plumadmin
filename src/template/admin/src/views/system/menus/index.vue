<template>
  <div class="system-menus-container">
    <curd-card class="menus-tree">
      <!--菜单-->
      <div>
        <el-dropdown v-permission="'rule@create'" placement="bottom" @command="handleCreateMenus">
          <el-button size="small" type="primary">添加菜单 <i class="el-icon-arrow-down el-icon--right"></i></el-button>
          <el-dropdown-menu style="user-select: none">
            <el-dropdown-item command="menus1" :disabled="!!currenNode">添加顶栏目录</el-dropdown-item>
            <el-dropdown-item command="menus2" :disabled="!currenNode || [2,3].includes(currenNode.type)">添加子目录
            </el-dropdown-item>
            <el-dropdown-item command="menus3" :disabled="!!currenNode">添加顶栏菜单</el-dropdown-item>
            <el-dropdown-item command="menus4" :disabled="!currenNode || [3].includes(currenNode.type)">添加子菜单
            </el-dropdown-item>
            <el-dropdown-item command="menus5" :disabled="!currenNode || [1,3].includes(currenNode.type)">添加权限
            </el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
        <el-button
          plain
          style="margin-left:10px;"
          size="small"
          icon="el-icon-menu"
          @click="handleTreeExpandCompress"
        >
          {{ isExpand ? '全部收起' : '全部展开' }}
        </el-button>
        <el-button v-permission="'rule@delete'" v-if="checkedNodes.length>0" @click="handleDelete" plain size="small" type="danger"
                   icon="el-icon-delete">删除
        </el-button>
        <el-divider/>
      </div>
      <!--搜索-->
      <el-input v-model="keyword" clearable style="margin-bottom: 20px" size="small" placeholder="请输入菜单名称搜索"
                prefix-icon="el-icon-search"/>
      <!--树状-->
      <el-scrollbar style="flex-grow: 1" wrap-style="overflow-x:hidden;" v-loading="menusPageLoading">
        <el-tree
          ref="tree"
          empty-text="暂无菜单"
          show-checkbox
          node-key="id"
          :data="tree"
          :filter-node-method="filterNode"
          :props="{label:'title'}"
          @check="handleChecked"
          default-expand-all
          :expand-on-click-node="false"
          highlight-current
          :check-strictly="true"
          @node-click="handleNodeClick"
        >
          <template v-slot="{data}">
            <div class="tree-node">
              <el-tag size="mini" v-if="data.type===1">目录</el-tag>
              <el-tag size="mini" v-if="data.type===2" type="warning">菜单</el-tag>
              <el-tag size="mini" v-if="data.type===3" type="info">权限</el-tag>
              <span style="margin-left: 8px">{{ data.title }}</span>
            </div>
          </template>
        </el-tree>
      </el-scrollbar>
    </curd-card>

    <curd-card class="menus-form">
      <div>{{ formTitle }}</div>
      <el-divider/>
      <el-scrollbar style="flex-grow: 1" wrap-style="overflow-x:hidden;" v-loading="detailPageLoading">
        <div v-if="!currenNode" class="menus-form-tip">从菜单列表选择一项后，进行编辑</div>
        <el-form v-else :model="form" label-width="150px" size="small">
          <el-form-item label="类型">
            <el-tag size="small" v-if="form.type===1">目录</el-tag>
            <el-tag size="small" v-if="form.type===2" type="warning">菜单</el-tag>
            <el-tag size="small" v-if="form.type===3" type="info">权限</el-tag>
          </el-form-item>
          <el-form-item label="规则名称" prop="title" :rules="{required:true,message:'请填写规则名称'}">
            <el-input placeholder="请填写规则名称" v-model="form.title"></el-input>
          </el-form-item>

          <el-form-item v-if="form.type===2" label="组件name值" prop="name" :rules="{required:true,message:'请填写组件name值'}">
            <el-input placeholder="请填写组件name值" v-model="form.name"></el-input>
          </el-form-item>

          <el-form-item label="外链" v-if="form.type===1">
            <el-input v-model="form.url" placeholder="请填写外链( 需加协议例如:http:// )"/>
          </el-form-item>

          <el-form-item v-if="form.type===3" label="权限集" prop="method" :rules="{required:true,message:'请选择权限集'}">
            <permission-select v-model="form.method"/>
          </el-form-item>

          <el-form-item v-if="form.type===2" label="路由地址" prop="routes" :rules="{required:true,message:'请填写路由地址'}">
            <el-input placeholder="请填写路由地址" v-model="form.routes"></el-input>
          </el-form-item>

          <el-form-item v-if="form.type===2" label="组件路径" prop="component" :rules="{required:true,message:'请填写组件路径'}">
            <components-select v-model="form.component"/>
          </el-form-item>

          <el-form-item v-if="form.type===1 ||form.type===2" label="图标" prop="icon">
            <select-icon v-model="form.icon"/>
          </el-form-item>

          <el-form-item v-if="form.type===1 || form.type===2" label="隐藏菜单" prop="menu_hidden">
            <el-switch v-model="form.menu_hidden"/>
          </el-form-item>

          <el-form-item v-if="form.type===3" label="前端标识" prop="mark" :rules="[{required:true,message:'请填写前端标识'}]">
            <el-input v-model="form.mark" placeholder="请输入前端标识"></el-input>
          </el-form-item>

          <el-form-item v-if="form.type===2" label="缓存路由" prop="keep_alive">
            <el-switch v-model="form.keep_alive"/>
          </el-form-item>

          <el-form-item label="排序" prop="sort">
            <el-input-number style="width: 50%" v-model="form.sort" placeholder="请填写排序(升序)"></el-input-number>
          </el-form-item>

          <el-form-item>
            <el-button v-permission="'rule@update'" size="small" type="primary" @click="handleUpdate" :loading="formLoading">保存修改</el-button>
            <el-button size="small" @click="handleReset">重置</el-button>
          </el-form-item>
        </el-form>
      </el-scrollbar>
    </curd-card>

    <!--新增-->
    <dialog-form ref="createForm" :model="createForm" @submit="handleCreate">
      <el-form-item label="类型">
        <el-tag size="small" v-if="createForm.type===1">目录</el-tag>
        <el-tag size="small" v-if="createForm.type===2" type="warning">菜单</el-tag>
        <el-tag size="small" v-if="createForm.type===3" type="info">权限</el-tag>
      </el-form-item>
      <el-form-item label="规则名称" prop="title" :rules="{required:true,message:'请填写规则名称'}">
        <el-input placeholder="请填写规则名称" v-model="createForm.title"></el-input>
      </el-form-item>

      <el-form-item v-if="createForm.type===2" label="组件name值" prop="name"
                    :rules="{required:true,message:'请填写组件name值'}">
        <el-input placeholder="请填写组件name值" v-model="createForm.name"></el-input>
      </el-form-item>

      <el-form-item label="外链" v-if="createForm.type===1">
        <el-input v-model="createForm.url" placeholder="请填写外链( 需加协议例如:http:// )"/>
      </el-form-item>

      <el-form-item v-if="createForm.type===3" label="权限集" prop="method" :rules="{required:true,message:'请选择权限集'}">
        <permission-select v-model="createForm.method"/>
      </el-form-item>

      <el-form-item v-if="createForm.type===2" label="路由地址" prop="routes" :rules="{required:true,message:'请填写路由地址'}">
        <el-input placeholder="请填写路由地址" v-model="createForm.routes"></el-input>
      </el-form-item>

      <el-form-item v-if="createForm.type===2" label="组件路径" prop="component" :rules="{required:true,message:'请填写组件路径'}">
        <components-select v-model="createForm.component"/>
      </el-form-item>

      <el-form-item v-if="createForm.type===1 ||createForm.type===2" label="图标" prop="icon">
        <select-icon v-model="createForm.icon"/>
      </el-form-item>

      <el-form-item v-if="createForm.type===1 || createForm.type===2" label="隐藏菜单" prop="menu_hidden">
        <el-switch v-model="createForm.menu_hidden"/>
      </el-form-item>

      <el-form-item v-if="createForm.type===3" label="前端标识" prop="mark" :rules="[{required:true,message:'请填写前端标识'}]">
        <el-input v-model="createForm.mark" placeholder="请输入前端标识"></el-input>
      </el-form-item>

      <el-form-item v-if="createForm.type===2" label="缓存路由" prop="keep_alive">
        <el-switch v-model="createForm.keep_alive"/>
      </el-form-item>

      <el-form-item label="排序" prop="sort">
        <el-input-number style="width: 50%" v-model="createForm.sort" placeholder="请填写排序(升序)"></el-input-number>
      </el-form-item>
    </dialog-form>
  </div>
</template>

<script>
import PermissionSelect from './components/PermissionSelect'
import ComponentsSelect from './components/ComponentsSelect'
import SelectIcon from '@/components/SelectIcon'

export default {
  name: "system-menus",
  components: {PermissionSelect, ComponentsSelect, SelectIcon},
  data() {
    return {
      tree: [],
      keyword: '',
      checkedNodes: [],
      currenNode: undefined,
      formLoading: false,
      menusPageLoading: false,
      detailPageLoading: false,
      createForm: {
        method: [],
        title: '',
        name: '',
        parent_id: 0,
        type: 1,
        routes: '',
        component: '',
        mark: '',
        icon: '',
        menu_hidden: false,
        keep_alive: true,
        sort: 200,
        url: ''
      },
      form: {
        method: [],
        title: '',
        name: '',
        parent_id: 0,
        type: 1,
        routes: '',
        component: '',
        mark: '',
        icon: '',
        menu_hidden: false,
        keep_alive: true,
        sort: 200,
        url: ''
      },
      isExpand: true
    }
  },
  computed: {
    formTitle() {
      let title = ['编辑', '编辑目录', '编辑菜单', '编辑权限']
      if (this.currenNode) {
        return title[this.currenNode.type] + ":" + this.currenNode.title
      }
      return title[0]
    }
  },
  mounted() {
    this.getTree()
  },
  methods: {
    // 获取树状
    getTree() {
      this.menusPageLoading = true
      this.$http.post('system/menus/tree')
        .then(({data}) => {
          this.tree = data
        })
        .finally(() => {
          this.menusPageLoading = false
        })
    },
    // 过滤菜单
    filterNode(value, data) {
      if (!value) return true;
      return data.title.indexOf(value) !== -1;
    },
    // 当树状被选中的时候
    handleChecked(_, {checkedNodes}) {
      this.checkedNodes = checkedNodes
    },
    // 选中当前node
    handleNodeClick(e) {
      if (this.currenNode && this.currenNode.id === e.id) {
        this.$refs.tree.setCurrentKey(null)
        this.currenNode = undefined
        Object.assign(this.form, this.$tools.cloneDeep(this.$options.data().form))
      } else {
        this.currenNode = e
        this.detailPageLoading = true
        this.$http.post('system/menus/detail', {id: e.id})
          .then(data => {
            Object.assign(this.form, data.data)
          })
          .catch(error => {
          })
          .finally(() => {
            this.detailPageLoading = false
          })
      }
    },
    // 展开收拢树状
    handleTreeExpandCompress() {
      this.isExpand = !this.isExpand
      this.$refs.tree.$children.forEach(e => e.expanded = this.isExpand)
    },
    // 表单重置
    handleReset() {
      Object.assign(this.form, this.$tools.cloneDeep(this.currenNode))
    },
    // 修改
    handleUpdate() {
      this.formLoading = true
      this.$http.post('system/menus/update', this.form)
        .then(data => {
          this.$message.success(data.message)
          this.getTree()
        })
        .catch(err => {

        })
        .finally(() => {
          this.formLoading = false
        })
    },
    //新增
    handleCreate(form, success, error) {
      this.$http.post('system/menus/create', form)
        .then(data => {
          this.$message.success(data.message)
          this.getTree()
          success()
        })
        .catch(err => {
          error()
        })
    },
    // 创建菜单
    handleCreateMenus(command) {
      switch (command) {
        case 'menus1':
          this.$refs.createForm.open({
            parent_id: 0,
            type: 1
          })
          break;
        case 'menus2':
          this.$refs.createForm.open({
            parent_id: this.currenNode.id,
            type: 1
          })
          break;
        case 'menus3':
          this.$refs.createForm.open({
            parent_id: 0,
            type: 2
          })
          break;
        case 'menus4':
          this.$refs.createForm.open({
            parent_id: this.currenNode.id,
            type: 2
          })
          break;
        case 'menus5':
          this.$refs.createForm.open({
            parent_id: this.currenNode.id,
            type: 3
          })
          break;
      }
    },
    //更新
    handleDelete() {
      const content = this.checkedNodes.map(e => e.title).join(',')
      this.$confirm(`此操作将删除菜单及子菜单,确认要删除 <strong>${content}</strong> 吗?`, '提示', {
        type: 'warning',
        dangerouslyUseHTMLString: true,
      }).then(() => {
        this.$http.post('system/menus/delete', {
          ids: this.checkedNodes.map(e => e.id)
        }).then(data => {
          this.$message.success(data.message)
          //如果删除和当前选中是一样的话,则重置form和currentNode
          if (this.currenNode && this.checkedNodes.map(e => e.id).includes(this.currenNode.id)) {
            this.currenNode = undefined
            Object.assign(this.form, this.$tools.cloneDeep(this.$options.data().form))
          }
          //选中重置
          this.checkedNodes = []
          this.getTree()
        })
          .catch(err => {
        })
      }).catch(err => {
      })
    }
  },
  watch: {
    // 搜索字符
    keyword(val) {
      this.$refs.tree.filter(val)
      this.formKey = val.id
    }
  }
}
</script>

<style scoped lang="scss">
.system-menus-container {
  display: flex;
  height: calc(100vh - 104px - 10px);
  //权限树状
  .menus-tree {
    ::v-deep .el-card__body {
      height: calc(100% - 40px);
      display: flex;
      flex-direction: column;
    }

    //树状内容高度
    ::v-deep .el-tree-node__content {
      height: 30px;
    }

    flex: 0 0 450px;
    margin: 0 10px 0 20px;

    .tree-node {
      width: 100%;
      font-size: 14px;
      color: #515a6e;
      display: flex;
      align-items: center;
    }
  }

  //编辑
  .menus-form {
    flex-grow: 1;
    margin: 0 20px 0 10px;

    ::v-deep .el-card__body {
      height: calc(100% - 40px);
      display: flex;
      flex-direction: column;
    }

    //提示
    .menus-form-tip {
      border: 1px solid #abdcff;
      background-color: #f0faff;
      padding: 8px 48px 8px 16px;
      border-radius: 4px;
      color: #515a6e;
      font-size: 14px;
      line-height: 16px;
      margin-bottom: 10px;
    }
  }
}
</style>
