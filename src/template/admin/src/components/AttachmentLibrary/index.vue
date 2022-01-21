<template>
  <div>
    <!--文件库-->
    <dialog-attachment-library @select="handleSelectLibrary" :multiple="multiple" :type="type"
                               :visible.sync="libraryVisible"/>
    <!--预览文件-->
    <dialog-preview-attachment ref="dialogPreview"/>
    <!--多附件-->
    <template v-if="multiple">
      <div class="attachment-list">
        <vue-draggable class="attachment-list">
          <div class="attachment-item attachment-box-border" v-for="(item,index) in value" :key="index">
            <div class="preview-item">
              <image-thumb-preview v-if="type==='image'" :data="item"/>
              <video-thumb-preview v-if="type==='video'" :data="item"/>
              <file-thumb-preview v-if="type==='file'" :data="item"/>
            </div>
            <div class="mask-item">
              <i v-if="['image','video'].includes(type)" class="el-icon-view" @click.stop="handlePreview(index)"></i>
              <i class="el-icon-delete" @click.stop="handleDelete(index)"></i>
            </div>
          </div>
        </vue-draggable>
        <!--上传按钮-->
        <div v-if="!limit || limit>value.length" class="attachment-item attachment-box-border upload-button"
             @click="libraryVisible=true">
          <i class="el-icon-upload"></i>
          <span v-if="limit">({{ value.length }}/{{ limit }})</span>
        </div>
      </div>

    </template>
    <template v-else>
      <div class="attachment-list">
        <div v-if="value && value.url && value.name" class="attachment-item"
             @click="libraryVisible=true">
          <div class="preview-item">
            <image-thumb-preview v-if="type==='image'" :data="value"/>
            <video-thumb-preview v-if="type==='video'" :data="value"/>
            <file-thumb-preview v-if="type==='file'" :data="value"/>
          </div>
          <div class="mask-item">
            <i v-if="['image','video'].includes(type)" class="el-icon-view" @click.stop="handlePreview"></i>
            <i class="el-icon-delete" @click.stop="handleDelete"></i>
          </div>
        </div>
        <div v-else class="attachment-item attachment-box-border upload-button" @click="libraryVisible=true">
          <i class="el-icon-upload"></i>
        </div>
      </div>
    </template>
    <vue-draggable></vue-draggable>
  </div>
</template>

<script>
import DialogAttachmentLibrary from '@/components/AttachmentLibrary/Dialog'
import VueDraggable from 'vuedraggable'
import ImageThumbPreview from '@/components/AttachmentLibrary/PreviewView/ImageThumbPreview'
import FileThumbPreview from '@/components/AttachmentLibrary/PreviewView/FileThumbPreview'
import VideoThumbPreview from '@/components/AttachmentLibrary/PreviewView/VideoThumbPreview'
import DialogPreviewAttachment from '@/components/AttachmentLibrary/DialogPreviewAttachment'

export default {
  name: "AttachmentLibrary",
  components: {
    DialogAttachmentLibrary, VueDraggable, ImageThumbPreview, FileThumbPreview, VideoThumbPreview,
    DialogPreviewAttachment
  },
  props: {
    multiple: {
      type: Boolean,
      default: false
    },
    type: {
      type: String,
      default: 'image'
    },
    limit: Number | String,
    value: Object | Array
  },
  data() {
    return {
      libraryVisible: false
    }
  },
  methods: {
    // 删除
    handleDelete(index) {
      if (this.multiple) {
        this.value.splice(index, 1)
      } else {
        this.$emit('input', undefined)
      }
    },
    // 预览
    handlePreview(index) {
      if (this.multiple) {
        this.$refs.dialogPreview.open(this.value[index], this.type)
      } else {
        this.$refs.dialogPreview.open(this.value, this.type)
      }
    },
    // 附件库选中
    handleSelectLibrary(files) {
      let value = this.value
      if (!value) {
        value = this.multiple ? [] : {}
      }
      if (this.multiple) {
        //多文件
        if (this.limit > 0) {
          //有限制
          let list = value.concat(files)
          this.$emit('input', list.slice(0, this.limit))
        } else {
          //无限制
          this.$emit('input', value.concat(files))
        }
      } else {
        //单文件
        this.$emit('input', files[0])
      }
    }
  }
}
</script>

<style scoped lang="scss">
.attachment-list {
  display: flex;
  flex-wrap: wrap;

  .attachment-box-border {
    border: 1px dashed #c0ccda;
  }

  .upload-button {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    font-size: 24px;
    color: #8c939d;

    span {
      font-size: 14px;
    }
  }

  .attachment-item {
    position: relative;
    width: 100px;
    height: 100px;
    overflow: hidden;
    margin: 0 10px 10px 0;
    background-color: #fbfdff;
    border-radius: 6px;
    box-sizing: border-box;
    cursor: pointer;

    &:hover .mask-item {
      display: flex;
    }

    .preview-item {
      z-index: 9;
      width: 100px;
      height: 100px;
      overflow: hidden;
    }

    .mask-item {
      position: absolute;
      top: 0;
      width: 100px;
      height: 100px;
      background: rgba(#000, .5);
      color: #ffffff;
      display: none;
      align-items: center;
      justify-content: center;

      i {
        margin: 5px;
      }
    }
  }

}
</style>
