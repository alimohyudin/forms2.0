import Vue from 'vue'
import axios from 'axios'
import { BootstrapVue, VBPopover, BCardGroup, BCard, BCardText } from 'bootstrap-vue'
import Toaster from 'v-toaster'
// import SocketIO from 'socket.io-client'
// import VueSocketIO from 'vue-socket.io'
// import VueApexCharts from 'vue-apexcharts'


import App from './App'
import router from './router'
import store from './store'
import 'v-toaster/dist/v-toaster.css'



Vue.prototype.$socket = "";


Vue.prototype.$local = {
    appType: 'web',
    userData: {},
    server: 'https://cryptoauto.top/api',
    //server: 'https://api.mcquare1.net/api',
    //server: 'http://149.102.138.126:7861/api',
    //server: process.env.NODE_ENV === 'development' ? 'http://localhost:7861/api' : 'https://cryptoauto.top/api',
    //server: 'http://31.42.188.108:7861/api',
    //server: 'http://94.176.238.154:7861/api',//close branch
    token: localStorage.getItem('user_token') || '',
    isExchange: localStorage.getItem('isExchange') || '',
    getRequest: function (subUrl) {
        //console.log('hello get request' + this.server)
        return axios.get(this.server + subUrl)
            .then(function (data) {
                //console.log(data)
            })
            .catch(function (err) {
                //console.log('error')
                console.log(err)
            })
    },
    postRequest: function (subUrl, formData, that) {
        //console.log('hello post request')
        let config = {
            headers: { 'Authorization': 'bearer ' + this.token },
        }

        return axios.post(
            this.server + subUrl,
            formData,
            config,
        ).then(function (data) {
            //console.log('in axios then')
            //console.log(data)
            data = data.data;

            if (data.success) {
                return new Promise(function (resolve, reject) {
                    if (subUrl.indexOf('login') >= 0 || subUrl.indexOf('verify-email') >= 0) {
                        global.vm.$local.token = data.data.token;
                        localStorage.setItem('user_token', data.data.token);
                        global.vm.$local.userData = data.data;
                        global.vm.$local.isExchange = '' + data.data.isExchangeAccountSettings;
                        localStorage.setItem('isExchange', (data.data.isExchangeAccountSettings));

                        //global.vm.$socket = SocketIO('http://94.176.238.154:7861/', { query: `token=${data.data.token}` });

                        //console.log(global.vm.$socket);
                    }
                    //console.log("updatted token: " + global.vm.$local.token)
                    //console.log(data);
                    if (data.extras && data.extras != {}) {
                        data.data.v_extras = data.extras;
                    }

                    resolve(data.data);
                });
            } else {
                return new Promise(function ({ }, reject) {
                    if (subUrl.indexOf('login') >= 0 || subUrl.indexOf('register') >= 0) {
                        global.vm.$local.token = '';
                        localStorage.removeItem('user_token');
                    }
                    reject(data.message);
                });
            }
        }).catch(function (err) {
            return new Promise(function (resolve, reject) {
                //let err = data.message;
                console.log("in catch promise")
                console.log('error', err)
                if (err.toString().includes('code 429')){
                    that.$toaster.error("Too Many Requests. Try again later.")
                    reject('');                    
                }else
                reject(err);
            });
        });
    },
    emptyUserData: function () {
        for (let member in this.userData) {
            delete this.userData[member]
        }
        this.token = ''
    }
}



window.jQuery = window.$ = require('jquery/dist/jquery.min')
//Vue.prototype.$localDatabase = require('./api/index')

Vue.http = Vue.prototype.$http = axios
Vue.config.productionTip = false

Vue.use(Toaster, { timeout: 3000 })
// Vue.use(VueApexCharts)

//library.add(faCoffee, faArrowCircleLeft, faTrash, faPencilAlt, faCheck, faTimes, faEllipsisV, faSpinner, faToggleOff, faToggleOn, faFilter)

//Vue.component('font-awesome-icon', FontAwesomeIcon)

/**
 * Bootstrap directives
 */
Vue.directive('b-popover', VBPopover);
Vue.component('b-card-group', BCardGroup);
Vue.component('b-card', BCard);
Vue.component('b-card-text', BCardText);
// Vue.component('apexchart', VueApexCharts)

/* eslint-disable no-new */
var vm = new Vue({
    components: { App },
    router,
    store,
    //BootstrapVue,
    template: '<App/>'
});
global.vm = vm;
vm.$mount('#app')