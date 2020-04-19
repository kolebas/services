import Vue from 'vue'
import VueRouter from 'vue-router'
import Ns001 from '../views/Ns001.vue'


Vue.use(VueRouter)

  const routes = [
  {
    path: '/',
    name: 'Services',
    component: () => import(/* webpackChunkName: "services" */ '../views/Services.vue')
  },
  {
    path: '/ns001',
    name: 'Ns001',
    component: Ns001
  },
  {
    path: '/rb001',
    name: 'Rb001',
    component: () => import(/* webpackChunkName: "services" */ '../views/Rb001.vue')
  },
  {
    path: '/sf001',
    name: 'Sf001',
    component: () => import(/* webpackChunkName: "services" */ '../views/Sf001.vue')
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


//const services = () => import(/*webpackChunkName: "Services"*/  '../components/Services.vue')

export default router
