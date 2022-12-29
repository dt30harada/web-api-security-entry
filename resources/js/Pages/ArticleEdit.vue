<template>
  <div class="acticle-edit">
    <h2>記事{{ edit ? '編集' : '登録' }}画面</h2>
    <div class="form-item">
      <label for="article-title">タイトル</label>
      <input id="article-title" autocomplete="off" type="text" v-model="form.title" />
    </div>
    <div class="form-item">
      <label for="article-body">本文</label>
      <textarea id="article-body" cols="30" rows="10" v-model="form.body"></textarea>
    </div>
    <div class="form-item">
      <button class="button" @click="onClickSend">送信</button>
    </div>
  </div>
</template>

<script>
import articleRepository from '@/repositories/articleRepository'

export default {
  data() {
    return {
      edit: false,
      form: {
        title: '',
        body: '',
      },
    }
  },
  created() {
    this.edit = this.$route.name === 'articles-edit'

    if (this.edit) this.getArticle()
  },
  methods: {
    // 記事を取得
    async getArticle() {
      const articleId = this.$route.params.id
      const article = await articleRepository.get(articleId)
      if (article.error) {
        this.$router.push({ name: 'articles' })
      }
      this.form.title = article.title
      this.form.body = article.body
    },
    // 送信
    async onClickSend() {
      let res
      if (this.edit) {
        res = await articleRepository.put(this.$route.params.id, this.form.title, this.form.body)
      } else {
        res = await articleRepository.post(this.form.title, this.form.body)
      }
      if (res.error) {
        if (_.has(res, 'errors')) {
          alert(res.errors.join('\n'))
        }
        return
      }
      // 詳細画面へ
      this.$router.push({ name: 'articles-show', params: { id: res.id } })
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
  width: 80%;
  padding: 0.5rem;
  font: inherit;
}
textarea {
  width: 80%;
  padding: 0.5rem;
  font: inherit;
}
.form-item button {
  padding: 0.5%;
  width: 10%;
  font-size: 16px;
}
</style>
