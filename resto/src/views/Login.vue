<template>
  <ion-page>
    <ion-content fullscreen class="ion-padding">
      <div class="login-container">
        <h1 class="title">Login</h1>
        <p class="subtitle">Masuk ke aplikasi</p>

          <ion-item>
            <ion-input
              type="email"
              placeholder="Email"
              v-model="email"
            />
          </ion-item>

          <ion-item class="mt">
            <ion-input
              type="password"
              placeholder="Password"
              v-model="password"
            />
          </ion-item>

        <ion-button expand="block" class="login-btn" @click="login">
          Login
        </ion-button>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import {
  IonPage,
  IonContent,
  IonItem,
  IonInput,
  IonButton
} from '@ionic/vue'

import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const email = ref('')
const password = ref('')

const login = async () => {
  if (!email.value || !password.value) {
    alert('Please enter email and password')
    return
  }

  try {
    const response = await axios.post(
      'http://localhost:8000/auth/login.php',
      {
        email: email.value,
        password: password.value
      }
    )

    console.log("Response Server:", response.data); // Tambahkan ini untuk debug

    if (response.data.success) {
      const user = response.data.user
      
      // Simpan user ke localStorage
      localStorage.setItem('user', JSON.stringify(user))

      // Sesuaikan pengecekan Role (di gambar Anda id_role bisa jadi string atau number)
      if (user.id_role == 1 || user.role === 'Admin') { 
        router.push('/dashboard')
      } else {
        alert('Access denied. Admin only.')
      }
    } else {
      alert(response.data.message || 'Login gagal')
    }
  } catch (error: any) {
    // Tampilkan pesan error yang lebih spesifik
    const errorMsg = error.response?.data?.message || 'Tidak dapat terhubung ke server (Cek CORS atau Port)'
    alert('Error: ' + errorMsg)
    console.error("Detail Error:", error)
  }
}

</script>


<style scoped>
.login-container {
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  max-width: 400px;
  margin: auto;
}

.title {
  text-align: center;
  font-size: 28px;
  font-weight: bold;
}

.subtitle {
  text-align: center;
  color: gray;
  margin-bottom: 30px;
}

.mt {
  margin-top: 15px;
}

.login-btn {
  margin-top: 20px;
  height: 48px;
}
</style>