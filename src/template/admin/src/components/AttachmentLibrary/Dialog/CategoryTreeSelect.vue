<template>
  <el-popover v-model="visible">
    <el-tree
        ref="tree"
        style="width: 340px"
        node-key="id"
        default-expand-all
        :data="data"
        :props="{label:'name'}"
        @node-click="handleSelect"
        :expand-on-click-node="false"
        highlight-current
    />
    <el-input slot="reference" :value="valueText" readonly placeholder="请选择上级分类"/>
  </el-popover>
</template>

<script>
export default {
  name: "CategoryTreeSelect",
  props: {
    data: Array,
    value: Number
  },
  data() {
    return {
      visible:false,
      valueText:''
    }
  },
  methods: {
    // 选择当前分类
    handleSelect({id}) {
      this.$emit('input', id)
      this.visible = false
    }
  },
  watch:{
    value:{
      immediate:true,
      handler(val){
        this.$nextTick(()=>{
          this.$refs.tree.setCurrentKey(val)
          this.valueText = this.$refs.tree.getCurrentNode().name
        })
      }
    }
  }
}
</script>

<style scoped>

</style>
