<template>
  <ion-page>
    <ion-content class="ion-padding">
      <h1>Update Stok Management</h1>
      <p>Kelola data update sok di sini.</p>
      <ion-button @click="goMaster">Kembali ke Masterdata</ion-button>

      <ion-card>
        <ion-card-header>
          <ion-card-title>
            {{ isEdit ? 'Edit Menu' : 'Tambah Menu' }}
          </ion-card-title>
        </ion-card-header>

        <ion-card-content>
          <ion-item>
            <ion-select v-model="id_menu" placeholder="Pilih Menu">
              <ion-select-option
                v-for="r in menus"
                :key="r.id_menu"
                :value="r.id_menu"
              >
                {{ r.nama }}
              </ion-select-option>
            </ion-select>
          </ion-item>

          <ion-item>
            <ion-input
              v-model="jumlah_porsi"
              type="number" 
              placeholder="Stok"
            />
          </ion-item>

          <ion-item>
            <ion-input
              v-model="tanggal_update"
              type="date" 
              placeholder="Tanggal Update"
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
          <ion-card-title>Daftar Update Stok</ion-card-title>
        </ion-card-header>

        <ion-card-content>
          <ion-grid>
            <ion-row class="table-header">
              <ion-col size="2">No.</ion-col>
              <ion-col size="2">Nama Menu</ion-col>
              <ion-col size="2">Jumlah Porsi</ion-col>
              <ion-col size="2">Tanggal Update</ion-col>
              <ion-col size="2">Aksi</ion-col> 
            </ion-row>

            <ion-row
              v-for="(u, index) in updates"
              :key="u.id_update"
              class="table-row"
            >
              <ion-col size="2">{{ index + 1 }}</ion-col>
              <ion-col size="2">{{ u.nama }}</ion-col>
              <ion-col size="2">{{ u.jumlah_porsi }}</ion-col>
              <ion-col size="2">{{ u.tanggal_update }}</ion-col>
              <ion-col size="2">
                <ion-button size="small" color="warning" @click="editUpdate(u)">
                  Edit
                </ion-button>
                <ion-button size="small" color="danger" @click="removeUpdate(u.id_update)">
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
import { getUpdates, createUpdate, updateUpdate, deleteUpdate } from '@/services/updateService'
import { getMenus } from '@/services/menuService'

const router = useRouter()
const updates = ref<any[]>([])
const menus = ref<any[]>([])

const id_menu = ref<number | null>(null)
const jumlah_porsi = ref('')
const tanggal_update = ref('')


const isEdit = ref(false)
const editId = ref<number | null>(null)

const loadData = async () => {
  const resUpdates = await getUpdates()
  updates.value = resUpdates.data.data
  const resMenus = await getMenus()
  menus.value = resMenus.data.data
}

const submit = async () => {
  if (!id_menu.value || !jumlah_porsi.value || !tanggal_update.value) return

  if (isEdit.value && editId.value !== null) {
    await updateUpdate({
      id_update: editId.value,
      id_menu: id_menu.value,
      jumlah_porsi: jumlah_porsi.value,
      tanggal_update: tanggal_update.value,

    })
  } else {
    await createUpdate({
      id_menu: id_menu.value,
      jumlah_porsi: jumlah_porsi.value,
      tanggal_update: tanggal_update.value,

    })
  }

  resetForm()
  loadData()
}

const editUpdate = (u: any) => {
  isEdit.value = true
  editId.value = u.id_update
  id_menu.value = u.id_menu
  jumlah_porsi.value = u.jumlah_porsi
  tanggal_update.value = u.tanggal_update
}

const cancelEdit = () => {
  resetForm()
}

const resetForm = () => {
  id_menu.value = null
  jumlah_porsi.value = ''
  tanggal_update.value = ''
  isEdit.value = false
  editId.value = null
  window.location.reload()
}

const removeUpdate = async (id: number) => {
  if (!confirm('Hapus update ini?')) return
  await deleteUpdate(id)
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