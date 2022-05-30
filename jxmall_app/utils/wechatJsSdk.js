import {
	request,
	getMallId,
	getUrlParam
} from './request.js'

var jweixin = require('jweixin-module');
export default {
	getUrl() {
		let pages = getCurrentPages();
		let curPage = pages[pages.length - 1];
		let url = curPage.$route.fullPath;
		return url;
	},
	//判断是否在微信中  
	isWechat: function() {
		var ua = window.navigator.userAgent.toLowerCase();
		if (ua.match(/micromessenger/i) == 'micromessenger') {
			// console.log('是微信客户端')
			return true;
		} else {
			// console.log('不是微信客户端')
			return false;
		}
	},
	//初始化sdk配置  
	initJssdk: function(callback, url) {
		// 这是我这边封装的 request 请求工具，实际就是 uni.request 方法。
		let root_url = window.location.href.split('#')[0];
		request({
			url: '/api/wechat/jssdk',
			data: {
				url: root_url
			}
		}).then((res) => {
			if (res.code == 0) {
				let sdk = res.data.jssdk;
				jweixin.config({
					debug: sdk.debug,
					appId: sdk.appId,
					timestamp: sdk.timestamp,
					nonceStr: sdk.nonceStr,
					signature: sdk.signature,
					jsApiList: sdk.jsApiList
				});
				uni.setStorageSync('DEFAULT_SHARE', res.data.default_share)
				//配置完成后，再执行分享等功能  
				if (callback) {
					callback(sdk);
				}
			}
		});
	},
	//在需支付
	pay: function(data, success_url, fail_url, paramas) {

		if (!this.isWechat()) {
			return;
		}
		return new Promise((resolve, reject) => {
			//每需要重新初始化
			this.initJssdk(function(sdk) {
				jweixin.ready(function() {
					//分享给朋友接口  
					jweixin.chooseWXPay({
						appId: data.appId,
						timestamp: data.timestamp, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
						nonceStr: data.nonceStr, // 支付签名随机串，不长于 32 位
						package: data.package, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=\*\*\*）
						signType: data.signType, // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
						paySign: data.paySign, // 支付签名
						success: function(res) {
							resolve(res)
						},
						fail: function(e) {
							reject(e);
						}
					});
				});
			});
		});

	},

	share: function(param, diy) {

		if (!this.isWechat()) {
			return;
		}
		let default_share = uni.getStorageSync('DEFAULT_SHARE')
		let title = '',
			desc = '',
			link = '',
			imgUrl = '';
		if (default_share) {
			title = default_share.name;
			desc = default_share.detail;
			link = '';
			imgUrl = default_share.logo;
		}
		if (diy) {
			if (diy.share_title) {
				title = diy.share_title;
			}
			if (diy.share_desc) {
				desc = diy.share_desc;
			}
			if (diy.share_pic) {
				imgUrl = diy.share_pic;
			}
		}
		// 获取当前 URL
		let url = window.location.href.split("#")[0];
		// 截取 URL ?code 前面 URL
		url = url.split('?code')[0];
		// 获取 当前商城id
		let mall_id = getMallId();

		// 判断 param 是否存在 存在则把参数里面字符截取出来 追加在 url 里
		if (param) {
			param = param.split('&mall_id')[0];
			url = `${url}#${param}`;
		}
		// param 存在则 mall_id 前为 & 否则 mall_id 前为 ?
		let routes = getCurrentPages(); // 获取当前打开过的页面路由数组
		let curRoute = routes[routes.length - 1].$route.fullPath
		if (getUrlParam('mall_id')) {
			url = param ? `${url}&mall_id=${mall_id}` : `${url}#${curRoute}`;
		} else {
			if (curRoute == "/") {
				curRoute = "/pages/index/index"
			}
			if (curRoute.indexOf('?') !== -1) {
				url = param ? `${url}&mall_id=${mall_id}` : `${url}#${curRoute}&mall_id=${mall_id}`;
			} else {
				url = param ? `${url}&mall_id=${mall_id}` : `${url}#${curRoute}?mall_id=${mall_id}`;
			}

		}
		// 如果当前用户数据存在 则获取当前用户的id 追加在 url 末尾 为 pid的值
		// 获取当前用户
		let user = uni.getStorageSync("USER") ? uni.getStorageSync("USER") : {};
		if (user) {
			//	url = url + `&pid=${user.id}`
		}
		/////////////////////////////////////////////////////////////////

		let id = 0;

		if (!user || user.id == '' || user.id == undefined) {
			id = 0;
		} else {
			id = user.id;
		}

		let arr = url.split('?');
		let tempUrl = arr[0];
		let arr2 = [];
		if (arr.length > 1) {
			let paramas = arr[1];
			arr2 = paramas.split('&');
		}
		if (arr2.length > 0) {
			let flag = false;
			for (let i in arr2) {
				let item = arr2[i];
				if (item.indexOf("pid=") != -1) {
					item = 'pid=' + id;
					flag = true;
				}
				if (i == 0) {
					tempUrl += '?' + item
				}
				if (i > 0) {
					tempUrl += '&' + item
				}
			}
			if (!flag) {
				tempUrl += '&pid=' + id;
			}
			url = tempUrl;
		} else {
			url += "?pid=" + id
		}
		//每需要重新初始化
		this.initJssdk(function(sdk) {
			jweixin.ready((e) => {
				// 分享给朋友
				jweixin.updateAppMessageShareData({
					title: title, // 分享标题
					desc: desc, // 分享描述
					link: url, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
					imgUrl: imgUrl, // 分享图标
					success: function(e) {
						// 用户点击了分享后执行的回调函数
					}
				});
				jweixin.updateTimelineShareData({
					title: title, // 分享标题
					desc: desc, // 分享描述
					link: url, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
					imgUrl: imgUrl, // 分享图标
					success: function(e) {
						// 用户点击了分享后执行的回调函数
					}
				});

			})
		}, url)
	},
	//在需要自定义分享的页面中调用  
	shareUrl: function(data, url) {
		url = url ? url : window.location.href;
		if (!this.isWechat()) {
			return;
		}
		console.log('----------------------');
		console.log(url);
		//每次都需要重新初始化配置，才可以进行分享  
		this.initJssdk(function(signData) {
			jweixin.ready(function() {
				var shareData = {
					title: data && data.title ? data.title : signData.site_name,
					desc: data && data.desc ? data.desc : signData.site_description,
					link: url,
					imgUrl: data && data.img ? data.img : signData.site_logo,
					success: function(res) {
						//用户点击分享后的回调，这里可以进行统计，例如分享送金币之类的  
						// post('/api/member/share');
					},
					cancel: function(res) {}
				};
				//分享给朋友接口  
				jweixin.onMenuShareAppMessage(shareData);
				//分享到朋友圈接口  
				jweixin.onMenuShareTimeline(shareData);
			});
		}, url);
	},
	//在需要定位页面调用
	location: function(callback) {
		if (!this.isWechat()) {
			console.log('不是微信客户端')
			return;
		}
		// console.info('定位')
		this.initJssdk(function(res) {
			jweixin.ready(function() {
				console.info('定位ready')
				jweixin.getLocation({
					type: 'gcj02', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
					success: function(res) {
						// console.log(res);
						callback(res)
					},
					fail: function(res) {
						console.log(res)
					},
					// complete:function(res){
					//     console.log(res)
					// }
				});
			});
		});
	}
}
