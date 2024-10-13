# Travel Mate - Backend

Ce dépôt contient le backend de l'application TravelMate. Cette application est un système de gestion de voyages avec un frontend React et un backend Laravel. Suivez les instructions ci-dessous pour configurer et démarrer l'application.


## Pré-requis

Assurez-vous d'avoir les éléments suivants installés sur votre machine avant de commencer :

- **PHP >= 7.4** ou supérieur
- **Composer**
- **Node.js** et **npm**
- **MySQL** ou un autre système de base de données compatible
- **Git** pour cloner les dépôts

## Installation du Backend

**1.** Clonez ce dépôt sur votre machine locale :
```
git clone https://github.com/RejdaQATI/travelmate-backend.git
cd travelmate-backend
```
     
**2.** Installez les dépendances PHP en utilisant **Composer**

```
composer install
```
**3.** Copiez le fichier `.env.example` en `.env` et configurez vos variables d'environnement, telles que la connexion à la base de données.

```
cp .env.example .env
```

**4.** Générez une clé d'application Laravel 

```
php artisan key:generate
```
**5.** Exécutez les migrations pour créer les tables nécessaires dans votre base de données :

```
php artisan migrate
```

**6.** Vous pouvez également exécuter les commandes de **seeding** pour ajouter des données initiales :

```
php artisan db:seed
```

**7.** Pour démarrer le backend, lancez le serveur local Laravel :

```
php artisan serve
```
Le backend sera disponible à l'adresse suivante : [http://localhost:8000](http://localhost:8000).

## Installation du Frontend

L'installation du frontend est nécessaire pour le bon fonctionnement du site. Vous pouvez trouver le projet ainsi que les étapes d'installation à l'adresse suivante :

```
https://github.com/RejdaQATI/travelmate-frontend.git
```

Suivez les instructions fournies dans le dépôt du frontend pour le configurer correctement.


## API Documentation
La documentation des API publiques est disponible via Swagger. Une fois le serveur démarré, accédez à la documentation à l'adresse suivante :
```
http://localhost:8000/api/documentation
```