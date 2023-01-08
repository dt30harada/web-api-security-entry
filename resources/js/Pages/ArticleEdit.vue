<template>
  <div class="acticle-edit">
    <h2>記事{{ edit ? '編集' : '登録' }}画面</h2>
    <div class="form">
      <div class="form-item">
        <label for="article-title">タイトル</label>
        <input id="article-title" autocomplete="off" type="text" v-model="form.title" />
      </div>
      <div class="form-item md-editor">
        <div class="md-editor-input-area">
          <label for="article-body">本文</label>
          <textarea id="article-body" cols="30" rows="10" :value="form.body" @input="onInputBody"></textarea>
        </div>
        <div class="md-editor-preview-area">
          <div>プレビュー</div>
          <div class="md-editor-preview">
            <div v-html="htmlBody"></div>
          </div>
        </div>
      </div>
      <div class="form-item">
        <button class="button" @click="onClickSend">送信</button>
      </div>
    </div>
  </div>
</template>

<script>
import { debounce } from 'lodash'
import { marked } from 'marked'
import articleRepository from '@/repositories/articleRepository'

export default {
  data() {
    return {
      edit: false,
      form: {
        title: '',
        body: '',
      },
      htmlBody: '',
    }
  },
  created() {
    this.edit = this.$route.name === 'articles-edit'

    if (this.edit) this.getArticle()
  },
  methods: {
    onInputBody: debounce(function (e) {
      this.form.body = e.target.value
      this.htmlBody = marked(e.target.value)
    }, 300),
    // 記事を取得
    async getArticle() {
      const articleId = this.$route.params.id
      const article = await articleRepository.get(articleId)
      if (article.error) {
        this.$router.push({ name: 'articles' })
      }
      this.form.title = article.title
      this.form.body = article.body
      this.htmlBody = marked(article.body)
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
  width: 100%;
  padding: 0.5rem;
  font: inherit;
}
textarea {
  width: 100%;
  min-height: 92%;
  padding: 0.5rem;
  font: inherit;
  resize: none;
}
.form {
  width: 80%;
}
.form-item button {
  padding: 0.5%;
  width: 10%;
  font-size: 16px;
}
.md-editor {
  display: flex;
  min-height: 300px;
}
.md-editor label {
  min-height: 8%;
}
.md-editor-input-area {
  width: 48%;
  margin-right: 4%;
  min-height: 100%;
}
.md-editor-preview-area {
  width: 48%;
  min-height: 100%;
}
.md-editor-preview {
  border: solid 1px gray;
  background-color: white;
  min-height: 92%;
}
</style>
