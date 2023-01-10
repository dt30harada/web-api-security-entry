const API_BASE_URL = process.env.MIX_API_URL

export default {
  /**
   * 記事登録
   *
   * @param {String} title
   * @param {String} body
   */
  async post(title, body) {
    const params = {
      title,
      body,
    }
    try {
      const res = await axios.post(`${API_BASE_URL}/articles`, params)
      return res.data.data
    } catch (e) {
      if (e.response.status === 422) {
        return { error: true, errors: e.response.data.errors }
      } else {
        return { error: true }
      }
    }
  },

  /**
   * 記事検索
   *
   * @param {Object} search 検索パラメータ
   */
  async query(search) {
    const params = {
      page: search.page,
      per_page: search.perPage,
      title: search.title,
    }
    try {
      const res = await axios.post(`${API_BASE_URL}/articles/query`, params)
      return res.data.data
    } catch (e) {
      if (e.response.status === 422) {
        return { error: true, errors: e.response.data.errors }
      } else {
        return { error: true }
      }
    }
  },

  /**
   * 記事詳細取得
   *
   * @param {Number} id
   */
  async get(id) {
    try {
      const res = await axios.get(`${API_BASE_URL}/articles/${id}`)
      return res.data.data
    } catch (e) {
      return { error: true }
    }
  },

  /**
   * 記事更新
   *
   * @param {Number} id
   * @param {String} title
   * @param {String} body
   */
  async put(id, title, body) {
    const params = {
      title,
      body,
    }
    try {
      await axios.put(`${API_BASE_URL}/articles/${id}`, params)
      return { error: false }
    } catch (e) {
      if (e.response.status === 422) {
        return { error: true, errors: e.response.data.errors }
      } else {
        return { error: true }
      }
    }
  },

  /**
   * 記事削除
   *
   * @param {Number} id
   */
  async delete(id) {
    try {
      await axios.delete(`${API_BASE_URL}/articles/${id}`)
      return { error: false }
    } catch (e) {
      return { error: true }
    }
  },
}
