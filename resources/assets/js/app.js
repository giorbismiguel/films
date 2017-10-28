/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import router from './route.js';
import {EventBus} from './event-bus.js';
import Vue from 'vue';

import VueRouter from 'vue-router';
import VeeValidate from 'vee-validate';
import Notifications from 'vue-notification'
import datePicker from 'vue-bootstrap-datetimepicker';
import starRating from 'vue-star-rating';
import vSelect from "vue-select"

Vue.use(Notifications)
Vue.use(VeeValidate);
Vue.use(VueRouter);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    data(){
        return {
            name: '',
            loginSuccess: false,
            isLoginData: localStorage.getItem('token'),
            userStorage: localStorage.getItem('user') ? localStorage.getItem('user') : 'Welcome'
        }
    },
    computed: {
        isLogin(){
            if (this.loginSuccess) {
                return true;
            } else if (this.isLoginData) {
                return true
            }
            return false;
        }
    },
    components: {
        datePicker,
        starRating,
        vSelect,
    },
    methods: {
        logoutUser(){
            axios.post('/api/logout')
                .then(response => {
                    if (response.data.success) {
                        this.loginSuccess = false;
                        localStorage.removeItem('token');
                        localStorage.removeItem('user');
                        this.$router.push('/login');
                    }
                })
                .catch(error => {
                    this.errors_server = error.response.data;
                });
        }
    },
    mounted() {
        EventBus.$on('loginSuccess', user => {

            console.log(user);

            this.$router.push('/');
            this.loginSuccess = true;
            this.userStorage = user;
        });
    }
});
