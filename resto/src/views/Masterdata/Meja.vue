<template>
  <ion-page>
    <ion-content class="ion-padding">
        <h1>Meja Management</h1>
        <p>Kelola jenis meja di sini.</p>
        <ion-button @click="goMaster">Kembali ke Masterdata</ion-button>
      <!-- FORM -->
      <ion-card>
        <ion-card-header>
          <ion-card-title>
            {{ isEdit ? 'Edit Role' : 'Tambah Role' }}
          </ion-card-title>
        </ion-card-header>

        <ion-card-content>
          <ion-item>
            <ion-input
              v-model="meja"
              placeholder="Jenis Meja"
            />
          </ion-item>

          <ion-item>
            <ion-input
              v-model="jumlah_kursi"
              placeholder="Jumlah Kursi"
            />
          </ion-item>

          <ion-item>
            <ion-select v-model="ketersediaan" placeholder="Ketersediaan">
              <ion-select-option value="1">Tersedia</ion-select-option>
              <ion-select-option value="0">Tidak Tersedia</ion-select-option>
            </ion-select>
          </ion-item>

          <ion-button
            expand="block"
            class="ion-margin-top"
            @click="submit"
          >
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

      <!-- TABEL -->
      <ion-card>
        <ion-card-header>
          <ion-card-title>Daftar Meja</ion-card-title>
        </ion-card-header>

        <ion-card-content>
          <ion-grid>

            <!-- HEADER -->
            <ion-row class="table-header">
              <ion-col size="1">No.</ion-col>
              <ion-col size="2">Meja</ion-col>
              <ion-col size="2">Jumlah Kursi</ion-col>
              <ion-col size="2">Ketersediaan</ion-col>
              <ion-col size="3">Aksi</ion-col>
            </ion-row>

            <!-- DATA -->
            <ion-row
              v-for="(p, index) in roles"
              :key="p.id_meja"
              class="table-row"
            >
              <ion-col size="1">{{ index + 1 }}</ion-col>
              <ion-col size="2">{{ p.meja }}</ion-col>
              <ion-col size="2">{{ p.jumlah_kursi }}</ion-col>
              <ion-col size="2">{{ p.ketersediaan == 1 ? 'Tersedia' : 'Tidak Tersedia' }}</ion-col>
              <ion-col size="3">
                <ion-button
                  size="small"
                  color="warning"
                  @click="editMeja(p)"
                >
                  Edit
                </ion-button>

                <ion-button
                  size="small"
                  color="danger"
                  @click="removeMeja(p.id_meja)"
                >
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
  IonItem, IonInput, IonButton
} from '@ionic/vue'

import { ref, onMounted } from 'vue'
import {
  getMejas,
  createMeja,
  updateMeja,
  deleteMeja
} from '@/services/mejaService'

const roles = ref<any[]>([])
const meja = ref('')
const jumlah_kursi = ref('')
const ketersediaan = ref(1)
const isEdit = ref(false)
const editId = ref<number | null>(null)

const loadRoles = async () => {
  const res = await getMejas()
  roles.value = res.data.data
}

const submit = async () => {
  if (!meja.value || !jumlah_kursi.value || !ketersediaan.value) return

  if (isEdit.value && editId.value !== null) {
    await updateMeja(editId.value, meja.value, jumlah_kursi.value, ketersediaan.value)
  } else {
    await createMeja(meja.value, jumlah_kursi.value, ketersediaan.value)
  }

  resetForm()
  loadRoles()
}

const editMeja = (p: any) => {
  isEdit.value = true
  editId.value = p.id_meja
  meja.value = p.meja
  jumlah_kursi.value = p.jumlah_kursi
  ketersediaan.value = p.ketersediaan
}

const cancelEdit = () => {
  resetForm()
}

const resetForm = () => {
  meja.value = ''
  jumlah_kursi.value = ''
  ketersediaan.value = 1
  isEdit.value = false
  editId.value = null
}

const removeMeja = async (id: number) => {
  if (!confirm('Hapus data ini?')) return
  await deleteMeja(id)
  loadRoles()
}

onMounted(loadRoles)

import { useRouter } from 'vue-router'
const router = useRouter()

const goMaster = () => {
  router.push('/master-data')
}
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
}
</style>

