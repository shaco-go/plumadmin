<template>
  <el-select v-model="insideValue" clearable>
    <el-option v-for="(item,index) in options" :label="item.name" :value="item.id" :key="index"/>
  </el-select>
</template>

<script>
export default {
  name: "CategorySelect",
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
    // 获取数据
    getData() {
      this.$http.post('article/category/list')
          .then(data => {
            this.options = data.data
          })
          .catch()
    }
  },
  watch: {
    value: {
      immediate: true,
      handler(val) {
        this.insideValue = val
      }
    },
    insideValue: {
      immediate: true,
      handler(val) {
        this.$emit('input', val)
      }
    }
  }
}
</script>

<style scoped>

</style>
