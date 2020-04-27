import Vue from 'vue'
import VueRouter from 'vue-router'
//import Ns001 from '../views/Ns001.vue'

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
    component: () => import(/* webpackChunkName: "services" */ '../views/Ns001.vue')
  },
  {
    path: '/ph001',
    name: 'Ph001',
    component: () => import(/* webpackChunkName: "services" */ '../views/Ph001.vue')
  },
  {
    path: '/ar001',
    name: 'Ar001',
    component: () => import(/* webpackChunkName: "services" */ '../views/Ar001.vue')
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
    path: '/test',
    name: 'Test',
    component: () => import(/* webpackChunkName: "services" */ '../views/Test.vue')
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})


export default router
