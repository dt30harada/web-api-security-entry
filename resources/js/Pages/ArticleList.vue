<template>
  <div class="articles">
    <h2>記事一覧画面</h2>
    <router-link to="articles/new">記事を投稿する</router-link>
    <div class="search">
      <label for="search-title">タイトル</label>
      <input type="text" v-model="search.title" id="search-title" />
      <button @click="onClickSearch">検索</button>
    </div>
    <div style="margin-bottom: 8px">{{ total }}件中{{ search.perPage }}件表示</div>
    <div class="article-list">
      <table>
        <thead>
          <tr>
            <th style="min-width: 60px"></th>
            <th style="width: 6%">ID</th>
            <th style="width: 40%">タイトル</th>
            <th style="width: 12%">投稿者</th>
            <th>投稿日時</th>
            <th>更新日時</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="article in articles" :key="article.id">
            <td style="text-align: center">
              <button @click="onClickShow(article.id)">詳細</button>
            </td>
            <td style="text-align: center">{{ article.id }}</td>
            <td class="truncate-text">{{ article.title }}</td>
            <td>{{ article.user_login_id }}</td>
            <td>{{ article.created_at }}</td>
            <td>{{ article.updated_at }}</td>
          </tr>
        </tbody>
      </table>
      <paginate
        v-model="search.page"
        :page-count="pageCount"
        :page-range="3"
        :click-handler="onClickPage"
        :prev-text="'<'"
        :next-text="'>'"
        :first-last-button="true"
        :first-button-text="'<<'"
        :last-button-text="'>>'"
        :container-class="'pagination'"
        :page-class="'page-item'"
      />
    </div>
  </div>
</template>

<script>
import paginate from 'vuejs-paginate'
import articleRepository from '@/repositories/articleRepository'

export default {
  components: {
    paginate,
  },
  data() {
    return {
      disabledSearch: false,
      articles: [],
      total: 0,
      search: {
        page: 1,
        perPage: 5,
        title: '',
      },
    }
  },
  computed: {
    pageCount() {
      return Math.ceil(this.total / this.search.perPage)
    },
  },
  mounted() {
    this.fetchArticles()
  },
  methods: {
    async fetchArticles() {
      const res = await articleRepository.query(this.search)
      if (res.error) {
        if (_.has(res, 'errors')) {
          alert(res.errors.join('\n'))
        }
        return
      }
      this.articles = res.items || []
      this.total = res.total || 0
    },
    onClickSearch() {
      this.search.page = 1
      this.fetchArticles()
    },
    onClickPage() {
      this.fetchArticles()
    },
    onClickShow(articleId) {
      this.$router.push({ name: 'articles-show', params: { id: articleId } })
    },
  },
}
</script>

<style>
.pagination {
  display: flex;
  padding-left: 0;
}
.pagination li {
  list-style: none;
  padding: 1%;
  margin: 0 2px;
  border: 1px solid gray;
  cursor: pointer;
}
.pagination li a {
  width: 100%;
  height: 100%;
}
</style>

<style scoped>
.truncate-text {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.search {
  margin: 8px 0;
}
.article-list table {
  width: 100%;
  min-width: 760px;
  background-color: white;
  border-collapse: collapse;
}
.article-list table th {
  border: 1px solid gray;
  padding: 1%;
}
.article-list table td {
  border: 1px solid gray;
  padding: 1%;
}
</style>
