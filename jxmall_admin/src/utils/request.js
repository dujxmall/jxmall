import axios from 'axios'
import {
	Notification,
	MessageBox,
	Message,
	Loading
} from 'element-ui'
import store from '@/store'
import {
	getToken,
	removeToken,
	getMallId
} from '@/utils/auth'
import errorCode from '@/utils/errorCode'
import {
	tansParams,
	blobValidate
} from "@/utils/jxmall";
import cache from '@/plugins/cache'
import {
	saveAs
} from 'file-saver'



let downloadLoadingInstance;
// 是否显示重新登录
export let isRelogin = {
	show: false
};

axios.defaults.headers['Content-Type'] = 'application/x-www-form-urlencoded'
import qs from 'qs'
if (process.env.NODE_ENV === 'development') {
	axios.defaults.baseURL = '/api?' + 'r='
} else if (process.env.NODE_ENV === 'debug') {
	axios.defaults.baseURL = ''
} else if (process.env.NODE_ENV === 'production') {
	axios.defaults.baseURL = window.location.protocol + '//' + window.location.host + '/web/index.php?r=' //独立版
}
// 创建axios实例
const service = axios.create({
	// axios中请求配置有baseURL选项，表示请求URL公共部分
	baseURL: axios.defaults.baseURL,
	// 超时
	timeout: 100000
})

// request拦截器
service.interceptors.request.use(config => {
	// 是否需要设置 token
	const isToken = (config.headers || {}).isToken === false
	// 是否需要防止数据重复提交
	const isRepeatSubmit = (config.headers || {}).repeatSubmit === false
	if (getToken() && !isToken) {
		config.headers['x-admin-token'] = getToken() // 让每个请求携带自定义token 请根据实际情况自行修改
	}
	config.headers['x-mall-id'] = getMallId();
	if (config.method === 'post' || config.method === 'POST') {
		if (config.data) {
			config.data = qs.stringify(config.data)
		}
	} else {
		config.params = config.data;
	}
	if (!config.headers['Content-Type']) {
		config.headers['Content-Type'] = 'application/x-www-form-urlencoded'
	}
	return config
}, error => {
	console.log(error)
	Promise.reject(error)
})

// 响应拦截器
service.interceptors.response.use(res => {
		// 未设置状态码则默认成功状态
		const code = res.data.code;
		// 获取错误信息
		const msg = errorCode[code] || res.data.msg || errorCode['default']
		// 二进制数据则直接返回
		if (res.request.responseType === 'blob' || res.request.responseType === 'arraybuffer') {
			return res.data
		}
		if (code === -1) {
			removeToken();
			isRelogin.show = true;
			MessageBox.confirm('登录状态已过期，您可以继续留在该页面，或者重新登录', '系统提示', {
				confirmButtonText: '重新登录',
				cancelButtonText: '取消',
				type: 'warning'
			}).then(() => {
				location.href = '/admin';
			}).catch(() => {
				isRelogin.show = false;
			});
			//return Promise.reject('无效的会话，或者会话已过期，请重新登录。')
			return Promise.reject(res.data)
		} else if (code === 1) {
			Message({
				message: msg,
				type: 'error'
			})
			return Promise.reject(res.data)
		} else if (code !== 0) {
			Notification.error({
				title: msg
			})
			return Promise.reject('error')
		} else {
			return res.data
		}
	},
	error => {
		console.log('err' + error)
		let {
			message
		} = error;
		if (message == "Network Error") {
			message = "后端接口连接异常";
		} else if (message.includes("timeout")) {
			message = "系统接口请求超时";
		} else if (message.includes("Request failed with status code")) {
			message = "系统接口" + message.substr(message.length - 3) + "异常";
		}
		Message({
			message: message,
			type: 'error',
			duration: 5 * 1000
		})
		return Promise.reject(error)
	}
)

// 通用下载方法
export function download(url, params, filename) {
	downloadLoadingInstance = Loading.service({
		text: "正在下载数据，请稍候",
		spinner: "el-icon-loading",
		background: "rgba(0, 0, 0, 0.7)",
	})
	return service.post(url, params, {
		transformRequest: [(params) => {
			return tansParams(params)
		}],
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
			'x-mall-id': getMallId()
		},
		responseType: 'blob'
	}).then(async (data) => {
		const isLogin = await blobValidate(data);
		if (isLogin) {
			const blob = new Blob([data])
			saveAs(blob, filename)
		} else {
			const resText = await data.text();
			const rspObj = JSON.parse(resText);
			const errMsg = errorCode[rspObj.code] || rspObj.msg || errorCode['default']
			Message.error(errMsg);
		}
		downloadLoadingInstance.close();
	}).catch((r) => {
		console.error(r)
		Message.error('下载文件出现错误，请联系管理员！')
		downloadLoadingInstance.close();
	})
}

export default service
