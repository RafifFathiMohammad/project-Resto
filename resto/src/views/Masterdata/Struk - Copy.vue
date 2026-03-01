<template>
  <ion-page>
    <ion-content class="ion-padding">
      <h1>Payment Receipt Management</h1>
      <p>Kelola data struk pembayaran di sini.</p>
      <ion-button @click="goMaster">Kembali ke Masterdata</ion-button>

      <ion-card>
        <ion-card-header>
          <ion-card-title>{{ isEdit ? 'Edit Struk' : 'Tambah Struk' }}</ion-card-title>
        </ion-card-header>

        <ion-card-content>
          <ion-grid>
            <ion-row>

            <ion-col size="6">
              <ion-item>
                <ion-label position="stacked">Pengalaman</ion-label>
                <ion-select v-model="id_Pengalaman" placeholder="Pilih Pengalaman">
                  <ion-select-option v-for="exp in pengalamans" :key="exp.id_Pengalaman" :value="exp.id_Pengalaman">
                    {{ exp.type }}
                  </ion-select-option>
                </ion-select>
              </ion-item>
            </ion-col>

            <ion-col size="6">
              <ion-item>
                <ion-label position="stacked">Pilih Meja</ion-label>
                <ion-select v-model="id_meja" placeholder="Pilih Meja" :disabled="!isDineIn">
                  <ion-select-option v-for="m in mejas" :key="m.id_meja" :value="m.id_meja">
                    Meja {{ m.meja }}
                  </ion-select-option>
                </ion-select>
              </ion-item>
            </ion-col>

              <ion-col size="6">
                <ion-item>
                  <ion-label position="stacked">Pemesanan</ion-label>
                  <ion-select v-model="id_pemesanan" placeholder="Pilih ID Pemesanan">
                    <ion-select-option v-for="p in pemesanans" :key="p.id_pemesanan" :value="p.id_pemesanan">
                      Order #{{ p.id_pemesanan }}
                    </ion-select-option>
                  </ion-select>
                </ion-item>
              </ion-col>

              <ion-col size="6">
                <ion-item>
                  <ion-label position="stacked">Pegawai (Kasir)</ion-label>
                  <ion-select v-model="id_pegawai" placeholder="Pilih Pegawai">
                    <ion-select-option v-for="u in users" :key="u.id_user" :value="u.id_user">
                      {{ u.nama }}
                    </ion-select-option>
                  </ion-select>
                </ion-item>
              </ion-col>

              <ion-col size="6">
                <ion-item>
                  <ion-label position="stacked">Metode Bayar</ion-label>
                  <ion-select v-model="id_payment" placeholder="Pilih Payment">
                    <ion-select-option v-for="py in payments" :key="py.id_payment" :value="py.id_payment">
                      {{ py.type }}
                    </ion-select-option>
                  </ion-select>
                </ion-item>
              </ion-col>

              <ion-col size="6">
                <ion-item>
                  <ion-label position="stacked">Total Harga</ion-label>
                  <ion-input 
                    v-model="total" 
                    type="number" 
                    placeholder="0" 
                    :readonly="true"
                  ></ion-input>
                </ion-item>
              </ion-col>

              <ion-col size="6">
                <ion-item>
                  <ion-label position="stacked">Tanggal</ion-label>
                  <ion-input v-model="tanggal" type="date" />
                </ion-item>
              </ion-col>

              <ion-col size="6">
                <ion-item>
                  <ion-label position="stacked">Status</ion-label>
                  <ion-select v-model="status" placeholder="Pilih Status">
                    <ion-select-option :value="0">Pending</ion-select-option>
                    <ion-select-option :value="1">Lunas</ion-select-option>
                  </ion-select>
                </ion-item>
              </ion-col>

            </ion-row>
          </ion-grid>

          <ion-button expand="block" class="ion-margin-top" @click="submit">
            {{ isEdit ? 'Update Struk' : 'Simpan Struk' }}
          </ion-button>
          <ion-button v-if="isEdit" expand="block" fill="outline" color="medium" @click="cancelEdit">
            Batal
          </ion-button>
        </ion-card-content>
      </ion-card>

      <ion-card>
        <ion-card-header>
          <ion-card-title>Daftar Struk Pembayaran</ion-card-title>
        </ion-card-header>
        <ion-card-content>
          <div style="overflow-x: auto;">
            <ion-grid>
              <ion-row class="table-header">
                <ion-col size="0.5">No</ion-col>
                <ion-col size="1">Meja</ion-col>
                <ion-col size="1.5">Order</ion-col>
                <ion-col size="1.5">Pengalaman</ion-col>
                <ion-col size="1.5">Kasir</ion-col>
                <ion-col size="1.5">Payment</ion-col>
                <ion-col size="1.5">Total</ion-col>
                <ion-col size="1">Status</ion-col>
                <ion-col size="2">Aksi</ion-col>
              </ion-row>

              <ion-row v-for="(s, index) in struks" :key="s.id_struk" class="table-row">
                <ion-col size="0.5">{{ index + 1 }}</ion-col>
                <ion-col size="1">{{ s.meja }}</ion-col>
                <ion-col size="1.5">#{{ s.id_pemesanan }}</ion-col>
                <ion-col size="1.5">{{ s.Pengalaman }}</ion-col>
                <ion-col size="1.5">{{ s.pegawai }}</ion-col>
                <ion-col size="1.5">{{ s.payment }}</ion-col>
                <ion-col size="1.5">Rp {{ Number(s.total).toLocaleString() }}</ion-col>
                <ion-col size="1">
                  <ion-badge :color="s.status == 1 ? 'success' : 'warning'">
                    {{ s.status == 1 ? 'Lunas' : 'Pending' }}
                  </ion-badge>
                </ion-col>
                <ion-col size="2">
                  <ion-button size="small" color="warning" @click="editStruk(s)">Edit</ion-button>
                  <ion-button size="small" color="danger" @click="removeStruk(s.id_struk)">Hapus</ion-button>
                </ion-col>
              </ion-row>
            </ion-grid>
          </div>
        </ion-card-content>
      </ion-card>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { 
  IonPage, IonContent, IonGrid, IonRow, IonCol, IonCard, IonCardHeader, 
  IonCardTitle, IonCardContent, IonItem, IonInput, IonButton, IonSelect, 
  IonSelectOption, IonBadge, IonLabel 
} from '@ionic/vue'
import { ref, onMounted, computed, watch } from 'vue'
import { useRouter } from 'vue-router'

// Import semua service yang diperlukan untuk dropdown
import { getstruk, createstruk, updatestruk, deletestruk } from '@/services/strukSrevice'
import { getPemesanans } from '@/services/pemesananService'
import { getMejas } from '@/services/mejaService'
import { getUsers } from '@/services/userService' 
import { getPayments } from '@/services/paymentService'
import { getPengalamans } from '@/services/pengalamanService'
// Tambahkan di bagian import service
import { getDetails } from '@/services/detailService' // Sesuaikan nama filenya

const router = useRouter()
const struks = ref<any[]>([])
const mejas = ref<any[]>([])
const pemesanans = ref<any[]>([])
const users = ref<any[]>([])
const payments = ref<any[]>([])
const pengalamans = ref<any[]>([])

// State Form (Lengkap sesuai DB)
const id_meja = ref<number | null>(null)
const id_pemesanan = ref<number | null>(null)
const id_Pengalaman = ref<number | null>(null)
const id_pegawai = ref<number | null>(null)
const id_payment = ref<number | null>(null)
const total = ref<number | string>('')
const tanggal = ref('') // Format: YYYY-MM-DD
const status = ref('')

const isEdit = ref(false)
const editId = ref<number | null>(null)

const loadData = async () => {
  try {
    const [resS, resM, resP, resU, resPy, resE] = await Promise.all([
      getstruk(),
      getMejas(),
      getPemesanans(),
      getUsers(),
      getPayments(),
      getPengalamans()
    ])
    struks.value = resS.data.data
    mejas.value = resM.data.data
    pemesanans.value = resP.data.data
    users.value = resU.data.data
    payments.value = resPy.data.data
    pengalamans.value = resE.data.data
  } catch (err) {
    console.error("Gagal memuat data:", err)
  }
}

const submit = async () => {
  if ((isDineIn.value && !id_meja.value) || !id_pemesanan.value || !id_pegawai.value || !total.value) {
    alert("Mohon lengkapi data!");
    return;
  }

  const payload = {
    id_meja: id_meja.value || 0,
    id_pemesanan: id_pemesanan.value,
    id_Pengalaman: id_Pengalaman.value,
    id_pegawai: id_pegawai.value,
    id_payment: id_payment.value,
    total: Number(total.value), // Konversi ke Number
    tanggal: tanggal.value,
    status: Number(status.value) // Kirim sebagai angka 0 atau 1
  };

  try {
    if (isEdit.value && editId.value !== null) {
      await updatestruk({ id_struk: editId.value, ...payload });
    } else {
      await createstruk(payload);
    }
    alert("Simpan Berhasil!");
    resetForm(); 
    await loadData(); // Panggil data terbaru tanpa reload page
  } catch (err) {
    console.error(err);
  }
};

const editStruk = (u: any) => {
  isEdit.value = true
  editId.value = u.id_struk
  id_meja.value = u.id_meja
  id_pemesanan.value = u.id_pemesanan
  id_Pengalaman.value = u.id_Pengalaman
  id_pegawai.value = u.id_pegawai
  id_payment.value = u.id_payment
  total.value = u.total
  tanggal.value = u.tanggal
  status.value = u.status
}

const cancelEdit = () => resetForm()

const resetForm = () => {
  id_meja.value = null; id_pemesanan.value = null; id_Pengalaman.value = null
  id_pegawai.value = null; id_payment.value = null; total.value = ''
  status.value = ''; isEdit.value = false; editId.value = null
  tanggal.value = ''
  window.location.reload()
}

const removeStruk = async (id: number) => {
  if (confirm('Hapus struk ini?')) {
    await deletestruk(id)
    loadData()
  }
}

// --- LOGIKA FITUR 1: DISABLE MEJA ---
const isDineIn = computed(() => {
  if (!id_Pengalaman.value) return false;

  // Cek apakah id_Pengalaman berisi ID (angka) atau langsung teks "Dine In"
  const selected = pengalamans.value.find(exp => exp.id_Pengalaman == id_Pengalaman.value);
  
  // Jika data ditemukan di array, cek tipenya. 
  // Jika tidak ditemukan (undefined), cek apakah v-model itu sendiri adalah tulisan "Dine In"
  const typeName = selected ? selected.type : id_Pengalaman.value;
  
  return String(typeName).toLowerCase().trim() === 'dine in';
});

// --- LOGIKA FITUR 2: AUTO TOTAL HARGA (PERBAIKAN) ---
watch(id_pemesanan, async (newVal) => {
  if (newVal) {
    try {
      // Pastikan Anda sudah mengimport getDetails dari detailService.ts
      const res = await getDetails(); 
      const allDetails = res.data.data;

      // Filter menggunakan nama kolom yang sesuai dengan screenshot console Anda: 'id_Pemesanaan'
      const filtered = allDetails.filter((d: any) => 
        d.id_Pemesanaan == newVal || d.id_pemesanan == newVal
      );

      console.log("Mencari ID:", newVal);
      console.log("Hasil Filter:", filtered);

      // Hitung Total dari kolom 'subtotal'
      const totalBayar = filtered.reduce((sum: number, item: any) => {
        return sum + (Number(item.subtotal) || 0);
      }, 0);

      total.value = totalBayar;
      
    } catch (err) {
      console.error("Gagal hitung total:", err);
      total.value = 0;
    }
  } else {
    total.value = 0;
  }
});

// Logika reset meja jika bukan Dine In (Tambahkan ini agar lebih aman)
watch(id_Pengalaman, () => {
  if (!isDineIn.value) {
    id_meja.value = null;
  }
});

const goMaster = () => router.push('/master-data')
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