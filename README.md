# 🇲🇦 MarocExplore API

API REST pour la plateforme **MarocExplore** — une plateforme de tourisme au Maroc permettant aux voyageurs de créer et partager des itinéraires touristiques personnalisés.

---

## 🛠️ Technologies

- **PHP 8.3**
- **Laravel 12**
- **MySQL**
- **JWT Authentication** (php-open-source-saver/jwt-auth)
- **Swagger** (darkaonline/l5-swagger)

---

## ⚙️ Installation

### 1. Cloner le projet
```bash
git clone https://github.com/ton-repo/marocexplore.git
cd marocexplore
```

### 2. Installer les dépendances
```bash
composer install
```

### 3. Configurer l'environnement
```bash
cp .env.example .env
```
Modifie le fichier `.env` :
```env
DB_DATABASE=MarocExplore
DB_USERNAME=root
DB_PASSWORD=ton_password

JWT_SECRET=ta_cle_jwt
```

### 4. Générer la clé de l'application
```bash
php artisan key:generate
```

### 5. Générer la clé JWT
```bash
php artisan jwt:secret
```

### 6. Créer les tables
```bash
php artisan migrate
```

### 7. Créer le lien symbolique pour les images
```bash
php artisan storage:link
```

### 8. Lancer le serveur
```bash
php artisan serve
```

---

## 🔐 Authentification

L'API utilise **JWT (JSON Web Token)** pour l'authentification.

Pour accéder aux endpoints protégés, ajoute le token dans le header :
```
Authorization: Bearer ton_token
```

---

## 📡 Endpoints

### Auth

| Method | Endpoint | Description | Protégé |
|--------|----------|-------------|---------|
| POST | `/api/register` | Créer un compte | ❌ |
| POST | `/api/login` | Se connecter | ❌ |
| POST | `/api/logout` | Se déconnecter | ✅ |
| GET | `/api/me` | Voir son profil | ✅ |

### Itinéraires

| Method | Endpoint | Description | Protégé |
|--------|----------|-------------|---------|
| GET | `/api/itineraires` | Liste tous les itinéraires | ❌ |
| GET | `/api/itineraires/{id}` | Voir un itinéraire | ❌ |
| POST | `/api/itineraires` | Créer un itinéraire | ✅ |
| PUT | `/api/itineraires/{id}` | Modifier un itinéraire | ✅ |
| DELETE | `/api/itineraires/{id}` | Supprimer un itinéraire | ✅ |
| POST | `/api/itineraires/{id}/favori` | Ajouter/retirer des favoris | ✅ |

### Recherche & Filtres

| Method | Endpoint | Description | Protégé |
|--------|----------|-------------|---------|
| GET | `/api/search?keyword=xxx` | Rechercher par mot-clé | ❌ |
| GET | `/api/filter?categorie=xxx&duree=xxx` | Filtrer par catégorie et durée | ❌ |
| GET | `/api/popular` | Itinéraires les plus populaires | ❌ |
| GET | `/api/stats/categories` | Stats par catégorie | ❌ |
| GET | `/api/stats/months` | Stats des inscriptions par mois | ❌ |

---

## 📝 Exemples de Requêtes

### Register
```json
POST /api/register
{
    "name": "Ahmed",
    "email": "ahmed@gmail.com",
    "password": "123456",
    "password_confirmation": "123456"
}
```

### Login
```json
POST /api/login
{
    "email": "ahmed@gmail.com",
    "password": "123456"
}
```

### Créer un itinéraire
```
POST /api/itineraires
Content-Type: multipart/form-data

titre: Voyage Marrakech
categorie: monument
duree: 3
image: [fichier image]
destinations[0][nom]: Marrakech
destinations[0][logement]: Hotel Atlas
destinations[0][items][0][nom]: Jemaa el-Fna
destinations[0][items][0][type]: endroit
destinations[1][nom]: Essaouira
destinations[1][logement]: Riad Mogador
destinations[1][items][0][nom]: Tagine
destinations[1][items][0][type]: plat
```

### Filtrer les itinéraires
```
GET /api/filter?categorie=monument&duree=3
```

### Rechercher par mot-clé
```
GET /api/search?keyword=Marrakech
```

---

## 🗄️ Structure de la Base de Données

```
users
├── id
├── name
├── email
├── password
└── timestamps

itineraires
├── id
├── titre
├── categorie
├── duree
├── image
├── user_id (FK → users)
└── timestamps

destinations
├── id
├── nom
├── logement
├── itineraire_id (FK → itineraires)
└── timestamps

destination_items
├── id
├── nom
├── type
├── destination_id (FK → destinations)
└── timestamps

favoris
├── id
├── user_id (FK → users)
├── itineraire_id (FK → itineraires)
└── timestamps
```

---

## 🧪 Tests

### Lancer tous les tests
```bash
php artisan test
```

### Résultat attendu
```
Tests: 14 passed (22 assertions)
```

### Tests disponibles
- ✅ `AuthTest` — 5 tests (register, login, me...)
- ✅ `ItineraireTest` — 7 tests (CRUD, favoris...)

---

## 📚 Documentation Swagger

La documentation interactive est disponible sur :
```
http://127.0.0.1:8000/api/documentation
```

Pour régénérer la documentation :
```bash
php artisan l5-swagger:generate
```

---

## 📁 Structure du Projet

```
app/
├── Http/
│   └── Controllers/
│       ├── AuthController.php
│       ├── ItineraireController.php
│       ├── SearchController.php
│       └── SwaggerController.php
└── Models/
    ├── User.php
    ├── Itineraire.php
    ├── Destination.php
    ├── DestinationItem.php
    └── Favori.php

database/
├── migrations/
└── factories/
    ├── UserFactory.php
    └── ItineraireFactory.php

routes/
└── api.php

tests/
└── Feature/
    ├── AuthTest.php
    └── ItineraireTest.php
```

---

## 👨‍💻 Auteur

Projet réalisé dans le cadre de la formation **MarocExplore API REST avec Laravel**.
