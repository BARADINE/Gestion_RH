# 🏛️ Gestion RH — Université de N'Djamena

> **Application Web de Gestion des Ressources Humaines** — Développée pour l'Université de N'Djamena

---

## 📋 Présentation

Cette application permet de gérer efficacement les ressources humaines : employés, demandes de congés, et fiches de paie. Elle offre une interface simple et intuitive adaptée aux besoins de l'établissement.

---

## ✨ Fonctionnalités

| Module | Description |
|--------|-------------|
| 📊 **Tableau de bord** | Vue d'ensemble : nombre d'employés, congés en attente et validés |
| 👤 **Gestion des employés** | Ajouter, consulter, modifier et supprimer des employés |
| 🏖️ **Gestion des congés** | Enregistrer, valider ou refuser les demandes de congé |
| 💰 **Gestion de la paie** | Créer et imprimer des fiches de paie au format PDF |
| 📁 **Exports** | Exporter les données en fichiers Excel et CSV |
| 📊 **Organigramme** | Visualiser la structure hiérarchique de l'établissement |
| 🔐 **Authentification** | Connexion sécurisée pour accéder à l'application |

---

## 🛠️ Technologies utilisées

- **Langage serveur :** PHP 8+
- **Base de données :** MySQL
- **Interface :** HTML5, CSS3, JavaScript
- **Serveur :** Apache (XAMPP/WAMP)

---

## 📂 Structure du projet


📄 Super idée ! Voici un README.md complet et professionnel pour ton projet
 
Crée un fichier nommé exactement  README.md  à la racine de ton projet, et colle ceci :
 
 
 
markdown    
# 🏛️ Gestion RH — Université de N'Djamena

> **Application Web de Gestion des Ressources Humaines** — Développée pour l'Université de N'Djamena

---

## 📋 Présentation

Cette application permet de gérer efficacement les ressources humaines : employés, demandes de congés, et fiches de paie. Elle offre une interface simple et intuitive adaptée aux besoins de l'établissement.

---

## ✨ Fonctionnalités

| Module | Description |
|--------|-------------|
| 📊 **Tableau de bord** | Vue d'ensemble : nombre d'employés, congés en attente et validés |
| 👤 **Gestion des employés** | Ajouter, consulter, modifier et supprimer des employés |
| 🏖️ **Gestion des congés** | Enregistrer, valider ou refuser les demandes de congé |
| 💰 **Gestion de la paie** | Créer et imprimer des fiches de paie au format PDF |
| 📁 **Exports** | Exporter les données en fichiers Excel et CSV |
| 📊 **Organigramme** | Visualiser la structure hiérarchique de l'établissement |
| 🔐 **Authentification** | Connexion sécurisée pour accéder à l'application |

---

## 🛠️ Technologies utilisées

- **Langage serveur :** PHP 8+
- **Base de données :** MySQL
- **Interface :** HTML5, CSS3, JavaScript
- **Serveur :** Apache (XAMPP/WAMP)

---

## 📂 Structure du projet

 
 
Gestion RH/
├── index.php              # Page de redirection
├── 03_index.php           # Page principale avec navigation
├── 02_config.php          # Connexion à la base de données
├── 04_dashboard.php       # Tableau de bord
├── 05_employes.php        # Gestion des employés
├── 06_conges.php          # Gestion des congés
├── 07_paie.php            # Gestion de la paie
├── 08_organigramme.php    # Organigramme
├── 09_connexion.php       # Page de connexion
├── 11_inscription.php     # Inscription utilisateur
├── 12_exporter_excel.php  # Export Excel
├── 13_exporter_csv.php    # Export CSV
├── 01_creer_base_donnee.sql # Script de création BDD
├── logo_univ_ndjamena.png # Logo de l'université
└── README.md              # Ce fichier
 
plaintext    

---

##  Installation locale

### Étape 1 — Prérequis
- Installer **XAMPP** ou **WAMP** (Apache + MySQL + PHP)

### Étape 2 — Base de données
1. Ouvrir **phpMyAdmin** (`localhost/phpmyadmin`)
2. Créer une base de données nommée `gestion_rh`
3. Importer le fichier `01_creer_base_donnee.sql`

### Étape 3 — Configuration
Ouvrir `02_config.php` et vérifier les paramètres de connexion :
```php
$host = 'localhost';
$dbname = 'gestion_rh';
$username = 'root';
$password = '';
 
 
Étape 4 — Lancer l'application
 
- Placer le dossier  Gestion RH  dans  htdocs  (XAMPP) ou  www  (WAMP)
- Accéder via :  localhost/Gestion%20RH/03_index.php 
- Se connecter avec vos identifiants
 
 
 
👤 Auteur
 
- Projet : Application de Gestion RH — Université de N'Djamena
- Développeur : [BARADINE ATIE BECHIR]
- Date : NOVEMBRE 2026
 
 
 
📝 Licence
 
Ce projet est réalisé dans un cadre académique. Tous droits réservés.
 
 
 
