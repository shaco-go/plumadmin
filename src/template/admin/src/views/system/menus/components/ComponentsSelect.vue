<template>
  <el-autocomplete
      placeholder="请填写组件地址"
      clearable
      style="width: 100%"
      v-model="insideValue"
      :fetch-suggestions="querySearch"
      @change="setValue"
      @select="setValue"
  />
</template>

<script>
export default {
  name: 'ComponentsSelect',
  props: ['value'],
  data() {
    return {
      insideValue: undefined,
      components: []
    }
  },
  created() {
    const req = require.context('@/views', true, /\.vue$/)
    let components = []
    req.keys().map(e => {
      components.push({value: e.replace('./', '')})
    })
    this.components = components
  },
  methods: {
    querySearch(queryString, cb) {
      let components = JSON.parse(JSON.stringify(this.components))
      if (queryString) {
        cb(components.filter(e => {
          return e.value.toLowerCase().indexOf(queryString.toLowerCase()) !== -1
        }))
      } else {
        cb(this.components)
      }
    },
    setValue() {
      this.$emit('input', this.insideValue)
    }
  },
  watch: {
    value: {
      immediate: true,
      handler(val) {
        this.insideValue = val
      }
    }
  }
}
</script>

<style>

</style>
