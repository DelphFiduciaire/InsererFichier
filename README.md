# CRM Symfony - Gestion des Comptes et Dépôt de Fichiers

## Description

Ce projet implémente un CRM (Customer Relationship Management) permettant aux clients de déposer des fichiers demandés par les comptables. Chaque utilisateur (Client, Comptable, Administrateur) dispose d'une interface et de privilèges différents. L'objectif est d'améliorer la gestion des informations et des fichiers nécessaires à la comptabilité des clients, tout en offrant une visibilité claire sur les informations disponibles.

Les fonctionnalités principales incluent :

- **Gestion des utilisateurs avec trois rôles distincts** : Administrateur, Comptable, Client.
- **Dépôt de fichiers pour les comptables et les clients.**
- **Interface personnalisée en fonction du rôle de l'utilisateur**.

---

## Fonctionnalités

### Page de connexion

La page de connexion permet aux utilisateurs de se connecter en remplissant un formulaire avec leur nom d'utilisateur et mot de passe. Une fois connecté, l'utilisateur est redirigé vers une page d'accueil personnalisée en fonction de son rôle.

### Page d'Accueil

- **Client** : Un tableau de ses fichiers déposés avec des options pour les modifier ou les supprimer. Le menu contient des liens pour ajouter de nouveaux fichiers et pour accéder à son espace personnel.
- **Comptable** : Liste de ses clients avec un bouton pour consulter les fichiers déposés de chaque client.
- **Administrateur** : Liste de tous les fichiers déposés par les clients, gestion des utilisateurs et des fichiers. Accès à la création de nouveaux utilisateurs ou comptables.

### Page d'Inscription

L'administrateur peut créer de nouveaux comptes utilisateurs en fournissant l'adresse e-mail et le mot de passe. Un e-mail est envoyé à l'utilisateur avec ses informations de connexion.

### Gestion des utilisateurs et des clients

- **Liste des utilisateurs** : L'administrateur peut consulter, modifier ou supprimer des utilisateurs.
- **Liste des clients** : L'administrateur peut consulter les clients, leurs informations et leurs fichiers déposés.
- **Liste des fichiers** : Affiche tous les fichiers des clients avec un indicateur sur leur statut (présent, manquant ou non applicable).

---

## Environnement de Développement

### Prérequis

- **PHP** : [Télécharger PHP](https://windows.php.net/download/)
- **Composer** : [Installer Composer](https://getcomposer.org/download/)
- **Symfony CLI** : [Télécharger Symfony CLI](https://symfony.com/download)

### Installation

1. Installez PHP, Composer et Symfony CLI.
2. Créez un projet Symfony :
   ```bash
   symfony new crm-project --version=6.2
3. Lancez le serveur Symfony :
    ```bash
   symfony serve
### Base de Données
1. Modifiez le fichier .env pour configurer la base de données.

2. Créez la base de données :
   ```bash
   symfony console doctrine:database:create

3. Créez l'entité User pour la gestion des utilisateurs :
   ```bash
    symfony console make:user
   
4. Créez l'entité InfoClient pour stocker les informations des clients :
   ```bash
    symfony console make:entity InfoClient

## Routes et Contrôleurs
Symfony génère automatiquement les routes et les contrôleurs pour gérer les opérations CRUD (Create, Read, Update, Delete) sur les entités User et InfoClient.

### Exemple de création de CRUD
Pour créer un CRUD pour l'entité User :
```bash
  symfony console make:crud User

Cela génère un contrôleur et des vues pour effectuer des actions sur les utilisateurs.

