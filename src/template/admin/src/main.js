import Vue from 'vue'
import App from './App.vue'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import router from './router'
import store from './store'
import request from '@/utils/request'
import tools from 'lodash'
import isPermission from '@/utils/auth'
import './icons' // 加载图标
import 'normalize.css'
import './style/index.scss'
import './permission'
import './useComponents'

Vue.use(ElementUI)
Vue.config.productionTip = false

tools.isPermission = isPermission
Vue.prototype.$http = request
Vue.prototype.$tools = tools

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
