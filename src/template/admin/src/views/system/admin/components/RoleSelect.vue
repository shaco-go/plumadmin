<template>
  <el-select style="width: 100%" v-model="insideValue" multiple filterable>
    <el-option v-for="(item,index) in options" :label="item.name" :value="item.id" :key="index"/>
  </el-select>
</template>

<script>
export default {
  name: "RoleSelect",
  props: ['value'],
  data() {
    return {
      options: [],
      insideValue: undefined
    }
  },
  mounted() {
    this.getData()
  },
  methods: {
    // 获取角色列表
    getData() {
      this.$http.post('system/role/list')
          .then(data => {
            this.options = data.data
          })
    }
  },
  watch: {
    insideValue: {
      immediate: true,
      handler(val) {
        this.$emit('input', val)
      }
    },
    value: {
      immediate: true,
      handler(val) {
        this.insideValue = val
      }
    }
  }
}
</script>

<style scoped>

</style>
