import axios from 'axios'

const API = 'http://localhost:8000/masterdata/detail'

export const getDetails = () =>
  axios.get(`${API}/list.php`)

export const createDetail = (data: any) =>
  axios.post(`${API}/create.php`, data)

export const updateDetail = (data: any) =>
  axios.post(`${API}/update.php`, data)

export const deleteDetail = (id: number) =>
  axios.post(`${API}/delete.php`, { id_detail: id })