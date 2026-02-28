<template>
  <ion-page>
    <ion-content class="ion-padding">
        <h1>Pengalaman Management</h1>
        <p>Kelola jenis pengalaman di sini.</p>
        <ion-button @click="goMaster">Kembali ke Masterdata</ion-button>
      <!-- FORM -->
      <ion-card>
        <ion-card-header>
          <ion-card-title>
            {{ isEdit ? 'Edit Pengalaman' : 'Tambah Pengalaman' }}
          </ion-card-title>
        </ion-card-header>

        <ion-card-content>
          <ion-item>
            <ion-input
              v-model="type"
              placeholder="Jenis Pengalaman"
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
          <ion-card-title>Daftar Pengalaman</ion-card-title>
        </ion-card-header>

        <ion-card-content>
          <ion-grid>

            <!-- HEADER -->
            <ion-row class="table-header">
              <ion-col size="2">No.</ion-col>
              <ion-col size="5">Type</ion-col>
              <ion-col size="5">Aksi</ion-col>
            </ion-row>

            <!-- DATA -->
            <ion-row
              v-for="(p, index) in pengalamans"
              :key="p.id_pengalaman"
              class="table-row"
            >
              <ion-col size="2">{{ index + 1 }}</ion-col>
              <ion-col size="5">{{ p.type }}</ion-col>
              <ion-col size="5">
                <ion-button
                  size="small"
                  color="warning"
                  @click="editPengalaman(p)"
                >
                  Edit
                </ion-button>

                <ion-button
                  size="small"
                  color="danger"
                  @click="removePengalaman(p.id_pengalaman)"
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
  getPengalamans,
  createPengalaman,
  updatePengalaman,
  deletePengalaman
} from '@/services/pengalamanService'

const pengalamans = ref<any[]>([])
const type = ref('')
const isEdit = ref(false)
const editId = ref<number | null>(null)

const loadPengalamans = async () => {
  const res = await getPengalamans()
  pengalamans.value = res.data.data
}

const submit = async () => {
  if (!type.value) return

  if (isEdit.value && editId.value !== null) {
    await updatePengalaman(editId.value, type.value)
  } else {
    await createPengalaman(type.value)
  }

  resetForm()
  loadPengalamans()
}

const editPengalaman = (p: any) => {
  isEdit.value = true
  editId.value = p.id_pengalaman
  type.value = p.type
}

const cancelEdit = () => {
  resetForm()
}

const resetForm = () => {
  type.value = ''
  isEdit.value = false
  editId.value = null
}

const removePengalaman = async (id: number) => {
  if (!confirm('Hapus data ini?')) return
  await deletePengalaman(id)
  loadPengalamans()
}

onMounted(loadPengalamans)

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

