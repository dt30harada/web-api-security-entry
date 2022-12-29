export default {
  methods: {
    setAxiosInterceptors() {
      axios.interceptors.request.use(request => {
        return request
      })
      axios.interceptors.response.use(
        response => {
          return response
        },
        error => {
          if (error.response.status === 401) {
            // 未認証の場合はログイン画面へ
            this.$store.commit('User/reset')
            if (this.$route.name !== 'login') {
              this.$router.push({ name: 'login' })
            }
          }
          return Promise.reject(error)
        },
      )
    },
  },
}
