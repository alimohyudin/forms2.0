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



// Vue.prototype.$socket = "";


Vue.prototype.$local = {
    appType: 'web',
    userData: {},
    
    // server: 'http://localhost/forms2.0/backend-php/api',
    server: 'https://tclmunderground.com/forms2.0/backend-php/api',

    token: localStorage.getItem('user_token') || '',
    isExchange: localStorage.getItem('isExchange') || '',
    getRequest: function (subUrl) {
        console.log('hello get request: ' + this.server);
    
        let config = {
            headers: { 'Authorization': 'Bearer ' + this.token }  // "Bearer" should be capitalized
        };
    
        console.log(config);
    
        return axios.get(this.server + subUrl, config); // Pass `config` as the second argument
    },
    postRequest: function (subUrl, formData, that) {
        console.log('hello post request')
        console.log(formData)
        let config = {
            headers: { 'Authorization': 'Bearer ' + this.token },
        }

        return axios.post(
            this.server + subUrl,
            formData,
            config,
        ).then(function (data) {
            // console.log('in axios then')
            // console.log(data)
            data = data.data;

            // if (data.message == "Login successful") {
                return new Promise(function (resolve, reject) {
                    if (subUrl.indexOf('login') >= 0 || subUrl.indexOf('verify-email') >= 0) {
                        if (data.token) {
                            global.vm.$local.token = data.token;
                            localStorage.setItem('user_token', data.token);
                            localStorage.setItem('user_name', data.user.username)
                        } else {
                            reject(data);
                        }
                        // global.vm.$local.token = data.token;
                        // localStorage.setItem('user_token', data.token);
                        // localStorage.setItem('user_name', data.user.username)
                    }
                    //console.log("updatted token: " + global.vm.$local.token)
                    //console.log(data);

                    resolve(data);
                });
            // } else {
            //     return new Promise(function ({ }, reject) {
            //         if (subUrl.indexOf('login') >= 0 || subUrl.indexOf('register') >= 0) {
            //             global.vm.$local.token = '';
            //             localStorage.removeItem('user_token');
            //         }
            //         reject(data);
            //     });
            // }
        }).catch(function (err) {
            return new Promise(function (resolve, reject) {
                //let err = data.message;
                console.log("in catch promise")
                console.log('error', err)
                reject(err);
            });
        });
    },
    putRequest: function (subUrl, formData, that) {
        //console.log('hello post request')
        let config = {
            headers: { 'Authorization': 'Bearer ' + this.token },
        }

        return axios.put(
            this.server + subUrl,
            formData,
            config,
        );
    },
    deleteRequest: function (subUrl, that) {
        //console.log('hello post request')
        let config = {
            headers: { 'Authorization': 'Bearer ' + this.token },
        }

        return axios.delete(
            this.server + subUrl,
            config,
        );
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