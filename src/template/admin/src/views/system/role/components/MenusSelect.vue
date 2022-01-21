<template>
  <el-tree
      v-loading="loading"
      :data="data"
      show-checkbox
      default-expand-all
      node-key="id"
      ref="tree"
      highlight-current
      @check-change="handleChange"
      :props="{label:'title'}"
  >
  </el-tree>
</template>

<script>
import * as _ from 'lodash'

export default {
  name: 'MenusSelect',
  props: ['value'],
  data() {
    return {
      loading: false,
      data: [],
      nodeIds: []
    }
  },
  created() {
    this.loading = true
    this.$http.post('system/menus/tree')
        .then(({data}) => {
          this.data = data
          //获取所有的子节点
          this.nodeIds = this.getNodeIds(data)
          this.setCheckedKey(this.value)
        })
        .finally(() => {
          this.loading = false
        })
  },
  methods: {
    handleChange: _.debounce(function () {
      this.$emit('input', [...this.$refs.tree.getCheckedKeys(), ...this.$refs.tree.getHalfCheckedKeys()].sort())
    }, 500),
    getNodeIds(data) {
      let nodeIds = []
      data.forEach(e => {
        if (e.children && e.children.length > 0) {
          nodeIds.push(...this.getNodeIds(e.children))
        } else {
          nodeIds.push(e.id)
        }
      })
      return nodeIds
    },
    setCheckedKey(val) {
      this.$nextTick(() => {
        if (this.$tools.isArray(val)) {
          this.$refs.tree.setCheckedKeys(this.$tools.intersection(val, this.nodeIds))
        }
      })
    }
  },
  watch: {
    value: {
      immediate: true,
      handler(val) {
        this.setCheckedKey(val)
      }
    }
  }
}
</script>

<style scoped>

</style>
