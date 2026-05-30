# Back-end — Vue d'ensemble

L'API repose sur Symfony 7.4, PHP 8.2+ et Doctrine, avec une authentification JWT. La base de données est PostgreSQL, pilotable via Docker ou en local.

## Prérequis

PHP 8.2+, Composer, PostgreSQL (ou Docker), et OpenSSL pour les clés JWT.

## Installation en 10 étapes

1. Installer PHP, Composer et Docker si nécessaire.
2. Cloner le dépôt et se placer dans le dossier back-end.
3. Installer les dépendances : `composer install`.
4. Vérifier la présence des fichiers `.env`, `.env.dev`, `.env.test`.
5. Adapter la variable `DATABASE_URL` selon le mode choisi.
6. Démarrer la base de données, soit via Docker (`docker compose up -d`), soit en local.
7. Générer les clés JWT avec `generate_jwt_keys.php` si elles sont absentes, et vérifier la passphrase dans `.env`.
8. Appliquer les migrations : `php bin/console doctrine:migrations:migrate`.
9. Charger les fixtures si besoin : `php bin/console doctrine:fixtures:load`.
10. Démarrer le serveur (`symfony server:start`) et vérifier avec `php bin/phpunit`.

⚠️ Veiller à la cohérence entre la `DATABASE_URL` du `.env` et la base lancée par Docker. Les deux configurations doivent correspondre.
