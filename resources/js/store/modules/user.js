import _ from 'lodash'

// ユーザー情報
const User = {
  namespaced: true,

  state: {
    user: {},
    isAuth: false,
  },
  getters: {
    get: state => state.user,
    isAuth: state => state.isAuth,
  },
  mutations: {
    set(state, user) {
      state.user = user
      state.isAuth = _.has(user, 'login_id')
    },
    reset(state) {
      state.user = {}
      state.isAuth = false
    },
  },
  actions: {},
}

export default User
