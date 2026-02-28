<template>
  <ion-page>
    <ion-content class="ion-padding">
      <h1>User Management</h1>
      <p>Kelola data pengguna dan hak akses di sini.</p>
      <ion-button @click="goMaster">Kembali ke Masterdata</ion-button>

      <ion-card>
        <ion-card-header>
          <ion-card-title>
            {{ isEdit ? 'Edit User' : 'Tambah User' }}
          </ion-card-title>
        </ion-card-header>

        <ion-card-content>
          <ion-item>
            <ion-input v-model="nama" placeholder="Nama" />
          </ion-item>

          <ion-item>
            <ion-input v-model="email" placeholder="Email" />
          </ion-item>

          <ion-item>
            <ion-input
              v-model="password"
              type="text" 
              placeholder="Password"
            />
          </ion-item>

          <ion-item>
            <ion-select v-model="id_role" placeholder="Pilih Role">
              <ion-select-option
                v-for="r in roles"
                :key="r.id_role"
                :value="r.id_role"
              >
                {{ r.pekerjaan }}
              </ion-select-option>
            </ion-select>
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
          <ion-card-title>Daftar User</ion-card-title>
        </ion-card-header>

        <ion-card-content>
          <ion-grid>
            <ion-row class="table-header">
              <ion-col size="1">No.</ion-col>
              <ion-col size="2">Nama</ion-col>
              <ion-col size="2">Email</ion-col>
              <ion-col size="2">Password</ion-col> 
              <ion-col size="2">Role</ion-col>
              <ion-col size="3">Aksi</ion-col> 
            </ion-row>

            <ion-row
              v-for="(u, index) in users"
              :key="u.id_user"
              class="table-row"
            >
              <ion-col size="1">{{ index + 1 }}</ion-col>
              <ion-col size="2">{{ u.nama }}</ion-col>
              <ion-col size="2">{{ u.email }}</ion-col>
              <ion-col size="2">{{ u.password }}</ion-col> <ion-col size="2">{{ u.role }}</ion-col>
              <ion-col size="3">
                <ion-button size="small" color="warning" @click="editUser(u)">
                  Edit
                </ion-button>
                <ion-button size="small" color="danger" @click="removeUser(u.id_user)">
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
import { getUsers, createUser, updateUser, deleteUser } from '@/services/userService'
import { getRoles } from '@/services/roleService'

const router = useRouter()
const users = ref<any[]>([])
const roles = ref<any[]>([])

const nama = ref('')
const email = ref('')
const password = ref('')
const id_role = ref<number | null>(null)

const isEdit = ref(false)
const editId = ref<number | null>(null)

const loadData = async () => {
  const resUsers = await getUsers()
  const resRoles = await getRoles()
  users.value = resUsers.data.data
  roles.value = resRoles.data.data
}

const submit = async () => {
  if (!nama.value || !email.value || !id_role.value || !password.value) return

  if (isEdit.value && editId.value !== null) {
    await updateUser({
      id_user: editId.value,
      nama: nama.value,
      email: email.value,
      password: password.value, 
      id_role: id_role.value
    })
  } else {
    await createUser({
      nama: nama.value,
      email: email.value,
      password: password.value,
      id_role: id_role.value
    })
  }

  resetForm()
  loadData()
}

const editUser = (u: any) => {
  isEdit.value = true
  editId.value = u.id_user
  nama.value = u.nama
  email.value = u.email
  id_role.value = u.id_role
  password.value = u.password // Memasukkan password lama ke input saat edit
}

const cancelEdit = () => {
  resetForm()
}

const resetForm = () => {
  nama.value = ''
  email.value = ''
  password.value = ''
  id_role.value = null
  isEdit.value = false
  editId.value = null
}

const removeUser = async (id: number) => {
  if (!confirm('Hapus user ini?')) return
  await deleteUser(id)
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