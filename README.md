# trgrd
Savoir si un dossier est ouvert sous Windows sans programme - Track when someone access a folder on Windows without any program.

# Demo
http://trgrd.site/

-> Cliquer sur Générer mon dossier piégé.

-> Cliquer sur Télécharger votre dossier piégé.

-> Cliquer sur Voir les statistiques de mon dossier.

-> Désarchiver le dossier et l'ouvrir (testé sous Win 10 64 bits et Win Vista 32 bits)

-> Observer dans la page des statistiques les ouvertures (fonctionne également si le dossier est déplacé, renommé, supprimé, vu depuis un dossier parent, etc).


trgrd est un projet basé sur le serveur DNS PHP de @yswery (https://github.com/yswery/PHP-DNS-SERVER).
Le code permet de créer des dossiers Windows à la volée contenant un dossier, lui-même contenant un fichier système desktop.ini. 

Ce fichier système de Windows permet de définir une icône au dossier, mais contient une URL générée dynamiquement pour cela, sous la forme suivante : %USERNAME%.%USERDOMAIN%.SEPARATEUR.ID_UNIQUE.trgrd.site\resource.dll (le fichier resource.dll est fictif mais supposé contenir l'icône).

Windows effectue automatiquement une requête DNS pour chercher l'icône du dossier dans resource.dll. La requête est interceptée pour récupérer non seulement la date de l'action, mais aussi les variables %USERNAME% et %USERDOMAIN% que Windows fait fuiter. (Découverte initiale par Alex Inführ https://insert-script.blogspot.com/2018/08/leaking-environment-variables-in_20.html)


# Instructions
-> créer une base de données mysql (STATS) avec une table (table1), le schéma est visible dans infos_bdd.php.

-> éditer fichier infos_bdd.php pour y placer les identifiants d'accès à la base de données.

-> éditer le fichier dns_record.json pour y placer l'adresse IP du site hébergeant le serveur DNS.

-> éditer le fichier src/Server.php pour y placer, ligne 17, l'adresse IP du site hébergeant le serveur DNS.

-> lancer le serveur DNS

-> héberger le reste du site et naviguer vers index.php.


# Autres infos
Le projet n'utilise pas ZipArchive ni pack/unpack, mais il est (peut-être) possible de les utiliser au lieu de créer le dossier octet par octet. Projet réalisé dans une optique plus générale d'apprentissage sur le format de fichier zip, les attributs de fichiers Windows et le fonctionnement d'un serveur DNS.


English below... I mean maybe one day...
