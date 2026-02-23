import axios from "axios";

const server = axios.create({
    baseURL: process.env.REACT_APP_API_URL + "/api/",
    timeout: 20000,
});

const token = sessionStorage.getItem('token');
if (token) {
    server.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

export default server;
