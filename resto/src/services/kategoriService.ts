import axios from 'axios'

const API = 'http://localhost:8000/masterdata/kategori'

export const getKategoris = () =>
  axios.get(`${API}/list.php`)

export const createKategori = (kategori: string) =>
  axios.post(`${API}/create.php`, { kategori })

export const updateKategori = (id: number, kategori: string) =>
  axios.post(`${API}/update.php`, {
    id_kategori: id,
    kategori
  })

export const deleteKategori = (id: number) =>
  axios.post(`${API}/delete.php`, {
    id_kategori: id
  })