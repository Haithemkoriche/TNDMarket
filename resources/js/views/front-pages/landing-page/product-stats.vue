<script setup>
import patients from '@images/svg/Check.svg';
import appointment from '@images/svg/Diamond.svg';
import satisfaction from '@images/svg/laptop.svg';
import doctors from '@images/svg/user.svg';


// import appointment from '@images/svg/appointment.svg' // Icône pour les rendez-vous
// import doctors from '@images/svg/doctors.svg' // Icône pour les médecins
// import patients from '@images/svg/patients.svg' // Icône pour les patients
// import satisfaction from '@images/svg/satisfaction.svg' // Icône pour la satisfaction
const statData = [
  {
    title: 'Rendez-vous pris',
    value: '10k+',
    icon: appointment,
    color: 'primary',
  },
  {
    title: 'Médecins partenaires',
    value: '500+',
    icon: doctors,
    color: 'success',
  },
  {
    title: 'Patients satisfaits',
    value: '95%',
    icon: patients,
    color: 'info',
  },
  {
    title: 'Disponibilité 24/7',
    value: '100%',
    icon: satisfaction,
    color: 'warning',
  },
]
</script>

<template>
  <div :style="{ 'background-color': 'rgb(var(--v-theme-surface))' }">
    <VContainer>
      <div class="py-12">
        <VRow>
          <VCol
            v-for="(product, index) in statData"
            :key="index"
            cols="12"
            sm="6"
            lg="3"
          >
            <VCard
              flat
              :style="{ border: `1px solid rgba(var(--v-theme-${product.color}))` }"
            >
              <VCardText class="text-center">
                <VIcon
                  :color="product.color"
                  :icon="product.icon"
                  size="64"
                  class="mb-4"
                />
                <h3 class="text-h3">
                  {{ product.value }}
                </h3>
                <p class="text-body-1 font-weight-medium mb-0 text-wrap">
                  {{ product.title }}
                </p>
              </VCardText>
            </VCard>
          </VCol>
        </VRow>
      </div>
    </VContainer>
  </div>
</template>
