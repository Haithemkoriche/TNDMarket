<script setup>
import paperPlane from '@images/front-pages/icons/paper-airplane.png'
import plane from '@images/front-pages/icons/plane.png'
import pricingPlanArrow from '@images/front-pages/icons/pricing-plans-arrow.png'
import shuttleRocket from '@images/front-pages/icons/shuttle-rocket.png'

const annualMonthlyPlanPriceToggler = ref(true)

const pricingPlans = [
  {
    title: 'Gratuit',
    image: paperPlane,
    monthlyPrice: 0,
    yearlyPrice: 0,
    features: [
      'Prise de rendez-vous en ligne',
      'Gestion des disponibilités',
      'Rappels automatiques',
      'Support par e-mail',
    ],
    supportType: 'Basique',
    supportMedium: 'E-mail uniquement',
    respondTime: 'Temps moyen : 24h',
    current: false,
  },
  {
    title: 'Pro',
    image: plane,
    monthlyPrice: 29,
    yearlyPrice: 264,
    features: [
      'Tout inclus dans le plan Gratuit',
      'Gestion des patients',
      'Statistiques avancées',
      'Support par e-mail et chat',
    ],
    supportType: 'Standard',
    supportMedium: 'E-mail & Chat',
    respondTime: 'Temps moyen : 6h',
    current: true,
  },
  {
    title: 'Entreprise',
    image: shuttleRocket,
    monthlyPrice: 49,
    yearlyPrice: 444,
    features: [
      'Tout inclus dans le plan Pro',
      'Intégrations personnalisées',
      'Support prioritaire',
      'Formation et assistance',
    ],
    supportType: 'Exclusif',
    supportMedium: 'E-mail, Chat & Visio',
    respondTime: 'Support en direct',
    current: false,
  },
]
</script>

<template>
  <div id="pricing-plan">
    <VContainer>
      <div class="pricing-plans">
        <!-- 👉 Headers  -->
        <div class="headers d-flex justify-center flex-column align-center flex-wrap">
          <VChip
            label
            color="primary"
            class="mb-4"
            size="small"
          >
            Plans Tarifaires
          </VChip>
          <h4 class="d-flex align-center text-h4 mb-1 flex-wrap justify-center">
            <div class="position-relative me-2">
              <div class="section-title">
                Des plans adaptés
              </div>
            </div>
            à vos besoins
          </h4>
          <div class="text-center text-body-1">
            <p class="mb-0">
              Tous les plans incluent des fonctionnalités avancées pour simplifier vos rendez-vous médicaux.
            </p>
            <p class="mb-0">
              Choisissez le plan qui correspond le mieux à vos besoins.
            </p>
          </div>
        </div>
        <!-- 👉 Annual and monthly price toggler -->
        <div class="d-flex align-center justify-center mx-auto mt-6 mb-16">
          <VLabel
            for="pricing-plan-toggle"
            class="me-3"
          >
            Paiement Mensuel
          </VLabel>
          <div class="position-relative">
            <VSwitch
              id="pricing-plan-toggle"
              v-model="annualMonthlyPlanPriceToggler"
            >
              <template #label>
                <div class="text-body-1">
                  Paiement Annuel
                </div>
              </template>
            </VSwitch>
            <div class="position-absolute pricing-plan-arrow d-md-flex d-none">
              <VImg
                :src="pricingPlanArrow"
                class="flip-in-rtl"
                width="60"
                height="42"
              />
              <div class="text-no-wrap text-body-1 font-weight-medium">
                Économisez 25%
              </div>
            </div>
          </div>
        </div>
        <VRow>
          <VCol
            v-for="(plan, index) in pricingPlans"
            :key="index"
          >
            <VCard :style="plan.current ? 'border:2px solid rgb(var(--v-theme-primary))' : ''">
              <VCardText class="pa-8 pt-12">
                <VImg
                  :src="plan.image"
                  width="88"
                  height="88"
                  class="mx-auto mb-8"
                />
                <h4 class="text-h4 text-center">
                  {{ plan.title }}
                </h4>
                <div class="d-flex justify-center mb-8 position-relative">
                  <div class="d-flex align-end">
                    <div class="pricing-title text-primary me-1">
                      ${{ annualMonthlyPlanPriceToggler ? Math.floor(plan.yearlyPrice) / 12 : plan.monthlyPrice }}
                    </div>
                    <span class="text-disabled mb-2">/mois</span>
                  </div>

                  <!-- 👉 Annual Price -->
                  <span
                    v-show="annualMonthlyPlanPriceToggler"
                    class="annual-price-text position-absolute text-sm text-disabled"
                  >
                    {{ plan.yearlyPrice === 0 ? 'gratuit' : `USD ${plan.yearlyPrice}/an` }}
                  </span>
                </div>
                <VList class="card-list">
                  <VListItem
                    v-for="(item, i) in plan.features"
                    :key="i"
                  >
                    <template #prepend>
                      <VAvatar
                        size="16"
                        :variant="!plan.current ? 'tonal' : 'elevated'"
                        color="primary"
                        class="me-3"
                      >
                        <VIcon
                          icon="tabler-check"
                          size="12"
                          :color="!plan.current ? 'primary' : 'white'"
                        />
                      </VAvatar>
                      <h6 class="text-h6">
                        {{ item }}
                      </h6>
                    </template>
                  </VListItem>
                </VList>
                <VBtn
                  block
                  :variant="plan.current ? 'elevated' : 'tonal'"
                  class="mt-8"
                  :to="{
                    //  name: 'front-pages-payment'
                     }"
                >
                  {{ plan.title === 'Gratuit' ? 'Commencer gratuitement' : 'Choisir ce plan' }}
                </VBtn>
              </VCardText>
            </VCard>
          </VCol>
        </VRow>
      </div>
    </VContainer>
  </div>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 12px;
}

#pricing-plan {
  border-radius: 3.75rem;
  background-color: rgb(var(--v-theme-background));
}

.pricing-title {
  font-size: 38px;
  font-weight: 800;
  line-height: 52px;
}

.pricing-plans {
  margin-block: 5.25rem;
}

@media (max-width: 600px) {
  .pricing-plans {
    margin-block: 4rem;
  }
}

.save-upto-chip {
  inset-block-start: -1.5rem;
  inset-inline-end: -7rem;
}

.pricing-plan-arrow {
  inset-block-start: -0.5rem;
  inset-inline-end: -8rem;
}

.section-title {
  font-size: 24px;
  font-weight: 800;
  line-height: 36px;
}

.section-title::after {
  position: absolute;
  background: url("../../../assets/images/front-pages/icons/section-title-icon.png") no-repeat left bottom;
  background-size: contain;
  block-size: 100%;
  content: "";
  font-weight: 700;
  inline-size: 120%;
  inset-block-end: 0;
  inset-inline-start: -12%;
}

.annual-price-text {
  inset-block-end: -40%;
}
</style>
