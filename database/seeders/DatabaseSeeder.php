<?php

namespace Database\Seeders;

use App\Models\Centre;
use App\Models\Form;
use App\Models\FormType;
use App\Models\Order;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer l'admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Créer 5 utilisateurs normaux
        User::factory()
            ->count(5)
            ->create(['role' => 'user']);

        // Créer 3 centres avec abonnements
        User::factory()
            ->count(3)
            ->create(['role' => 'centre'])
            ->each(function ($user) {
                $centre = Centre::create([
                    'user_id' => $user->id,
                    'name' => fake()->company(),
                    'address' => fake()->address(),
                    'phone' => fake()->phoneNumber(),
                    'description' => fake()->paragraph(),
                ]);

                Subscription::create([
                    'centre_id' => $centre->id,
                    'status' => 'active',
                    'start_date' => now(),
                    'end_date' => now()->addYear(),
                    'price' => fake()->randomFloat(2, 100, 500),
                ]);
            });

        // Créer les types de formulaires de bilan
        $cognitive = FormType::create([
            'name' => 'Bilan Cognitif',
            'description' => 'Évaluation cognitive complète',
            'fields' => [
                ['name' => 'attention_soutenue', 'type' => 'number', 'label' => 'Attention soutenue'],
                ['name' => 'memoire_travail', 'type' => 'number', 'label' => 'Mémoire de travail'],
                ['name' => 'flexibilite_cognitive', 'type' => 'number', 'label' => 'Flexibilité cognitive'],
                ['name' => 'planification', 'type' => 'number', 'label' => 'Planification'],
                ['name' => 'inhibition', 'type' => 'number', 'label' => 'Inhibition'],
                ['name' => 'raisonnement', 'type' => 'number', 'label' => 'Raisonnement'],
                ['name' => 'observations_cognitives', 'type' => 'textarea', 'label' => 'Observations cognitives']
            ],
            'is_active' => true,
        ]);

        $autonomie = FormType::create([
            'name' => 'Échelle École/Travail - Autonomie',
            'description' => 'Évaluation de l\'autonomie scolaire et professionnelle',
            'fields' => [
                // Compétences professionnelles
                ['name' => 'triage_objets', 'type' => 'select', 'label' => 'Triage d\'objets', 'options' => ['R', 'Em', 'E']],
                ['name' => 'assemblage', 'type' => 'select', 'label' => 'Assemblage', 'options' => ['R', 'Em', 'E']],
                ['name' => 'assemblage_classement', 'type' => 'select', 'label' => 'Assemblage pour le classement', 'options' => ['R', 'Em', 'E']],
                ['name' => 'outils_simples', 'type' => 'select', 'label' => 'Utilise des appareils ou des outils simples', 'options' => ['R', 'Em', 'E']],
                ['name' => 'suit_dimensions', 'type' => 'select', 'label' => 'Suit les dimensions', 'options' => ['R', 'Em', 'E']],
                ['name' => 'mesures', 'type' => 'select', 'label' => 'Mesures', 'options' => ['R', 'Em', 'E']],
                ['name' => 'etiquetage', 'type' => 'select', 'label' => 'Étiquetage', 'options' => ['R', 'Em', 'E']],
                ['name' => 'nettoie_espace', 'type' => 'select', 'label' => 'Nettoie son espace de travail', 'options' => ['R', 'Em', 'E']],

                // Autonomie
                ['name' => 'toilettes', 'type' => 'select', 'label' => 'Toilettes', 'options' => ['R', 'Em', 'E']],
                ['name' => 'mange_seul', 'type' => 'select', 'label' => 'Mange seul', 'options' => ['R', 'Em', 'E']],
                ['name' => 'mange_a_table', 'type' => 'select', 'label' => 'Mange à table', 'options' => ['R', 'Em', 'E']],
                ['name' => 'deplace_autonomie', 'type' => 'select', 'label' => 'Se déplace avec autonomie', 'options' => ['R', 'Em', 'E']],
                ['name' => 'gestion_vetements', 'type' => 'select', 'label' => 'Gestion des vêtements et horaires', 'options' => ['R', 'Em', 'E']],
                ['name' => 'comportement_transitions', 'type' => 'select', 'label' => 'Comportement au cours des transitions', 'options' => ['R', 'Em', 'E']],
                ['name' => 'adapte_modifications', 'type' => 'select', 'label' => 'S\'adapte aux modifications de sa routine', 'options' => ['R', 'Em', 'E']],
                ['name' => 'comportement_public', 'type' => 'select', 'label' => 'Comportement en public', 'options' => ['R', 'Em', 'E']],

                // Activités de loisirs
                ['name' => 'temps_libre', 'type' => 'select', 'label' => 'Utilisation du temps libre', 'options' => ['R', 'Em', 'E']],
                ['name' => 'activites_solitaires', 'type' => 'select', 'label' => 'Activités solitaires', 'options' => ['R', 'Em', 'E']],
                ['name' => 'jeux_regles', 'type' => 'select', 'label' => 'Jeux avec règles', 'options' => ['R', 'Em', 'E']],
                ['name' => 'temps_pause', 'type' => 'select', 'label' => 'Repos au temps de pause', 'options' => ['R', 'Em', 'E']],
                ['name' => 'activites_groupe', 'type' => 'select', 'label' => 'Activités de groupe', 'options' => ['R', 'Em', 'E']],
                ['name' => 'sports_automatique', 'type' => 'select', 'label' => 'Sports/Distributeur automatique', 'options' => ['R', 'Em', 'E']],
                ['name' => 'activites_loisirs', 'type' => 'select', 'label' => 'Apprentissage de nouvelles activités de loisirs', 'options' => ['R', 'Em', 'E']],
                ['name' => 'activites_exterieur', 'type' => 'select', 'label' => 'Activités à l\'extérieur', 'options' => ['R', 'Em', 'E']],

                // Comportement professionnel
                ['name' => 'comportement_temps', 'type' => 'select', 'label' => 'Comportement dans le temps', 'options' => ['R', 'Em', 'E']],
                ['name' => 'rythme_travail', 'type' => 'select', 'label' => 'Rythme de travail', 'options' => ['R', 'Em', 'E']],
                ['name' => 'pourcentage_arrets', 'type' => 'select', 'label' => 'Pourcentage d\'arrêts', 'options' => ['R', 'Em', 'E']],
                ['name' => 'regles_securite', 'type' => 'select', 'label' => 'Règles de sécurité', 'options' => ['R', 'Em', 'E']],
                ['name' => 'respect_reglements', 'type' => 'select', 'label' => 'Respect des biens et du contrôl, des règles et règlements', 'options' => ['R', 'Em', 'E']],
                ['name' => 'travail_proximite', 'type' => 'select', 'label' => 'Travail à proximité d\'autres personnes', 'options' => ['R', 'Em', 'E']],
                ['name' => 'relations_superviseurs', 'type' => 'select', 'label' => 'Relations avec les superviseurs et la hiérarchie', 'options' => ['R', 'Em', 'E']],

                // Communication fonctionnelle
                ['name' => 'communication_besoins', 'type' => 'select', 'label' => 'Communication des besoins élémentaires', 'options' => ['R', 'Em', 'E']],
                ['name' => 'communication_activites', 'type' => 'select', 'label' => 'Communication des besoins au cours des activités', 'options' => ['R', 'Em', 'E']],
                ['name' => 'reactions_instructions', 'type' => 'select', 'label' => 'Réactions à des instructions simples', 'options' => ['R', 'Em', 'E']],
                ['name' => 'reactions_interdits', 'type' => 'select', 'label' => 'Réactions aux interdits', 'options' => ['R', 'Em', 'E']],
                ['name' => 'compte_objets', 'type' => 'select', 'label' => 'Compte les objets', 'options' => ['R', 'Em', 'E']],
                ['name' => 'lit_noms_objets', 'type' => 'select', 'label' => 'Lit les noms d\'objets', 'options' => ['R', 'Em', 'E']],
                ['name' => 'comprend_noms_formes', 'type' => 'select', 'label' => 'Comprend les noms des formes, des couleurs, des grandeurs et des lettres', 'options' => ['R', 'Em', 'E']],
                ['name' => 'instructions_decision', 'type' => 'select', 'label' => 'Instructions nécessitant une prise de décision', 'options' => ['R', 'Em', 'E']],

                // Comportement interpersonnel
                ['name' => 'reagit_presence', 'type' => 'select', 'label' => 'Réagit à la présence d\'autres personnes', 'options' => ['R', 'Em', 'E']],
                ['name' => 'comportement_positif', 'type' => 'select', 'label' => 'Comportement interpersonnel positif avec les proches', 'options' => ['R', 'Em', 'E']],

                ['name' => 'notes_autonomie', 'type' => 'textarea', 'label' => 'Notes et observations'],
            ],
            'is_active' => true,
        ]);

        $sensoriel = FormType::create([
            'name' => 'Bilan Sensoriel',
            'description' => 'Évaluation des capacités sensorielles',
            'fields' => [
                ['name' => 'audition', 'type' => 'select', 'label' => 'Audition', 'options' => ['Normal', 'Léger déficit', 'Déficit modéré', 'Déficit sévère']],
                ['name' => 'vision', 'type' => 'select', 'label' => 'Vision', 'options' => ['Normal', 'Léger déficit', 'Déficit modéré', 'Déficit sévère']],
                ['name' => 'toucher', 'type' => 'select', 'label' => 'Toucher', 'options' => ['Normal', 'Hypersensible', 'Hyposensible']],
                ['name' => 'proprioception', 'type' => 'select', 'label' => 'Proprioception', 'options' => ['Normal', 'Déficit léger', 'Déficit important']],
                ['name' => 'equilibre_vestibulaire', 'type' => 'select', 'label' => 'Équilibre vestibulaire', 'options' => ['Normal', 'Instabilité légère', 'Instabilité importante']],
                ['name' => 'integration_sensorielle', 'type' => 'number', 'label' => 'Score d\'intégration sensorielle (1-10)'],
                ['name' => 'observations_sensorielles', 'type' => 'textarea', 'label' => 'Observations sensorielles']
            ],
            'is_active' => true,
        ]);

        $communication = FormType::create([
            'name' => 'Bilan Communication',
            'description' => 'Évaluation des capacités de communication',
            'fields' => [
                ['name' => 'expression_verbale', 'type' => 'select', 'label' => 'Expression verbale', 'options' => ['Excellente', 'Bonne', 'Moyenne', 'Faible', 'Absente']],
                ['name' => 'comprehension_verbale', 'type' => 'select', 'label' => 'Compréhension verbale', 'options' => ['Excellente', 'Bonne', 'Moyenne', 'Faible', 'Absente']],
                ['name' => 'communication_non_verbale', 'type' => 'select', 'label' => 'Communication non verbale', 'options' => ['Excellente', 'Bonne', 'Moyenne', 'Faible', 'Absente']],
                ['name' => 'contact_visuel', 'type' => 'select', 'label' => 'Contact visuel', 'options' => ['Approprié', 'Évitement', 'Excessif', 'Absent']],
                ['name' => 'tour_de_parole', 'type' => 'select', 'label' => 'Respect du tour de parole', 'options' => ['Toujours', 'Souvent', 'Parfois', 'Rarement', 'Jamais']],
                ['name' => 'initiation_communication', 'type' => 'select', 'label' => 'Initiation de la communication', 'options' => ['Spontanée', 'Avec aide', 'Rare', 'Absente']],
                ['name' => 'observations_communication', 'type' => 'textarea', 'label' => 'Observations communication']
            ],
            'is_active' => true,
        ]);

        $comportemental = FormType::create([
            'name' => 'Bilan Comportemental',
            'description' => 'Évaluation comportementale et sociale',
            'fields' => [
                ['name' => 'adaptation_sociale', 'type' => 'number', 'label' => 'Adaptation sociale (1-10)'],
                ['name' => 'gestion_emotions', 'type' => 'select', 'label' => 'Gestion des émotions', 'options' => ['Excellente', 'Bonne', 'Moyenne', 'Difficile', 'Très difficile']],
                ['name' => 'tolerance_frustration', 'type' => 'select', 'label' => 'Tolérance à la frustration', 'options' => ['Élevée', 'Moyenne', 'Faible', 'Très faible']],
                ['name' => 'comportements_repetitifs', 'type' => 'select', 'label' => 'Comportements répétitifs', 'options' => ['Absents', 'Légers', 'Modérés', 'Importants']],
                ['name' => 'interactions_pairs', 'type' => 'select', 'label' => 'Interactions avec les pairs', 'options' => ['Spontanées', 'Guidées', 'Limitées', 'Évitées']],
                ['name' => 'respect_regles', 'type' => 'select', 'label' => 'Respect des règles', 'options' => ['Toujours', 'Souvent', 'Parfois', 'Rarement', 'Jamais']],
                ['name' => 'observations_comportementales', 'type' => 'textarea', 'label' => 'Observations comportementales']
            ],
            'is_active' => true,
        ]);
        $Apprentissage = FormType::create([
            'name' => 'Bilan Apprentissage',
            'description' => 'Évaluation du comportement d’apprentissage',
            'fields' => [
                ['name' => 'reconnait_son_nom', 'type' => 'select', 'label' => 'Reconnaît son nom', 'options' => ['Oui', 'Non']],
                ['name' => 'comprend_consigne', 'type' => 'select', 'label' => 'Comprend consignes simples', 'options' => ['Oui', 'Non']],
                ['name' => 'repond_question_simples', 'type' => 'select', 'label' => 'Répond à des questions simples', 'options' => ['Oui', 'Non']],
                ['name' => 'exprime_besoins', 'type' => 'select', 'label' => 'Exprime ses besoins', 'options' => ['Oui', 'Non']],
                ['name' => 'reconnait_objets', 'type' => 'select', 'label' => 'Reconnaît les objets', 'options' => ['Oui', 'Non']],
                ['name' => 'reconnait_formes', 'type' => 'select', 'label' => 'Reconnaît les formes, couleurs et lettres', 'options' => ['Oui', 'Non']],
                ['name' => 'utilise_symboles_simples', 'type' => 'select', 'label' => 'Utilise des pictogrammes/symboles simples', 'options' => ['Oui', 'Non']],
                ['name' => 'observations_apprentissage', 'type' => 'textarea', 'label' => 'Observations']
            ],
            'is_active' => true,
        ]);
        $Social = FormType::create([
            'name' => 'Bilan Social',
            'description' => 'Évaluation du comportement social et relationnel',
            'fields' => [
                ['name' => 'reagit_prenom', 'type' => 'select', 'label' => 'Réagit à son prénom', 'options' => ['Oui', 'Non']],
                ['name' => 'regarde_visage', 'type' => 'select', 'label' => 'Regarde le visage', 'options' => ['Oui', 'Non']],
                ['name' => 'interagit_pairs', 'type' => 'select', 'label' => 'Interagit avec ses pairs', 'options' => ['Oui', 'Non']],
                ['name' => 'cherche_contact', 'type' => 'select', 'label' => 'Cherche le contact avec l’adulte', 'options' => ['Oui', 'Non']],
                ['name' => 'rit', 'type' => 'select', 'label' => 'Rit', 'options' => ['Oui', 'Non']],
                ['name' => 'se_controle', 'type' => 'select', 'label' => 'Se contrôle', 'options' => ['Oui', 'Non']],
                ['name' => 'joue_autres', 'type' => 'select', 'label' => 'Joue avec d’autres enfants', 'options' => ['Oui', 'Non']],
                ['name' => 'interagit_physique', 'type' => 'select', 'label' => 'Interaction physique appropriée', 'options' => ['Oui', 'Non']],
                ['name' => 'observations_social', 'type' => 'textarea', 'label' => 'Observations']
            ],
            'is_active' => true,
        ]);
        // Stocker les types de formulaires pour les utiliser plus tard
        $formTypes = [$cognitive, $autonomie, $sensoriel, $communication, $comportemental, $Apprentissage, $Social];

        // Créer des formulaires pour chaque centre
        Centre::all()->each(function ($centre) use ($formTypes) {
            foreach ($formTypes as $formType) {
                Form::factory()
                    ->count(3)
                    ->create([
                        'centre_id' => $centre->id,
                        'form_type_id' => $formType->id,
                        'form_data' => function () use ($formType) {
                            $data = [];

                            foreach ($formType->fields as $field) {
                                switch ($field['type']) {
                                    case 'number':
                                        $data[$field['name']] = fake()->numberBetween(1, 10);
                                        break;
                                    case 'select':
                                        if (isset($field['options'])) {
                                            $data[$field['name']] = fake()->randomElement($field['options']);
                                        } else {
                                            $data[$field['name']] = fake()->word();
                                        }
                                        break;
                                    case 'textarea':
                                        $data[$field['name']] = fake()->paragraph();
                                        break;
                                    default:
                                        $data[$field['name']] = fake()->sentence();
                                }
                            }

                            return $data;
                        }
                    ]);
            }
        });

        // Créer des produits pour les utilisateurs normaux
        User::where('role', 'user')->get()->each(function ($user) {
            Product::factory()
                ->count(3)
                ->create(['user_id' => $user->id]);
        });

        // Créer des commandes
        Product::where('stock', '>', 0)->get()->each(function ($product) {
            Order::factory()
                ->count(2)
                ->create([
                    'product_id' => $product->id,
                    'total_price' => function ($attributes) use ($product) {
                        return $product->price * $attributes['quantity'];
                    }
                ]);

            // Mettre à jour le stock
            $product->refresh();
            $product->stock -= 2;
            $product->save();
        });
        $this->call([
            GuestBuyerSeeder::class,
        ]);
    }
}
