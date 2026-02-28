<template>
  <ion-page>
    <ion-content class="ion-padding">
        <h1> Pemesanan Management</h1>
        <p>Kelola pemesanan di sini.</p>
        <ion-button @click="goMaster">Kembali ke Masterdata</ion-button>
      <!-- FORM -->
      <ion-card>
        <ion-card-header>
          <ion-card-title>
            {{ isEdit ? 'Edit Pemesanan' : 'Tambah Pemesanan' }}
          </ion-card-title>
        </ion-card-header>

        <ion-card-content>
          <ion-item>
            <ion-input
              v-model="catatan"
              placeholder="Catatan Pemesanan"
            />
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
          <ion-card-title>Daftar Pemesanan</ion-card-title>
        </ion-card-header>

        <ion-card-content>
          <ion-grid>

            <!-- HEADER -->
            <ion-row class="table-header">
              <ion-col size="2">No.</ion-col>
              <ion-col size="5">Catatan</ion-col>
              <ion-col size="5">Aksi</ion-col>
            </ion-row>

            <!-- DATA -->
            <ion-row
              v-for="(p, index) in pemesanans"
              :key="p.id_pemesanan"
              class="table-row"
            >
              <ion-col size="2">{{ index + 1 }}</ion-col>
              <ion-col size="5">{{ p.catatan }}</ion-col>
              <ion-col size="5">
                <ion-button
                  size="small"
                  color="warning"
                  @click="editPemesanan(p)"
                >
                  Edit
                </ion-button>

                <ion-button
                  size="small"
                  color="danger"
                  @click="removePemesanan(p.id_pemesanan)"
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
  getPemesanans,
  createPemesanan,
  updatePemesanan,
  deletePemesanan
} from '@/services/pemesananService'

const pemesanans = ref<any[]>([])
const catatan = ref('')
const isEdit = ref(false)
const editId = ref<number | null>(null)

const loadPemesanans = async () => {
  const res = await getPemesanans()
  pemesanans.value = res.data.data
}

const submit = async () => {
  const value = catatan.value.trim() || '-'

  if (isEdit.value && editId.value !== null) {
    await updatePemesanan(editId.value, value)
  } else {
    await createPemesanan(value)
  }

  resetForm()
  loadPemesanans()
}

const editPemesanan = (p: any) => {
  isEdit.value = true
  editId.value = p.id_pemesanan
  catatan.value = p.catatan
}

const cancelEdit = () => {
  resetForm()
}

const resetForm = () => {
  catatan.value = ''
  isEdit.value = false
  editId.value = null
}

const removePemesanan = async (id: number) => {
  if (!confirm('Hapus data ini?')) return
  await deletePemesanan(id)
  loadPemesanans()
}

onMounted(loadPemesanans)

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

