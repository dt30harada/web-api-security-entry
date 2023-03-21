<template>
  <div class="login">
    <h2>ログイン画面</h2>
    <div class="form">
      <div class="form-item">
        <label for="loginId">ログインID</label>
        <input id="loginId" v-model="form.loginId" autocomplete="off" type="text" />
      </div>
      <div class="form-item">
        <label for="password">パスワード</label>
        <input id="password" v-model="form.password" autocomplete="off" type="password" />
      </div>
      <div class="form-item">
        <button class="button" @click="onClickLogin">送信</button>
      </div>
    </div>
  </div>
</template>

<script>
import authRepository from '@/repositories/authRepository'

export default {
  data() {
    return {
      form: {
        loginId: '',
        password: '',
      },
    }
  },
  computed: {
    isAuth() {
      return this.$store.getters['User/isAuth']
    },
  },
  watch: {
    isAuth(v) {
      if (v) {
        this.$router.push({ name: 'articles' })
      }
    },
  },
  methods: {
    async onClickLogin() {
      const res = await authRepository.login(this.form.loginId, this.form.password)
      if (res.error) {
        if (_.has(res, 'message')) alert(res.message)
        return
      }
      this.$router.push({ name: 'articles' })
    },
  },
}
</script>

<style scoped>
.form-item {
  margin: 2% auto;
}
label {
  display: block;
}
input {
  width: 40%;
  padding: 0.5rem;
  font: inherit;
}
.form-item button {
  padding: 0.5%;
  width: 10%;
  font-size: 16px;
}
</style>
