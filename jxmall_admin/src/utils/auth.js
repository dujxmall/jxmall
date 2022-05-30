import Cookies from 'js-cookie'

const TokenKey = 'Admin-Token'

export function getToken() {
	return Cookies.get(TokenKey)
}

export function setToken(token) {
	return Cookies.set(TokenKey, token)
}

export function removeToken() {
	return Cookies.remove(TokenKey)
}
export function getMallId() {
	const mall_id = Cookies.get('X-MALL-ID');
	return mall_id ? mall_id : 0;
}

export function setMallId(mall_id) {
	return Cookies.set('X-MALL-ID', mall_id)
}
