<template>
  <ion-page>
    <ion-content class="ion-padding">
      <h1>Menu Management</h1>
      <p>Kelola data menu di sini.</p>
      <ion-button @click="goMaster">Kembali ke Masterdata</ion-button>

      <ion-card>
        <ion-card-header>
          <ion-card-title>
            {{ isEdit ? 'Edit Menu' : 'Tambah Menu' }}
          </ion-card-title>
        </ion-card-header>

        <ion-card-content>
          <ion-item>
            <ion-input v-model="nama" placeholder="Nama" />
          </ion-item>

          <ion-item>
            <ion-select v-model="id_kategori" placeholder="Pilih Kategori">
              <ion-select-option
                v-for="r in kategoris"
                :key="r.id_kategori"
                :value="r.id_kategori"
              >
                {{ r.kategori }}
              </ion-select-option>
            </ion-select>
          </ion-item>

          <ion-item>
            <ion-input
              v-model="Stok"
              type="number" 
              placeholder="Stok"
            />
          </ion-item>

          <ion-item>
            <ion-select v-model="ketersediaan" placeholder="Pilih Ketersediaan">
              <ion-select-option value="1">Tersedia</ion-select-option>
              <ion-select-option value="0">Tidak Tersedia</ion-select-option>
            </ion-select>
          </ion-item>

          <ion-item>
            <ion-input
              v-model="harga"
              type="number" 
              placeholder="Harga"
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
          <ion-card-title>Daftar Menu</ion-card-title>
        </ion-card-header>

        <ion-card-content>
          <ion-grid>
            <ion-row class="table-header">
              <ion-col size="2">No.</ion-col>
              <ion-col size="2">Nama</ion-col>
              <ion-col size="2">Kategori</ion-col>
              <ion-col size="1">Stok</ion-col> 
              <ion-col size="1">Ketersediaan</ion-col>
              <ion-col size="1">Harga</ion-col>
              <ion-col size="2">Aksi</ion-col> 
            </ion-row>

            <ion-row
              v-for="(u, index) in menus"
              :key="u.id_menu"
              class="table-row"
            >
              <ion-col size="2">{{ index + 1 }}</ion-col>
              <ion-col size="2">{{ u.nama }}</ion-col>
              <ion-col size="2">{{ u.kategori }}</ion-col>
              <ion-col size="1">{{ u.Stok }}</ion-col> 
              <ion-col size="1">{{ u.ketersediaan == 1 ? 'Tersedia' : 'Tidak Tersedia' }}</ion-col>
              <ion-col size="1">{{ u.harga }}</ion-col>
              <ion-col size="2">
                <ion-button size="small" color="warning" @click="editMenu(u)">
                  Edit
                </ion-button>
                <ion-button size="small" color="danger" @click="removeMenu(u.id_menu)">
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
  IonItem, IonInput, IonButton, IonSelect, IonSelectOption
} from '@ionic/vue'

import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { getMenus, createMenu, updateMenu, deleteMenu } from '@/services/menuService'
import { getKategoris } from '@/services/kategoriService'

const router = useRouter()
const menus = ref<any[]>([])
const kategoris = ref<any[]>([])


const nama = ref('')
const id_kategori = ref<number | null>(null)
const Stok = ref('')
const ketersediaan = ref(1)
const harga = ref('')

const isEdit = ref(false)
const editId = ref<number | null>(null)

const loadData = async () => {
  const resMenus = await getMenus()
  const resKategoris = await getKategoris()
  menus.value = resMenus.data.data
  kategoris.value = resKategoris.data.data
}

const submit = async () => {
  if (!nama.value || !id_kategori.value || !Stok.value || ketersediaan.value === null || !harga.value) return

  if (isEdit.value && editId.value !== null) {
    await updateMenu({
      id_menu: editId.value,
      nama: nama.value,
      id_kategori: id_kategori.value,
      Stok: Stok.value,
      ketersediaan: ketersediaan.value,
      harga: harga.value
    })
  } else {
    await createMenu({
      nama: nama.value,
      id_kategori: id_kategori.value,
      Stok: Stok.value,
      ketersediaan: ketersediaan.value,
      harga: harga.value
    })
  }

  resetForm()
  loadData()
}

const editMenu = (u: any) => {
  isEdit.value = true
  editId.value = u.id_menu
  nama.value = u.nama
  id_kategori.value = u.id_kategori
  Stok.value = u.Stok
  ketersediaan.value = u.ketersediaan
  harga.value = u.harga // Memasukkan harga lama ke input saat edit
}

const cancelEdit = () => {
  resetForm()
}

const resetForm = () => {
  nama.value = ''
  id_kategori.value = null
  Stok.value = ''
  ketersediaan.value = 1
  harga.value = ''
  isEdit.value = false
  editId.value = null
  window.location.reload()
}

const removeMenu = async (id: number) => {
  if (!confirm('Hapus menu ini?')) return
  await deleteMenu(id)
  loadData()
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
}

.table-row {
  border-bottom: 1px solid #eee;
  padding: 6px 0;
  display: flex;
  align-items: center;
}

ion-button {
  margin-right: 4px;
}
</style>