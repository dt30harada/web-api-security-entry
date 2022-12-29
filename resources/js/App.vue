<template>
  <div class="container">
    <header>
      <h1>WebAPI脆弱性サンプルサイト</h1>
      <div v-if="isAuth" class="menu">
        <router-link to="/setting">設定</router-link>
        <a @click.prevent="onClickLogout">ログアウト</a>
      </div>
      <div v-else-if="$route.name === 'login'" class="menu">
        <router-link to="/register">ユーザー登録画面へ</router-link>
      </div>
      <div v-else-if="$route.name === 'register'" class="menu">
        <router-link to="/login">ログイン画面へ</router-link>
      </div>
    </header>
    <div>
      <router-view />
    </div>
  </div>
</template>

<script>
import authRepository from '@/repositories/authRepository'
import initAxios from '@/mixins/initAxios'

export default {
  mixins: [initAxios],
  created() {
    this.setAxiosInterceptors()
    this.fetchLoginUser()
  },
  computed: {
    user: {
      get() {
        return this.$store.getters['User/get']
      },
      set(v) {
        this.$store.commit('User/set', v)
      },
    },
    isAuth() {
      return this.$store.getters['User/isAuth']
    },
  },
  methods: {
    async fetchLoginUser() {
      const user = await authRepository.getUser()
      if (user.error) return
      this.user = user
    },
    async onClickLogout() {
      const res = await authRepository.logout()
      if (res.error) return
      this.$store.commit('User/reset')
      this.$router.push({ name: 'login' })
    },
  },
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap');
body {
  font-family: 'Noto Sans JP', '游ゴシック体', YuGothic, '游ゴシック', 'Yu Gothic', 'メイリオ',
    'Hiragino Kaku Gothic ProN', 'Hiragino Sans', sans-serif;
  font-weight: 400;
  background-color: #f5f6f6;
}
</style>

<style scoped>
.container {
  margin: 0 1rem;
}
header {
  display: flex;
  justify-items: center;
  align-items: center;
  justify-content: space-between;
}
header a {
  cursor: pointer;
  text-decoration: underline;
  color: blue;
}
.menu {
  width: 30%;
  font-size: 1.2rem;
  display: flex;
  justify-content: space-evenly;
}
</style>
