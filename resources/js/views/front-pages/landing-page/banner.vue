<script setup>
import ctaDashboard from '@images/front-pages/landing-page/cta-dashboard.png' // Assurez-vous d'avoir une image adaptée
</script>

<template>
  <div
    class="landing-cta bg-surface"
    :class="$vuetify.theme.current.dark ? 'banner-bg-dark' : 'banner-bg-light'"
  >
    <VContainer>
      <div class="d-flex justify-center justify-md-space-between flex-wrap gap-6 gap-x-10 position-relative pt-12">
        <div class="align-self-center">
          <div class="banner-title text-primary mb-1">
            Prêt à simplifier vos rendez-vous médicaux ?
          </div>
          <h5 class="text-h5 text-medium-emphasis mb-8">
            Essayez DoctoFree gratuitement pendant 14 jours et découvrez une nouvelle façon de gérer vos consultations.
          </h5>
          <VBtn
            color="primary"
            :to="{ 
              // name: 'front-pages-payment'
               }"
            :size="$vuetify.display.smAndUp ? 'large' : 'default'"
          > 
            Essayez gratuitement
          </VBtn>
        </div>

        <div class="banner-img">
          <img
            :src="ctaDashboard"
            class="w-100"
            alt="Interface de DoctoFree"
          >
        </div>
      </div>
    </VContainer>
  </div>
</template>

<style lang="scss">
.landing-cta {
  background-size: cover;
  margin-block: auto;
}

.banner-img {
  margin-block-end: -22px;
}

.banner-title {
  font-size: 34px;
  font-weight: 700;
  line-height: 44px;
}

.banner-bg-light {
  background-image: url("@images/front-pages/backgrounds/cta-bg.png");
}

.banner-bg-dark {
  background-image: url("@images/front-pages/backgrounds/cta-bg-dark.png");
}
</style>
