import axios from 'axios'

const API = 'http://localhost:8000/masterdata/meja'

export const getMejas = () =>
  axios.get(`${API}/list.php`)

export const createMeja = (meja: string, jumlah_kursi: string, ketersediaan: number) =>
  axios.post(`${API}/create.php`, { meja, jumlah_kursi, ketersediaan })

export const updateMeja = (id: number, meja: string, jumlah_kursi: string, ketersediaan: number) =>
  axios.post(`${API}/update.php`, {
    id_meja: id,
    meja,
    jumlah_kursi,
    ketersediaan
  })

export const deleteMeja = (id: number) =>
  axios.post(`${API}/delete.php`, {
    id_meja: id
  })