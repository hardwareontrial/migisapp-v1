import http from '@/customs/axios'
import router from '@/router'

export const customHandleError = {
  namespaced: true,
  state: {
    errorcode: '',
    errormsg: '',
    error: false,
  },
  getters: {},
  mutations: {
    SET_ERROR(state, val) {
      // console.log(val)
      state.errorcode = val.response.status
      state.errormsg = val.response.data.message
      state.error = true
    },
    UNSET_ERROR(state, val) {
      state.errorcode = val
      state.errormsg = val
      state.error = false
    }
  },
  actions: {},
}

export const customHandleAuth = {
  namespaced: true,
  state: {},
  getters: {},
  mutations: {},
  actions: {
    handleLogin({commit, dispatch}, payload){
      return new Promise((resolve, reject)=>{
        commit('customHandleError/UNSET_ERROR', '', {root: true})
        http
        .post('auth/login', payload)
        .then((data)=> {
          localStorage.setItem('userdata', JSON.stringify(data.data.userdata))
          localStorage.setItem('token', JSON.stringify(data.data.token))
          resolve(data)
        })
        .catch((e)=> {
          // console.error('customHandleAuth/handleLogin :'+e)
          console.error(e)
          commit('customHandleError/SET_ERROR', e, {root: true})
          reject(e)
        })
      })
    },
    handleLogout({rootState, commit}){
      // let token = rootState.customHandleUser.token
      return new Promise((resolve, reject) => {
        commit('customHandleError/UNSET_ERROR', '', {root: true})
        http
        // .post('logout', null, { params: { token: token } })
        .post('user/logout')
        .then((data) => {
          localStorage.removeItem('userdata')
          localStorage.removeItem('token')
          resolve(data)
        })
        .catch((e)=> {
          console.error(e)
          commit('customHandleError/SET_ERROR', e, {root: true})
          reject(e)
        })
      })
    }
  },
}
