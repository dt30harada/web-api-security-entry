<template>
  <div class="setting">
    <h2>設定画面</h2>
    <div class="form">
      <div class="form-title">
        <div>パスワード{{ confirmed ? '変更' : '確認' }}</div>
      </div>
      <div class="form-item">
        <template v-if="confirmed">
          <label for="new-password">新しいパスワード</label>
          <input id="new-password" autocomplete="off" type="password" v-model="newPassword" />
        </template>
        <template v-else>
          <label for="password">現在のパスワード</label>
          <input id="password" autocomplete="off" type="password" v-model="password" />
        </template>
      </div>
      <div class="form-item">
        <button class="button" @click="onClickSend">送信</button>
      </div>
    </div>
  </div>
</template>

<script>
import authRepository from '@/repositories/authRepository'

export default {
  data() {
    return {
      confirmed: false,
      password: '',
      newPassword: '',
    }
  },
  methods: {
    onClickSend() {
      this.confirmed ? this.changePassword() : this.confirmPassword()
    },
    async confirmPassword() {
      const res = await authRepository.confirmPassword(this.password)
      if (res.error) {
        const message = _.has(res, 'message') ? res.message : 'error.'
        alert(message)
        return
      }
      this.confirmed = true
    },
    async changePassword() {
      const res = await authRepository.changePassword(this.password, this.newPassword)
      if (res.error) {
        const message = _.has(res, 'message') ? res.message : 'error.'
        alert(message)
        return
      }
      alert('Changed password !')
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
  width: 30%;
  padding: 0.5rem;
  font: inherit;
}

.form-item button {
  padding: 0.5%;
  width: 10%;
  font-size: 16px;
}
</style>
