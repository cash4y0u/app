import '@mdi/font/css/materialdesignicons.css'
import '@fortawesome/fontawesome-free/css/all.css'

window._ = require('lodash');

window.Vue = require('vue');

import App from './components/App.vue'

import router from './router'
import store from './store'

import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

import pt_BR from 'vee-validate/dist/locale/pt_BR'

import VeeValidate, { Validator } from 'vee-validate'

Validator.localize('pt_BR', pt_BR);

Vue.use(VeeValidate, {
    locale: 'pt_BR',

});

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

Vue.use(Vuetify,{
    iconfont: 'mdi',
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.auth)) {
        if (store.state.auth.token) {
            next()
        } else {
            next({
                path: '/login',
                query: {
                    redirect: to.fullPath
                }
            })
        }
    } else {
        next()
    }
})
if (store.state.auth.token) {
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${store.state.auth.token}`
}


new Vue({
    store,
    router,
    render: h => h(App)
}).$mount('#app')
