<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
const products = ref([]);
const loading = ref(true);
onMounted(async () => {
  const res = await axios.get("/api/products");
  products.value = res.data;
  loading.value = false;
});
const formatPrice = (p) =>
  new Intl.NumberFormat("fr-FR", { style: "currency", currency: "EUR" }).format(
    p
  );
</script>

<template>
  <section id="products" class="products-section">
    <VContainer>
      <h2>Nos produits</h2>
      <div v-if="loading">Chargement...</div>
      <VRow v-else>
        <VCol v-for="prod in products" :key="prod.id" cols="12" md="4">
          <VCard>
            <VImg :src="prod.photo_url" height="200px" />
            <VCardTitle>{{ prod.title }}</VCardTitle>
            <VCardText>{{ formatPrice(prod.price) }}</VCardText>
            <VCardActions>
              <VBtn color="primary">Ajouter au panier</VBtn>
            </VCardActions>
          </VCard>
        </VCol>
      </VRow>
    </VContainer>
  </section>
</template>

<style scoped>
.products-section {
  background: #f9fafb;
  padding-block: 4rem;
  padding-inline: 2rem;
}
h2 {
  margin-block-end: 2rem;
  text-align: center;
}
</style>
