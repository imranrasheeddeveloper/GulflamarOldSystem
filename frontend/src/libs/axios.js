import Vue from "vue";

// axios
import axios from "axios";

const axiosIns = axios.create({
    // You can add your headers here
    // ================================
    //  baseURL: 'https://app.gulflamar.com/api/',
    baseURL: "http://127.0.0.1:8000/api/"
    // timeout: 1000,
    // headers: {'Content-Type': 'multipart/form-data'}
});
axios.interceptors.response.use(
  (resp) => {
    let v = resp.headers['vers'] || 'default'
    if(v !== localStorage.getItem('vers') && resp.config.method == 'get'){
      localStorage.setItem('vers', v)
      window.location.reload() // For new version, simply reload on any get
     }
  return Promise.resolve(resp)
})
Vue.prototype.$http = axiosIns;

export default axiosIns;
