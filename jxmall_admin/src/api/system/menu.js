import request from '@/utils/request'

// 查询菜单列表
export function listMenu(query) {
	return request({
		url: '/admin/admin/menu-list',
		method: 'get',
		params: query
	})
}

// 查询菜单详细
export function getMenu(menuId) {
	return request({
		url: '/admin/admin/menu',
		method: 'get',
		data: {
			menu_id: menuId
		}
	})
}

// 查询菜单下拉树结构
export function treeselect() {
	return request({
		url: '/admin/admin/menu-tree',
		method: 'get'
	})
}

// 根据角色ID查询菜单下拉树结构
export function roleMenuTreeselect(roleId) {
	return request({
		url: '/admin/admin/role-menu-tree-select',
		method: 'get',
		data: {
			role_id: roleId
		}
	})
}

// 新增菜单
export function addMenu(data) {
	return request({
		url: '/admin/admin/add-menu',
		method: 'post',
		data: data
	})
}

// 修改菜单
export function updateMenu(data) {
	return request({
		url: '/admin/admin/menu-edit',
		method: 'post',
		data: data
	})
}

// 删除菜单
export function delMenu(menuId) {
	return request({
		url: '/admin/admin/menu-delete',
		method: 'post',
		data: {
			menu_id: menuId
		}
	})
}
