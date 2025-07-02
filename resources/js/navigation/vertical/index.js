export default [
  // {
  //   title: 'Home',
  //   to: { name: 'root' },
  //   icon: { icon: 'tabler-smart-home' },
  // },
  // {
  //   title: 'Second page',
  //   to: { name: 'second-page' },
  //   icon: { icon: 'tabler-file' },
  // },
   {
    title: 'Accueil',
    to: { name: 'dashboard' },
    icon: { icon: 'tabler-smart-home' },
    role: ['admin', 'user', 'centre'] // Accessible à tous
  },

  // Liens pour les utilisateurs normaux (role: user)
  {
    title: 'Mes Produits',
    to: { name: 'product' },
    icon: { icon: 'tabler-shopping-cart' },
    role: ['user']
  },
  {
    title: 'Mes Commandes',
    to: { name: 'orders' },
    icon: { icon: 'tabler-file-invoice' },
    role: ['user']
  },

  // Liens pour les centres (role: centre)
  {
    title: 'Formulaires',
    to: { name: 'forms' },
    icon: { icon: 'tabler-clipboard-text' },
    role: ['centre']
  },

  // Liens pour les administrateurs (role: admin)
  {
    title: 'Administration',
    header: true,
    role: ['admin']
  },
  {
    title: 'Tableau de bord',
    to: { name: 'dashboard' },
    icon: { icon: 'tabler-chart-pie' },
    role: ['admin']
  },
  // {
  //   title: 'Centres',
  //   to: { name: 'centres' },
  //   icon: { icon: 'tabler-building-hospital' },
  //   role: ['admin']
  // },
  {
    title: 'Abonnements',
    to: { name: 'admin-subscriptions' },
    icon: { icon: 'tabler-credit-card' },
    role: ['admin']
  },
  {
    title: 'Types de formulaires',
    to: { name: 'admin-form-types' },
    icon: { icon: 'tabler-forms' },
    role: ['admin']
  }
]
