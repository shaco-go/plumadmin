<template>
    <el-select
        style="width: 100%"
        v-model="insideValue"
        multiple
        filterable
        clearable
        placeholder="请选中权限集(支持搜索)">
      <el-option v-for="item in list" :key="item.id" :label="item" :value="item"/>
    </el-select>
</template>

<script>
export default {
  name: "PermissionSelect",
  props: ['value'],
  data() {
    return {
      list: [],
      insideValue: undefined
    }
  },
  mounted() {
    this.getData()
  },
  methods: {
    getData() {
      this.$http.get('common/routes')
          .then(data => {
            this.list = data.data
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
