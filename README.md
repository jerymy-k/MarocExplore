# MarocExplore - API de Gestion des Itinéraires Touristiques

![Laravel](https://img.shields.io/badge/Laravel-12.0-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![Sanctum](https://img.shields.io/badge/Sanctum-Authentication-green)

## 📋 Description du Projet

**MarocExplore** est une plateforme API RESTful développée avec Laravel 12 pour la promotion du tourisme au Maroc. Elle permet aux voyageurs de créer, partager et découvrir des itinéraires touristiques personnalisés.

---

## 🚀 Fonctionnalités

### 1. Authentification Sanctum
- Inscription utilisateur
- Connexion/Déconnexion
- Rafraîchissement de token
- Gestion de profil

### 2. Gestion des Itinéraires
- Création d'itinéraires avec titre, catégorie, durée, image et description
- Minimum 2 destinati
ons par itinéraire
- Modification/suppression (propriétaire uniquement)
- Liste des itinéraires publics

### 3. Gestion des Destinations
- Nom, hébergement, lieux à visiter
- Activités et plats à essayer
- Ordre dans l'itinéraire

### 4. Liste de Favoris ("Wishlist")
- Ajout/retrait d'itinéraires aux favoris
- Consultation de la liste personnelle

### 5. Système de Notation et Commentaires
- Notation de 1 à 5 étoiles
- Commentaires optionnels
- Moyenne des notes

### 6. Recherche et Filtrage
- Filtrage par catégorie (plage, montagne, rivière, monument, etc.)
- Filtrage par durée
- Recherche par mot-clé dans le titre
- Tri par date, popularité

### 7. Statistiques
- Itinéraires par catégorie
- Utilisateurs inscrits par mois
- Itinéraires les plus populaires
- Tableau de bord



---

## 📁 Structure du Projet

```
MarocExplore/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/
│   │           ├── AuthController.php
│   │           ├── ItineraryController.php
│   │           ├── WishlistController.php
│   │           ├── ReviewController.php
│   │           ├── StatisticsController.php
│   │           └── RecommendationController.php
│   └── Models/
│       ├── User.php
│       ├── Itinerary.php
│       ├── Destination.php
│       ├── Wishlist.php
│       └── Review.php
├── database/
│   ├── migrations/
│   └── factories/
├── routes/
│   └── api.php
├── config/
│   ├── auth.php
│   └── jwt.php
├── tests/
│   ├── Unit/
│   └── Feature/Api/
└── TODO.md
```

---

## 🛠️ Installation

### Prérequis
- PHP 8.2+
- Composer
- Laravel 12

### Étapes d'installation

1. **Cloner le projet**
```bash
git clone <repository-url>
cd MarocExplore
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configurer l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```



5. **Exécuter les migrations**
```bash
php artisan migrate
```

6. **Lancer le serveur**
```bash
php artisan serve
```

---

## 📡 Documentation de l'API

### Routes Publiques

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| POST | `/api/auth/register` | Inscription |
| POST | `/api/auth/login` | Connexion |
| GET | `/api/itineraries` | Liste des itinéraires |
| GET | `/api/itineraries/{id}` | Détails d'un itinéraire |
| GET | `/api/itineraries/{id}/reviews` | Avis sur un itinéraire |
| GET | `/api/statistics/dashboard` | Tableau de bord |
| GET | `/api/statistics/itineraries-by-category` | Itinéraires par catégorie |
| GET | `/api/statistics/users-by-month` | Utilisateurs par mois |
| GET | `/api/statistics/popular-itineraries` | Itinéraires populaires |
| GET | `/api/recommendations/trending` | Itinéraires tendances |

### Routes Protégées (JWT requis)

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| POST | `/api/auth/logout` | Déconnexion |
| GET | `/api/auth/me` | Profil utilisateur |
| POST | `/api/auth/refresh` | Rafraîchir token |
| GET | `/api/my/itineraries` | Mes itinéraires |
| POST | `/api/itineraries` | Créer un itinéraire |
| PUT | `/api/itineraries/{id}` | Modifier un itinéraire |
| DELETE | `/api/itineraries/{id}` | Supprimer un itinéraire |
| GET | `/api/wishlist` | Ma liste de favoris |
| POST | `/api/wishlist/{id}` | Ajouter aux favoris |
| DELETE | `/api/wishlist/{id}` | Retirer des favoris |
| POST | `/api/itineraries/{id}/reviews` | Ajouter un avis |
| GET | `/api/recommendations/personalized` | Recommandations personnalisées |

---

## 📝 Exemples d'Utilisation

### 1. Inscription
```bash
curl -X POST http://127.0.0.1:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 2. Connexion
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### 3. Créer un Itinéraire
```bash
curl -X POST http://127.0.0.1:8000/api/itineraries \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Aventure au Maroc",
    "category": "aventure",
    "duration": 7,
    "image": "https://example.com/maroc.jpg",
    "description": "Un voyage inoubliable",
    "destinations": [
      {
        "name": "Marrakech",
        "accommodation": "Riad Yasmine",
        "places_to_visit": ["Jemaa el-Fnaa", "Majorelle Garden"],
        "activities": ["Shopping", "Food tour"],
        "food_to_try": ["Tagine", "Couscous"]
      },
      {
        "name": "Essaouira",
        "accommodation": "Hotel Miramar",
        "places_to_visit": ["Medina", "Port"],
        "activities": ["Surf", "Cycling"],
        "food_to_try": ["Fish tagine", "Seafood"]
      }
    ]
  }'
```

### 4. Rechercher des Itinéraires
```bash
curl "http://127.0.0.1:8000/api/itineraries?search=Marrakech&category=ville&max_duration=7"
```

---

## 🧪 Tests

### Exécuter tous les tests
```bash
php artisan test
```

### Tests unitaires
```bash
php artisan test --testsuite=Unit
```

### Tests fonctionnels API
```bash
php artisan test --testsuite=Feature
```

---

## 📊 Catégories d'Itinéraires

- `plage` - Plages et bords de mer
- `montagne` - Montagnes et trekking
- `riviere` - Rivières et sports nautiques
- `monument` - Monuments historiques
- `desert` - Désert et oasis
- `ville` - Visites urbaines
- `village` - Villages traditionnels
- `nature` - Nature et wildlife
- `culture` - Immersion culturelle
- `aventure` - Aventure et adrenaline

---

## 📈 Query Builder (Routes de Statistiques)

| Endpoint | Description |
|----------|-------------|
| `GET /api/statistics/itineraries-with-destinations` | Tous les itinéraires avec destinations |
| `GET /api/statistics/filter-itineraries?category=plage&max_duration=5` | Filtrer par catégorie/durée |
| `GET /api/statistics/search-itineraries?keyword=Marrakech` | Rechercher par mot-clé |
| `GET /api/statistics/popular-itineraries` | Itinéraires les plus aimés |
| `GET /api/statistics/itineraries-by-category` | Nombre d'itinéraires par catégorie |
| `GET /api/statistics/users-by-month?year=2026` | Utilisateurs par mois |
| `GET /api/statistics/dashboard` | Statistiques globales |

---

## 🔒 Sécurité

- Authentification JWT avec tokens sécurisés
- Hachage des mots de passe avec bcrypt
- Validation des entrées utilisateur
- Protection CSRF
- Autorisation basée sur les propriétaire des ressources

---

## 📝 License

Ce projet est développé dans le cadre d'un projet académique.

---

## 👤 Auteur

- Nom: [Votre Nom]
- Projet: MarocExplore - Plateforme Touristique Marocaine

---

## 📚 Technologies Utilisées

- **Backend:** Laravel 12, PHP 8.2+
- **Authentification:** Laravel Sanctum
- **Base de données:** SQLite (défaut), MySQL, PostgreSQL
- **Tests:** PHPUnit, Laravel Dusk
- **Documentation:** Postman, Swagger

