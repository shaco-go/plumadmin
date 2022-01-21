<template>
  <el-form v-loading="pageLoading" :model="form" size="small" class="attachment-form" label-width="150px">
    <el-form-item label="上传方式" prop="default">
      <el-radio-group v-model="form.default">
        <el-radio label="local">本地</el-radio>
        <el-radio label="aliyun">阿里云</el-radio>
        <el-radio label="qiniu">七牛云</el-radio>
        <el-radio label="qcloud">腾讯云</el-radio>
      </el-radio-group>
    </el-form-item>

    <!--阿里云-->
    <template v-if="form.default === 'aliyun'">
      <el-form-item label="accessId">
        <el-input v-model="form.disks.aliyun.accessId"/>
      </el-form-item>
      <el-form-item label="accessSecret">
        <el-input v-model="form.disks.aliyun.accessSecret"/>
      </el-form-item>
      <el-form-item label="bucket">
        <el-input v-model="form.disks.aliyun.bucket"/>
      </el-form-item>
      <el-form-item label="endpoint">
        <el-input v-model="form.disks.aliyun.endpoint"/>
      </el-form-item>
      <el-form-item label="url">
        <el-input v-model="form.disks.aliyun.url"/>
      </el-form-item>
    </template>

    <!--七牛云-->
    <template v-if="form.default === 'qiniu'">
      <el-form-item label="accessKey">
        <el-input v-model="form.disks.qiniu.accessKey"/>
      </el-form-item>
      <el-form-item label="secretKey">
        <el-input v-model="form.disks.qiniu.secretKey"/>
      </el-form-item>
      <el-form-item label="bucket">
        <el-input v-model="form.disks.qiniu.bucket"/>
      </el-form-item>
      <el-form-item label="url">
        <el-input v-model="form.disks.qiniu.url"/>
      </el-form-item>
    </template>

    <!--腾讯云-->
    <template v-if="form.default === 'qcloud'">
      <el-form-item label="region">
        <el-input v-model="form.disks.qcloud.region"/>
      </el-form-item>
      <el-form-item label="appId">
        <el-input v-model="form.disks.qcloud.appId"/>
      </el-form-item>
      <el-form-item label="secretId">
        <el-input v-model="form.disks.qcloud.secretId"/>
      </el-form-item>
      <el-form-item label="secretKey">
        <el-input v-model="form.disks.qcloud.secretKey"/>
      </el-form-item>
      <el-form-item label="bucket">
        <el-input v-model="form.disks.qcloud.bucket"/>
      </el-form-item>
      <el-form-item label="cdn">
        <el-input v-model="form.disks.qcloud.cdn"/>
      </el-form-item>
    </template>
    <el-form-item>
      <el-button :loading="loading" @click="handleSubmit" type="primary">保存</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
export default {
  name: "Attachment",
  data() {
    return {
      pageLoading: false,
      loading: false,
      form: {
        default: 'local',
        disks: {
          aliyun: {
            type: 'aliyun',
            accessId: undefined,
            accessSecret: undefined,
            bucket: undefined,
            endpoint: undefined,
            url: undefined
          },
          qiniu: {
            type: 'qiniu',
            accessKey: undefined,
            secretKey: undefined,
            bucket: undefined,
            url: undefined//不要斜杠结尾，此处为URL地址域名。
          },
          qcloud: {
            type: 'qcloud',
            region: undefined, //bucket 所属区域 英文
            appId: undefined, // 域名中数字部分
            secretId: undefined,
            secretKey: undefined,
            bucket: undefined,
            cdn: undefined
          }
        }
      }
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
      this.$http.post('system/config/attachment', {data: this.form})
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
      this.$http.get('system/config/attachment')
          .then(data => {
            this.form = data.data
            this.pageLoading = false
          })
    }
  }
}
</script>

<style scoped>
.attachment-form {
  max-width: 800px;
  /*margin: auto;*/
}
</style>
