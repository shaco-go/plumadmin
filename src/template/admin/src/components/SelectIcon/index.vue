<template>
  <el-popover trigger="click" placement="bottom-start" v-model="visible">
    <template v-slot:reference>
        <el-input clearable placeholder="请选择图标" v-model="icon_text"  @change="$emit('input',icon_text)"/>
    </template>
    <el-scrollbar style="height: 200px;width: 500px" wrap-style="overflow-x:hidden;">
      <div class="icons-list">
        <div class="item" v-for="(item,index) in icons" :key="index" @click="select(item)">
          <svg-icon :icon-class="item"/>
        </div>
      </div>
    </el-scrollbar>
  </el-popover>
</template>

<script>
export default {
  name: 'index',
  props:['value'],
  data() {
    return {
      icon_text:'',
      icons: [],
      visible:false,
    }
  },
  watch:{
    value:{
      immediate:true,
      handler(){
        this.icon_text = this.value
      }
    }
  },
  created() {
    const req = require.context('@/icons/svg', false, /\.svg$/)
    let icons = []
    req.keys().map(e => {
      icons.push(e.replace(/\S+\/(\S+)\.svg$/, '$1'))
    })
    this.icons = icons
  },
  methods:{
    select(e){
      this.$emit('input',e)
      this.visible = false
    }
  }
}
</script>

<style scoped lang="scss">
.icons-list{
  display: flex;
  flex-wrap: wrap;
  .item{
    &:hover{
      background:#f0f0f0 ;
    }
    width: 40px;
    height: 40px;
    border-radius: 5px;
    font-size: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 5px;
  }
}
</style>
