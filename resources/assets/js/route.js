/**
 * Created by Giorbis Miguel on 25/10/2017.
 */
/**
 * Created by Giorbis Miguel on 24/9/2017.
 */

import VueRouter from 'vue-router';

let routes = [
    {
        path: '/',
        component: require('./components/Home')
    },
    {
        path: '/films',
        component: require('./components/Films')
    },
    {
        path: '/create',
        component: require('./components/Create')
    },
    {
        path: '/create',
        component: require('./components/Create')
    },
    {
        path: '/login',
        component: require('./components/Login')
    },
    {
        path: '/register',
        component: require('./components/Register')
    },
    {
        path: '/films/:slug',
        component: require('./components/Slug')
    }
];

export default new VueRouter({
    routes
});