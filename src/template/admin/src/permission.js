import router from './router'
import store from './store'
import {Message} from 'element-ui'
import NProgress from 'nprogress' // progress bar
import 'nprogress/nprogress.css' // progress bar style
import {getToken} from '@/utils/token' // get token from cookie
import getPageTitle from '@/utils/tools'


//配置NProgress
NProgress.configure({showSpinner: false})

//不需要TOKEN,白名单
const whiteList = ['/admin/login','/admin/403','/admin/500','/admin/404']

//前置守卫
router.beforeEach(async (to, from, next) => {
    // 开启进度条
    NProgress.start()
    // 设置浏览器的标题
    document.title = getPageTitle(to.meta.title)

    const hasToken = getToken()
    if (hasToken) {
        //存在token
        if (to.path === '/admin/login') {
            // 如果是跳转到登录页,则重新跳转到首页
            next('/admin')
            NProgress.done()
        } else {
            // 正常跳转
            if (store.getters.user) {
                next()
            } else {
                // 用户信息不存在,获取用户信息
                try {
                    await store.dispatch('user/getUserInfo')
                    //跳转,replace可以使路由没有记录
                    next({
                        ...to,
                        replace: true
                    })
                } catch (error) {
                    // 清除token,跳转到登录页
                    await store.dispatch('user/logout')
                    Message.error(error || 'Has Error')
                    next(`/admin/login?redirect=${to.path}`)
                    NProgress.done()
                }
            }
        }
    } else {
        //token不存在
        if (whiteList.indexOf(to.path) !== -1) {
            // 如果在白名单则,直接下一步
            next()
        } else {
            // 如果没有token,又不在白名单,跳转到登录页面
            next(`/admin/login?redirect=${to.path}`)
            NProgress.done()
        }
    }
})

router.afterEach((to, from) => {
    //添加tab
    store.commit('tabs/ADD_TAB', to.path)
    //结束进度条
    NProgress.done()
})
