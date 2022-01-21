const getters = {
  //用户
  user: state => state.user.user,
  avatar: state => state.user.avatar,
  nickname: state => state.user.nickname,
  menus: state => state.permission.menus,
  tabs: state => state.tabs.activeTabs,
  permissions: state => state.permission.permissions
}
export default getters
