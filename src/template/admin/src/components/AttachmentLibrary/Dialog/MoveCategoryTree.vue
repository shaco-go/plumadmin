<template>
  <el-popover v-model="visible">
    <el-tree
        ref="tree"
        style="width: 300px"
        node-key="id"
        default-expand-all
        :data="data"
        :props="{label:'name'}"
        @node-click="handleSelect"
        :expand-on-click-node="false"
    />
    <el-input
        style="width: 200px;margin-left: 10px;"
        size="small"
        suffix-icon="el-icon-arrow-down"
        slot="reference"
        readonly
        :placeholder="placeholder"
    />
  </el-popover>
</template>

<script>
export default {
  name: "MoveCategoryTree",
  props: {
    data: Array,
    placeholder: String
  },
  data() {
    return {
      visible: false,
    }
  },
  methods: {
    // 选择当前分类
    handleSelect({id}) {
      this.$emit('moveCategory',id)
      this.visible=false
    }
  }
}
</script>

<style scoped lang="scss">
::v-deep input{
  cursor: pointer;
}
</style>
