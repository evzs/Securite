# A propos

Ce projet vise à mettre en place un système de login sécurisé.
Il comprend jusque là l'inscription, la vérification de compte, la demande et la confirmation de changement de mot de passe, et de suppression de compte.

Le projet a été développé en utilisant PHP 8.2 et en utilisant MariaDB version 10.6.18 comme base de données.

# Mesures de sécurité

- Hash des mots de passe : les mots de passe sont sécurisés à l'aide de la bibliothèque SHA512 et sont stockés avec des salts pour éviter les attaques par dictionnaire
- OTP (One-Time Passwords) : les OTP sont générés pour valider les actions sensibles, garantissant que seul l'utilisateur propriétaire de l'adresse email peut confirmer ces actions
- Actions sécurisées : toutes les actions sensibles (vérification de compte, changement de mot de passe, suppression de compte) nécessitent la validation par OTP

# Structure du projet

- Services : gèrent les requêtes entrantes et les distribuent aux handlers appropriés. 
- Handlers : traitent les différentes étapes des requêtes.
- Identifier : gère les opérations liées aux utilisateurs comme l'inscription, la vérification d'existence de compte, et les mises à jour de mot de passe. 
- SecuredActioner : gère les actions sécurisées, incluant la génération et la validation des OTP.
- a completer



# Convention de nommage personnelle

- **Classes**: PascalCase
- **Methodes et fonctions**: camelCase
- **Variables**: snake_case (a l'exception des variables dans le HandlerManager, qui font reference a des classes et sont donc mises en pascalCase)

# Installation et configuration

- Clonez le depot sur votre machine locale: `git clone https://github.com/evzs/Securite.git`.
- Configurez les fichiers PATH.php ainsi que votre fichier externe contenant vos credentials avec les parametres aproppries.
- Votre fichier contenant les credentials devrait avoir cette structure (remplacez avec les valeurs correspondantes), au format json:
``` json
{
  "server_name": "nom du serveur sur lequel vous travaillez (ex: localhost)",
  "db_name": "nom de la base de donnees avec laquelle vous interagissez",
  "port": "le port utilise pour la base de donnees", 
  "username": "l'utilisateur de la base de donnees", 
  "password": "le mot de passe de cet utilisateur"
}
```
- Dans PATH.php, dans la methode CREDENTIALS, specifiez le dossier ou se trouve votre fichier credentials (idealement au-dessus de la racine du projet)

# Utilisation de l'API

A utiliser idealement avec un client du style Postman/Insomnia.
Vos requetes se feront sous ce format:
`<adresseduserveur>/Securite/api/<endpoint>/`

## Creation du compte temporaire
Vous pouvez creer un compte avec une adresse mail et mot de passe.

`POST /Securite/api/signUp/`
```json
{
    "email": "votre@email.com",
    "password": "votremdp"
}
```

## Validation du compte avec OTP
Vous pouvez valider votre compte avec l'OTP renvoyé suite a la creation du compte temporaire.

`POST /Securite/api/verifyAccount/`
```json
{
    "email": "votre@email.com",
    "otp": "otp"
}
```

## Changement de mot de passe
Vous pouvez demander a changer de mot de passe en fournissant votre mail.

`POST /Securite/api/changePassword/`
```json
{
    "email": "votre@email.com"
}
```

## Confirmation du changement de mot de passe avec OTP
Vous pouvez confirmer le changement de mot de passe avec l'OTP renvoyé suite a la demande de changement.

`POST /Securite/api/confirmPasswordChange/`
```json
{
    "email": "votre@email.com",
    "otp": "otp",
    "new_password": "votrenouveaumdp"
}
```

## Suppression du compte
Vous pouvez demander a supprimer votre compte en fournissant votre mail.

`POST /Securite/api/deleteAccount/`
```json
{
    "email": "votre@email.com"
}
```

## Confirmation de la suppression du compte avec OTP
Vous pouvez confirmer la suppression de votre compte avec l'OTP renvoyé suite a la demande de suppression.

`POST /Securite/api/confirmAccountDeletion/`
```json
{
    "email": "votre@email.com",
    "otp": "otp"
}
```

# A finir

A ma grande deception le projet n'est pas complet pour l'instant.
Ce qu'il reste a faire :

- Tokenizer : gestion des tokens pour les sessions
- QuotaManager : gestion des quotas pour limiter les actions de l'utilisateur (demande d'OTP...)
- SignIn : authentification des utilisateurs
- SignedIn : gestion de l'état connecté de l'utilisateur
- SignOut : déconnexion des utilisateurs


- Anti brute-force
- Check de format d'emails
- Check de format de mots de passe
- Gestion des erreurs HTTP renforcee
- Affichage message quand 200 OK (je me servais de la BDD pour verifier que toutes les operations se faisaient correctement)

