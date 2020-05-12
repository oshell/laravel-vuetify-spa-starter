import Vue from 'vue'
import vuetify from './plugins/vuetify'
import router from './router'
import App from './views/App'

window.Vue = require('vue');
const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const app = new Vue({
    el: '#app',
    components: { App },
    vuetify,
    router,
});