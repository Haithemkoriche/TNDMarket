<script setup>
import boyWithLogo from '@images/front-pages/landing-page/faq-boy-with-logos.png'; // Assurez-vous d'avoir une image adaptée

const faqData = [
  {
    question: 'Comment prendre un rendez-vous sur DoctoFree ?',
    answer: 'Pour prendre un rendez-vous, il vous suffit de créer un compte, de sélectionner un professionnel de santé et de choisir un créneau disponible. Le processus est simple et rapide !',
  },
  {
    question: 'DoctoFree est-il gratuit pour les patients ?',
    answer: 'Oui, DoctoFree est entièrement gratuit pour les patients. Vous pouvez prendre des rendez-vous sans frais supplémentaires.',
  },
  {
    question: 'Comment les professionnels de santé peuvent-ils s\'inscrire sur DoctoFree ?',
    answer: 'Les professionnels de santé peuvent s\'inscrire en remplissant un formulaire en ligne. Une fois inscrits, ils peuvent gérer leurs disponibilités et leurs rendez-vous facilement.',
  },
  {
    question: 'DoctoFree propose-t-il des rappels de rendez-vous ?',
    answer: 'Oui, DoctoFree envoie des rappels automatiques par e-mail ou SMS pour éviter les oublis de rendez-vous.',
  },
  {
    question: 'Puis-je annuler ou modifier un rendez-vous sur DoctoFree ?',
    answer: 'Oui, vous pouvez annuler ou modifier un rendez-vous à tout moment via votre compte DoctoFree.',
  },
]
</script>

<template>
  <div id="faq">
    <VContainer>
      <!-- 👉 Header  -->
      <div class="faq-section">
        <div class="headers d-flex justify-center flex-column align-center">
          <VChip
            label
            color="primary"
            size="small"
            class="mb-4"
          >
            FAQ
          </VChip>
          <h4 class="d-flex align-center text-h4 mb-1 flex-wrap justify-center">
            Questions fréquentes sur
            <div class="position-relative ms-2">
              <div class="section-title">
                DoctoFree
              </div>
            </div>
          </h4>
          <p class="text-body-1 mb-0">
            Trouvez les réponses aux questions les plus posées sur DoctoFree.
          </p>
        </div>
        <VRow>
          <VCol
            cols="12"
            md="5"
          >
            <div class="pt-10 d-flex align-center justify-center h-100">
              <VImg
                :src="boyWithLogo"
                height="330"
                width="330"
                alt="Illustration DoctoFree"
              />
            </div>
          </VCol>
          <VCol
            cols="12"
            md="7"
          >
            <VExpansionPanels class="pt-16">
              <VExpansionPanel
                v-for="faq in faqData"
                :key="faq.question"
              >
                <VExpansionPanelTitle>
                  {{ faq.question }}
                </VExpansionPanelTitle>
                <VExpansionPanelText>
                  {{ faq.answer }}
                </VExpansionPanelText>
              </VExpansionPanel>
            </VExpansionPanels>
          </VCol>
        </VRow>
      </div>
    </VContainer>
  </div>
</template>

<style lang="scss" scoped>
.faq-section {
  margin-block: 5.25rem;
}

@media (max-width: 600px) {
  .faq-section {
    margin-block: 4rem;
  }
}

#faq {
  border-radius: 3.75rem 3.75rem 0 0;
  background-color: rgba(var(--v-theme-background));
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
  font-weight: 800;
  inline-size: 130%;
  inset-block-end: 12%;
  inset-inline-start: -12%;
}
</style>
