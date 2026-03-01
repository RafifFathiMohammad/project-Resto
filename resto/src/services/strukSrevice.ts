import axios from 'axios'

const API = 'http://localhost:8000/masterdata/struk'

export const getstruk = () =>
  axios.get(`${API}/list.php`)

export const createstruk = (data: any) =>
  axios.post(`${API}/create.php`, data)

export const updatestruk = (data: any) =>
  axios.post(`${API}/update.php`, data)

export const deletestruk = (id: number) =>
  axios.post(`${API}/delete.php`, { id_struk: id })