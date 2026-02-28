import axios from 'axios'

const API = 'http://localhost:8000/masterdata/payment'

export const getPayments = () =>
  axios.get(`${API}/list.php`)

export const createPayment = (type: string) =>
  axios.post(`${API}/create.php`, { type })

export const updatePayment = (id: number, type: string) =>
  axios.post(`${API}/update.php`, {
    id_payment: id,
    type
  })

export const deletePayment = (id: number) =>
  axios.post(`${API}/delete.php`, {
    id_payment: id
  })