import Vue from 'vue'
import Router from 'vue-router'

// 解决ElementUI导航栏中的vue-router在3.0版本以上重复点菜单报错问题
const originalPush = Router.prototype.push
Router.prototype.push = function push(location) {
  return originalPush.call(this, location).catch(err => err)
}

Vue.use(Router)

export const constantRoutes = [
  {
    path: '/admin/login',
    component: () => import('@/views/login'),
  },
  {
    path: '/admin/404',
    component: () => import('@/layout/error_page/404'),
  },
  {
    path: '/admin/403',
    component: () => import('@/layout/error_page/403'),
  },
  {
    path: '/admin/500',
    component: () => import('@/layout/error_page/500'),
  }
]


const createRouter = () => new Router({
  mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes
})

const router = createRouter()

// 重置路由
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router
