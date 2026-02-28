import axios from 'axios'

const API = 'http://localhost:8000/masterdata/pengalaman'

export const getPengalamans = () =>
  axios.get(`${API}/list.php`)

export const createPengalaman = (type: string) =>
  axios.post(`${API}/create.php`, { type })

export const updatePengalaman = (id: number, type: string) =>
  axios.post(`${API}/update.php`, {
    id_pengalaman: id,
    type
  })

export const deletePengalaman = (id: number) =>
  axios.post(`${API}/delete.php`, {
    id_pengalaman: id
  })