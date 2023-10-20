import axios from "axios";

const axiosInstance = axios.create({
    withCredentials: true
});

axiosInstance.defaults.headers['Content-Type'] = 'application/json';

export default axiosInstance;