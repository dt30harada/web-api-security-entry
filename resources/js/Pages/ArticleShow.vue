<template>
  <div class="acticles-show">
    <h2>記事詳細画面</h2>
    <router-link to="/articles">記事一覧へ</router-link>
    <div class="article">
      <div class="title">{{ article.title }}</div>
      <div class="info">
        <div style="margin-bottom: 1rem">
          <span style="margin-right: 1rem"> 登録日時: {{ article.created_at }} </span>
          <span>更新日時: {{ article.updated_at }}</span>
        </div>
        <div v-if="article.is_owner">
          <button @click="onClickEdit" style="margin-right: 1rem">編集</button>
          <button @click="onClickDelete">削除</button>
        </div>
      </div>
      <div class="body" v-html="article.body"></div>
    </div>
  </div>
</template>

<script>
import articleRepository from '@/repositories/articleRepository'

export default {
  data() {
    return {
      article: {},
    }
  },
  mounted() {
    this.fetchArticle()
  },
  methods: {
    async fetchArticle() {
      const articleId = this.$route.params.id
      if (!articleId) {
        this.$router.push({ name: 'articles' })
        return
      }
      const article = await articleRepository.get(articleId)
      if (article.error) {
        if (_.has(article, 'message')) alert(article.message)
        this.$router.push({ name: 'articles' })
      }
      this.article = article
    },
    async onClickEdit() {
      this.$router.push({ name: 'articles-edit', params: { id: this.article.id } })
    },
    async onClickDelete() {
      const res = await articleRepository.delete(this.article.id)
      if (res.error && _.has(res, 'message')) {
        alert(res.message)
      }
      this.$router.push({ name: 'articles' })
    },
  },
}
</script>

<style scoped>
.article {
  width: 90%;
  min-width: 760px;
  padding: 18px;
  background-color: white;
  border-radius: 8px;
  margin: 4% 0;
}
.article .title {
  font-size: 32px;
  font-weight: bold;
  margin-bottom: 4%;
}
.article .info {
  border-bottom: 0.5px solid #c9bebe;
  padding-bottom: 32px;
}
.article .body {
  margin: 2rem auto;
  font-size: 18px;
}
</style>
