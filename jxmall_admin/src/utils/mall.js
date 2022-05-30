import Cookies from 'js-cookie'
const x_mall_id = 'x-mall-id'
export function getMallId() {
	return 1
}
export function setMallId(mall_id) {
	return Cookies.set(x_mall_id, 1)
}
export function removeMallId() {f
	return Cookies.remove(x_mall_id)
}
