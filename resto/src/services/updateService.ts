import axios from 'axios'

const API = 'http://localhost:8000/masterdata/updatestok'

export const getUpdates = () =>
  axios.get(`${API}/list.php`)

export const createUpdate = (data: any) =>
  axios.post(`${API}/create.php`, data)

export const updateUpdate = (data: any) =>
  axios.post(`${API}/update.php`, data)

export const deleteUpdate = (id: number) =>
  axios.post(`${API}/delete.php`, { id_update: id })