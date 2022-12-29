import Vue from 'vue'
import VueRouter from 'vue-router'

import Login from '@/Pages/Login'
import Register from '@/Pages/Register'
import Setting from '@/Pages/Setting'
import ArticleList from '@/Pages/ArticleList'
import ArticleEdit from '@/Pages/ArticleEdit'
import ArticleShow from '@/Pages/ArticleShow'

Vue.use(VueRouter)

const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login,
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
  },
  {
    path: '/setting',
    name: 'setting',
    component: Setting,
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
