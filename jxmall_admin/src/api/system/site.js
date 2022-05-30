import request from '@/utils/request'
export function setSetting(data) {
	return request({
		url: '/admin/site/setting',
		method: 'post',
		data
	})
}

export function getSetting() {
	return request({
		url: '/admin/site/setting',
		method: 'get',

	})
}
