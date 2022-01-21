const tokenKey = 'token'

export function getToken() {
  let item = getAuth()
  if (!item) {
    return null
  }
  return item.token
}

export function getRefreshToken() {
  let item = getAuth()
  if (!item) {
    return null
  }
  return item.refresh_token
}

function getAuth() {
  let item = localStorage.getItem(tokenKey)
  if (!item) {
    return null
  }
  return JSON.parse(item)
}

export function setAuth(item) {
  localStorage.setItem(tokenKey, JSON.stringify(item))
}

export function isTokenExpire() {
  //如果时间小于50秒认为过期
  const timestamp = Math.floor((new Date().getTime() / 1000)) - 50
  const item = getAuth()
  return !(!item || item.expire < timestamp)
}

export function isRefreshTokenExpire() {
  //如果时间小于50秒认为过期
  const timestamp = Math.floor((new Date().getTime() / 1000)) - 50
  const item = getAuth()
  return !(!item || item.refresh_expire < timestamp)
}

export function clearAuth() {
  localStorage.removeItem(tokenKey)
}
