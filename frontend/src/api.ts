import axios from 'axios'
export const api = axios.create({baseURL: import.meta.env.VITE_API_BASE_URL || 'http://127.0.0.1:8000/api'})
api.interceptors.request.use(config => { const token=sessionStorage.getItem('habittrack_token'); if(token) config.headers.Authorization=`Bearer ${token}`; return config })
export async function request<T=any>(method:string,url:string,data?:unknown):Promise<T>{const {data:result}=await api.request<T>({method,url,data});return result}
