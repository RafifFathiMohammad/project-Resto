import axios from 'axios'

const API = 'http://localhost:8000/masterdata/role'

export const getRoles = () =>
  axios.get(`${API}/list.php`)

export const createRole = (pekerjaan: string) =>
  axios.post(`${API}/create.php`, { pekerjaan })

export const updateRole = (id: number, pekerjaan: string) =>
  axios.post(`${API}/update.php`, {
    id_role: id,
    pekerjaan
  })

export const deleteRole = (id: number) =>
  axios.post(`${API}/delete.php`, {
    id_role: id
  })