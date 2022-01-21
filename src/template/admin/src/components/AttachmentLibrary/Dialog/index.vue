<template>
  <el-dialog
      custom-class="file-library-dialog"
      :visible.sync="insideVisible"
      :close-on-click-modal="false"
      top="calc((100vh - 560px) / 2)"
      width="850px"
      @closed="handleDialogClosed"
      append-to-body
  >
    <template #title>
      <div class="library-title">{{ `${typeText}库` }}</div>
    </template>
    <!--主体-->
    <div class="library-container">
      <!--侧边栏-->
      <div class="slide-container">
        <el-input
            @input="handleSearch"
            v-model="keyword"
            clearable
            size="small"
            prefix-icon="el-icon-search"
            :placeholder="`请搜索${typeText}名称`"
        />
        <el-scrollbar
            style="margin-top:20px;height: 100%;"
            wrap-style="overflow-x:hidden;flex-grow:1;"
        >
          <el-tree
              ref="categoryTree"
              node-key="id"
              :data="categoryOptions"
              highlight-current
              empty-text="暂无分类"
              :expand-on-click-node="false"
              default-expand-all
          >
            <template #default="{node,data}">
              <div class="category-tree-item">
                <span @click="selectCategory(data)">{{ data.name }} </span>
                <el-dropdown trigger="click" @command="handleCategoryMenus">
                  <i class="el-icon-more"></i>
                  <el-dropdown-menu>
                    <el-dropdown-item :command="{type:'create',data:data}">新增分类</el-dropdown-item>
                    <el-dropdown-item v-if="data.id!==0" :command="{type:'update',data:data}">编辑分类</el-dropdown-item>
                    <el-dropdown-item v-if="data.id!==0" :command="{type:'delete',data:data}">删除分类</el-dropdown-item>
                  </el-dropdown-menu>
                </el-dropdown>
              </div>
            </template>
          </el-tree>
        </el-scrollbar>
      </div>

      <!--图片库-->
      <div class="main-container">
        <!--工具栏-->
        <div class="main-tool-container">
          <el-button @click="handleConfirm" :disabled="attachmentActiveList.length===0" size="small" type="primary">
            使用选中{{ typeText }}
          </el-button>
          <upload-button style="margin: 0 10px" @upload="getAttachmentList">
            <el-button size="small" type="primary">上传{{ typeText }}</el-button>
          </upload-button>
          <el-button @click="handleDelete" :disabled="attachmentActiveList.length===0" size="small" type="danger">
            删除{{ typeText }}
          </el-button>
          <move-category-tree @moveCategory="handleMoveCategory" :data="unCategoryOptions"
                              :placeholder="`${typeText}移动至`"/>
        </div>
        <!--附件库-->
        <div class="main-attachment-list" v-loading="attachmentLoading" v-if="attachmentList.length>0">
          <el-tooltip :content="item.name" placement="bottom" v-for="(item,index) in attachmentList" :key="index">
            <div class="attachment-item" @click="selectAttachmentItem(item)">
              <div class="item-preview">
                <image-thumb-preview v-if="type==='image'" :data="item"/>
                <video-thumb-preview v-if="type==='video'" :data="item"/>
                <file-thumb-preview v-if="type==='file'" :data="item"/>
              </div>
              <div :class="{'item-selected':true,active:activeAttachment(item.id)}">
                <div class="icon-number">{{ item.id | activeAttachmentNumber(attachmentActiveList) }}</div>
              </div>
              <div class="item-title">{{ item.name }}</div>
            </div>
          </el-tooltip>
        </div>
        <el-empty v-loading="attachmentLoading" style="flex-grow:1;" v-else :description="`暂无${typeText}`"></el-empty>
        <!--分页-->
        <div class="main-pagination">
          <el-pagination
              v-if="pagination && pagination.total>0"
              class="pagination-container"
              background
              layout="total,prev, pager, next"
              :current-page="pagination.page"
              :total="pagination.total"
              :page-size="pagination.size"
              @current-change="changePage"
          />
        </div>
      </div>
    </div>


    <!--分类dialog-->
    <el-dialog
        :visible.sync="categoryVisible"
        :title="categoryForm.id?'编辑':'新增'"
        width="500px"
        append-to-body
        @close="handleCategoryClose"
        @closed="handleCategoryClosed"
    >
      <el-form
          ref="categoryForm"
          :model="categoryForm"
          size="mini"
          label-width="100px"
      >
        <el-form-item label="分类名称" prop="name" :rules="{required:true,message:'请输入分类名称'}">
          <el-input v-model="categoryForm.name" placeholder="请输入分类名称"></el-input>
        </el-form-item>
        <el-form-item label="上级分类" prop="parent_id" :rules="{required:true,message:'请选择上级分类'}">
          <category-tree-select v-model="categoryForm.parent_id" :data="selectCategoryOptions"/>
        </el-form-item>
        <el-form-item label="排序" prop="sort">
          <el-input-number v-model="categoryForm.sort"/>
        </el-form-item>
        <el-form-item>
          <el-button :loading="categoryLoading" style="width:100%;" size="small" type="primary"
                     @click="handleCategorySubmit">保存
          </el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </el-dialog>
</template>

<script>
import CategoryTreeSelect from '@/components/AttachmentLibrary/Dialog/CategoryTreeSelect'
import MoveCategoryTree from '@/components/AttachmentLibrary/Dialog/MoveCategoryTree'
import UploadButton from '@/components/AttachmentLibrary/Upload'
import ImageThumbPreview from '@/components/AttachmentLibrary/PreviewView/ImageThumbPreview'
import FileThumbPreview from '@/components/AttachmentLibrary/PreviewView/FileThumbPreview'
import VideoThumbPreview from '@/components/AttachmentLibrary/PreviewView/VideoThumbPreview'
import _ from 'lodash'

export default {
  name: 'DialogAttachmentLibrary',
  components: {
    CategoryTreeSelect, MoveCategoryTree, UploadButton, ImageThumbPreview, VideoThumbPreview, FileThumbPreview
  },
  props: {
    visible: Boolean,
    multiple: {
      type: Boolean,
      default: false
    },
    type: {
      type: String,
      default: 'image'
    }
  },
  data() {
    return {
      insideVisible: false,
      categoryVisible: false,
      categoryLoading: false,
      keyword: '',
      currentCategory: {
        id: 0,
        name: '全部分类',
        parent_id: 0
      },
      attachmentLoading: false,
      categoryOptions: [],
      selectCategoryOptions: [],
      unCategoryOptions: [],
      attachmentList: [],
      attachmentActiveList: [],
      categoryForm: {
        id: undefined,
        name: '',
        sort: 200,
        parent_id: 0
      },
      pagination: {
        page: 1,
        size: 15,
        total: 100
      }
    }
  },
  computed: {
    typeText() {
      switch (this.type) {
        case 'image':
          return '图片';
        case 'video':
          return '视频';
        case 'file':
          return '文件';
        default:
          return '文件'
      }
    }
  },
  filters: {
    //选中序号
    activeAttachmentNumber(id, list) {
      return list.findIndex(e => e.id === id) + 1
    }
  },
  async mounted() {
    // 获取附件分类
    await this.getAttachmentCategory()
  },
  methods: {
    // 获取附件分类
    getAttachmentCategory() {
      return this.$http.post('system/attachment/category/tree')
          .then(data => {
            this.categoryOptions = [
              {
                id: 0,
                name: '全部' + this.typeText,
                parent_id: 0
              },
              ...data.data
            ]
            this.selectCategoryOptions = [
              {
                id: 0,
                name: '顶级分类',
                parent_id: 0
              },
              ...data.data
            ]
            this.unCategoryOptions = [
              {
                id: 0,
                name: '未分类',
                parent_id: 0
              },
              ...data.data
            ]
            this.adjustSelectCategory()
            return Promise.resolve()
          })
    },
    // 获取附件列表
    getAttachmentList() {
      this.attachmentLoading = true
      const categoryId = this.currentCategory?.id
      return this.$http.post('system/attachment/page', {
        size: this.pagination.size,
        page: this.pagination.page,
        type: this.type,
        name: this.keyword.trim() ? this.keyword.trim() : undefined,
        category_id: categoryId ? categoryId : undefined
      }).then(data => {
        this.attachmentList = data.data.data
        this.pagination.total = data.data.total
        this.attachmentActiveList = []
        return Promise.resolve()
      }).finally(() => {
        this.attachmentLoading = false
      })
    },
    // 分类菜单
    handleCategoryMenus({type, data}) {
      //新增
      if (type === 'create') {
        this.categoryVisible = true
        this.categoryForm.parent_id = data.id
      }
      //编辑
      if (type === 'update') {
        this.categoryVisible = true
        Object.assign(this.categoryForm, data)
      }
    },
    // 分类提交
    handleCategorySubmit() {
      const url = this.categoryForm.id ? 'system/attachment/category/update' : 'system/attachment/category/create'
      this.$refs.categoryForm.validate(valid => {
        if (valid) {
          this.categoryLoading = true
          this.$http.post(url, this.categoryForm)
              .then(data => {
                this.categoryVisible = false
                this.$message.success(data.message)
                this.getAttachmentCategory()
              })
              .catch(err => {

              })
              .finally(() => {
                this.categoryLoading = false
              })
        }
      })
    },
    // 搜索
    handleSearch: _.debounce(function () {
      this.getAttachmentList()
    }, 1000),
    // 关闭分类
    handleCategoryClose() {
      //清除表单内容
      this.categoryForm = this.$options.data().categoryForm
    },
    // 关闭分类动画
    handleCategoryClosed() {
      //清除表单验证
      this.$refs.categoryForm.clearValidate()
    },
    //分页修改
    changePage(page) {
      this.pagination.page = page
      this.getAttachmentList()
    },
    //选择分类
    selectCategory(data) {
      this.currentCategory = data
    },
    // 是否选中
    activeAttachment(id) {
      return !!this.attachmentActiveList.find(e => e.id === id)
    },
    //选中附件
    selectAttachmentItem(item) {
      const index = this.attachmentActiveList.findIndex(e => e.id === item.id)
      if (index !== -1) {
        this.attachmentActiveList.splice(index, 1)
      } else {
        this.attachmentActiveList.push(item)
      }
    },
    //调整选中分类状态
    adjustSelectCategory() {
      //如果currentCategory不存在的话,重新选中一下
      this.$nextTick(() => {
        if (this.visible && !this.$refs.categoryTree.getCurrentKey()) {
          this.$refs.categoryTree.setCurrentKey(this.currentCategory.id)
        }
      })
    },
    // 转移分类
    handleMoveCategory(category_id) {
      if (this.attachmentActiveList.length === 0) {
        this.$message.warning('请选择图片')
      } else {
        const ids = this.attachmentActiveList.map(e => e.id)
        this.$http.post('system/attachment/move', {ids, category_id})
            .then(data => {
              this.$message.success(data.message)
              this.getAttachmentList()
            })
            .catch(error => {

            })
      }
    },
    // 删除
    handleDelete() {
      this.$confirm('此操作将不可逆,确认删除图片?', '提示', {
        type: 'error'
      }).then(() => {
        const ids = this.attachmentActiveList.map(e => e.id)
        this.$http.post('system/attachment/delete', {ids})
            .then(data => {
              this.$message.success(data.message)
              this.getAttachmentList()
            })
            .catch(error => {

            })
      }).catch(() => {

      })
    },
    // 确认选中
    handleConfirm() {
      if (!this.multiple && this.attachmentActiveList.length > 1) {
        this.$message.warning(`至多只能选择一个`)
      }else{
        this.$emit('update:visible',false)
        this.$emit('select', this.$tools.cloneDeep(this.attachmentActiveList))
      }
    },
    // 弹出层动画关闭
    handleDialogClosed() {
      this.attachmentActiveList = []
    }
  },
  watch: {
    insideVisible: {
      immediate: true,
      handler(val) {
        this.$emit('update:visible', val)
      }
    },
    visible: {
      immediate: true,
      handler(val) {
        this.insideVisible = val
        if (val === true) {
          this.adjustSelectCategory()
        }
      }
    },
    currentCategory: {
      immediate: true,
      handler(val, old) {
        //获取附件列表
        this.getAttachmentList()
        this.adjustSelectCategory()
      }
    }
  }
}
</script>

<style scoped lang="scss">

::v-deep .el-dialog__body {
  padding: 0 20px 20px 20px
}

//库标题
.library-title {
  font-size: 18px;
  line-height: 30px;
}

//侧边栏分类item项
.el-tree-node.is-current > .el-tree-node__content .category-tree-item .el-icon-more {
  display: block;
}

.category-tree-item {
  display: flex;
  width: 100%;
  height: 100%;
  padding-right: 10px;
  justify-content: space-between;
  align-items: center;

  span {
    flex-grow: 1;
    height: 100%;
    display: flex;
    align-items: center;
  }

  .el-icon-more {
    display: none;
  }

  &:hover .el-icon-more {
    display: block;
  }
}

.library-container {
  width: 100%;
  height: 500px;
  display: flex;

  //侧边栏
  .slide-container {
    display: flex;
    flex-grow: 1;
    flex-direction: column;
    height: 100%;
    width: 300px;
    border-right: 1px solid #eee;
    padding-right: 20px;
    overflow: hidden;
  }

  //主内容
  .main-container {
    padding: 0 10px;
    flex-shrink: 0;
    width: 560px;
    display: flex;
    flex-direction: column;
    //工具内容
    .main-tool-container {
    }

    //文件库
    .main-attachment-list {
      flex-grow: 1;
      margin: 20px 0;
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      grid-template-rows: repeat(3, 1fr);

      .attachment-item {
        width: 100px;
        height: 125px;
        position: relative;

        .item-preview {
          width: 100px;
          height: 100px;
          border-radius: 2px;
          overflow: hidden;
          position: relative;
        }

        // 选中
        .item-selected {
          &.active {
            display: flex;
          }

          border-radius: 2px;
          overflow: hidden;
          display: none;
          position: absolute;
          top: 0;
          width: 100px;
          height: 100px;
          justify-content: center;
          align-items: center;
          background: rgba(#000, .6);

          .icon-number {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #ffffff;
            border-radius: 50%;
            width: 34px;
            height: 34px;
            line-height: 34px;
            border: 2px solid #ffffff;
            text-align: center;
          }
        }

        .item-title {
          overflow: hidden;
          text-overflow: ellipsis;
          white-space: nowrap;
          text-align: center;
          padding: 0 5px;
        }
      }
    }

    //分页
    .pagination-container {
      text-align: right;
    }
  }
}
</style>
