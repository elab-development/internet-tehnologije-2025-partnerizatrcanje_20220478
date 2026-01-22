import axios from "axios";

const server = axios.create({
    baseURL: "http://127.0.0.1:8000/api/",
    timeout: 20000,
});

const token = sessionStorage.getItem('token');
if (token) {
    server.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

export default server;
