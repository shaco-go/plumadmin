import router from '@/router'
import Layout from '@/layout'

const getDefaultState = () => {
    return {
        routes: [],
        menus: [],
        permissions: []
    }
}

//格式化菜单
function formatMenus(menus) {
    return menus.map(val => {
        let item = {
            id: val.id,
            title: val.title,
            icon: val.icon,
            path: val.routes,
            hidden: val.menu_hidden,
            //是否有外链
            url: (val.type === 1 && val.url.length > 0) ? val.url : false
        }
        if (val.children && val.children.length > 0) {
            item.children = formatMenus(val.children)
        }
        return item
    })
}

//格式化路由
function formatRoutes(routes) {
    return routes.map(val => {
        let item = {
            path: val.routes,
            component: resolve => require([`@/views/${val.component.replaceAll(/(^\/)|(\/$)/g, '')}`], resolve),
            name: val.name,
            meta: {
                title: val.title,
                icon: val.icon,
                keep_alive: val.keep_alive,
                hidden: val.menu_hidden
            }
        }
        if (val.children && val.children.length > 0) {
            item.children = formatRoutes(val.children)
        }
        return item
    })
}

export default {
    namespaced: true,
    state: getDefaultState(),
    mutations: {
        SET_PERMISSION(state, user) {
            //设置权限
            state.permissions = user.permissions
            //设置菜单
            state.menus = formatMenus(user.menus.tree)
            //设置路由
            state.routes = user.routes
            const routes = [{
                path: '/admin',
                component: Layout,
                redirect: user.routes.tree[0].routes,
                children: formatRoutes(user.routes.tree)
            }, {
                path: '*',
                hidden: false,
                redirect: '/admin/404'
            }]
            routes.forEach(item => {
                router.addRoute(item)
            })
        }
    }
}
