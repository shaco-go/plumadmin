import store from '@/store'

// 校验权限
export default function (permission) {
  return store.getters.permissions.some(e => {
    return permission.toLowerCase() === e.toLowerCase()
  })
}
