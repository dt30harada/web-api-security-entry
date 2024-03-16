const API_BASE_URL = process.env.MIX_API_URL

export default {
  /**
   * ユーザー登録
   *
   * @param {String} loginId
   * @param {String} password
   */
  async register(loginId, password) {
    try {
      const params = {
        login_id: loginId,
        password,
      }
      await axios.post(`${API_BASE_URL}/auth/register`, params)
      return { error: false }
    } catch (e) {
      if (e.response.status === 422) {
        return { error: true, message: e.response.data.errors }
      } else {
        return { error: true }
      }
    }
  },

  /**
   * ログイン実行
   *
   * @param {String} loginId
   * @param {String} password
   */
  async login(loginId, password) {
    try {
      const params = {
        login_id: loginId,
        password,
      }
      await axios.post(`${API_BASE_URL}/auth/login`, params)
      return { error: false }
    } catch (e) {
      if (e.response.status === 422) {
        return { error: true, message: 'ログインIDかパスワードが違います。' }
      } else {
        return { error: true }
      }
    }
  },

  /**
   * ログインユーザー情報 取得
   */
  async getUser() {
    try {
      const res = await axios.get(`${API_BASE_URL}/auth/user`)
      return res.data.data
    } catch (e) {
      return { error: true }
    }
  },

  /**
   * ログアウト実行
   */
  async logout() {
    try {
      await axios.post(`${API_BASE_URL}/auth/logout`)
      return { error: false }
    } catch (e) {
      return { error: true }
    }
  },

  /**
   * パスワード確認
   *
   * @param {String} password
   */
  async confirmPassword(password) {
    try {
      const params = {
        password,
      }
      await axios.post(`${API_BASE_URL}/auth/confirm-password`, params)
      return { error: false }
    } catch (e) {
      if (e.response.status === 422) {
        return { error: true, message: e.response.data.errors }
      } else {
        return { error: true }
      }
    }
  },

  /**
   * パスワード変更
   *
   * @param {String} password
   * @param {String} newPassword
   */
  async changePassword(password, newPassword) {
    try {
      const params = {
        password,
        new_password: newPassword,
      }
      await axios.put(`${API_BASE_URL}/auth/password`, params)
      return { error: false }
    } catch (e) {
      if (e.response.status === 422) {
        return { error: true, message: e.response.data.errors }
      } else {
        return { error: true }
      }
    }
  },
}
