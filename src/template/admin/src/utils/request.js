import axios from 'axios'
import {Message} from 'element-ui'
import {clearAuth, getRefreshToken, getToken, isRefreshTokenExpire, isTokenExpire, setAuth} from '@/utils/token'
import {debug, requestUrl} from '@/config'


let refresh = false
let requestList = []

// 初始化axios
const service = axios.create({
  baseURL: requestUrl,
  timeout: debug ? 0 : 5000 // request timeout
})

// 请求拦截
service.interceptors.request.use(config => {
  if (debug) {
    //后端xdebug调试
    if (!config.params)
      config.params = {}
    config.params.XDEBUG_SESSION_START = 'PHPSTORM'
  }
  //如果token存在,带上token请求
  if (getToken()) {
    //携带token
    config.headers['Authorization'] = 'Bearer ' + getToken()
    //如果是请求刷新token不需要拦截直接返回
    if (config.url.toLowerCase().includes('refresh')) {
      return config
    }
    //token临近过期(50秒),且refresh没有过期,则刷新token
    if (!isTokenExpire() && isRefreshTokenExpire()) {
      if (!refresh) {
        refresh = true
        //请求刷新
        service.get('refresh', {
          params: {refresh_token: getRefreshToken()}
        }).then(({data}) => {
          setAuth(data)
          requestList.forEach((cb) => cb(data.token))
          refresh = false
          requestList = []
        })
      }
      //等待token请求
      return new Promise(resolve => {
        requestList.push(token => {
          config.headers['Authorization'] = 'Bearer ' + token
          resolve(config)
        })
      })
    }
  }
  return config
}, error => {
  return Promise.reject(error)
})

// 响应拦截
service.interceptors.response.use(response => {
  const data = response.data
  if (data.code === 200) {
    return data
  } else if (data.code === 401) {
    //清除缓存
    clearAuth()
    //登录
    location.href = '/admin/login'
    return Promise.reject(data)
  } else if (data.code === 403) {
    // 跳转403页面
    location.href = '/admin/403'
    return Promise.reject(data)
  } else if (data.code === 500) {
    //跳转到500页面
    location.href = '/admin/500'
    return Promise.reject(data)
  } else {
    // 如果没有显式定义custom的toast参数为false的话，默认对报错进行toast弹出提示
    if (response.config.toast !== false) {
      Message.error(data.message)
    }
    return Promise.reject(data)
  }
}, error => {
  //跳转到服务器错误500
  location.href = '/admin/500'
  return Promise.reject(error)
})

export default service
