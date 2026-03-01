import { createRouter, createWebHistory } from '@ionic/vue-router';
import { RouteRecordRaw } from 'vue-router';
import Dashboard from '../views/Admin/Dashboard.vue';
import Login from '../views/Login.vue';
import MasterData from '../views/Admin/MasterData.vue';
import Transaksi from '../views/Admin/Transaksi.vue';
import Payment from '../views/Masterdata/Payment.vue';
import Role from '../views/Masterdata/Role.vue';
import Pengalaman from '../views/Masterdata/Pengalaman.vue';
import Pemesanan from '../views/Masterdata/Pemesanan.vue';
import Kategori from '../views/Masterdata/Kategori.vue';
import User from '../views/Masterdata/User.vue';
import Meja from '../views/Masterdata/Meja.vue';
import Menu from '../views/Masterdata/Menu.vue';
import Detail from '../views/Masterdata/Detail.vue';
import Update from '../views/Masterdata/Update.vue';
import Struk from '../views/Masterdata/Struk.vue';

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    redirect: '/Login'
  },
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard
  },
  {
    path: '/master-data',
    name: 'MasterData',
    component: MasterData
  },
  {
    path: '/transaksi',
    name: 'Transaksi',
    component: Transaksi
  },
  {
    path: '/payment',
    name: 'Payment',
    component: Payment
  },
  {
    path: '/role',
    name: 'Role',
    component: Role
  },
  {
    path: '/pengalaman',
    name: 'Pengalaman',
    component: Pengalaman
  },
  {
    path: '/pemesanan',
    name: 'Pemesanan',
    component: Pemesanan
  },
  {
    path: '/kategori',
    name: 'Kategori',
    component: Kategori
  },
  {
    path: '/user',
    name: 'User',
    component: User
  },
  {
    path: '/meja',
    name: 'Meja',
    component: Meja
  },
  {
    path: '/menu',
    name: 'Menu',
    component: Menu
  },
  {
    path: '/detail',
    name: 'Detail',
    component: Detail
  },
  {
    path: '/update',
    name: 'Update',
    component: Update
  },
  {
    path: '/struk',
    name: 'Struk',
    component: Struk
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router
