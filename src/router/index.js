import Vue from 'vue'
import VueRouter from 'vue-router'
import Ns001 from '../views/Ns001.vue'

Vue.use(VueRouter)

  const routes = [
  
  {
    path: '/ns001',
    name: 'Ns001',
    component: Ns001
  },
  {
    path: '/about',
    name: 'About',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
