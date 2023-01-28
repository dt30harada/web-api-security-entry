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
          const status = error.response.status
          if ([401, 429].includes(status)) {
            // 未認証orレート制限によるエラーの場合はログイン画面へ
            this.$store.commit('User/reset')
            let msg = ''
            switch (status) {
              case 401:
                msg = 'Unauthenticated.'
                break
              case 429:
                msg = 'Too many requests.'
                break
            }
            if (this.$route.meta.requiredAuth) {
              if (confirm(msg)) this.$router.push({ name: 'login' })
            } else if (status === 429) {
              alert(msg)
            }
          }
          return Promise.reject(error)
        },
      )
    },
  },
}
