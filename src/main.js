import Vue from 'vue'
//import './plugins/axios'
import App from './App.vue'
import router from './router'
export const bus = new Vue()
import 'babel-polyfill'
import vuetify from './plugins/vuetify';


Vue.config.productionTip = true

new Vue({
  router,
  vuetify,
  render: h => h(App)
}).$mount('#app')
