import request from '@/utils/request'
import {clearAuth, setAuth} from "@/utils/token";
import {resetRouter} from '@/router'

const getDefaultState = () => {
  return {
    user: null,
    avatar: '',
    nickname: ''
  }
}

export default {
  namespaced: true,
  state: getDefaultState(),
  mutations: {
    //重置state
    RESET_STATE(state) {
      Object.assign(state, getDefaultState())
    },
    //设置用户
    SET_USER(state, user) {
      state.user = user
      state.avatar = user?.avatar?.url ?? ''
      state.nickname = user.nickname
    }
  },
  actions: {
    //获取当前登录的用户信息
    getUserInfo({commit}) {
      return new Promise((resolve, reject) => {
        request.get('userinfo')
          .then(response => {
            commit('SET_USER', response.data)
            //添加菜单permission和tabs
            commit('permission/SET_PERMISSION', response.data, {root: true})
            commit('tabs/INIT', response.data, {root: true})
            resolve(response)
          })
          .catch(error => {
            reject(error)
          })
      })
    },
    //登录
    login({commit}, form) {
      return new Promise((resolve, reject) => {
        const {
                username,
                password
              } = form
        request.post('login/account', {
          username,
          password
        })
          .then(response => {
            //保存token信息
            setAuth(response.data)
            resolve(response)
          })
          .catch((error) => {
            reject(error)
          })
      })
    },
    //登出
    logout({commit}) {
      return new Promise((resolve, reject) => {
        request.get('logout')
          .then(response => {
            //清除token
            clearAuth()
            //重置state
            commit('RESET_STATE')
            //重置路由
            resetRouter()
            resolve(response)
          })
          .catch(error => {
            //清除token
            clearAuth()
            reject(error)
          })
      })
    }
  }
}
