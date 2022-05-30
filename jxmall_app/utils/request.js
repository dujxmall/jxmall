//公共js,以及基本方法封装 nvue里使用
import Vue from 'vue';
//var siteinfo = require("@/siteinfo.js");
import siteinfo from "@/siteinfo.js"
console.log(siteinfo);
const http = {
	toast: function (tips) {
		uni.showToast({
			title: tips || "出错啦~",
			icon: 'none',
			duration: 2000
		})
	},
	loading: function (tips) {
		uni.showLoading({
			title: tips || "加载中~",
		})
	},
	hideLoading: function (tips) {
		uni.hideLoading();
	},
	showModal: function (content, title) {
		if (!title) {
			title = '提示';
		}
		uni.showModal({
			title: title,
			content: content
		})
	},
	getApiRoot: function () {
		// #ifdef H5
		return '/api?r='; //开发 
		return window.location.protocol + "//" + window.location.host + "/web/index.php?r=";
		// #endif
		const mpwx = '1.0.0';
		const ios = '1.0.0';
		const android = '1.0.0';
		let checking_versions = uni.getStorageSync('CHECKING_VERSIONS');
		if (checking_versions && checking_versions.is_enabled == 1) {
			//#ifdef MP-WEIXIN
			if (checking_versions.mpwx == mpwx && checking_versions.api_url) {
				return checking_versions.api_url;
			}
			//#endif
		}

		return siteinfo.siteroot;

	},
	setToken: function (token) {
		uni.setStorageSync("ACCESS_TOKEN", token)
	},

	getMallId: function () {
		//#ifdef MP
		return siteinfo.mall_id;
		//#endif
		return uni.getStorageSync("X-MALL-ID") ? uni.getStorageSync("X-MALL-ID") : 1;
	},
	setMallId: function (mall_id) {
		return uni.setStorageSync("X-MALL-ID", mall_id);
	},
	getPid: function () {


		return uni.getStorageSync("X-PARENT-ID") ? uni.getStorageSync("X-PARENT-ID") : -1;
	},
	setPid: function (pid) {

		return uni.setStorageSync("X-PARENT-ID", pid);
	},

	getToken() {

		return uni.getStorageSync("ACCESS_TOKEN")
	},
	isLogin: function () {
		return uni.getStorageSync("ACCESS_TOKEN") ? true : false
	},
	logout: function () {
		uni.removeStorageSync("ACCESS_TOKEN");

		uni.reLaunch({
			url: '/pages/index/index'
		})
	},
	// 获取当前环境
	getPlatform() {
		let platform = 'H5';
		// #ifdef H5
		platform = 'H5';
		// #endif
		// #ifdef MP-WEIXIN
		platform = 'MPWX'
		// #endif
		// #ifdef APP-PLUS
		platform = 'APP'
		// #endif
		return platform;
	},
	getUrlParam: function (name, pageObj) {
		var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)')
		let url = window.location.href;
		let search = url.split('?')[1]
		if (search) {
			var r = search.substr(0).match(reg)
			if (r !== null) {
				return unescape(r[2])
			}
			return null
		} else {
			return null
		}
	},
	request: function (requestData) {
		let platform = http.getPlatform();
		let api_root = http.getApiRoot();
		let url = api_root + requestData.url,
			postData = requestData.data,
			method = requestData.method,
			showLoading = requestData.showLoading;
		//接口请求
		method = (method === 'post' || method === 'POST') ? 'POST' : 'GET';
		if (showLoading) {
			uni.showLoading({
				mask: true,
				title: '请稍候...'
			})
		}

		let access_token = http.getToken();
		return new Promise((resolve, reject) => {
			const header = {
				'content-type': 'application/x-www-form-urlencoded',
				'x-mall-id': http.getMallId(),
				'x-access-token': access_token,
				'x-parent-id': http.getPid(),
				'x-platform': platform
			};
			header.Cookie = uni.getStorageSync('COOKIES');
			uni.request({
				url: url,
				data: postData,
				header: header,
				method: method,
				dataType: 'json',
				success: (res) => {
					let cookies = res.header['Set-Cookie'];
					if (cookies && cookies != 'undefined') {
						console.log(cookies);
						uni.setStorageSync('COOKIES', cookies);
					}
					if (res.statusCode === 500) {
						fetch.toast("服务错误~")
						reject(res)
					}
					showLoading && uni.hideLoading()
					if (res.data.code == -1) {
						uni.removeStorageSync('WECHAT_USER_INFO');
						uni.removeStorageSync('ACCESS_TOKEN');
					}
					resolve(res.data)
				},
				fail: (res) => {
					fetch.toast("网络不给力，请稍后再试~")
					reject(res)
				}
			})
		})
	},
	// 上传头像
	uploadFile: function (requestData) {
		let {
			serverUrl,
			file,
			fileKeyName
		} = requestData;
		let platform = fetch.getPlatform();
		const access_token = fetch.getToken() || "";
		return new Promise((resolve, reject) => {
			uni.uploadFile({
				url: serverUrl,
				name: fileKeyName,
				header: {
					'x-mall-id': http.getMallId(),
					'x-access-token': access_token,
					'x-parent-id': http.getPid(),
					'x-platform': platform
				},
				formData: {},
				filePath: file,
				success: function (res) {
					if (res.statusCode === 500) {
						fetch.toast("服务错误~")
						reject(res)
					}
					resolve(JSON.parse(res.data))
				},
				fail: function (err) {
					fetch.toast("网络不给力，请稍后再试~")
					reject(err)
				}
			})

		})

	}
}

module.exports = http
