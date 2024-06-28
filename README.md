## Description
Ce projet vise a realiser un DAL avec des points API associes, exposant l'ensemble des requetes preparees de services d'acces aux tables de donnees.

## Utilisation
- Clonez le depot sur votre machine locale: `git clone https://github.com/evzs/DAL.git`.
- Configurez les fichiers PATH.php ainsi que votre fichier externe contenant vos credentials avec les parametres aproppries (voir la section Configuration).
- Accedez au projet via un serveur prenant en charge PHP, en naviguant vers index.php.

Le projet est incomplet jusque la, n'ayant pas d'API, mais reste utilisable et permet de performer les operations CRUD attendues.

## Configuration
- Votre fichier contenant les credentials devrait avoir cette structure (remplacez avec les valeurs correspondantes), au format json:
``` json
{
  "server_name": "nom du serveur sur lequel vous travaillez (ex: localhost)",
  "db_name": " nom de la base de donnees avec laquelle vous interagissez",
  "port": "le port utilise pour la base de donnees", 
  "username": "l'utilisateur de la base de donnees", 
  "password": "le mot de passe de cet utilisateur"
}
```
- Dans PATH.php, dans la methode CREDENTIALS, specifiez le dossier ou se trouve votre fichier credentials (idealement au-dessus de la racine du projet)

---