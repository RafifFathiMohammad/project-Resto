<template>
  <ion-page>
    <ion-content class="ion-padding">
      <h1>Detail Pemesanan Management</h1>
      <p>Kelola data detail pemesanan di sini.</p>
      <ion-button @click="goMaster">Kembali ke Masterdata</ion-button>

      <ion-card>
        <ion-card-header>
          <ion-card-title>
            {{ isEdit ? 'Edit Detail' : 'Tambah Detail' }}
          </ion-card-title>
        </ion-card-header>

        <ion-card-content>
          <ion-item>
            <ion-select v-model="id_pemesanan" placeholder="Pilih Pemesanan">
              <ion-select-option
                v-for="r in pemesanans"
                :key="r.id_pemesanan"
                :value="r.id_pemesanan"
              >
                ID Order: {{ r.id_pemesanan }}
              </ion-select-option>
            </ion-select>
          </ion-item>

          <ion-item>
            <ion-select v-model="id_menu" placeholder="Pilih Menu" @ionChange="handleMenuChange">
              <ion-select-option
                v-for="r in menus"
                :key="r.id_menu"
                :value="r.id_menu"
              >
                {{ r.nama }} (Rp {{ formatNumber(r.harga) }})
              </ion-select-option>
            </ion-select>
          </ion-item>

          <ion-item>
            <ion-input
              v-model="jumlah"
              type="number" 
              placeholder="Jumlah"
              @ionInput="calculateSubtotal"
            />
          </ion-item>

          <ion-item>
            <ion-input
              v-model="subtotal"
              type="number" 
              placeholder="Subtotal"
              readonly
            />
            <ion-note slot="helper">Subtotal dihitung otomatis (Harga x Jumlah)</ion-note>
          </ion-item>

          <ion-item>
            <ion-input
              v-model="catatan"
              placeholder="Catatan (Kosongkan jika tidak ada)"
            />
          </ion-item>

          <ion-button expand="block" class="ion-margin-top" @click="submit">
            {{ isEdit ? 'Update' : 'Simpan' }}
          </ion-button>

          <ion-button
            v-if="isEdit"
            expand="block"
            fill="outline"
            color="medium"
            class="ion-margin-top"
            @click="cancelEdit"
          >
            Batal
          </ion-button>
        </ion-card-content>
      </ion-card>

      <ion-card>
        <ion-card-header>
          <ion-card-title>Daftar Detail Pemesanan</ion-card-title>
        </ion-card-header>

        <ion-card-content>
          <ion-grid>
            <ion-row class="table-header">
              <ion-col size="1">No.</ion-col>
              <ion-col size="2">Order ID</ion-col>
              <ion-col size="2">Menu</ion-col>
              <ion-col size="1">Qty</ion-col> 
              <ion-col size="2">Subtotal</ion-col>
              <ion-col size="2">Catatan</ion-col>
              <ion-col size="2">Aksi</ion-col> 
            </ion-row>

            <ion-row
              v-for="(u, index) in details"
              :key="u.id_detail"
              class="table-row"
            >
              <ion-col size="1">{{ index + 1 }}</ion-col>
              <ion-col size="2">#{{ u.id_Pemesanaan }}</ion-col>
              <ion-col size="2">{{ u.nama_menu }}</ion-col>
              <ion-col size="1">{{ u.jumlah }}</ion-col> 
              <ion-col size="2">Rp {{ formatNumber(u.subtotal) }}</ion-col>
              <ion-col size="2">
                <ion-badge :color="u.catatan === '-' ? 'medium' : 'primary'">
                  {{ u.catatan }}
                </ion-badge>
              </ion-col>
              <ion-col size="2">
                <ion-button size="small" color="warning" @click="editDetail(u)">
                  Edit
                </ion-button>
                <ion-button size="small" color="danger" @click="removeDetail(u.id_detail)">
                  Hapus
                </ion-button>
              </ion-col>
            </ion-row>

          </ion-grid>
        </ion-card-content>
      </ion-card>

    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import {
  IonPage, IonContent, IonGrid, IonRow, IonCol,
  IonCard, IonCardHeader, IonCardTitle, IonCardContent,
  IonItem, IonInput, IonButton, IonSelect, IonSelectOption,
  IonNote, IonBadge
} from '@ionic/vue'

import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { getDetails, createDetail, updateDetail, deleteDetail } from '@/services/detailService'
import { getMenus } from '@/services/menuService'
import { getPemesanans } from '@/services/pemesananService'

const router = useRouter()
const details = ref<any[]>([])
const menus = ref<any[]>([])
const pemesanans = ref<any[]>([])

const id_pemesanan = ref<number | null>(null)
const id_menu = ref<number | null>(null)
const jumlah = ref<any>('')
const subtotal = ref<any>('')
const catatan = ref('')
const selectedHarga = ref(0) // Untuk menyimpan harga menu yang dipilih

const isEdit = ref(false)
const editId = ref<number | null>(null)

// Fitur hitung total otomatis (Computed Property)
const totalKeseluruhan = computed(() => {
  return details.value.reduce((acc, curr) => acc + Number(curr.subtotal), 0)
})

const loadData = async () => {
  const resDetails = await getDetails()
  const resPemesanans = await getPemesanans()
  const resMenus = await getMenus()
  details.value = resDetails.data.data
  pemesanans.value = resPemesanans.data.data
  menus.value = resMenus.data.data
}

// Fungsi saat menu dipilih (Mendapatkan harga menu)
const handleMenuChange = () => {
  const menu = menus.value.find(m => m.id_menu === id_menu.value)
  if (menu) {
    selectedHarga.value = menu.harga
    calculateSubtotal()
  }
}

// Fungsi hitung subtotal otomatis
const calculateSubtotal = () => {
  if (id_menu.value && jumlah.value) {
    subtotal.value = selectedHarga.value * Number(jumlah.value)
  } else {
    subtotal.value = 0
  }
}

const submit = async () => {
  // Validasi input
  if (!id_pemesanan.value || !id_menu.value || !jumlah.value) {
    alert('Harap isi ID Pemesanan, Menu, dan Jumlah!')
    return
  }

  // Fitur: Jika catatan kosong isi dengan "-"
  const validatedCatatan = catatan.value.trim() === "" ? "-" : catatan.value;

  const dataPayload = {
    id_detail: editId.value,
    id_pemesanan: id_pemesanan.value,
    id_menu: id_menu.value,
    jumlah: jumlah.value,
    subtotal: subtotal.value,
    catatan: validatedCatatan
  }

  if (isEdit.value && editId.value !== null) {
    await updateDetail(dataPayload)
  } else {
    await createDetail(dataPayload)
  }

  resetForm()
  loadData()
}

const editDetail = (u: any) => {
  isEdit.value = true
  editId.value = u.id_detail
  id_pemesanan.value = u.id_Pemesanaan // Sesuai field list.php
  id_menu.value = u.id_menu
  jumlah.value = u.jumlah
  subtotal.value = u.subtotal
  // Jika catatan adalah "-", tampilkan kosong agar user bisa isi
  catatan.value = u.catatan === "-" ? "" : u.catatan

  // Cari harga menu untuk kalkulasi ulang jika user ubah jumlah saat edit
  const menu = menus.value.find(m => m.id_menu === u.id_menu)
  selectedHarga.value = menu ? menu.harga : 0
}

const cancelEdit = () => {
  resetForm()
}

const resetForm = () => {
  id_pemesanan.value = null
  id_menu.value = null
  jumlah.value = ''
  subtotal.value = ''
  catatan.value = ''
  selectedHarga.value = 0
  isEdit.value = false
  editId.value = null
  window.location.reload()
}

const removeDetail = async (id: number) => {
  if (!confirm('Hapus detail ini?')) return
  await deleteDetail(id)
  loadData()
}

const formatNumber = (num: any) => {
  return new Intl.NumberFormat('id-ID').format(num)
}

const goMaster = () => {
  router.push('/master-data')
}

onMounted(loadData)
</script>

<style scoped>
.table-header {
  font-weight: bold;
  border-bottom: 2px solid #ccc;
  padding-bottom: 8px;
  background-color: #f9f9f9;
}

.table-row {
  border-bottom: 1px solid #eee;
  padding: 6px 0;
  display: flex;
  align-items: center;
}

.total-row {
  margin-top: 15px;
  padding-top: 10px;
  border-top: 2px dashed #ccc;
  font-size: 1.1rem;
}

ion-button {
  margin-right: 4px;
}

ion-badge {
  font-size: 0.8rem;
}
</style>