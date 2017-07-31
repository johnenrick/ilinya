
export default{
  routes: [{
    path: '/plenos',
    name: 'plenos',
    component: resolve => require(['modules/test/Plenos.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/dev_table_component',
    name: 'dev_table_component',
    component: resolve => require(['modules/test/TableComponent.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/dev_common_module',
    name: 'dev_common_module',
    component: resolve => require(['modules/test/CommonModule.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/department_management',
    name: 'departmentManagement',
    component: resolve => require(['modules/department/DepartmentManagement.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/queue_form_management',
    name: 'queueFormManagement',
    component: resolve => require(['modules/queue_form/QueueFormManagement.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/counter',
    name: 'counter',
    component: resolve => require(['modules/counter/Counter.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/auth',
    name: 'auth',
    component: resolve => require(['modules/test/Auth.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  }]
}
