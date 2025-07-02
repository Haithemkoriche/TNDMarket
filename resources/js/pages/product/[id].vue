<script setup>
definePage({
  meta: {
    layout: "blank",
    public: true,
  },
});

import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const product = ref(null);
const loading = ref(true);
const error = ref(null);

// État du dialog de commande
const orderDialog = ref(false);
const selectedProduct = ref(null);
const orderForm = ref({
  quantity: 1,
  buyer_info: {
    full_name: "",
    email: "",
    phone: "",
    address: "",
    wilaya: "",
  },
});

const orderLoading = ref(false);
const orderSuccess = ref(false);
const orderError = ref(null);

// Wilayas d'Algérie
const wilayas = [
  "Adrar",
  "Chlef",
  "Laghouat",
  "Oum El Bouaghi",
  "Batna",
  "Béjaïa",
  "Biskra",
  "Béchar",
  "Blida",
  "Bouira",
  "Tamanrasset",
  "Tébessa",
  "Tlemcen",
  "Tiaret",
  "Tizi Ouzou",
  "Alger",
  "Djelfa",
  "Jijel",
  "Sétif",
  "Saïda",
  "Skikda",
  "Sidi Bel Abbès",
  "Annaba",
  "Guelma",
  "Constantine",
  "Médéa",
  "Mostaganem",
  "M'Sila",
  "Mascara",
  "Ouargla",
  "Oran",
  "El Bayadh",
  "Illizi",
  "Bordj Bou Arréridj",
  "Boumerdès",
  "El Tarf",
  "Tindouf",
  "Tissemsilt",
  "El Oued",
  "Khenchela",
  "Souk Ahras",
  "Tipaza",
  "Mila",
  "Aïn Defla",
  "Naâma",
  "Aïn Témouchent",
  "Ghardaïa",
  "Relizane",
];

// Règles de validation
const quantityRules = computed(() => [
  (v) => !!v || "La quantité est requise",
  (v) => v > 0 || "La quantité doit être positive",
  (v) =>
    v <= (selectedProduct.value?.stock || 1) ||
    "Quantité supérieure au stock disponible",
]);

const buyerInfoRules = {
  full_name: [
    (v) => !!v || "Le nom complet est requis",
    (v) => (v && v.length >= 2) || "Le nom doit contenir au moins 2 caractères",
  ],
  email: [
    (v) => !!v || "L'email est requis",
    (v) => /.+@.+\..+/.test(v) || "L'email n'est pas valide",
  ],
  phone: [
    (v) => !!v || "Le téléphone est requis",
    (v) => /^[0-9+\-\s]+$/.test(v) || "Numéro de téléphone invalide",
  ],
  address: [
    (v) => !!v || "L'adresse est requise",
    (v) => (v && v.length >= 10) || "L'adresse doit être plus détaillée",
  ],
  wilaya: [(v) => !!v || "La wilaya est requise"],
};

// Computed properties
const minPrice = computed(() => {
  return Math.min(...products.value.map((p) => p.price), 0);
});

const maxPrice = computed(() => {
  return Math.max(...products.value.map((p) => p.price), 50000);
});
// Review system
const reviews = ref([]);
const newReview = ref({
  name: "",
  rating: 0,
  comment: "",
});
const hoverRating = ref(0);

const fetchProduct = async () => {
  try {
    loading.value = true;
    const response = await fetch(`/api/product/${route.params.id}`);
    if (!response.ok) throw new Error("Product not found");
    product.value = await response.json();
  } catch (err) {
    error.value = err.message;
  } finally {
    loading.value = false;
  }
};

const fetchReviews = async () => {
  const response = await fetch(`/api/product/${route.params.id}/reviews`);
  reviews.value = await response.json();
};

const submitReview = async () => {
  if (
    !newReview.value.name ||
    !newReview.value.comment ||
    newReview.value.rating === 0
  ) {
    alert("Please fill all fields and select a rating");
    return;
  }

  await fetch(`/api/product/${route.params.id}/reviews`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(newReview.value),
  });

  // Reset form and refresh reviews
  newReview.value = { name: "", rating: 0, comment: "" };
  fetchReviews();
};

const formatPrice = (price) => {
  return new Intl.NumberFormat("fr-DZ").format(price);
};

const getProductImageUrl = (photo) => {
  if (!photo) return "/placeholder-product.jpg";
  return photo.startsWith("http") ? photo : `/storage/${photo}`;
};
// const formatPrice = (price) => {
//   return new Intl.NumberFormat("fr-DZ").format(price);
// };

const openOrderDialog = (product) => {
  selectedProduct.value = product;
  orderForm.value.quantity = 1;
  orderForm.value.buyer_info = {
    full_name: "",
    email: "",
    phone: "",
    address: "",
    wilaya: "",
  };
  orderError.value = null;
  orderSuccess.value = false;
  orderDialog.value = true;
};

const closeOrderDialog = () => {
  orderDialog.value = false;
  selectedProduct.value = null;
  orderLoading.value = false;
  orderError.value = null;
  orderSuccess.value = false;
};

const calculateTotal = () => {
  if (!selectedProduct.value) return 0;
  return selectedProduct.value.price * orderForm.value.quantity;
};

const submitOrder = async () => {
  try {
    orderLoading.value = true;
    orderError.value = null;

    const orderData = {
      product_id: selectedProduct.value.id,
      quantity: orderForm.value.quantity,
      buyer_info: orderForm.value.buyer_info,
    };

    const response = await fetch("/api/orders", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify(orderData),
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message || "Erreur lors de la commande");
    }

    orderSuccess.value = true;

    // Mettre à jour le stock du produit localement
    const productIndex = products.value.findIndex(
      (p) => p.id === selectedProduct.value.id
    );
    if (productIndex !== -1) {
      products.value[productIndex].stock -= orderForm.value.quantity;
    }

    // Afficher un message de succès et fermer après 2 secondes
    setTimeout(() => {
      closeOrderDialog();
    }, 2000);
  } catch (error) {
    orderError.value = error.message;
    console.error("Erreur lors de la commande:", error);
  } finally {
    orderLoading.value = false;
  }
};

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: "smooth" });
};
onMounted(() => {
  fetchProduct();
  fetchReviews();
});
</script>

<template>
  <div>
    <!-- Navigation (same as your products page) -->
    <v-app-bar app fixed elevation="2" color="white" class="navbar-blur">
      <v-container class="d-flex align-center justify-space-between pa-0">
        <!-- Logo -->
        <div class="d-flex align-center">
          <v-avatar size="32" class="gradient-avatar me-2">
            <span class="text-white font-weight-bold text-caption">TM</span>
          </v-avatar>
          <span class="text-h6 font-weight-bold gradient-text">TNDMarket</span>
        </div>

        <!-- Desktop Menu -->
        <div class="d-none d-md-flex">
          <v-btn
            v-for="item in navItems"
            :key="item.title"
            :href="item.href"
            variant="text"
            class="me-4 text-body-1 font-weight-medium"
            color="grey-darken-2"
          >
            {{ item.title }}
          </v-btn>
        </div>

        <!-- CTA Buttons -->
        <div class="d-none d-md-flex ga-3">
          <v-btn
            variant="text"
            color="primary"
            class="font-weight-medium"
            href="/login"
          >
            Connexion
          </v-btn>
          <v-btn
            variant="flat"
            class="gradient-btn font-weight-medium"
            elevation="0"
            href="/register"
          >
            S'inscrire
          </v-btn>
        </div>

        <!-- Mobile Menu -->
        <v-app-bar-nav-icon class="d-md-none" @click="drawer = !drawer" />
      </v-container>
    </v-app-bar>

    <!-- Product Details Section -->
    <section class="py-8">
      <v-container>
        <!-- Loading State -->
        <div v-if="loading" class="text-center py-12">
          <v-progress-circular indeterminate color="primary" size="64" />
          <p class="mt-4 text-grey-darken-1">Chargement du produit...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="text-center py-12">
          <v-icon color="error" size="64" class="mb-4"
            >tabler-alert-circle</v-icon
          >
          <h3 class="text-h5 mb-2">Produit non trouvé</h3>
          <p class="text-grey-darken-1 mb-4">{{ error }}</p>
          <v-btn color="primary" href="/nosproducts"
            >Voir tous les produits</v-btn
          >
        </div>

        <!-- Product Content -->
        <div v-else-if="product" class="product-details">
          <v-row>
            <!-- Product Images -->
            <v-col cols="12" md="6">
              <v-card elevation="2" class="mb-4">
                <v-img
                  :src="getProductImageUrl(product.photo)"
                  :alt="product.title"
                  height="400"
                  cover
                  class="product-main-image"
                >
                  <template #placeholder>
                    <div
                      class="d-flex align-center justify-center fill-height bg-grey-lighten-3"
                    >
                      <v-icon color="grey-lighten-1" size="64"
                        >tabler-photo</v-icon
                      >
                    </div>
                  </template>
                </v-img>
              </v-card>
            </v-col>

            <!-- Product Info -->
            <v-col cols="12" md="6">
              <v-card elevation="2" class="pa-6 h-100">
                <div class="d-flex justify-space-between align-start mb-4">
                  <div>
                    <h1 class="text-h4 font-weight-bold mb-2">
                      {{ product.title }}
                    </h1>
                    <div v-if="product.user" class="d-flex align-center mb-4">
                      <v-avatar size="32" class="me-2">
                        <v-icon>tabler-user</v-icon>
                      </v-avatar>
                      <span class="text-body-1"
                        >Artisan: {{ product.user.name }}</span
                      >
                    </div>
                  </div>

                  <v-chip
                    v-if="product.stock <= 5 && product.stock > 0"
                    color="orange"
                    size="large"
                  >
                    Plus que {{ product.stock }} disponible{{
                      product.stock > 1 ? "s" : ""
                    }}
                  </v-chip>
                  <v-chip
                    v-else-if="product.stock === 0"
                    color="red"
                    size="large"
                  >
                    Épuisé
                  </v-chip>
                </div>

                <div class="text-h3 font-weight-bold text-primary mb-6">
                  {{ formatPrice(product.price) }} DZD
                </div>

                <div class="mb-6">
                  <h3 class="text-h6 font-weight-bold mb-2">Description</h3>
                  <p class="text-body-1">{{ product.description }}</p>
                </div>

                <div class="mb-6">
                  <h3 class="text-h6 font-weight-bold mb-2">Catégorie</h3>
                  <v-chip color="primary" size="large">{{
                    product.category
                  }}</v-chip>
                </div>

                <v-btn
                  color="success"
                  size="x-large"
                  block
                  :disabled="product.stock === 0"
                  @click="openOrderDialog(product)"
                >
                  <v-icon class="me-2">tabler-shopping-cart</v-icon>
                  Commander
                </v-btn>
              </v-card>
            </v-col>
          </v-row>

          <!-- Reviews Section -->
          <v-row class="mt-8">
            <v-col cols="12">
              <v-card elevation="2" class="pa-6">
                <h2 class="text-h4 font-weight-bold mb-6">Avis des clients</h2>

                <!-- Average Rating -->
                <div v-if="reviews.length > 0" class="mb-8">
                  <div class="d-flex align-center mb-2">
                    <div class="text-h3 font-weight-bold mr-4">
                      {{
                        (
                          reviews.reduce(
                            (acc, review) => acc + review.rating,
                            0
                          ) / reviews.length
                        ).toFixed(1)
                      }}
                    </div>
                    <div>
                      <v-rating
                        :model-value="
                          reviews.reduce(
                            (acc, review) => acc + review.rating,
                            0
                          ) / reviews.length
                        "
                        color="amber"
                        density="comfortable"
                        half-increments
                        readonly
                        size="28"
                      />
                      <div class="text-body-1">
                        Basé sur {{ reviews.length }} avis
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Review Form -->
                <v-card variant="outlined" class="mb-8 pa-6">
                  <h3 class="text-h6 font-weight-bold mb-4">
                    Donnez votre avis
                  </h3>
                  <v-form @submit.prevent="submitReview">
                    <v-text-field
                      v-model="newReview.name"
                      label="Votre nom"
                      variant="outlined"
                      required
                      class="mb-4"
                    />

                    <div class="mb-4">
                      <div class="text-body-1 mb-2">Votre note</div>
                      <v-rating
                        v-model="newReview.rating"
                        :hover="hoverRating"
                        @update:hover="hoverRating = $event"
                        color="amber"
                        density="comfortable"
                        size="28"
                      />
                    </div>

                    <v-textarea
                      v-model="newReview.comment"
                      label="Votre commentaire"
                      variant="outlined"
                      rows="3"
                      required
                      class="mb-4"
                    />

                    <v-btn color="primary" type="submit" size="large">
                      Soumettre l'avis
                    </v-btn>
                  </v-form>
                </v-card>

                <!-- Reviews List -->
                <div v-if="reviews.length > 0">
                  <div
                    v-for="review in reviews"
                    :key="review.id"
                    class="mb-6 pb-6 border-b"
                  >
                    <div class="d-flex justify-space-between mb-2">
                      <div class="font-weight-bold">{{ review.name }}</div>
                      <div class="text-grey">
                        {{ new Date(review.created_at).toLocaleDateString() }}
                      </div>
                    </div>
                    <v-rating
                      :model-value="review.rating"
                      color="amber"
                      density="compact"
                      half-increments
                      readonly
                      size="20"
                      class="mb-2"
                    />
                    <p class="text-body-1">{{ review.comment }}</p>
                  </div>
                </div>

                <div v-else class="text-center py-8">
                  <v-icon color="grey-lighten-1" size="64" class="mb-4"
                    >tabler-message</v-icon
                  >
                  <h3 class="text-h5 mb-2">Aucun avis pour ce produit</h3>
                  <p class="text-grey-darken-1">
                    Soyez le premier à donner votre avis
                  </p>
                </div>
              </v-card>
            </v-col>
          </v-row>
        </div>
      </v-container>
    </section>
    <!-- Dialog de commande -->
    <v-dialog v-model="orderDialog" max-width="800px" persistent>
      <v-card>
        <v-card-title class="text-h5 d-flex align-center">
          <v-icon class="me-2" color="success">tabler-shopping-cart</v-icon>
          Passer une commande
        </v-card-title>

        <v-divider />

        <v-card-text class="pa-6">
          <!-- État de succès -->
          <div v-if="orderSuccess" class="text-center py-8">
            <v-icon color="success" size="80" class="mb-4"
              >tabler-check-circle</v-icon
            >
            <h3 class="text-h5 mb-2 text-success">
              Commande passée avec succès!
            </h3>
            <p class="text-body-1 text-grey-darken-1">
              Vous recevrez une confirmation par email avec les détails de votre
              commande.
            </p>
          </div>

          <!-- Formulaire de commande -->
          <div v-else>
            <!-- Résumé du produit -->
            <div v-if="selectedProduct" class="mb-6">
              <h3 class="text-h6 mb-3">Produit sélectionné</h3>
              <v-card variant="outlined">
                <v-row no-gutters>
                  <v-col cols="4">
                    <v-img
                      :src="getProductImageUrl(selectedProduct.photo)"
                      height="120"
                      cover
                    />
                  </v-col>
                  <v-col cols="8">
                    <v-card-text>
                      <h4 class="text-h6 mb-2">{{ selectedProduct.title }}</h4>
                      <p class="text-body-2 text-grey-darken-1 mb-2">
                        {{ selectedProduct.description }}
                      </p>
                      <div class="d-flex justify-space-between align-center">
                        <span class="text-h6 font-weight-bold text-primary">
                          {{ formatPrice(selectedProduct.price) }} DZD
                        </span>
                        <span class="text-caption">
                          Stock: {{ selectedProduct.stock }} disponible{{
                            selectedProduct.stock > 1 ? "s" : ""
                          }}
                        </span>
                      </div>
                    </v-card-text>
                  </v-col>
                </v-row>
              </v-card>
            </div>

            <!-- Quantité -->
            <div class="mb-6">
              <h3 class="text-h6 mb-3">Quantité</h3>
              <v-row align="center">
                <v-col cols="6" sm="4">
                  <v-text-field
                    v-model.number="orderForm.quantity"
                    type="number"
                    label="Quantité"
                    variant="outlined"
                    :min="1"
                    :max="selectedProduct?.stock || 1"
                    :rules="quantityRules"
                    hide-details="auto"
                  />
                </v-col>
                <v-col cols="6" sm="8">
                  <div class="text-h6 font-weight-bold text-success">
                    Total: {{ formatPrice(calculateTotal()) }} DZD
                  </div>
                </v-col>
              </v-row>
            </div>

            <!-- Informations de l'acheteur -->
            <div class="mb-6">
              <h3 class="text-h6 mb-3">Vos informations</h3>
              <v-row>
                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="orderForm.buyer_info.full_name"
                    label="Nom complet *"
                    variant="outlined"
                    :rules="buyerInfoRules.full_name"
                    hide-details="auto"
                  />
                </v-col>
                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="orderForm.buyer_info.email"
                    label="Email *"
                    type="email"
                    variant="outlined"
                    :rules="buyerInfoRules.email"
                    hide-details="auto"
                  />
                </v-col>
                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="orderForm.buyer_info.phone"
                    label="Téléphone *"
                    variant="outlined"
                    :rules="buyerInfoRules.phone"
                    hide-details="auto"
                  />
                </v-col>
                <v-col cols="12" md="6">
                  <v-select
                    v-model="orderForm.buyer_info.wilaya"
                    :items="wilayas"
                    label="Wilaya *"
                    variant="outlined"
                    :rules="buyerInfoRules.wilaya"
                    hide-details="auto"
                  />
                </v-col>
                <v-col cols="12">
                  <v-textarea
                    v-model="orderForm.buyer_info.address"
                    label="Adresse complète *"
                    variant="outlined"
                    rows="3"
                    :rules="buyerInfoRules.address"
                    hide-details="auto"
                  />
                </v-col>
              </v-row>
            </div>

            <!-- Erreur -->
            <v-alert
              v-if="orderError"
              type="error"
              class="mb-4"
              dismissible
              @click:close="orderError = null"
            >
              {{ orderError }}
            </v-alert>
          </div>
        </v-card-text>

        <v-divider />

        <v-card-actions class="pa-6">
          <v-spacer />
          <v-btn
            variant="text"
            @click="closeOrderDialog"
            :disabled="orderLoading"
          >
            Annuler
          </v-btn>
          <v-btn
            v-if="!orderSuccess"
            color="success"
            :loading="orderLoading"
            @click="submitOrder"
          >
            Confirmer la commande
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<style scoped>
.product-details {
  margin-block: 0;
  margin-inline: auto;
  max-inline-size: 1200px;
}

.product-main-image {
  border-radius: 8px;
}

.navbar-blur {
  backdrop-filter: blur(10px);
  background: rgba(255, 255, 255, 95%) !important;
}

.gradient-avatar {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.gradient-text {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  background-clip: text;
  -webkit-text-fill-color: transparent;
}

.border-b {
  border-block-end: 1px solid rgba(0, 0, 0, 12%);
}
</style>
