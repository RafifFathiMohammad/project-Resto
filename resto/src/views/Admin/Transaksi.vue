<template>
  <ion-page>
    <ion-header>
      <ion-toolbar class="custom-toolbar">
        <ion-buttons slot="start">
          <div class="nav-left">
            <ion-title class="nav-title">Transaksi</ion-title>
          </div>          
          <ion-button class="beranda-btn" @click="goHome">Beranda</ion-button>
          <ion-button class="master-btn" @click="goMaster">Masterdata</ion-button>
          <ion-button class="transaksi-btn" @click="goTransaksi">Transaksi</ion-button>
        </ion-buttons>
        <ion-buttons slot="end">
          <ion-button class="logout-btn" @click="logout">Logout</ion-button>          
        </ion-buttons>
      </ion-toolbar>
    </ion-header>

    <ion-content class="ion-padding">
      
      <ion-card>
        <ion-card-header>
          <ion-card-title>{{ isEdit_p ? 'Edit Pemesanan' : '1. Tambah Pemesanan Baru' }}</ion-card-title>
        </ion-card-header>
        <ion-card-content>
          <ion-item>
            <ion-label position="stacked">Catatan Pesanan</ion-label>
            <ion-input v-model="catatan_p" placeholder="Contoh: Tanpa sambal / Meja Pojok" />
          </ion-item>
          <ion-button expand="block" class="ion-margin-top" @click="submit_p">
            {{ isEdit_p ? 'Update' : 'Simpan Pesanan' }}
          </ion-button>
          <ion-button v-if="isEdit_p" expand="block" fill="outline" color="medium" @click="resetForm_p">Batal</ion-button>
        </ion-card-content>
      </ion-card>

      <ion-card>
        <ion-card-header>
          <ion-card-title>{{ isEdit_d ? 'Edit Item' : '2. Input Detail Menu' }}</ion-card-title>
        </ion-card-header>
        <ion-card-content>
          <ion-item>
            <ion-select v-model="id_pemesanan_d" placeholder="Pilih ID Pemesanan">
              <ion-select-option v-for="r in pemesanans" :key="r.id_pemesanan" :value="r.id_pemesanan">
                ID Order: #{{ r.id_pemesanan }} ({{ r.catatan }})
              </ion-select-option>
            </ion-select>
          </ion-item>
          <ion-item>
            <ion-select v-model="id_menu" placeholder="Pilih Menu" @ionChange="handleMenuChange">
              <ion-select-option v-for="r in menus" :key="r.id_menu" :value="r.id_menu">
                {{ r.nama }} - Rp {{ formatNumber(r.harga) }}
              </ion-select-option>
            </ion-select>
          </ion-item>
          <ion-item>
            <ion-label position="stacked">Jumlah (Qty)</ion-label>
            <ion-input v-model="jumlah" type="number" @ionInput="calculateSubtotal" />
          </ion-item>
          <ion-item>
            <ion-label position="stacked">Subtotal</ion-label>
            <ion-input :value="formatNumber(subtotal)" readonly />
          </ion-item>
          <ion-button expand="block" class="ion-margin-top" color="secondary" @click="submit_d">
            {{ isEdit_d ? 'Update Detail' : 'Tambah ke Keranjang' }}
          </ion-button>
        </ion-card-content>

        <ion-grid class="ion-padding-top">
          <ion-row class="table-header">
            <ion-col size="2">Order #</ion-col> <ion-col size="3">Menu</ion-col>
            <ion-col size="1.5">Qty</ion-col>
            <ion-col size="2.5">Subtotal</ion-col>
            <ion-col size="3">Aksi</ion-col>
          </ion-row>
          <ion-row v-for="u in details" :key="u.id_detail" class="table-row">
            <ion-col size="2">#{{ u.id_Pemesanaan || u.id_pemesanan }}</ion-col>
            <ion-col size="3">{{ u.nama_menu }}</ion-col>
            <ion-col size="1.5">{{ u.jumlah }}</ion-col>
            <ion-col size="2.5">{{ formatNumber(u.subtotal) }}</ion-col>
            <ion-col size="3">
              <ion-button size="small" color="warning" @click="editDetail(u)">E</ion-button>
              <ion-button size="small" color="danger" @click="removeDetail(u.id_detail)">X</ion-button>
            </ion-col>
          </ion-row>
        </ion-grid>
      </ion-card>

      <ion-card>
        <ion-card-header>
          <ion-card-title>3. Penyelesaian Pembayaran (Struk)</ion-card-title>
        </ion-card-header>
        <ion-card-content>
          <ion-grid>
            <ion-row>
              <ion-col size="6">
                <ion-item>
                  <ion-label position="stacked">Tipe Layanan</ion-label>
                  <ion-select v-model="id_pengalaman" placeholder="Pilih">
                    <ion-select-option v-for="exp in pengalamans" :key="exp.id_pengalaman" :value="exp.id_pengalaman">
                      {{ exp.type }}
                    </ion-select-option>
                  </ion-select>
                </ion-item>
              </ion-col>
              <ion-col size="6">
                <ion-item>
                  <ion-label position="stacked">Meja</ion-label>
                  <ion-select v-model="id_meja" :disabled="!isDineIn">
                    <ion-select-option v-for="m in mejas" :key="m.id_meja" :value="m.id_meja">
                      {{ m.meja }}
                    </ion-select-option>
                  </ion-select>
                </ion-item>
              </ion-col>
              <ion-col size="12">
                <ion-item>
                  <ion-label position="stacked">Pilih Order ID untuk Dibayar</ion-label>
                  <ion-select v-model="id_pemesanan_s">
                    <ion-select-option v-for="p in pemesanans" :key="p.id_pemesanan" :value="p.id_pemesanan">
                      Order #{{ p.id_pemesanan }}
                    </ion-select-option>
                  </ion-select>
                </ion-item>
              </ion-col>
              <ion-col size="6">
                <ion-item>
                  <ion-label position="stacked">Total Tagihan</ion-label>
                  <ion-input :value="formatNumber(total_s)" readonly color="primary" style="font-weight: bold;"></ion-input>
                </ion-item>
              </ion-col>
              <ion-col size="6">
                <ion-item>
                  <ion-label position="stacked">Status</ion-label>
                  <ion-select v-model="status_s">
                    <ion-select-option :value="0">Pending</ion-select-option>
                    <ion-select-option :value="1">Lunas</ion-select-option>
                  </ion-select>
                </ion-item>
              </ion-col>
            </ion-row>
          </ion-grid>
          <ion-button expand="block" color="success" class="ion-margin-top" @click="submit_s">
            {{ isEdit_s ? 'Update Struk' : 'Cetak & Simpan Struk' }}
          </ion-button>
        </ion-card-content>
      </ion-card>

    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { ref, onMounted, computed, watch } from 'vue'
import { useRouter } from 'vue-router'
import { 
  IonPage, IonContent, IonGrid, IonRow, IonCol, IonCard, IonCardHeader, 
  IonCardTitle, IonCardContent, IonItem, IonInput, IonButton, IonSelect, 
  IonSelectOption, IonLabel, IonHeader, IonToolbar, IonTitle, IonButtons
} from '@ionic/vue'

// Import Services
import { getstruk, createstruk, updatestruk } from '@/services/strukSrevice'
import { getPemesanans, createPemesanan, updatePemesanan } from '@/services/pemesananService'
import { getMejas } from '@/services/mejaService'
import { getUsers } from '@/services/userService' 
import { getPayments } from '@/services/paymentService'
import { getPengalamans } from '@/services/pengalamanService'
import { getMenus } from '@/services/menuService'
import { getDetails, createDetail, updateDetail, deleteDetail } from '@/services/detailService'

const router = useRouter()

// Data Lists
const pemesanans = ref<any[]>([])
const mejas = ref<any[]>([])
const users = ref<any[]>([])
const payments = ref<any[]>([])
const pengalamans = ref<any[]>([])
const menus = ref<any[]>([])
const details = ref<any[]>([])

// Form State
const catatan_p = ref('')
const isEdit_p = ref(false)
const editId_p = ref<number | null>(null)

const id_pemesanan_d = ref<number | null>(null)
const id_menu = ref<number | null>(null)
const jumlah = ref<number>(1)
const subtotal = ref<number>(0)
const selectedHarga = ref(0)
const isEdit_d = ref(false)
const editId_d = ref<number | null>(null)

const id_pemesanan_s = ref<number | null>(null)
const id_meja = ref<number | null>(null)
const id_pengalaman = ref<number | null>(null)
const total_s = ref<number>(0)
const status_s = ref<number>(0)
const isEdit_s = ref(false)
const editId_s = ref<number | null>(null)

const loadAllData = async () => {
  try {
    const [resP, resM, resU, resPy, resE, resMnu, resDet] = await Promise.all([
      getPemesanans(), getMejas(), getUsers(), 
      getPayments(), getPengalamans(), getMenus(), getDetails()
    ])
    pemesanans.value = resP.data.data
    mejas.value = resM.data.data
    users.value = resU.data.data
    payments.value = resPy.data.data
    pengalamans.value = resE.data.data
    menus.value = resMnu.data.data
    details.value = resDet.data.data
  } catch (err) { console.error(err) }
}

onMounted(loadAllData)

const submit_p = async () => {
  try {
    if (isEdit_p.value && editId_p.value) {
      await updatePemesanan(editId_p.value, catatan_p.value || '-')
    } else {
      await createPemesanan(catatan_p.value || '-')
    }
    resetForm_p(); await loadAllData()
  } catch (e) { alert("Gagal Simpan Pesanan") }
}

const resetForm_p = () => { catatan_p.value = ''; isEdit_p.value = false; editId_p.value = null }

const handleMenuChange = () => {
  const menu = menus.value.find(m => m.id_menu === id_menu.value)
  if (menu) { selectedHarga.value = menu.harga; calculateSubtotal() }
}

const calculateSubtotal = () => { subtotal.value = selectedHarga.value * (Number(jumlah.value) || 0) }

const submit_d = async () => {
  if (!id_pemesanan_d.value || !id_menu.value) return alert("Pilih Order dan Menu")
  const payload = {
    id_detail: editId_d.value,
    id_pemesanan: id_pemesanan_d.value,
    id_menu: id_menu.value,
    jumlah: jumlah.value,
    subtotal: subtotal.value,
    catatan: "-"
  }
  try {
    isEdit_d.value ? await updateDetail(payload) : await createDetail(payload)
    resetForm_d(); await loadAllData()
  } catch (e) { console.error(e) }
}

const editDetail = (u: any) => {
  isEdit_d.value = true
  editId_d.value = u.id_detail
  id_pemesanan_d.value = u.id_Pemesanaan || u.id_pemesanan
  id_menu.value = u.id_menu
  jumlah.value = u.jumlah
  subtotal.value = u.subtotal
  const menu = menus.value.find(m => m.id_menu === u.id_menu)
  selectedHarga.value = menu ? menu.harga : 0
}

const removeDetail = async (id: number) => {
  if (confirm("Hapus item ini?")) {
    await deleteDetail(id); await loadAllData()
  }
}

const resetForm_d = () => { id_menu.value = null; jumlah.value = 1; subtotal.value = 0; isEdit_d.value = false }

const isDineIn = computed(() => {
  const selected = pengalamans.value.find(e => e.id_pengalaman === id_pengalaman.value)
  return selected?.type?.toLowerCase().includes('dine in')
})

watch(id_pemesanan_s, (newId) => {
  if (newId) {
    const filtered = details.value.filter(d => (d.id_Pemesanaan == newId || d.id_pemesanan == newId))
    total_s.value = filtered.reduce((acc, curr) => acc + Number(curr.subtotal), 0)
  }
})

// PERBAIKAN LOGIKA SUBMIT STRUK
const submit_s = async () => {
  if (!id_pemesanan_s.value || !id_pengalaman.value) {
    alert("Harap pilih ID Pemesanan dan Tipe Layanan"); return;
  }

  const payload = {
    id_struk: editId_s.value,
    id_meja: id_meja.value || null,
    id_pemesanan: id_pemesanan_s.value,
    id_pengalaman: id_pengalaman.value,
    // Pastikan ID pegawai & payment ada di DB
    id_pegawai: users.value.length > 0 ? users.value[0].id_user : 1, 
    id_payment: payments.value.length > 0 ? payments.value[0].id_payment : 1,
    total: total_s.value,
    tanggal: new Date().toISOString().split('T')[0],
    status: status_s.value
  }

  try {
    isEdit_s.value ? await updatestruk(payload) : await createstruk(payload)
    alert("Struk berhasil disimpan ke database!")
    await loadAllData()
  } catch (e) { 
    console.error("Gagal simpan struk:", e)
    alert("Gagal simpan struk. Cek koneksi atau data pegawai/pembayaran.")
  }
}

const formatNumber = (num: any) => new Intl.NumberFormat('id-ID').format(num)

const logout = () => { localStorage.clear(); router.push('/login') }
const goHome = () => router.push('/dashboard')
const goMaster = () => router.push('/master-data')
const goTransaksi = () => router.push('/transaksi')
</script>

<style scoped>
.custom-toolbar { --background: #1f2937; color: white; }
.table-header { font-weight: bold; border-bottom: 2px solid #ddd; background: #f4f4f4; padding: 10px 0; }
.table-row { border-bottom: 1px solid #eee; font-size: 0.9rem; align-items: center; padding: 5px 0; }
.nav-title { color: white; }
</style>