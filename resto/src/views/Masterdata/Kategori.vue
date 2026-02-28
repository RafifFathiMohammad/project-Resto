<template>
  <ion-page>
    <ion-content class="ion-padding">
        <h1> Kategori Management</h1>
        <p>Kelola jenis kategori di sini.</p>
        <ion-button @click="goMaster">Kembali ke Masterdata</ion-button>
      <!-- FORM -->
      <ion-card>
        <ion-card-header>
          <ion-card-title>
            {{ isEdit ? 'Edit Kategori' : 'Tambah Kategori' }}
          </ion-card-title>
        </ion-card-header>

        <ion-card-content>
          <ion-item>
            <ion-input
              v-model="kategori"
              placeholder="Jenis Kategori"
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
          <ion-card-title>Daftar Kategori</ion-card-title>
        </ion-card-header>

        <ion-card-content>
          <ion-grid>

            <!-- HEADER -->
            <ion-row class="table-header">
              <ion-col size="2">No.</ion-col>
              <ion-col size="5">Pekerjaan</ion-col>
              <ion-col size="5">Aksi</ion-col>
            </ion-row>

            <!-- DATA -->
            <ion-row
              v-for="(p, index) in kategoris"
              :key="p.id_kategori"
              class="table-row"
            >
              <ion-col size="2">{{ index + 1 }}</ion-col>
              <ion-col size="5">{{ p.kategori }}</ion-col>
              <ion-col size="5">
                <ion-button
                  size="small"
                  color="warning"
                  @click="editKategori(p)"
                >
                  Edit
                </ion-button>

                <ion-button
                  size="small"
                  color="danger"
                  @click="removeKategori(p.id_kategori)"
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
  getKategoris,
  createKategori,
  updateKategori,
  deleteKategori
} from '@/services/kategoriService'

const kategoris = ref<any[]>([])
const kategori = ref('')
const isEdit = ref(false)
const editId = ref<number | null>(null)

const loadKategoris = async () => {
  const res = await getKategoris()
  kategoris.value = res.data.data
}

const submit = async () => {
  if (!kategori.value) return

  if (isEdit.value && editId.value !== null) {
    await updateKategori(editId.value, kategori.value)
  } else {
    await createKategori(kategori.value)
  }

  resetForm()
  loadKategoris()
}

const editKategori = (p: any) => {
  isEdit.value = true
  editId.value = p.id_kategori
  kategori.value = p.kategori
}

const cancelEdit = () => {
  resetForm()
}

const resetForm = () => {
  kategori.value = ''
  isEdit.value = false
  editId.value = null
}

const removeKategori = async (id: number) => {
  if (!confirm('Hapus data ini?')) return
  await deleteKategori(id)
  loadKategoris()
}

onMounted(loadKategoris)

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

