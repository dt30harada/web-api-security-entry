import Vue from 'vue'
import VueRouter from 'vue-router'

import AccountLogin from '@/Pages/AccountLogin'
import AccountRegister from '@/Pages/AccountRegister'
import AccountSetting from '@/Pages/AccountSetting'
import ArticleList from '@/Pages/ArticleList'
import ArticleEdit from '@/Pages/ArticleEdit'
import ArticleShow from '@/Pages/ArticleShow'

Vue.use(VueRouter)

const routes = [
  {
    path: '/login',
    name: 'login',
    component: AccountLogin,
  },
  {
    path: '/register',
    name: 'register',
    component: AccountRegister,
  },
  {
    path: '/setting',
    name: 'setting',
    component: AccountSetting,
    meta: {
      requiredAuth: true,
    },
  },
  {
    path: '/articles',
    name: 'articles',
    component: ArticleList,
    meta: {
      requiredAuth: true,
    },
  },
  {
    path: '/articles/new',
    name: 'articles-new',
    component: ArticleEdit,
    meta: {
      requiredAuth: true,
    },
  },
  {
    path: '/articles/:id',
    name: 'articles-show',
    component: ArticleShow,
    meta: {
      requiredAuth: true,
    },
  },
  {
    path: '/articles/:id/edit',
    name: 'articles-edit',
    component: ArticleEdit,
    meta: {
      requiredAuth: true,
    },
  },
]

const router = new VueRouter({
  mode: 'history',
  routes,
})

export default router
