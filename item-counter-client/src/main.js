import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.config.productionTip = false
Vue.use(VueRouter)

import Dashboard from './components/Dashboard.vue'
import ItemCounter from './components/ItemCounter.vue'
import Auth from '@okta/okta-vue'

Vue.use(Auth, {
  issuer: 'YOUR OKTA ISSUER URL',
  client_id: 'YOUR OKTA CLIENT ID',
  redirect_uri: 'http://localhost:8080/callback',
  scope: 'openid profile email',
  pkce: true
})

const routes = [
  { path: '/callback', component: Auth.handleCallback() },
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
