import router from '@/router';
import store from '@/store'
export default {
	//页面跳转
	push(url) {
		return router.push(url);
	},
	back(){
		store.dispatch("tagsView/delView", router.currentRoute);
		router.go(-1);	
	}
}
