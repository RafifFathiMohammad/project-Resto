import axios from 'axios'

const API = 'http://localhost:8000/masterdata/user'

export const getUsers = () =>
  axios.get(`${API}/list.php`)

export const createUser = (data: any) =>
  axios.post(`${API}/create.php`, data)

export const updateUser = (data: any) =>
  axios.post(`${API}/update.php`, data)

export const deleteUser = (id: number) =>
  axios.post(`${API}/delete.php`, { id_user: id })