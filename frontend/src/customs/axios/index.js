import router from '@/router';
import axios from 'axios'

const http = axios.create({
  baseURL: 'http://'+process.env.VUE_APP_API_ENDPOINT+'/api/',
  // baseURL: 'http://localhost:8000/api/',
  // baseURL: 'http://192.168.100.227:82/api/',
  // baseURL: 'http://192.168.100.227:8081/api/',
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json'
  }
});

http.interceptors.response.use((res) => { return res 
// , (error) => {
  // if(error.response.status === 401){
  //   localStorage.removeItem('userdata')
  //   localStorage.removeItem('token')
  //   router.push({name: 'login'})
  // }
  // return error
})

http.interceptors.request.use(
  config => {
    const accessToken = JSON.parse(localStorage.getItem('token'))
    if(accessToken !== null){
      // eslint-disable-next-line no-param-reassign
      config.headers.Authorization = 'Bearer '+accessToken
    }
    return config
  },
  // error => Promise.reject(error)

)

// http.interceptors.request.use(function(config){
//   let token = localStorage.getItem('token') ? JSON.parse(localStorage.getItem('token')) : ''
//   console.log(token)
//   if(token){
//     config.headers["Authorization"] = 'Bearer '+token
//     return config
//   }
// });

export default http
