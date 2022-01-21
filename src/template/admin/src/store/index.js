import Vue from 'vue'
import Vuex from 'vuex'
import user from './modules/user'
import tabs from './modules/tabs'
import permission from './modules/permission'
import getters from './getters'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        user,
        tabs,
        permission
    },
    getters
})
