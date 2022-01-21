<template>
  <el-card class="curd-card-container" shadow="never" v-bind="$attrs">
    <slot />
  </el-card>
</template>

<script>
export default {
  name: "CurdCard"
}
</script>

<style scoped>
.curd-card-container {
  width: calc(100% - 20px);
  margin: auto;
  border:none;
  border-radius:5px;
}
</style>
