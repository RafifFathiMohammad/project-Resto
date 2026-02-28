<template>
  <ion-page>
    <ion-content class="ion-padding">
        <h1>Payment Management</h1>
        <p>Kelola jenis pembayaran di sini.</p>
        <ion-button @click="goMaster">Kembali ke Masterdata</ion-button>
      <!-- FORM -->
      <ion-card>
        <ion-card-header>
          <ion-card-title>
            {{ isEdit ? 'Edit Payment' : 'Tambah Payment' }}
          </ion-card-title>
        </ion-card-header>

        <ion-card-content>
          <ion-item>
            <ion-input
              v-model="type"
              placeholder="Jenis Pembayaran"
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
          <ion-card-title>Daftar Payment</ion-card-title>
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
              v-for="(p, index) in payments"
              :key="p.id_payment"
              class="table-row"
            >
              <ion-col size="2">{{ index + 1 }}</ion-col>
              <ion-col size="5">{{ p.type }}</ion-col>
              <ion-col size="5">
                <ion-button
                  size="small"
                  color="warning"
                  @click="editPayment(p)"
                >
                  Edit
                </ion-button>

                <ion-button
                  size="small"
                  color="danger"
                  @click="removePayment(p.id_payment)"
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
  getPayments,
  createPayment,
  updatePayment,
  deletePayment
} from '@/services/paymentService'

const payments = ref<any[]>([])
const type = ref('')
const isEdit = ref(false)
const editId = ref<number | null>(null)

const loadPayments = async () => {
  const res = await getPayments()
  payments.value = res.data.data
}

const submit = async () => {
  if (!type.value) return

  if (isEdit.value && editId.value !== null) {
    await updatePayment(editId.value, type.value)
  } else {
    await createPayment(type.value)
  }

  resetForm()
  loadPayments()
}

const editPayment = (p: any) => {
  isEdit.value = true
  editId.value = p.id_payment
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

const removePayment = async (id: number) => {
  if (!confirm('Hapus data ini?')) return
  await deletePayment(id)
  loadPayments()
}

onMounted(loadPayments)

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

