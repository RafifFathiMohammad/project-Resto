<template>
  <ion-page>
    <ion-content class="ion-padding">
        <h1>Role Management</h1>
        <p>Kelola jenis role di sini.</p>
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
              v-model="pekerjaan"
              placeholder="Jenis Role"
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
          <ion-card-title>Daftar Role</ion-card-title>
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
              v-for="(p, index) in roles"
              :key="p.id_role"
              class="table-row"
            >
              <ion-col size="2">{{ index + 1 }}</ion-col>
              <ion-col size="5">{{ p.pekerjaan }}</ion-col>
              <ion-col size="5">
                <ion-button
                  size="small"
                  color="warning"
                  @click="editRole(p)"
                >
                  Edit
                </ion-button>

                <ion-button
                  size="small"
                  color="danger"
                  @click="removeRole(p.id_role)"
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
  getRoles,
  createRole,
  updateRole,
  deleteRole
} from '@/services/roleService'

const roles = ref<any[]>([])
const pekerjaan = ref('')
const isEdit = ref(false)
const editId = ref<number | null>(null)

const loadRoles = async () => {
  const res = await getRoles()
  roles.value = res.data.data
}

const submit = async () => {
  if (!pekerjaan.value) return

  if (isEdit.value && editId.value !== null) {
    await updateRole(editId.value, pekerjaan.value)
  } else {
    await createRole(pekerjaan.value)
  }

  resetForm()
  loadRoles()
}

const editRole = (p: any) => {
  isEdit.value = true
  editId.value = p.id_role
  pekerjaan.value = p.pekerjaan
}

const cancelEdit = () => {
  resetForm()
}

const resetForm = () => {
  pekerjaan.value = ''
  isEdit.value = false
  editId.value = null
}

const removeRole = async (id: number) => {
  if (!confirm('Hapus data ini?')) return
  await deleteRole(id)
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

