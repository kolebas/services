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
    path: '/1c002',
    name: '1C002',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/1C002.vue')
  },
  {
    path: '/nu001',
    name: 'Nu001',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Nu001.vue')
  },
  {
    path: '/ns001',
    name: 'Ns001',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Ns001.vue')
  },
  {
    path: '/ph001',
    name: 'Ph001',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Ph001.vue')
  },
  {
    path: '/ar001',
    name: 'Ar001',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Ar001.vue')
  },
  {
    path: '/rb001',
    name: 'Rb001',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Rb001.vue')
  },
  {
    path: '/sf001',
    name: 'Sf001',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Sf001.vue')
  },
  {
    path: '/ms001',
    name: 'Ms001',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Ms001.vue')
  },
  {
    path: '/ms002',
    name: 'Ms002',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Ms002.vue')
  },
  {
    path: '/Ms003',
    name: 'Ms003',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Ms003.vue')
  },
  {
    path: '/Ms004',
    name: 'Ms004',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Ms004.vue')
  },
  {
    path: '/Inc',
    name: 'Inc',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Inc.vue')
  },
  {
    path: '/Hr',
    name: 'Hr',
    component: () => import(/* webpackChunkName: "services" */ '../views/hr/Hr.vue')
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})


export default router
