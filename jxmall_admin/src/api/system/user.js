import request from '@/utils/request'
import {
	parseStrEmpty
} from "@/utils/jxmall";

// 查询用户列表
export function listUser(query) {
	return request({
		url: 'admin/admin/user-list',
		method: 'get',
		data: query
	})
}

// 查询用户详细
export function getUser(userId) {
	return request({
		url: '/admin/admin/user',
		method: 'get',
		data: {
			id: userId
		}
	})
}

// 新增用户
export function addUser(data) {
	return request({
		url: '/admin/admin/user-edit',
		method: 'post',
		data: data
	})
}

// 修改用户
export function updateUser(data) {
	return request({
		url: '/system/user',
		method: 'put',
		data: data
	})
}

// 删除用户
export function delUser(userId) {
	return request({
		url: '/admin/admin/delete',
		method: 'post',
		data: {
			id: userId
		}
	})
}

// 用户密码重置
export function resetUserPwd(id, password) {
	const data = {
		id,
		password
	}
	return request({
		url: '/admin/admin/password-reset',
		method: 'post',
		data: data
	})
}



// 查询用户个人信息
export function getUserProfile() {
	return request({
		url: '/admin/admin/profile',
		method: 'get'
	})
}

// 修改用户个人信息
export function updateUserProfile(data) {
	return request({
		url: '/admin/admin/profile-update',
		method: 'post',
		data: data
	})
}

// 用户密码重置
export function updateUserPwd(password, newPassword) {
	const data = {
		password,
		newPassword
	}
	return request({
		url: '/admin/admin/password-change',
		method: 'post',
		data: data
	})
}

// 用户头像上传
export function uploadAvatar(data) {
	return request({
		url: '/admin/admin/set-avatar',
		method: 'post',
		data: data
	})
}
