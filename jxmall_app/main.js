import Vue from 'vue'
import App from './App'
import http from './utils/request.js'
// #ifdef H5
import wechatSdk from '@/utils/wechatJsSdk.js';
Vue.prototype.$wechatSdk = wechatSdk;
// #endif
Vue.config.productionTip = false
import share from './utils/share.js'
Vue.mixin(share)
App.mpType = 'app'

import util from './utils/util.js'
Vue.prototype.$getScene = util.getScene;
Vue.prototype.$request = http.request;
Vue.prototype.$http = http;
Vue.prototype.$showModal = util.showModal
Vue.prototype.$util = util;

import t from './utils/go.js'
Vue.prototype.$T = t;
/* import LoginModal from '@/components/thorui/tui-login-modal';
Vue.use(LoginModal); */
 
const app = new Vue({
	...App
})
app.$mount()
