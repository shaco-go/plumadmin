import _ from 'lodash'
const getDefaultState = () => {
    return {
        tabs:        [],
        activeTabs:  [],
        currentTabs: {}
    }
}

//根据path查看是否在tabs里,如果在返回tabsItem
function getTabsIndex(path, tabs) {
    return tabs.findIndex(e => {
        return e.path === path
    })
}

export default {
    namespaced: true,
    state:      getDefaultState(),
    mutations:  {
        //初始化
        INIT(state, user) {
            //获取所有的路由,平级
            state.tabs = user.routes.list.map(e => {
                return {
                    title: e.title,
                    path:  e.routes,
                    name:  e.name
                }
            })
        },
        //新增tab
        ADD_TAB(state, path) {
            const index = getTabsIndex(path, state.tabs)
            if (index !== -1) {
                //当前路由是否符合放入tabs
                const activeIndex = getTabsIndex(path, state.activeTabs)
                    , tab   = state.tabs[index]
                if (activeIndex === -1) {
                    //如果不在激活的tabs里面,则添加
                    state.activeTabs.push(tab)
                }
                state.currentTabs = tab
            }
        },
        //关闭当前的窗口
        CLOSE_TAB(state, path) {
            //只有当激活tabs,存一个以上的时候才可以关闭
            if (state.activeTabs.length > 1) {
                //关闭当前tabs
                const index = getTabsIndex(path, state.activeTabs)
                console.log(index)
                if (index !== -1) {
                    let a = state.activeTabs.splice(index, 1)
                    //当前tabs,变成最后一个
                    state.currentTabs = state.activeTabs.slice(-1)[0]
                }
            }
        },
        //关闭其他窗口
        CLOSE_OTHER_TAB(state, path) {
            //只有当激活tabs,存在一个以上的时候才可以关闭
            if (state.activeTabs.length > 1) {
                //关闭当前tabs
                const index = getTabsIndex(path, state.activeTabs)
                if (index !== -1) {
                    state.activeTabs = [state.activeTabs[index]]
                }

            }
        }
    }
}


