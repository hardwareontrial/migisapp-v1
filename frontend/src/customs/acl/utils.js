import store from "@/store"

export const isUserLoggedIn = () => {
  // return store.state.customHandleUser.user && store.state.customHandleUser.token
  return localStorage.getItem('userdata') && localStorage.getItem('token')
}