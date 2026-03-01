import axios from 'axios'

const API = 'http://localhost:8000/masterdata/menu'

export const getMenus = () =>
  axios.get(`${API}/list.php`)

export const createMenu = (data: any) =>
  axios.post(`${API}/create.php`, data)

export const updateMenu = (data: any) =>
  axios.post(`${API}/update.php`, data)

export const deleteMenu = (id: number) =>
  axios.post(`${API}/delete.php`, { id_menu: id })