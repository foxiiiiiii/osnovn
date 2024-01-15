const { createStore } = Vuex
const store = createStore({
  state: () => ({
    loading: true,
    authed: null,
    toNotify: false,
    tabname: '',
    user: {}
  }),
  mutations: {
    async getUser(state, token) {
      try {
        const res = await axios.get('/wp-json/tch/user/parse', {
          headers: { token: token ? token : Cookies.get('token') }
        });
        state.authed = true;
        state.user = res.data;
        state.loading = false;
        if(window.location.pathname.includes('auth')) {
          window.open('/account', '_self');
        }

      } catch (e) {
        window.loading = false;
        state.authed = false;
        if(window.location.pathname.includes('account')) {
          window.open('/auth', '_self');
        }
      }
    },
    async openTabFromHead(state, name) {
      state.tabname = name;
    },
    removeUser(state) {
      const currentPath = window.location.pathname;
      Cookies.remove('token');
      state.authed = false;
      state.user = {}
      if(currentPath.includes('account')) {
        window.open('/auth', '_self');
      } else {
        location.reload()
      }
    }
  }
})