import VueRouter from 'vue-router';

let routes = [
    {
        path: '/login',
        component: require('../components/pages/Login.vue').default,
    },
    {
        path: '',
        component: require('../components/pages/dashboard/index.vue').default,
        meta: { middlewareAuth: true },
        children: [
            {
                name: 'home',
                path: '/',
                component: require('../components/pages/dashboard/home').default,
                meta: { middlewareAuth: true }
            },
            {
                name: 'module',
                path: '/module/:module',
                component: require('../components/pages/dashboard/module').default,
                meta: { middlewareAuth: true }
            },
            {
                name: 'tasks',
                path: '/module/:module/task/:task',
                component: require('../components/pages/dashboard/task').default,
                meta: { middlewareAuth: true }
            },
            {
                name: 'users',
                path: '/admin/users',
                component: require('../components/pages/dashboard/admin/Users').default,
                meta: { middlewareAuth: true }
            },
            {
                name: 'modules',
                path: '/admin/modules',
                component: require('../components/pages/dashboard/admin/Modules').default,
                meta: { middlewareAuth: true }
            }
        ]
    }
];

const router = new VueRouter({
    mode: 'history',
    routes
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.middlewareAuth)) {
        if (!auth.check()) {
            next({
                path: '/login',
                query: { redirect: to.fullPath }
            });

            return;
        }
    } else if (to.path === '/login' && auth.check()) {
        next({
            path: '/',
            query: { redirect: to.fullPath }
        });

        return;
    }

    next();
});

export default router;
