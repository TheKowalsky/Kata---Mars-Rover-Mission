import axios from "axios";

// Configuracó per linkar-ho amb el backend
const API_BASE_URL = "http://mars-rover-mission.test";

export const api = axios.create({
  baseURL: `${API_BASE_URL}/api`,
  headers: {
    Accept: "application/json",
  },
});
// Configuracio de les peticions, afegim el token al head
api.interceptors.request.use((config) => {
  const token = localStorage.getItem("token");
  if (token) config.headers.Authorization = `Bearer ${token}`;
  return config;
});
