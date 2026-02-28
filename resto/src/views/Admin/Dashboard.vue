<template>
  <ion-page>
    <ion-header>
      <ion-toolbar class="custom-toolbar">

        <ion-buttons slot="start">
        <div class="nav-left">
          <ion-title class="nav-title">Dashboard</ion-title>
        </div>          
          <ion-button class="beranda-btn" @click="goHome">Beranda</ion-button>
          <ion-button class="master-btn" @click="goMaster">Masterdata</ion-button>
          <ion-button class="transaksi-btn" @click="goTransaksi">Transaksi</ion-button>

        </ion-buttons>
        <ion-buttons slot="end">
          <ion-button class="logout-btn" @click="logout">Logout</ion-button>          
        </ion-buttons>
        
      </ion-toolbar>
    </ion-header>

    <ion-content class="ion-padding">
      <h1>Dashboard</h1>
    </ion-content>
  </ion-page>
</template>


<script setup>
import {
  IonPage,
  IonHeader,
  IonToolbar,
  IonTitle,
  IonContent,
  IonButtons,
  IonButton,
  IonPopover,
  IonList,
  IonItem,
  IonLabel,
  IonIcon
} from '@ionic/vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const goHome = () => {
  router.push('/dashboard')
}
const goMaster = () => {
  router.push('/master-data')
}
const goTransaksi = () => {
  router.push('/transaksi')
}
const logout = () => {
  // Implement logout logic here (e.g., clear auth tokens, redirect to login page)
  router.push('/login')
}

</script>

<style scoped>
.custom-toolbar {
  --background: #1f2937; /* dark slate */
  display: flex;
  align-items: center;
}

/* Left-aligned navbar group */
.nav-left {
  display: flex;
  align-items: center;
  gap: 12px;
}

/* Dashboard text */
.nav-title {
  font-size: 18px;
  font-weight: 600;
  color: #ffffff;
}

/* Master Data button */
.master-btn {
  --color: #e5e7eb;
  text-transform: none;
  font-size: 14px;
}

/*beranda button */
.beranda-btn {
  --color: #e5e7eb;
  text-transform: none;
  font-size: 14px;
}

/* Transaksi button */
.transaksi-btn {
  --color: #e5e7eb;
  text-transform: none;
  font-size: 14px;
}

/* Dropdown width */
.popover-content {
  min-width: 160px;
}
</style>
