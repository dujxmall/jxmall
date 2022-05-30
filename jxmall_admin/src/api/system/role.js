import request from '@/utils/request'

// 查询角色列表
export function listRole(query) {
	return request({
		url: '/admin/admin/roles',
		method: 'get',
		params: query
	})
}

// 查询角色详细
export function getRole(roleId) {
	return request({
		url: '/admin/admin/role',
		method: 'get',
		data: {
			role_id: roleId
		}
	})
}

// 新增角色
export function roleEdit(data) {
	return request({
		url: '/admin/admin/role-edit',
		method: 'post',
		data: data
	})
}

// 修改角色
export function updateRole(data) {
	return request({
		url: '/system/role',
		method: 'put',
		data: data
	})
}

// 角色数据权限
export function dataScope(data) {
	return request({
		url: '/system/role/dataScope',
		method: 'put',
		data: data
	})
}

// 角色状态修改
export function changeRoleStatus(roleId, status) {
	const data = {
		roleId,
		status
	}
	return request({
		url: '/system/role/changeStatus',
		method: 'put',
		data: data
	})
}

// 删除角色
export function delRole(roleId) {
	return request({
		url: '/admin/admin/role-delete',
		method: 'post',
		data: {
			role_id: roleId
		}
	})
}

// 查询角色已授权用户列表
export function allocatedUserList(query) {
	return request({
		url: '/system/role/authUser/allocatedList',
		method: 'get',
		params: query
	})
}

// 查询角色未授权用户列表
export function unallocatedUserList(query) {
	return request({
		url: '/system/role/authUser/unallocatedList',
		method: 'get',
		params: query
	})
}

// 取消用户授权角色
export function authUserCancel(data) {
	return request({
		url: '/system/role/authUser/cancel',
		method: 'put',
		data: data
	})
}

// 批量取消用户授权角色
export function authUserCancelAll(data) {
	return request({
		url: '/system/role/authUser/cancelAll',
		method: 'put',
		params: data
	})
}

// 授权用户选择
export function authUserSelectAll(data) {
	return request({
		url: '/system/role/authUser/selectAll',
		method: 'put',
		params: data
	})
}
