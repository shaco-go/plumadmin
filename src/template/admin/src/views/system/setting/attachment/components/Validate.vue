<template>
  <div class="attachment-validate-form" v-loading="pageLoading">
    <div class="attachment-validate-item" v-for="(item,index) in validate" :key="index">
      <el-input clearable v-model="item.title" size="small" placeholder="限制名称" class="title"/>
      <el-select
          clearable
          size="small"
          class="extension"
          placeholder="请选择扩展名"
          v-model="item.extension"
          multiple
          filterable
          allow-create
          default-first-option
      >
        <el-option-group
            v-for="group in extOptions"
            :key="group.label"
            :label="group.label"
        >
          <el-option
              v-for="extItem in group.options"
              :key="extItem"
              :label="extItem"
              :value="extItem"
          >
          </el-option>
        </el-option-group>
      </el-select>
      <el-input-number v-model="item.size" placeholder="限制上传大小单位(B)" size="small" class="size"/>
      <div class="delete">
        <i @click="handleDelete(index)" class="el-icon-circle-close"></i>
      </div>
    </div>
    <div style="padding:10px 20px;">
      <el-button plain type="primary" size="small" @click="handleInsert">新增</el-button>
      <el-button :loading="loading" type="primary" size="small" @click="handleSubmit">保存</el-button>
    </div>
  </div>
</template>

<script>
export default {
  name: "Validate",
  data() {
    return {
      pageLoading: false,
      loading: false,
      validate: [],
      extOptions: [
        {
          label: '文档类型',
          options: [
            'txt', 'doc', 'hlp', 'rtf', 'html', 'pdf'
          ]
        },
        {
          label: '图形类型',
          options: [
            'bmp', 'gif', 'jpg', 'pic', 'png', 'tif'
          ]
        },
        {
          label: '压缩类型',
          options: [
            'rar', 'zip', 'arj', 'gz', 'z'
          ]
        },
        {
          label: '声音类型',
          options: [
            'wav', 'aif', 'au', 'mp3', 'ram', 'wma', 'mmf', 'amr', 'aac', 'flac'
          ]
        },
        {
          label: '动画类型',
          options: [
            'avi', 'mpg', 'mov', 'swf'
          ]
        }
      ]
    }
  },
  mounted() {
    this.pageLoading = true
    this.getData()
  },
  methods: {
    //提交
    handleSubmit() {
      this.loading = true
      this.$http.post('system/config/attachment/validate', {data: this.validate})
          .then(data => {
            this.$message.success(data.message)
            this.getData()
          })
          .finally(() => {
            this.loading = false
          })
    },
    //获取配置
    getData() {
      this.$http.get('system/config/attachment/validate')
          .then(data => {
            this.validate = data.data
            this.pageLoading = false
          })
    },
    //添加
    handleInsert() {
      this.validate.push({
        title: '',
        extension: [],
        size: 0
      })
    },
    //删除
    handleDelete(index) {
      this.validate.splice(index, 1)
    }
  }
}
</script>

<style scoped lang="scss">
.attachment-validate-form {
  max-width: 850px;
  //margin: auto;

  .size-tips {
    color: #909399;
    font-size: 12px;
  }

  .attachment-validate-item {
    display: flex;
    align-items: center;
    padding: 10px 20px;
    border-radius: 10px;

    &:hover {
      background: #f5f7f9;

      .el-icon-circle-close {
        display: inline-block;
      }
    }

    .title {
      flex: 0 0 100px;
    }

    .extension {
      flex: 1 1 auto;
    }

    .size {
      flex: 0 0 200px;
    }

    .delete {
      width: 32px;
      height: 32px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .el-icon-circle-close {
      display: none;
    }

    div + div {
      margin-left: 20px;
    }
  }
}
</style>
