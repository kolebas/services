import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Заявки',
    component: () => import(/* webpackChunkName: "services" */ '../views/Services.vue')
  },
  {
    path: '/1c002',
    name: 'Заявка на доработку 1С',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/1C002.vue')
  },
  {
    path: '/nu001',
    name: 'Заявка на нового сотрудника',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Nu001.vue')
  },
  {
    path: '/ns001',
    name: 'Удаленный доступ (VPN)',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Ns001.vue'),
  },
  {
    path: '/ph001',
    name: 'Предоставление служебной сотовой связи',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Ph001.vue')
  },
  {
    path: '/ar001',
    name: 'Отключение доступов',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Ar001.vue')
  },
  {
    path: '/rb001',
    name: 'Заявка на приобретение техники/программного обеспечения',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Rb001.vue')
  },
  {
    path: '/sf001',
    name: 'Установка программного обеспечения',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Sf001.vue')
  },
  {
    path: '/ms001',
    name: 'Корректировка данных в путевых листах 1С',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Ms001.vue')
  },
  {
    path: '/ms002',
    name: 'Предоставление доступа к программам мониторинга',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Ms002.vue')
  },
  {
    path: '/ms003',
    name: 'Заявка о неисправности телематического оборудования',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Ms003.vue')
  },
  {
    path: '/ms004',
    name: 'Добавление основного средства в папку «Техника для выработки»',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Ms004.vue')
  },
  {
    path: '/inc',
    name: 'Инцидент',
    component: () => import(/* webpackChunkName: "services" */ '../views/services/Inc.vue')
  },
  {
    path: '/hr',
    name: 'Hr',
    component: () => import(/* webpackChunkName: "services" */ '../views/hr/Hr.vue')
  },
  {
    path: '/market',
    name: 'Магазин',
    component: () => import(/* webpackChunkName: "services" */ '../views/hr/Market.vue')
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: () => import(/* webpackChunkName: "services" */ '../views/dashboard/index.vue')
  }
  
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
