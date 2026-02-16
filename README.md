<div align="center">

# 🏨 API de Réservation Laravel

### *Une solution complète pour gérer vos propriétés et réservations*

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](LICENSE)

[Installation](#-installation) • [Documentation](#-référence-api) • [Tests](#-tests) • [Contribution](#-contribution)

---

</div>

## 📋 À propos

Cette API de réservation développée avec Laravel propose une **solution complète** pour gérer des propriétés, des appartements et des réservations, similaire à l'API de Booking.com.

Elle permet aux propriétaires de gérer facilement leurs annonces, tandis que les utilisateurs peuvent rechercher et réserver des hébergements de manière simple et rapide.

---

## ✨ Fonctionnalités principales

<table>
<tr>
<td width="50%">

### 🔍 Recherche avancée
Recherchez des propriétés selon plusieurs critères :
- 📍 Localisation
- 💰 Prix
- 🛋️ Équipements
- 📅 Disponibilité

</td>
<td width="50%">

### 🏠 Gestion des propriétés
- Création et mise à jour
- 📸 Gestion des photos
- 📝 Descriptions détaillées
- ⚙️ Configuration des équipements

</td>
</tr>
<tr>
<td width="50%">

### 🏢 Gestion des appartements
- Ajout et modification
- 🛏️ Configuration des chambres
- 💵 Tarification flexible
- 📊 Suivi de disponibilité

</td>
<td width="50%">

### 📅 Gestion des réservations
- Recherche d'hébergements
- ✅ Création de réservations
- ❌ Annulation
- 📈 Suivi en temps réel

</td>
</tr>
</table>

### 🔐 Authentification et autorisation
Système sécurisé avec gestion des rôles :
- 👤 **Utilisateur** : Recherche et réservation
- 🏠 **Propriétaire** : Gestion complète des propriétés

---

## 🛠️ Prérequis

```bash
Framework Laravel
PHP >= 8.0
Base de données (MySQL, PostgreSQL, etc.)
Composer
```

---

## 🚀 Installation

### 1️⃣ Cloner le dépôt

```bash
git clone https://github.com/SakaizaNomena/api-reservation.git
cd api-reservation
```

### 2️⃣ Installer les dépendances

```bash
composer install
```

### 3️⃣ Configuration de l'environnement

Copiez le fichier `.env.example` et configurez votre base de données :

```bash
cp .env.example .env
```

Éditez le fichier `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=votre_base
DB_USERNAME=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe
```

### 4️⃣ Générer la clé d'application

```bash
php artisan key:generate
```

### 5️⃣ Migrer et remplir la base de données

```bash
php artisan migrate --seed
```

### 6️⃣ Lancer le serveur

```bash
php artisan serve
```

L'API sera accessible sur `http://localhost:8000` 🎉

---

## 📚 Référence API

> ⚠️ **Important** : Toujours préciser la version de l'API : `/api/v1`

### 🔑 Authentification

| Endpoint | Méthode | Paramètres |
|----------|---------|------------|
| `/register` | POST | `name`, `email`, `password`, `role_id` |
| `/login` | POST | `email`, `password` |

---

### 👤 Endpoints Utilisateurs

#### 📅 Gestion des réservations

| Endpoint | Méthode | Description |
|----------|---------|-------------|
| `/user/bookings` | POST | Créer une réservation |
| `/user/bookings` | GET | Voir toutes les réservations |
| `/user/bookings/{booking_id}` | GET | Voir une réservation |
| `/user/bookings/{booking_id}` | PUT | Modifier une réservation |
| `/user/bookings/{booking_id}/cancel` | PUT | Annuler une réservation |

---

### 🏠 Endpoints Propriétaires

#### 🏡 Gestion des propriétés

| Endpoint | Méthode | Description |
|----------|---------|-------------|
| `/owner/properties` | POST | Créer une propriété |
| `/owner/properties` | GET | Voir les propriétés |
| `/owner/properties/{property_id}` | GET | Voir une propriété |
| `/owner/properties/{property_id}` | PUT | Modifier une propriété |
| `/owner/properties/{property_id}/deactivate` | PUT | Désactiver une propriété |
| `/owner/properties/{property_id}/activate` | PUT | Activer une propriété |
| `/owner/properties/{property_id}/photos` | POST | Ajouter des photos |
| `/owner/properties/{property_id}/photos/{photo_id}/reorder` | PUT | Modifier l'ordre d'une photo |

#### 🏢 Gestion des appartements

| Endpoint | Méthode | Description |
|----------|---------|-------------|
| `/owner/properties/{property_id}/apartments` | GET | Voir les appartements |
| `/owner/properties/{property_id}/apartments` | POST | Créer un appartement |
| `/owner/properties/{property_id}/apartments/{apartment_id}` | GET | Voir un appartement |
| `/owner/properties/{property_id}/apartments/{apartment_id}/bookings` | GET | Voir les réservations |
| `/owner/properties/{property_id}/apartments/{apartment_id}` | PUT | Modifier un appartement |
| `/owner/properties/{property_id}/apartments/{apartment_id}/deactivate` | PUT | Désactiver un appartement |
| `/owner/properties/{property_id}/apartments/{apartment_id}/activate` | PUT | Activer un appartement |

#### 💰 Gestion des disponibilités et prix

| Endpoint | Méthode | Description |
|----------|---------|-------------|
| `/owner/properties/{property_id}/apartments/{apartment_id}/prices` | GET | Voir les prix et disponibilités |
| `/owner/properties/{property_id}/apartments/{apartment_id}/prices` | POST | Créer un prix |
| `/owner/properties/{property_id}/apartments/{apartment_id}/prices/{price_id}` | GET | Voir un prix |
| `/owner/properties/{property_id}/apartments/{apartment_id}/prices/{price_id}` | PUT | Modifier un prix |
| `/owner/properties/{property_id}/apartments/{apartment_id}/prices/{price_id}` | DELETE | Supprimer un prix |

---

### 🌍 Endpoints Publics

| Endpoint | Méthode | Description | Paramètres optionnels |
|----------|---------|-------------|-----------------------|
| `/search` | GET | Rechercher des propriétés | `city_id`, `country_id`, `geoobject_id`, `adult_capacity`, `children_capacity`, `price_from`, `price_to`, `facilities` |
| `/apartments/view/{apartment_id}` | GET | Voir un appartement | - |
| `/properties/view/{property_id}` | GET | Voir une propriété | - |

---

## 🧪 Tests

Pour exécuter les tests :

```bash
php artisan test
```

Avec couverture de code :

```bash
php artisan test --coverage
```

---

## 🚢 Déploiement

L'API peut être déployée sur n'importe quel serveur web supportant PHP et Laravel :

- 🔷 **Shared Hosting** (cPanel)
- ☁️ **Cloud** (AWS, DigitalOcean, Linode)
- 🐳 **Docker**
- ⚡ **Forge** / **Vapor** (Laravel)

---

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

```
MIT License - Libre d'utilisation, modification et distribution
```

---

## 👨‍💻 Auteur

**[@SakaizaNomena](https://github.com/SakaizaNomena)**

<div align="center">

### 💖 Contribution

Les contributions sont toujours les bienvenues ! 🚀

N'hésitez pas à :
- 🐛 Signaler des bugs
- 💡 Proposer des améliorations
- 📝 Améliorer la documentation
- ⭐ Mettre une étoile au projet

[Ouvrir une issue](../../issues) • [Créer une Pull Request](../../pulls)

---

<sub>Fait avec ❤️ par la communauté</sub>

</div>