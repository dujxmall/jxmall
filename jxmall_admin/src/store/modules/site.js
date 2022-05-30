import {
  getSetting,
  setSetting,
  removeSetting
} from '@/api/system/site'

const setting = JSON.parse(localStorage.getItem('site-setting')) || null
const site = {
  state: {
    setting: setting
  },
  mutations: {
    SET_SETTING: (state, setting) => {
      state.setting = setting;
      //Cookies.set('site-setting', setting);
    },
  },
  actions: {
    setting({
              commit,
              state
            }) {
      return new Promise((resolve, reject) => {
        getSetting().then(response => {
          const {
            data
          } = response
          if (!data) {
            return reject('Verification failed, please Login again.')
          }
          commit('SET_SETTING', JSON.stringify(data.setting))
          resolve(data)
        }).catch(error => {
          reject(error)
        })
      })
    },
    setSetting({
                 commit,
               }, setting) {
      return new Promise((resolve, reject) => {
        setSetting(setting).then(response => {
          if (response.code == 1) {
            return reject(response.msg)
          }
          commit('SET_SETTING', JSON.stringify(response.data.setting))
          resolve(response)
        }).catch(error => {
          reject(error)
        })
      })
    },
  }
}


export default site
