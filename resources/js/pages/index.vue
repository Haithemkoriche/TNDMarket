<script setup>
definePage({
  meta: {
    layout: "blank",
    public: true,
  },
});
</script>
<template>
  <div>
    <!-- Navigation -->
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

    <!-- Hero Section -->
    <section class="hero-section">
      <v-container class="py-16">
        <v-row align="center" class="min-h-screen">
          <v-col cols="12" lg="6" class="hero-content">
            <!-- Badge -->
            <v-chip
              class="mb-6 gradient-chip"
              prepend-icon="tabler-star"
              size="small"
            >
              Plateforme #1 en Algérie
            </v-chip>

            <!-- Title -->
            <h1 class="hero-title mb-6">
              La plateforme qui
              <span class="gradient-text d-block">connecte</span>
              et accompagne
            </h1>

            <!-- Description -->
            <p class="text-h6 text-grey-darken-1 mb-8 font-weight-regular">
              TNDMarket réunit personnes handicapées, centres d'évaluation et
              administrateurs sur une plateforme moderne et accessible.
            </p>

            <!-- CTA Buttons -->
            <div class="d-flex flex-column flex-sm-row ga-4 mb-12">
              <v-btn
                size="x-large"
                class="gradient-btn font-weight-bold"
                append-icon="tabler-arrow-right"
                href="/register"
              >
                Commencer gratuitement
              </v-btn>
              <v-btn
                size="x-large"
                variant="outlined"
                color="grey-darken-2"
                prepend-icon="tabler-play"
                class="font-weight-bold"
              >
                Voir la démo
              </v-btn>
            </div>

            <!-- Stats -->
            <v-row class="stats-row">
              <v-col
                v-for="stat in stats"
                :key="stat.label"
                cols="6"
                sm="3"
                class="text-center"
              >
                <div class="text-h4 font-weight-bold text-grey-darken-4">
                  {{ stat.number }}
                </div>
                <div class="text-caption text-grey-darken-1">
                  {{ stat.label }}
                </div>
              </v-col>
            </v-row>
          </v-col>

          <v-col cols="12" lg="6" class="d-flex justify-center">
            <v-card
              class="hero-card pa-8 rotate-card"
              elevation="24"
              max-width="400"
            >
              <div class="d-flex align-center mb-6">
                <v-avatar class="gradient-avatar me-4" size="48">
                  <v-icon color="white">tabler-dashboard</v-icon>
                </v-avatar>
                <div>
                  <h3 class="text-h6 font-weight-bold">Tableau de Bord</h3>
                  <p class="text-body-2 text-grey-darken-1">
                    Interface moderne et intuitive
                  </p>
                </div>
              </div>

              <div class="ga-3 d-flex flex-column">
                <v-card
                  class="pa-3 bg-grey-lighten-4"
                  elevation="0"
                  rounded="lg"
                >
                  <div class="d-flex justify-space-between align-center">
                    <span class="text-body-2 font-weight-medium"
                      >Produits vendus</span
                    >
                    <span class="text-green font-weight-bold">+24%</span>
                  </div>
                </v-card>
                <v-card
                  class="pa-3 bg-grey-lighten-4"
                  elevation="0"
                  rounded="lg"
                >
                  <div class="d-flex justify-space-between align-center">
                    <span class="text-body-2 font-weight-medium"
                      >Rapports générés</span
                    >
                    <span class="text-primary font-weight-bold">156</span>
                  </div>
                </v-card>
                <v-card
                  class="pa-3 bg-grey-lighten-4"
                  elevation="0"
                  rounded="lg"
                >
                  <div class="d-flex justify-space-between align-center">
                    <span class="text-body-2 font-weight-medium"
                      >Centres actifs</span
                    >
                    <span class="text-purple font-weight-bold">42</span>
                  </div>
                </v-card>
              </div>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </section>

    <!-- Nouvelle Section Produits -->
    <section id="products" class="py-16 bg-grey-lighten-5">
      <v-container>
        <!-- Section Header -->
        <div class="text-center mb-12">
          <h2 class="section-title mb-4">
            Nos
            <span class="gradient-text">Produits Artisanaux</span>
          </h2>
          <p
            class="text-h6 text-grey-darken-1 mx-auto"
            style="max-inline-size: 600px"
          >
            Découvrez une sélection unique de produits créés avec passion par
            nos artisans
          </p>
        </div>

        <!-- Loading State -->
        <div v-if="loadingProducts" class="text-center py-8">
          <v-progress-circular indeterminate color="primary" size="64" />
          <p class="mt-4 text-grey-darken-1">Chargement des produits...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="productsError" class="text-center py-8">
          <v-icon color="error" size="64" class="mb-4"
            >tabler-alert-circle</v-icon
          >
          <h3 class="text-h5 mb-2">Erreur de chargement</h3>
          <p class="text-grey-darken-1 mb-4">{{ productsError }}</p>
          <v-btn color="primary" @click="fetchProducts"> Réessayer </v-btn>
        </div>

        <!-- Products Grid -->
        <div v-else>
          <v-row v-if="products.length > 0">
            <v-col
              v-for="product in displayedProducts"
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
                    <v-btn
                      color="primary"
                      variant="outlined"
                      size="small"
                      :disabled="product.stock === 0"
                    >
                      {{ product.stock === 0 ? "Épuisé" : "Voir plus" }}
                    </v-btn>
                  </div>
                </v-card-actions>
              </v-card>
            </v-col>
          </v-row>

          <!-- Empty State -->
          <div v-else class="text-center py-12">
            <v-icon color="grey-lighten-1" size="80" class="mb-4"
              >tabler-package</v-icon
            >
            <h3 class="text-h5 mb-2">Aucun produit disponible</h3>
            <p class="text-grey-darken-1">
              Les produits seront bientôt ajoutés par nos artisans
            </p>
          </div>

          <!-- Load More Button -->
          <div v-if="products.length > productsToShow" class="text-center mt-8">
            <v-btn
              color="primary"
              variant="outlined"
              size="large"
              @click="loadMoreProducts"
            >
              Voir plus de produits
            </v-btn>
          </div>
        </div>
      </v-container>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 bg-white">
      <v-container>
        <!-- Section Header -->
        <div class="text-center mb-12">
          <h2 class="section-title mb-4">
            Trois interfaces,
            <span class="gradient-text">une mission</span>
          </h2>
          <p
            class="text-h6 text-grey-darken-1 mx-auto"
            style="max-inline-size: 600px"
          >
            Découvrez comment TNDMarket répond aux besoins spécifiques de chaque
            utilisateur avec des outils adaptés et performants.
          </p>
        </div>

        <!-- Features Cards -->
        <v-row class="mb-12">
          <v-col
            v-for="(feature, index) in features"
            :key="index"
            cols="12"
            md="4"
          >
            <v-card class="feature-card pa-8 h-100" elevation="4" hover>
              <v-avatar :class="`${feature.color} mb-6`" size="64">
                <v-icon color="white" size="32">{{ feature.icon }}</v-icon>
              </v-avatar>
              <h3 class="text-h5 font-weight-bold mb-4">{{ feature.title }}</h3>
              <p class="text-body-1 text-grey-darken-1 mb-6">
                {{ feature.description }}
              </p>
              <v-btn
                variant="text"
                color="primary"
                append-icon="tabler-chevron-right"
                class="font-weight-bold"
              >
                En savoir plus
              </v-btn>
            </v-card>
          </v-col>
        </v-row>

        <!-- Feature Tabs -->
        <v-card class="pa-8 bg-grey-lighten-5" elevation="0">
          <div class="d-flex justify-center flex-wrap ga-4 mb-8">
            <v-btn
              v-for="tab in tabs"
              :key="tab.id"
              :variant="activeTab === tab.id ? 'flat' : 'outlined'"
              :class="activeTab === tab.id ? 'gradient-btn' : ''"
              :color="activeTab === tab.id ? '' : 'grey-darken-2'"
              :prepend-icon="tab.icon"
              class="font-weight-bold"
              @click="activeTab = tab.id"
            >
              {{ tab.label }}
            </v-btn>
          </div>

          <v-row align="center">
            <v-col cols="12" lg="6">
              <div v-if="activeTab === 'users'">
                <h3 class="text-h4 font-weight-bold mb-6">
                  Interface Utilisateur
                </h3>
                <div class="ga-4 d-flex flex-column">
                  <div class="d-flex align-start">
                    <v-icon color="primary" class="me-3 mt-1"
                      >tabler-check</v-icon
                    >
                    <div>
                      <h4 class="font-weight-bold">
                        Vente de produits artisanaux
                      </h4>
                      <p class="text-grey-darken-1">
                        Créez votre boutique en ligne facilement
                      </p>
                    </div>
                  </div>
                  <div class="d-flex align-start">
                    <v-icon color="primary" class="me-3 mt-1"
                      >tabler-check</v-icon
                    >
                    <div>
                      <h4 class="font-weight-bold">Gestion des commandes</h4>
                      <p class="text-grey-darken-1">
                        Suivez vos ventes et statuts de livraison
                      </p>
                    </div>
                  </div>
                  <div class="d-flex align-start">
                    <v-icon color="primary" class="me-3 mt-1"
                      >tabler-check</v-icon
                    >
                    <div>
                      <h4 class="font-weight-bold">Interface accessible</h4>
                      <p class="text-grey-darken-1">
                        Conçue pour tous types de handicaps
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <div v-if="activeTab === 'centers'">
                <h3 class="text-h4 font-weight-bold mb-6">Interface Centres</h3>
                <div class="ga-4 d-flex flex-column">
                  <div class="d-flex align-start">
                    <v-icon color="primary" class="me-3 mt-1"
                      >tabler-check</v-icon
                    >
                    <div>
                      <h4 class="font-weight-bold">Formulaires d'évaluation</h4>
                      <p class="text-grey-darken-1">
                        Remplissez et gérez vos évaluations patients
                      </p>
                    </div>
                  </div>
                  <div class="d-flex align-start">
                    <v-icon color="primary" class="me-3 mt-1"
                      >tabler-check</v-icon
                    >
                    <div>
                      <h4 class="font-weight-bold">
                        Génération PDF automatique
                      </h4>
                      <p class="text-grey-darken-1">
                        Rapports professionnels en un clic
                      </p>
                    </div>
                  </div>
                  <div class="d-flex align-start">
                    <v-icon color="primary" class="me-3 mt-1"
                      >tabler-check</v-icon
                    >
                    <div>
                      <h4 class="font-weight-bold">Gestion d'abonnement</h4>
                      <p class="text-grey-darken-1">
                        Accès sécurisé selon votre forfait
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <div v-if="activeTab === 'admin'">
                <h3 class="text-h4 font-weight-bold mb-6">
                  Panneau d'Administration
                </h3>
                <div class="ga-4 d-flex flex-column">
                  <div class="d-flex align-start">
                    <v-icon color="primary" class="me-3 mt-1"
                      >tabler-check</v-icon
                    >
                    <div>
                      <h4 class="font-weight-bold">Gestion des utilisateurs</h4>
                      <p class="text-grey-darken-1">
                        Contrôle total des comptes et permissions
                      </p>
                    </div>
                  </div>
                  <div class="d-flex align-start">
                    <v-icon color="primary" class="me-3 mt-1"
                      >tabler-check</v-icon
                    >
                    <div>
                      <h4 class="font-weight-bold">Suivi des abonnements</h4>
                      <p class="text-grey-darken-1">
                        Gérez les paiements et renouvellements
                      </p>
                    </div>
                  </div>
                  <div class="d-flex align-start">
                    <v-icon color="primary" class="me-3 mt-1"
                      >tabler-check</v-icon
                    >
                    <div>
                      <h4 class="font-weight-bold">Analytics avancés</h4>
                      <p class="text-grey-darken-1">
                        Tableaux de bord et rapports détaillés
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </v-col>

            <v-col cols="12" lg="6" class="d-flex justify-center">
              <v-card class="pa-8 gradient-border" width="300" height="320">
                <div class="text-center">
                  <v-avatar class="gradient-avatar mb-4" size="80">
                    <v-icon
                      color="white"
                      size="40"
                      :icon="getTabIcon(activeTab)"
                    />
                  </v-avatar>
                  <h4 class="text-h5 font-weight-bold mb-2">
                    {{ getTabTitle(activeTab) }}
                  </h4>
                  <p class="text-grey-darken-1">
                    {{ getTabDescription(activeTab) }}
                  </p>
                </div>
              </v-card>
            </v-col>
          </v-row>
        </v-card>
      </v-container>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-16 bg-grey-lighten-5">
      <v-container>
        <div class="text-center mb-12">
          <h2 class="section-title mb-4">
            Tarifs
            <span class="gradient-text">transparents</span>
          </h2>
          <p class="text-h6 text-grey-darken-1">
            Choisissez le plan qui correspond à vos besoins
          </p>
        </div>

        <v-row justify="center">
          <!-- Plan Utilisateur -->
          <v-col cols="12" md="4">
            <v-card class="pricing-card pa-8 h-100" elevation="4">
              <div class="text-center mb-8">
                <v-avatar class="bg-light-green-lighten-4 mb-4" size="64">
                  <v-icon color="primary" size="32">tabler-user</v-icon>
                </v-avatar>
                <h3 class="text-h4 font-weight-bold mb-2">Utilisateur</h3>
                <p class="text-grey-darken-1 mb-4">
                  Pour les artisans handicapés
                </p>
                <div class="text-h2 font-weight-bold mb-2">Gratuit</div>
                <p class="text-grey-darken-1">À vie</p>
              </div>

              <v-list class="bg-transparent mb-8">
                <v-list-item
                  v-for="feature in userFeatures"
                  :key="feature"
                  class="pa-0 mb-2"
                >
                  <template #prepend>
                    <v-icon color="primary" size="20" class="me-3"
                      >tabler-check</v-icon
                    >
                  </template>
                  <v-list-item-title class="text-body-1">{{
                    feature
                  }}</v-list-item-title>
                </v-list-item>
              </v-list>

              <v-btn
                block
                color="primary"
                size="large"
                class="font-weight-bold"
                href="/register"
              >
                S'inscrire gratuitement
              </v-btn>
            </v-card>
          </v-col>

          <!-- Plan Centre -->
          <v-col cols="12" md="4">
            <v-card
              class="pricing-card pricing-popular pa-8 h-100 gradient-card"
              elevation="12"
            >
              <v-chip
                class="popular-badge"
                color="yellow-darken-2"
                size="small"
              >
                Populaire
              </v-chip>

              <div class="text-center mb-8 text-white">
                <v-avatar class="bg-white bg-opacity-20 mb-4" size="64">
                  <v-icon color="white" size="32">tabler-file</v-icon>
                </v-avatar>
                <h3 class="text-h4 font-weight-bold mb-2">Centre</h3>
                <p class="text-light-green-lighten-2 mb-4">
                  Pour les centres d'évaluation
                </p>
                <div class="text-h2 font-weight-bold mb-2">4000 DZD</div>
                <p class="text-light-green-lighten-2">Par mois</p>
              </div>

              <v-list class="bg-transparent mb-8">
                <v-list-item
                  v-for="feature in centerFeatures"
                  :key="feature"
                  class="pa-0 mb-2 text-white"
                >
                  <template #prepend>
                    <v-icon color="light-green-lighten-2" size="20" class="me-3"
                      >tabler-check</v-icon
                    >
                  </template>
                  <v-list-item-title class="text-body-1">{{
                    feature
                  }}</v-list-item-title>
                </v-list-item>
              </v-list>

              <v-btn
                block
                color="white"
                size="large"
                class="font-weight-bold text-primary"
                href="/register"
              >
                Commencer l'essai
              </v-btn>
            </v-card>
          </v-col>

          <!-- Plan Entreprise -->
          <v-col cols="12" md="4">
            <v-card class="pricing-card pa-8 h-100" elevation="4">
              <div class="text-center mb-8">
                <v-avatar class="bg-light-green-lighten-4 mb-4" size="64">
                  <v-icon color="primary" size="32">tabler-shield-check</v-icon>
                </v-avatar>
                <h3 class="text-h4 font-weight-bold mb-2">Entreprise</h3>
                <p class="text-grey-darken-1 mb-4">Pour les organisations</p>
                <div class="text-h2 font-weight-bold mb-2">Sur devis</div>
                <p class="text-grey-darken-1">Personnalisé</p>
              </div>

              <v-list class="bg-transparent mb-8">
                <v-list-item
                  v-for="feature in enterpriseFeatures"
                  :key="feature"
                  class="pa-0 mb-2"
                >
                  <template #prepend>
                    <v-icon color="primary" size="20" class="me-3"
                      >tabler-check</v-icon
                    >
                  </template>
                  <v-list-item-title class="text-body-1">{{
                    feature
                  }}</v-list-item-title>
                </v-list-item>
              </v-list>

              <v-btn
                block
                color="primary"
                size="large"
                class="font-weight-bold"
              >
                Nous contacter
              </v-btn>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="py-16 bg-white">
      <v-container>
        <div class="text-center mb-12">
          <h2 class="section-title mb-4">
            Ils nous font
            <span class="gradient-text">confiance</span>
          </h2>
          <p class="text-h6 text-grey-darken-1">
            Découvrez les témoignages de nos utilisateurs satisfaits
          </p>
        </div>

        <v-row>
          <v-col
            v-for="(testimonial, index) in testimonials"
            :key="index"
            cols="12"
            md="4"
          >
            <v-card class="testimonial-card pa-8 h-100" elevation="4">
              <div class="d-flex mb-4">
                <v-icon
                  v-for="i in 5"
                  :key="i"
                  color="yellow-darken-2"
                  size="20"
                  class="me-1"
                >
                  tabler-star
                </v-icon>
              </div>
              <p class="text-body-1 text-grey-darken-1 mb-6 font-italic">
                "{{ testimonial.content }}"
              </p>
              <div class="d-flex align-center">
                <v-avatar class="me-4" size="48">
                  <v-img :src="testimonial.image" :alt="testimonial.name" />
                </v-avatar>
                <div>
                  <h4 class="font-weight-bold">{{ testimonial.name }}</h4>
                  <p class="text-caption text-grey-darken-1">
                    {{ testimonial.role }}
                  </p>
                </div>
              </div>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-16">
      <v-container class="text-center">
        <div style="max-inline-size: 800px" class="mx-auto">
          <h2 class="text-h2 font-weight-bold text-white mb-6">
            Prêt à commencer votre aventure ?
          </h2>
          <p class="text-h6 text-blue-lighten-2 mb-8">
            Rejoignez des milliers d'utilisateurs qui font confiance à TNDMarket
            pour votre entreprise dès aujourd'hui
          </p>
          <div class="d-flex flex-column flex-sm-row ga-4 justify-center">
            <v-btn
              size="x-large"
              color="white"
              class="font-weight-bold text-primary"
              append-icon="tabler-arrow-right"
            >
              Commencer maintenant
            </v-btn>
            <v-btn
              size="x-large"
              variant="outlined"
              color="white"
              prepend-icon="tabler-phone"
              class="font-weight-bold"
            >
              Nous contacter
            </v-btn>
          </div>
        </div>
      </v-container>
    </section>

    <!-- Footer -->
    <footer class="bg-grey-darken-4 text-black py-12">
      <v-container>
        <v-divider class="my-8 border-grey-darken-2" />

        <div
          class="d-flex flex-column flex-sm-row justify-space-between align-center"
        >
          <p class="text-black mb-4 mb-sm-0">
            © 2024 TNDMarket. Tous droits réservés.
          </p>
          <div class="d-flex ga-6">
            <span class="text-grey-lighten-1 text-body-2"
              >Conditions d'utilisation</span
            >
            <span class="text-grey-lighten-1 text-body-2"
              >Politique de confidentialité</span
            >
            <span class="text-grey-lighten-1 text-body-2">Cookies</span>
          </div>
        </div>
      </v-container>
    </footer>
  </div>
</template>

<script>
export default {
  name: "TNDMarketLanding",
  data() {
    return {
      drawer: false,
      activeTab: "users",

      // Products data
      products: [],
      loadingProducts: false,
      productsError: null,
      productsToShow: 8,

      // Navigation
      navItems: [
        { title: "Accueil", href: "#home" },
        { title: "Produits", href: "/nosproducts" },
        { title: "Fonctionnalités", href: "#features" },
        { title: "Tarifs", href: "#pricing" },
        { title: "Témoignages", href: "#testimonials" },
        { title: "Contact", href: "#contact" },
      ],

      // Stats
      stats: [
        { number: "500+", label: "Utilisateurs actifs" },
        { number: "50+", label: "Centres partenaires" },
        { number: "1000+", label: "Produits vendus" },
        { number: "98%", label: "Satisfaction client" },
      ],

      // Features
      features: [
        {
          icon: "tabler-users",
          title: "Interface Utilisateur",
          description:
            "Vendez vos produits artisanaux facilement avec une interface adaptée à vos besoins.",
          color: "bg-blue",
        },
        {
          icon: "tabler-file",
          title: "Interface Centres",
          description:
            "Gérez vos évaluations et générez des rapports PDF professionnels automatiquement.",
          color: "bg-green",
        },
        {
          icon: "tabler-shield",
          title: "Panneau Admin",
          description:
            "Contrôlez et supervisez toute la plateforme avec des outils de gestion avancés.",
          color: "bg-purple",
        },
      ],

      // Tabs
      tabs: [
        { id: "users", label: "Utilisateurs", icon: "tabler-users" },
        { id: "centers", label: "Centres", icon: "tabler-hospital" },
        { id: "admin", label: "Admin", icon: "tabler-shield" },
      ],

      // Pricing features
      userFeatures: [
        "Boutique en ligne gratuite",
        "Gestion des produits illimitée",
        "Interface accessible",
        "Support par email",
        "Formation gratuite",
      ],

      centerFeatures: [
        "Formulaires d'évaluation avancés",
        "Génération PDF automatique",
        "Gestion de 100 patients/mois",
        "Support prioritaire",
        "Formation personnalisée",
        "Sauvegarde cloud sécurisée",
      ],

      enterpriseFeatures: [
        "Tout du plan Centre",
        "Utilisateurs illimités",
        "API personnalisée",
        "Support 24/7",
        "Formation sur site",
        "Intégrations sur mesure",
      ],

      // Testimonials
      testimonials: [
        {
          name: "Amina Boudjemaa",
          role: "Artisane, Alger",
          content:
            "TNDMarket m'a permis de vendre mes créations en ligne facilement. L'interface est vraiment adaptée à mes besoins.",
          image: "/api/placeholder/48/48",
        },
        {
          name: "Dr. Mohamed Khaldi",
          role: "Centre de Rééducation, Oran",
          content:
            "La génération automatique de rapports nous fait gagner énormément de temps. Un outil indispensable.",
          image: "/api/placeholder/48/48",
        },
        {
          name: "Sarah Bencheikh",
          role: "Administratrice, Constantine",
          content:
            "Le panneau d'administration est très complet. Nous avons une vue d'ensemble parfaite de nos activités.",
          image: "/api/placeholder/48/48",
        },
      ],

      // Footer links
      socialLinks: [
        { name: "Facebook", icon: "tabler-facebook" },
        { name: "Twitter", icon: "tabler-x" },
        { name: "LinkedIn", icon: "tabler-linkedin" },
        { name: "Instagram", icon: "tabler-instagram" },
      ],

      productLinks: [
        "Interface Utilisateur",
        "Interface Centres",
        "Panneau Admin",
        "API",
        "Intégrations",
      ],

      companyLinks: [
        "À propos",
        "Notre équipe",
        "Carrières",
        "Presse",
        "Partenaires",
      ],

      supportLinks: [
        "Centre d'aide",
        "Documentation",
        "Communauté",
        "Formation",
        "Status",
      ],

      legalLinks: [
        "Conditions générales",
        "Politique de confidentialité",
        "Mentions légales",
        "RGPD",
        "Cookies",
      ],
    };
  },

  computed: {
    displayedProducts() {
      return this.products.slice(0, this.productsToShow);
    },
  },

  mounted() {
    this.fetchProducts();
  },

  methods: {
    // Products methods
    async fetchProducts() {
      this.loadingProducts = true;
      this.productsError = null;

      try {
        const response = await fetch("/api/products/all");
        if (!response.ok) {
          throw new Error("Erreur lors du chargement des produits");
        }
        const data = await response.json();
        this.products = data.products || data || [];
        console.log("Produits chargés:", this.products);
      } catch (error) {
        console.error("Erreur API:", error);
        this.productsError = error.message;
        // Données de démonstration en cas d'erreur
        this.products = this.getMockProducts();
      } finally {
        this.loadingProducts = false;
      }
    },

    getMockProducts() {
      return [
        {
          id: 1,
          title: "Poterie Artisanale Berbère",
          description:
            "Magnifique poterie traditionnelle fabriquée à la main selon les techniques ancestrales berbères.",
          price: 2500,
          stock: 5,
          photo: "/api/placeholder/300/200",
          user: { name: "Fatima Amellal" },
        },
        {
          id: 2,
          title: "Tapis Tissé Main",
          description:
            "Tapis traditionnel algérien tissé à la main avec des motifs authentiques et des couleurs vives.",
          price: 15000,
          stock: 2,
          photo: "/api/placeholder/300/200",
          user: { name: "Ahmed Benali" },
        },
        {
          id: 3,
          title: "Bijoux en Argent",
          description:
            "Collection de bijoux en argent travaillés selon les traditions kabyles.",
          price: 4500,
          stock: 8,
          photo: "/api/placeholder/300/200",
          user: { name: "Malika Touati" },
        },
        {
          id: 4,
          title: "Panier en Osier",
          description:
            "Panier artisanal tressé en osier naturel, parfait pour la décoration ou le rangement.",
          price: 1200,
          stock: 0,
          photo: "/api/placeholder/300/200",
          user: { name: "Youcef Madani" },
        },
      ];
    },

    getProductImageUrl(photo) {
      if (!photo) return "/api/placeholder/300/200";
      if (photo.startsWith("http")) return photo;
      return `storage/${photo}`;
    },

    formatPrice(price) {
      return new Intl.NumberFormat("fr-DZ").format(price);
    },

    loadMoreProducts() {
      this.productsToShow += 8;
    },

    // Tab methods
    getTabIcon(tabId) {
      const tab = this.tabs.find((t) => t.id === tabId);
      return tab ? tab.icon : "tabler-help";
    },

    getTabTitle(tabId) {
      const titles = {
        users: "Interface Utilisateur",
        centers: "Interface Centres",
        admin: "Panneau Admin",
      };
      return titles[tabId] || "Interface";
    },

    getTabDescription(tabId) {
      const descriptions = {
        users:
          "Conçue spécifiquement pour les artisans handicapés avec une interface intuitive et accessible.",
        centers:
          "Outils professionnels pour les centres d'évaluation avec génération automatique de rapports.",
        admin:
          "Contrôle complet de la plateforme avec des analytics avancés et une gestion centralisée.",
      };
      return descriptions[tabId] || "Description de l'interface";
    },
  },
};
</script>

<style scoped>
/* Global Styles */
.gradient-text {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  background-clip: text;
  -webkit-text-fill-color: transparent;
}

.gradient-avatar {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.gradient-btn {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white !important;
}

.gradient-chip {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.gradient-border {
  border: 2px solid;
  border-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%) 1;
}

.gradient-card {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Navigation */
.navbar-blur {
  backdrop-filter: blur(10px);
}

/* Hero Section */
.hero-section {
  display: flex;
  align-items: center;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  min-block-size: 100vh;
}

.hero-title {
  font-size: 3.5rem !important;
  font-weight: 800;
  line-height: 1.2;
}

.hero-card {
  transform: rotate(-2deg);
  transition: transform 0.3s ease;
}

.hero-card:hover {
  transform: rotate(0deg) scale(1.05);
}

.rotate-card {
  animation: float 6s ease-in-out infinite;
}

@keyframes float {
  0%,
  100% {
    transform: rotate(-2deg) translateY(0);
  }

  50% {
    transform: rotate(-2deg) translateY(-10px);
  }
}

/* Products Section */
.product-card {
  overflow: hidden;
  transition: all 0.3s ease;
}

.product-card:hover {
  box-shadow: 0 12px 40px rgba(0, 0, 0, 15%) !important;
  transform: translateY(-8px);
}

.product-image-container {
  position: relative;
  overflow: hidden;
}

.product-image {
  transition: transform 0.3s ease;
}

.product-card:hover .product-image {
  transform: scale(1.1);
}

.stock-badge {
  position: absolute;
  z-index: 2;
  inset-block-start: 8px;
  inset-inline-end: 8px;
}

.line-clamp-2 {
  display: -webkit-box;
  overflow: hidden;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
  line-clamp: 2;
}

.line-clamp-3 {
  display: -webkit-box;
  overflow: hidden;
  -webkit-line-clamp: 3;
  line-clamp: 3;
}

/* Features Section */
.feature-card {
  border-radius: 16px !important;
  transition: all 0.3s ease;
}

.feature-card:hover {
  box-shadow: 0 12px 40px rgba(0, 0, 0, 15%) !important;
  transform: translateY(-8px);
}

/* Section Titles */
.section-title {
  font-size: 2.5rem !important;
  font-weight: 800;
  line-height: 1.2;
}

/* Pricing Cards */
.pricing-card {
  position: relative;
  border-radius: 16px !important;
  transition: all 0.3s ease;
}

.pricing-card:hover {
  box-shadow: 0 12px 40px rgba(0, 0, 0, 15%) !important;
  transform: translateY(-8px);
}

.pricing-popular {
  position: relative;
  transform: scale(1.05);
}

.popular-badge {
  position: absolute;
  z-index: 2;
  inset-block-start: -10px;
  inset-inline-start: 50%;
  transform: translateX(-50%);
}

/* Testimonials */
.testimonial-card {
  border-radius: 16px !important;
  transition: all 0.3s ease;
}

.testimonial-card:hover {
  box-shadow: 0 8px 30px rgba(0, 0, 0, 12%) !important;
  transform: translateY(-4px);
}

/* CTA Section */
.cta-section {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Responsive */
@media (max-width: 960px) {
  .hero-title {
    font-size: 2.5rem !important;
  }

  .section-title {
    font-size: 2rem !important;
  }

  .hero-card {
    margin-block-start: 2rem;
    transform: none;
  }

  .rotate-card {
    animation: none;
  }
}

@media (max-width: 600px) {
  .hero-title {
    font-size: 2rem !important;
  }

  .section-title {
    font-size: 1.75rem !important;
  }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
  .hero-card,
  .rotate-card,
  .feature-card,
  .pricing-card,
  .testimonial-card,
  .product-card {
    animation: none;
    transition: none;
  }

  .hero-card:hover,
  .feature-card:hover,
  .pricing-card:hover,
  .testimonial-card:hover,
  .product-card:hover {
    transform: none;
  }
}

/* Focus states for accessibility */
.v-btn:focus-visible {
  outline: 2px solid #667eea;
  outline-offset: 2px;
}

/* High contrast mode support */
@media (prefers-contrast: high) {
  .gradient-text {
    background: none;
    color: #1976d2;
    -webkit-text-fill-color: unset;
  }

  .gradient-btn {
    background: #1976d2 !important;
  }
}
</style>
