import axios from 'axios';

// Configuración base para llamados a la api
const instance = axios.create({
    baseURL: 'http://127.0.0.1:8000/api/v1'
});

// Configuración del interceptor para configuración de encabezados
instance.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = 'Bearer ' + token;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Configuración de llamado a la api
export const apiCall = (method, url, data, headers) => instance({ method, url, data, headers });
