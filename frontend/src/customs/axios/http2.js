import axios from 'axios'

const http2 = axios.create({
  baseURL: 'http://'+process.env.VUE_APP_API2_ENDPOINT+'/api/',
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json'
  }
});

export default http2