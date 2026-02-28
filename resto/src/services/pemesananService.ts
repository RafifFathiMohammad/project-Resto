import axios from 'axios'

const API = 'http://localhost:8000/masterdata/pemesanan'

export const getPemesanans = () =>
  axios.get(`${API}/list.php`)

export const createPemesanan = (catatan: string) =>
  axios.post(`${API}/create.php`, { catatan })

export const updatePemesanan = (id: number, catatan: string) =>
  axios.post(`${API}/update.php`, {
    id_pemesanan: id,
    catatan
  })

export const deletePemesanan = (id: number) =>
  axios.post(`${API}/delete.php`, {
    id_pemesanan: id
  })