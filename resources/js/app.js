require('./bootstrap')
import Vue from 'vue'

import router from '@/router'
import App from './App.vue'
import store from './store'

new Vue({
  router,
  store,
  render: h => h(App),
}).$mount('#app')

import authRepository from '@/repositories/authRepository'

router.beforeEach(async (to, from, next) => {
  // 要認証画面で未認証の場合はリダイレクト
  if (to.matched.some(record => record.meta.requiredAuth)) {
    const isAuth = store.getters['User/isAuth']
    if (isAuth) {
      next()
      return
    }
    const user = await authRepository.getUser()
    if (!user.error) {
      store.commit('User/set', user)
      next()
      return
    }
    console.log('redirect to login page.')
    next({
      name: 'login',
      query: { redirect: to.fullPath },
    })
  }

  next()
})
