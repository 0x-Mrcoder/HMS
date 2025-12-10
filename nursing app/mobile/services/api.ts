import axios from 'axios';
import * as SecureStore from 'expo-secure-store';

// REPLACE WITH YOUR COMPUTER'S LOCAL IP ADDRESS if running on physical device
// For Android Emulator use '10.0.2.2'
// For iOS Simulator use 'localhost'
const API_URL = 'http://10.36.225.156:8000/api/nursing';

const api = axios.create({
    baseURL: API_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

api.interceptors.request.use(async (config) => {
    const token = await SecureStore.getItemAsync('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export default api;
