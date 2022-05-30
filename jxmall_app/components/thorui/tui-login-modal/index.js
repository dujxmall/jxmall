import loginModalVue from './tui-login-modal.vue';
// 定义插件对象
const LoginModal = {};
// vue的install方法，用于定义vue插件
LoginModal.install = function(Vue, options) {
	const LoginModalInstance = Vue.extend(loginModalVue);
	let loginModal;
	const initInstance = () => {
		// 实例化vue实例
		loginModal = new LoginModalInstance();
		let loginModalEl = loginModal.$mount().$el;

		//#ifdef H5
		document.body.appendChild(loginModalEl);

		//#endif
	};

	// 在Vue的原型上添加实例方法，以全局调用
	Vue.prototype.$loginModal = {
		showLoginModal(flag) {
			if (!loginModal) {
				initInstance();
			}
			if (flag) {
				loginModal.showModal = true;
			} else {
				loginModal.showModal = false;
			}

			return loginModal; // 为了链式调用
		}
	};
};
export default LoginModal;
