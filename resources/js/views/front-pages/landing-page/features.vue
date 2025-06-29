<script setup>
import check from "@images/svg/Check.svg";
// import calendar from '@images/svg/calendar.svg'; // Nouvelle icône pour la prise de rendez-vous
// import doctor from '@images/svg/doctor.svg'; // Nouvelle icône pour les professionnels de santé
// import notification from '@images/svg/notification.svg'; // Nouvelle icône pour les rappels
// import patient from '@images/svg/patient.svg'; // Nouvelle icône pour les patients
// import support from '@images/svg/support.svg'; // Nouvelle icône pour le support
import calendar from "@images/svg/keyboard.svg";
import doctor from "@images/svg/laptop.svg";
import notification from "@images/svg/paper-send.svg";
import patient from "@images/svg/rocket.svg";
import support from "@images/svg/user.svg";

const featuresData = [
  {
    title: "Catalogue de produits varié",
    desc: "Explorez une large gamme de produits pour professionnels et particuliers en un seul endroit.",
    icon: calendar,
  },
  {
    title: "Interface intuitive",
    desc: "Une navigation fluide pour une expérience utilisateur agréable et rapide.",
    icon: doctor,
  },
  {
    title: "Commandes en un clic",
    desc: "Achetez rapidement vos produits favoris avec un système de panier intelligent.",
    icon: notification,
  },
  {
    title: "Espace client sécurisé",
    desc: "Gérez vos informations, commandes et préférences en toute confidentialité.",
    icon: patient,
  },
  {
    title: "Support client dédié",
    desc: "Une équipe disponible pour vous aider avant et après vos achats.",
    icon: support,
  },
  {
    title: "Paiement & livraison sécurisés",
    desc: "Des transactions protégées et des options de livraison fiables.",
    icon: check,
  },
];
</script>

<template>
  <VContainer id="features">
    <div class="feature-cards">
      <div class="headers d-flex justify-center flex-column align-center mb-12">
        <VChip label color="primary" class="mb-4" size="small">
          Fonctionnalités
        </VChip>
        <div class="d-flex text-h4 mb-1 align-center flex-wrap justify-center">
          <div class="position-relative me-2">
            <div class="section-title">Découvrez les avantages</div>
          </div>
          de TNDMarket
        </div>
        <p class="text-body-1 mb-0">
          Un marché digital conçu pour simplifier vos achats professionnels et
          personnels.
        </p>
      </div>
      <VRow>
        <VCol
          v-for="(data, index) in featuresData"
          :key="index"
          cols="12"
          md="4"
          sm="6"
        >
          <div
            class="d-flex flex-column align-center justify-center gap-4 mx-auto"
          >
            <VIcon :icon="data.icon" size="64" color="primary" />
            <div class="text-center">
              <h5 class="text-h5 mb-2">
                {{ data.title }}
              </h5>
              <p class="text-body-1 mb-6" style="max-inline-size: 310px">
                {{ data.desc }}
              </p>
            </div>
          </div>
        </VCol>
      </VRow>
    </div>
  </VContainer>
</template>

<style lang="scss" scoped>
.feature-cards {
  margin-block: 3.75rem;
}

.section-title::after {
  position: absolute;
  background: url("../../../assets/images/front-pages/icons/section-title-icon.png")
    no-repeat left bottom;
  background-size: contain;
  block-size: 100%;
  content: "";
  inline-size: 120%;
  inset-block-end: 0;
  inset-inline-start: -12%;
}

.section-title {
  font-size: 24px;
  font-weight: 800;
  line-height: 36px;
}

.feature {
  .v-avatar {
    &.v-avatar--variant-outlined {
      border: 2px solid rgba(var(--v-theme-primary), 0.32);

      &:hover {
        background: rgba(var(--v-theme-primary), 0.16);
        cursor: pointer;
      }
    }
  }
}

.feature-cards .v-col > div {
  padding: 2rem;
  border-radius: 12px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;

  &:hover {
    background-color: rgba(var(--v-theme-primary), 0.03);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 6%);
    transform: translateY(-4px);
  }
}
</style>
