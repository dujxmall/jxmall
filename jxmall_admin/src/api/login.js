import request from '@/utils/request'

// 登录方法
export function login(username, password, captcha, login_type) {
  const data = {
    username,
    password,
    captcha,
    login_type
  }
  return request({
    url: '/admin/passport/login',
    headers: {
      isToken: false
    },
    method: 'post',
    data: data
  })
}

// 注册方法
export function register(data) {
  return request({
    url: '/register',
    headers: {
      isToken: false
    },
    method: 'post',
    data: data
  })
}

// 获取用户详细信息
export function getInfo() {
  return request({
    url: '/admin/admin/info',
    method: 'get'
  })
}

// 退出方法
export function logout() {
  return request({
    url: '/admin/admin/logout',
    method: 'post'
  })
}

// 获取验证码
export function getCaptchaCode() {
  return request({
    url: '/admin/passport/captcha',
    headers: {
      isToken: false
    },
    method: 'get',
    timeout: 20000
  })
}
