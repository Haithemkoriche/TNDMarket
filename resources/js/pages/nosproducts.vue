<script setup>
definePage({
  meta: {
    layout: "blank",
    public: true,
  },
});

// Imports et composables
import { computed, onMounted, ref, watch } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();

// État réactif
const drawer = ref(false);
const searchQuery = ref("");
const selectedCategory = ref(null);
const sortBy = ref("date_desc");
const showAdvancedFilters = ref(false);
const priceRange = ref([0, 50000]);
const showOnlyInStock = ref(false);
const viewMode = ref("grid");
const currentPage = ref(1);
const itemsPerPage = 12;

// État des produits
const products = ref([]);
const loadingProducts = ref(true);
const productsError = ref(null);

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

// Navigation items
const navItems = [
  { title: "Accueil", href: "/" },
  { title: "Produits", href: "/products" },
  { title: "À propos", href: "/about" },
  { title: "Contact", href: "/contact" },
];

// Options de filtre et tri
const categories = [
  "Artisanat",
  "Textile",
  "Bijoux",
  "Décoration",
  "Cuisine",
  "Art",
  "Autre",
];

const sortOptions = [
  { title: "Plus récents", value: "date_desc" },
  { title: "Plus anciens", value: "date_asc" },
  { title: "Prix croissant", value: "price_asc" },
  { title: "Prix décroissant", value: "price_desc" },
  { title: "Alphabétique A-Z", value: "name_asc" },
  { title: "Alphabétique Z-A", value: "name_desc" },
];

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

const filteredProducts = computed(() => {
  let filtered = [...products.value];

  // Filtre par recherche
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(
      (product) =>
        product.title.toLowerCase().includes(query) ||
        product.description.toLowerCase().includes(query) ||
        (product.user && product.user.name.toLowerCase().includes(query))
    );
  }

  // Filtre par catégorie
  if (selectedCategory.value) {
    filtered = filtered.filter(
      (product) => product.category === selectedCategory.value
    );
  }

  // Filtre par prix
  filtered = filtered.filter(
    (product) =>
      product.price >= priceRange.value[0] &&
      product.price <= priceRange.value[1]
  );

  // Filtre par stock
  if (showOnlyInStock.value) {
    filtered = filtered.filter((product) => product.stock > 0);
  }

  // Tri
  switch (sortBy.value) {
    case "price_asc":
      filtered.sort((a, b) => a.price - b.price);
      break;
    case "price_desc":
      filtered.sort((a, b) => b.price - a.price);
      break;
    case "name_asc":
      filtered.sort((a, b) => a.title.localeCompare(b.title));
      break;
    case "name_desc":
      filtered.sort((a, b) => b.title.localeCompare(a.title));
      break;
    case "date_asc":
      filtered.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
      break;
    case "date_desc":
    default:
      filtered.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
      break;
  }

  return filtered;
});

const totalPages = computed(() => {
  return Math.ceil(filteredProducts.value.length / itemsPerPage);
});

const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filteredProducts.value.slice(start, end);
});

// Méthodes
const fetchProducts = async () => {
  try {
    loadingProducts.value = true;
    productsError.value = null;

    // Simuler un appel API - remplacez par votre vraie API
    const response = await fetch("/api/products/all");
    if (!response.ok) throw new Error("Erreur lors du chargement des produits");

    const data = await response.json();
    products.value = data;

    // Mettre à jour les valeurs min/max des prix
    if (products.value.length > 0) {
      priceRange.value = [minPrice.value, maxPrice.value];
    }
  } catch (error) {
    productsError.value = error.message;
    console.error("Erreur lors du chargement des produits:", error);
  } finally {
    loadingProducts.value = false;
  }
};

const searchProducts = () => {
  currentPage.value = 1;
};

const filterProducts = () => {
  currentPage.value = 1;
};

const sortProducts = () => {
  currentPage.value = 1;
};

const filterByPrice = () => {
  currentPage.value = 1;
};

const resetFilters = () => {
  searchQuery.value = "";
  selectedCategory.value = null;
  sortBy.value = "date_desc";
  priceRange.value = [minPrice.value, maxPrice.value];
  showOnlyInStock.value = false;
  currentPage.value = 1;
};

const formatPrice = (price) => {
  return new Intl.NumberFormat("fr-DZ").format(price);
};

const getProductImageUrl = (photo) => {
  if (!photo) return "/placeholder-product.jpg";
  return photo.startsWith("http") ? photo : `/storage/${photo}`;
};

const viewProduct = (product) => {
  router.push(`/product/${product.id}`);
};

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

// Lifecycle
onMounted(() => {
  fetchProducts();
});

// Watchers
watch([searchQuery, selectedCategory, showOnlyInStock], () => {
  filterProducts();
});
</script>

<template>
  <div>
    <!-- Navigation (même que landing page) -->
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

    <!-- Mobile Drawer -->
    <v-navigation-drawer
      v-model="drawer"
      temporary
      location="right"
      width="300"
    >
      <v-list>
        <v-list-item
          v-for="item in navItems"
          :key="item.title"
          :href="item.href"
          link
        >
          <v-list-item-title>{{ item.title }}</v-list-item-title>
        </v-list-item>
        <v-divider class="my-4" />
        <v-list-item>
          <v-btn
            block
            variant="text"
            color="primary"
            class="mb-2"
            href="/login"
          >
            Connexion
          </v-btn>
          <v-btn block class="gradient-btn" href="/register">
            S'inscrire
          </v-btn>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <!-- Hero Section pour la page produits -->
    <section class="products-hero py-16 mt-16">
      <v-container>
        <div class="text-center">
          <h1 class="text-h2 font-weight-bold mb-4">
            Nos <span class="gradient-text">Produits Artisanaux</span>
          </h1>
          <p
            class="text-h6 text-grey-darken-1 mb-8"
            style="margin-block: 0; margin-inline: auto; max-inline-size: 600px"
          >
            Découvrez une collection unique de produits créés avec passion par
            nos artisans handicapés
          </p>
        </div>
      </v-container>
    </section>

    <!-- Filtres et Recherche -->
    <section class="py-8 bg-grey-lighten-5">
      <v-container>
        <v-card class="pa-6" elevation="2">
          <v-row align="center">
            <!-- Barre de recherche -->
            <v-col cols="12" md="6">
              <v-text-field
                v-model="searchQuery"
                label="Rechercher un produit..."
                prepend-inner-icon="tabler-search"
                variant="outlined"
                clearable
                hide-details
                @input="searchProducts"
              />
            </v-col>

            <!-- Filtre par catégorie -->
            <v-col cols="12" sm="6" md="3">
              <v-select
                v-model="selectedCategory"
                :items="categories"
                label="Catégorie"
                variant="outlined"
                clearable
                hide-details
                @update:model-value="filterProducts"
              />
            </v-col>

            <!-- Tri -->
            <v-col cols="12" sm="6" md="3">
              <v-select
                v-model="sortBy"
                :items="sortOptions"
                label="Trier par"
                variant="outlined"
                hide-details
                @update:model-value="sortProducts"
              />
            </v-col>
          </v-row>

          <!-- Filtres avancés (repliable) -->
          <v-expand-transition>
            <div v-show="showAdvancedFilters">
              <v-divider class="my-4" />
              <v-row>
                <!-- Filtre par prix -->
                <v-col cols="12" md="4">
                  <v-range-slider
                    v-model="priceRange"
                    :min="minPrice"
                    :max="maxPrice"
                    :step="100"
                    label="Fourchette de prix (DZD)"
                    @end="filterByPrice"
                  >
                    <template #prepend>
                      <span class="text-caption">{{
                        formatPrice(priceRange[0])
                      }}</span>
                    </template>
                    <template #append>
                      <span class="text-caption">{{
                        formatPrice(priceRange[1])
                      }}</span>
                    </template>
                  </v-range-slider>
                </v-col>

                <!-- Filtre par disponibilité -->
                <v-col cols="12" md="4">
                  <v-checkbox
                    v-model="showOnlyInStock"
                    label="Produits en stock uniquement"
                    @change="filterProducts"
                  />
                </v-col>

                <!-- Bouton reset -->
                <v-col cols="12" md="4" class="d-flex align-center">
                  <v-btn
                    variant="outlined"
                    color="grey-darken-2"
                    @click="resetFilters"
                  >
                    Réinitialiser les filtres
                  </v-btn>
                </v-col>
              </v-row>
            </div>
          </v-expand-transition>

          <!-- Toggle filtres avancés -->
          <div class="text-center mt-4">
            <v-btn
              variant="text"
              color="primary"
              @click="showAdvancedFilters = !showAdvancedFilters"
            >
              {{ showAdvancedFilters ? "Masquer" : "Afficher" }} les filtres
              avancés
              <v-icon :class="{ 'rotate-180': showAdvancedFilters }">
                tabler-chevron-down
              </v-icon>
            </v-btn>
          </div>
        </v-card>
      </v-container>
    </section>

    <!-- Section Produits -->
    <section class="py-8">
      <v-container>
        <!-- Résultats et statistiques -->
        <div class="d-flex justify-space-between align-center mb-6">
          <div>
            <h3 class="text-h5 font-weight-bold">
              {{ filteredProducts.length }} produit{{
                filteredProducts.length > 1 ? "s" : ""
              }}
              trouvé{{ filteredProducts.length > 1 ? "s" : "" }}
            </h3>
            <p v-if="searchQuery" class="text-grey-darken-1">
              Résultats pour "{{ searchQuery }}"
            </p>
          </div>

          <!-- Vue grille/liste -->
          <div class="d-flex ga-2">
            <v-btn-toggle
              v-model="viewMode"
              mandatory
              variant="outlined"
              color="primary"
            >
              <v-btn value="grid" icon="tabler-grid-3x3" />
              <v-btn value="list" icon="tabler-list" />
            </v-btn-toggle>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loadingProducts" class="text-center py-12">
          <v-progress-circular indeterminate color="primary" size="64" />
          <p class="mt-4 text-grey-darken-1">Chargement des produits...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="productsError" class="text-center py-12">
          <v-icon color="error" size="64" class="mb-4"
            >tabler-alert-circle</v-icon
          >
          <h3 class="text-h5 mb-2">Erreur de chargement</h3>
          <p class="text-grey-darken-1 mb-4">{{ productsError }}</p>
          <v-btn color="primary" @click="fetchProducts">Réessayer</v-btn>
        </div>

        <!-- Aucun résultat -->
        <div
          v-else-if="filteredProducts.length === 0 && !loadingProducts"
          class="text-center py-12"
        >
          <v-icon color="grey-lighten-1" size="80" class="mb-4"
            >tabler-search-off</v-icon
          >
          <h3 class="text-h5 mb-2">Aucun produit trouvé</h3>
          <p class="text-grey-darken-1 mb-4">
            Essayez de modifier vos critères de recherche ou parcourez tous nos
            produits
          </p>
          <v-btn color="primary" @click="resetFilters">
            Voir tous les produits
          </v-btn>
        </div>

        <!-- Grille de produits -->
        <div v-else>
          <!-- Vue grille -->
          <v-row v-if="viewMode === 'grid'">
            <v-col
              v-for="product in paginatedProducts"
              :key="product.id"
              cols="12"
              sm="6"
              md="4"
              lg="3"
            >
              <v-card class="product-card h-100" elevation="4" hover>
                <!-- Product Image -->
                <div class="product-image-container">
                  <v-img
                    :src="getProductImageUrl(product.photo)"
                    :alt="product.title"
                    height="200"
                    cover
                    class="product-image"
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
                    <template #error>
                      <div
                        class="d-flex align-center justify-center fill-height bg-grey-lighten-3"
                      >
                        <v-icon color="grey-lighten-1" size="64"
                          >tabler-photo-off</v-icon
                        >
                      </div>
                    </template>
                  </v-img>

                  <!-- Stock Badge -->
                  <v-chip
                    v-if="product.stock <= 5 && product.stock > 0"
                    class="stock-badge"
                    color="orange"
                    size="small"
                  >
                    Plus que {{ product.stock }}
                  </v-chip>
                  <v-chip
                    v-else-if="product.stock === 0"
                    class="stock-badge"
                    color="red"
                    size="small"
                  >
                    Épuisé
                  </v-chip>
                </div>

                <!-- Product Content -->
                <v-card-text class="pb-2">
                  <h3 class="text-h6 font-weight-bold mb-2 line-clamp-2">
                    {{ product.title }}
                  </h3>
                  <p class="text-body-2 text-grey-darken-1 mb-3 line-clamp-3">
                    {{ product.description }}
                  </p>

                  <!-- Seller Info -->
                  <div v-if="product.user" class="d-flex align-center mb-3">
                    <v-avatar size="24" class="me-2">
                      <v-icon size="16">tabler-user</v-icon>
                    </v-avatar>
                    <span class="text-caption text-grey-darken-1">
                      Par {{ product.user.name }}
                    </span>
                  </div>
                </v-card-text>

                <!-- Product Actions -->
                <v-card-actions class="pt-0">
                  <div class="d-flex justify-space-between align-center w-100">
                    <div class="text-h6 font-weight-bold text-primary">
                      {{ formatPrice(product.price) }} DZD
                    </div>
                    <div class="d-flex ga-2">
                      <v-btn
                        color="primary"
                        variant="outlined"
                        size="small"
                        :disabled="product.stock === 0"
                        @click="viewProduct(product)"
                      >
                        Voir
                      </v-btn>
                      <v-btn
                        color="success"
                        variant="flat"
                        size="small"
                        :disabled="product.stock === 0"
                        @click="openOrderDialog(product)"
                      >
                        <v-icon>tabler-shopping-cart</v-icon>
                      </v-btn>
                    </div>
                  </div>
                </v-card-actions>
              </v-card>
            </v-col>
          </v-row>

          <!-- Vue liste -->
          <div v-else class="d-flex flex-column ga-4">
            <v-card
              v-for="product in paginatedProducts"
              :key="product.id"
              class="product-list-card"
              elevation="2"
              hover
            >
              <v-row no-gutters>
                <v-col cols="12" sm="4" md="3">
                  <v-img
                    :src="getProductImageUrl(product.photo)"
                    :alt="product.title"
                    height="200"
                    cover
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
                </v-col>
                <v-col cols="12" sm="8" md="9">
                  <v-card-text class="pa-6">
                    <div class="d-flex justify-space-between align-start mb-3">
                      <div>
                        <h3 class="text-h5 font-weight-bold mb-2">
                          {{ product.title }}
                        </h3>
                        <div
                          v-if="product.user"
                          class="d-flex align-center mb-2"
                        >
                          <v-avatar size="20" class="me-2">
                            <v-icon size="12">tabler-user</v-icon>
                          </v-avatar>
                          <span class="text-caption text-grey-darken-1">
                            Par {{ product.user.name }}
                          </span>
                        </div>
                      </div>
                      <div class="text-end">
                        <div class="text-h5 font-weight-bold text-primary mb-2">
                          {{ formatPrice(product.price) }} DZD
                        </div>
                        <v-chip
                          v-if="product.stock <= 5 && product.stock > 0"
                          color="orange"
                          size="small"
                        >
                          Plus que {{ product.stock }}
                        </v-chip>
                        <v-chip
                          v-else-if="product.stock === 0"
                          color="red"
                          size="small"
                        >
                          Épuisé
                        </v-chip>
                      </div>
                    </div>
                    <p class="text-body-1 text-grey-darken-1 mb-4">
                      {{ product.description }}
                    </p>
                    <div class="d-flex ga-3">
                      <v-btn
                        color="primary"
                        variant="outlined"
                        :disabled="product.stock === 0"
                        @click="viewProduct(product)"
                      >
                        Voir le produit
                      </v-btn>
                      <v-btn
                        color="success"
                        :disabled="product.stock === 0"
                        @click="openOrderDialog(product)"
                      >
                        <v-icon class="me-2">tabler-shopping-cart</v-icon>
                        Commander
                      </v-btn>
                    </div>
                  </v-card-text>
                </v-col>
              </v-row>
            </v-card>
          </div>

          <!-- Pagination -->
          <div v-if="totalPages > 1" class="d-flex justify-center mt-8">
            <v-pagination
              v-model="currentPage"
              :length="totalPages"
              :total-visible="7"
              color="primary"
              @update:model-value="scrollToTop"
            />
          </div>
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

.gradient-btn {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
  color: white !important;
}

.products-hero {
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.product-card {
  transition: all 0.3s ease;
}

.product-card:hover {
  transform: translateY(-4px);
}

.product-image-container {
  position: relative;
  overflow: hidden;
}

.product-image {
  transition: transform 0.3s ease;
}

.product-card:hover .product-image {
  transform: scale(1.05);
}

.stock-badge {
  position: absolute;
  z-index: 1;
  inset-block-start: 8px;
  inset-inline-end: 8px;
}

.line-clamp-2 {
  display: -webkit-box;
  overflow: hidden;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
}

.line-clamp-3 {
  display: -webkit-box;
  overflow: hidden;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 3;
}

.product-list-card {
  transition: all 0.3s ease;
}

.product-list-card:hover {
  transform: translateX(4px);
}

.rotate-180 {
  transform: rotate(180deg);
  transition: transform 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 600px) {
  .products-hero h1 {
    font-size: 2rem !important;
  }

  .products-hero p {
    font-size: 1.1rem !important;
  }
}

/* Animation pour les filtres avancés */
.v-expand-transition-enter-active,
.v-expand-transition-leave-active {
  transition: all 0.3s ease;
}

.v-expand-transition-enter-from,
.v-expand-transition-leave-to {
  max-block-size: 0;
  opacity: 0;
}

.v-expand-transition-enter-to,
.v-expand-transition-leave-from {
  max-block-size: 500px;
  opacity: 1;
}

/* Amélioration de l'accessibilité */
.product-card:focus-within {
  outline: 2px solid #667eea;
  outline-offset: 2px;
}

/* Style pour les boutons de vue */
.v-btn-toggle .v-btn {
  min-inline-size: 48px;
}

/* Loading et états vides */
.text-center .v-progress-circular {
  margin-block-end: 1rem;
}

/* Amélioration du dialog */
.v-dialog .v-card {
  max-block-size: 90vh;
  overflow-y: auto;
}

/* Style pour les badges de stock */
.stock-badge {
  font-weight: 600;
  letter-spacing: 0.5px;
}

/* Animation pour les cartes produits */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.product-card,
.product-list-card {
  animation: fadeInUp 0.5s ease forwards;
}

/* Stagger animation pour les produits */
.product-card:nth-child(1) {
  animation-delay: 0.1s;
}

.product-card:nth-child(2) {
  animation-delay: 0.2s;
}

.product-card:nth-child(3) {
  animation-delay: 0.3s;
}

.product-card:nth-child(4) {
  animation-delay: 0.4s;
}

/* Responsive pour les cartes en mode liste */
@media (max-width: 960px) {
  .product-list-card .v-row {
    flex-direction: column;
  }

  .product-list-card .v-img {
    block-size: 200px;
  }
}

/* Style pour le range slider */
.v-range-slider {
  margin-block-start: 1rem;
}

/* Amélioration des états de chargement */
.v-progress-circular {
  margin-block: 0;
  margin-inline: auto;
}

/* Style pour les alertes */
.v-alert {
  border-radius: 8px;
}

/* Amélioration du formulaire de commande */
.v-dialog .v-card-text {
  max-block-size: 70vh;
  overflow-y: auto;
}

/* Style pour les informations du vendeur */
.seller-info {
  opacity: 0.8;
  transition: opacity 0.3s ease;
}

.product-card:hover .seller-info {
  opacity: 1;
}
</style>
