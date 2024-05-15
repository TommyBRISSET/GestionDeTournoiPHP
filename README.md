# Projet : Création d'une plateforme de gestion de tournois de sport

## Description

Le projet consiste à créer une plateforme de gestion de tournois sportifs. L'application permet aux utilisateurs de créer, organiser et participer à des tournois de sport. Les fonctionnalités principales incluent la gestion des tournois, des joueurs, des inscriptions et des parties.

## Fonctionnalités clés :

- **Gestion des Tournois :** Les utilisateurs créent et organisent des tournois de sport, définissent les dates, les lieux et les règles des tournois.
- **Gestion des Joueurs :** Les utilisateurs s'inscrivent, créent un profil et participent à des tournois en tant que participants.
- **Inscriptions aux Tournois :** Les participants s'inscrivent à des tournois disponibles, gèrent leurs inscriptions et suivent leur statut.
- **Gestion des Parties :** Les parties entre les participants sont organisées et gérées au sein des tournois, avec la possibilité de saisir les scores et de déterminer les gagnants.
- **Administration :** Une interface d'administration permet aux administrateurs de gérer les tournois, les participants, les inscriptions et les parties de manière centralisée.

## Spécificités :

- **Événements :**
    - Lorsqu’un tournoi est remporté, une notification est envoyée aux participants du tournoi, indiquant le vainqueur.
    - Si un participant met à jour son score, une notification est envoyée à l’autre joueur pour qu’il le remplisse s’il ne l’a pas encore fait. En revanche, si un administrateur modifie les scores, aucune notification n’est envoyée.
    - Lorsque les deux participants ont rempli leur score, le statut de la partie passe automatiquement à “terminé”.
    - L’interface d’administration est accessible uniquement aux administrateurs. Elle permet de gérer les tournois, les parties, les utilisateurs et les inscriptions aux tournois.
- **Règles :**
  - Lorsqu’une partie est créée dans un tournoi, seuls les joueurs ayant une inscription confirmée dans ce tournoi peuvent y participer.
- **Commande Symfony :**
  - Créer des fixtures.
  - Faire une commande qui prend en entrée un ID utilisateur et qui sort le nombre de victoires et de défaites au total. Rajouter un argument ID de tournoi qui s’il est passé ressort le nombre de victoires et de défaites pour ce tournoi.

## Entités

- **Tournament (Tournoi) :**  
    - tournamentName (string) : Nom du tournoi
    - startDate (date) : Date de début du tournoi
    - endDate (date) : Date de fin du tournoi
    - location (string, optionnel) : Lieu du tournoi
    - description (texte) : Description du tournoi
    - maxParticipants (integer) : Nombre maximum de participants au tournoi
    - status (dynamique) : Le statut du tournoi doit être renvoyé dynamiquement en se
      basant sur les dates de début et de fin du tournoi.
    - sport (string) : Nom du sport
    - organizer (relation vers l'entité User) : Organisateur du tournoi
    - winner (relation vers l’entité User) : Gagnant du tournoi
    - Attention la relation avec Game est au pluriel (games)
- **User (Joueur) :**
    - lastName (string) : Nom de famille du joueur
    - firstName (string) : Prénom du joueur
    - username (string) : Nom d'utilisateur du joueur
    - emailAddress (string) : Adresse email du joueur
    -  password (string) : Mot de passe du joueur (haché)
    - status (string) : Statut de l’utilisateur (actif, suspendu, banni)
- **Registration (Inscription) :**
    - player (relation vers l'entité User) : Joueur inscrit au tournoi
    - tournament (relation vers l'entité Tournament) : Tournoi auquel le joueur est
      inscrit
    - registrationDate (date) : Date d'inscription du joueur au tournoi
    -  status (string) : Statut de l'inscription (confirmée, en attente)
- **SportMatch (Partie) :**
    - tournament (relation vers l'entité Tournament) : Tournoi auquel le match
      appartient
    - player1 (relation vers l'entité User) : Joueur 1 du match
    - player2 (relation vers l'entité User) : Joueur 2 du match
    - matchDate (date) : Date du match
    - scorePlayer1 (integer) : Score du joueur 1 dans le match
    - scorePlayer2 (integer) : Score du joueur 2 dans le match
    - status (string) : Statut du match (en attente, en cours, terminé)

## Routes 

- **Tournament Management (Gestion des tournois) :**
    - GET /api/tournaments : récupère la liste des tournois
    - POST /api/tournaments : crée un nouveau tournoi
    - GET /api/tournaments/{id} : récupère les détails d'un tournoi spécifique
    - PUT /api/tournaments/{id} : mets à jour les informations d'un tournoi spécifique
    - DELETE /api/tournaments/{id} : Supprime un tournoi spécifique
- **Player Management (Gestion des joueurs) :**
    - GET /api/players : récupère la liste des joueurs
    - POST /register : créer un utilisateur
    - GET /api/players/{id} : récupère les détails d'un joueur spécifique
    - PUT /api/players/{id} : mets à jour les informations d'un joueur spécifique
    - DELETE /api/players/{id} : Supprime un joueur spécifique
- **Registration Management (Gestion des inscriptions) :**
    - GET /api/tournaments/{id}/registrations : récupère la liste des inscriptions pour un
      tournoi spécifique
    - POST /api/tournaments/{id}/registrations : inscris un joueur à un tournoi spécifique
    - DELETE /api/tournaments/{idTournament}/registrations/{idRegistration} : Annule
      l'inscription d'un joueur à un tournoi spécifique
- **SportMatch Management (Gestion des parties) :**
    - GET /api/tournaments/{id}/sport-matchs : récupère la liste des parties pour un
      tournoi spécifique
    - POST /api/tournaments/{id}/sport-matchs : crée une nouvelle partie pour un
      tournoi spécifique
    - GET /api/tournaments/{idTournament}/sport-matchs/{idSportMatchs} : récupère
      les détails d'une partie spécifique
    - PUT /api/tournaments/{idTournament}/sport-matchs/{idSportMatchs} : mets à
      jour les résultats d'une partie spécifique
    - DELETE /api/tournaments/{idTournament}/sport-matchs/{idSportMatchs} :
      Supprime une partie spécifique

# Details du projet

## Installation

### Version des outils utilisés

Ce projet a été réalisé avec Symfony 7.0.6, PHP 8.2.12 et Composer 2.7.2.
Les logiciels utilisés pour le développement sont les suivants :
- PhpStorm 2022.3.2
- base de données MySQL
- xampp
- Postman
- Openssl

### Information concernant php

Pour faire fonctionne le projet, il est important de modifier le fichier php.ini pour activer les extensions suivantes :
- extension=sodium
- extension=intl

Activez cette extension uniquement si vous des erreurs survient lors de la commande 'composer install'
- extension=zip

### Information serveur mail

Pour que les mails soient envoyés, il est nécessaire de configurer le serveur mail. Pour cela, ouvrez le fichier .env et modifiez les lignes suivantes :
- MAILER_DSN=smtp://localhost:1025

vous pouvez utiliser Mailtrap pour tester l'envoi de mail.

### Base de données

Le projet utilise une base de données MySQL. Vous pouvez modifier les informations de connexion dans le fichier .env.
La ligne a modifier est la suivante :
- DATABASE_URL=mysql://user:password@host:port/database_name


### Comment installer le projet

1. Cloner le projet sur votre machine ou telecharger le zip.
2. Ouvrir le projet avec un éditeur de code.
3. Dirigez vous dans le fichier .env et modifier la ligne DATABASE_URL avec vos informations de connexion à la base de données.
4. Ouvrir un terminal et entrez les commandes suivantes :
- ```composer install``` (si une erreur de telechargement survient, referez vous a la section 'Information concernant php' pour activer les extensions nécessaires)
- ```php bin/console doctrine:database:create```
- ```php bin/console doctrine:migrations:migrate```
- ```php bin/console doctrine:fixtures:load```
- Et enfin demarrer le serveur avec la commande ```symfony server:start```.

Il ne faut pas oublier d'allumer le serveur de base de données.

### Commande Symfony

Une commande a été créée pour afficher le nombre de victoires et de défaites d'un joueur. Pour l'utiliser, entrez la commande suivante dans le terminal :
```php bin/console app:user:stat {id} {idTournament}```

{id} est l'identifiant de l'utilisateur et {idTournament} est l'identifiant du tournoi. Si vous ne souhaitez pas afficher les statistiques pour un tournoi spécifique, vous pouvez laisser l'argument {idTournament} vide.

### Authentification JWT

Pour l'authentification, le bundle LexikJWTAuthenticationBundle a été utilisé.

Pour générer un token, voici la démarche à suivre :
1. Ouvrir Postman.
2. Créer une nouvelle requête POST : ```http://localhost:8000/api/login_check```
3. Dans le body, sélectionner raw et JSON.
4. Dans le body, ajouter les informations suivantes de la manière suivante :
```json
{
    "emailAddress": "admin.symfony@gmail.com",
    "password": "password"
}
```
5. Cliquer sur Send.
6. Vous devriez recevoir un token en retour dans le body de la réponse.

Pour accéder aux routes protégées, ajoutez le token dans le header de la requête de la manière suivante :

Dans la section Key : **Authorization** et dans **Value** : Bearer {token}

### Tests unitaires

Pour lancer les tests, entrez la commande suivante dans le terminal :
```php bin/phpunit```

### Utlisation des routes

Pour pouvoir utiliser les routes, vous pouvez les faire via Postman ou tout autre logiciel de test d'API.

Ci dessous le lien de la collection Postman pour tester les routes :
[![Run in Postman](https://run.pstmn.io/button.svg)](https://supinfotours.postman.co/workspace/New-Team-Workspace~60af31a4-7374-4d56-ac3c-02b5501e1a50/collection/24605543-074b3706-a619-48ac-ba6d-d6997266b54e?action=share&creator=24605543)

### Licence

Ce projet est sous licence MIT. Pour plus d'informations, veuillez consulter le fichier LICENSE.