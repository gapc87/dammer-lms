import VueRouter from 'vue-router';

let routes = [
    {
        path: '/login',
        component: require('../components/pages/Login.vue').default,
    },
];

const router = new VueRouter({
    mode: 'history',
    routes
});

export default router;