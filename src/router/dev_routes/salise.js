
export default{
  routes: [{
    path: '/salise',
    name: 'salise',
    component: resolve => require(['modules/test/Salise.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/position_management',
    name: 'positionManagement',
    component: resolve => require(['modules/position/PositionManagement.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/latest_data',
    name: 'latestData',
    component: resolve => require(['modules/test/LatestData.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: resolve => require(['modules/dashboard/Dashboard.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  }]
}
