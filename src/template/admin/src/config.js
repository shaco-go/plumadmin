//生产环境下请求
const PRO_REQUEST_URL = '/adminapi'
//开发环境下请求
const DEV_REQUEST_URL = 'http://localhost:8000/adminapi'
//是否处于开发模式用于php xdebug
const DEBUG = false
// const DEBUG = true

module.exports = {
    requestUrl: process.env.NODE_ENV === 'development' ? DEV_REQUEST_URL : PRO_REQUEST_URL,
    title: 'PLUM ADMIN',
    logo: require('@/assets/logo.png'),
    sliderLogo: require('@/assets/logo.png'),
    sliderTitle: 'PLUM ADMIN',
    debug:DEBUG
}
