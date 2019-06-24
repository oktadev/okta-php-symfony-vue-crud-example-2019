import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.config.productionTip = false
Vue.use(VueRouter)

import Dashboard from './components/Dashboard.vue'
import ItemCounter from './components/ItemCounter.vue'
import Auth from '@okta/okta-vue'

Vue.use(Auth, {
  issuer: 'https://dev-354685.oktapreview.com/oauth2/default',
  client_id: '0oalsm9qsj3xPnVxM0h7',
  redirect_uri: 'http://localhost:8080/implicit/callback',
  scope: 'openid profile email'
})

const routes = [
  { path: '/implicit/callback', component: Auth.handleCallback() },
  { path: '/counter', component: ItemCounter }
]

const router = new VueRouter({
  mode: 'history',
  routes
})

new Vue({
  router,
  render: h => h(Dashboard)
}).$mount('#app')
