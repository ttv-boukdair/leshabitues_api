API REST (Les habitues)



1: Construire les dockers (ngnix,php et mysql) 

    docker-compose up -d –build


2: Démarrer le projet Symfony (dossier app)

•	Exécuter composer Install

•	Créer  la base de données

        php bin/console doctrine:database:create
        php bin/console doctrine:migrations:migrate

•	Générer la clé privé (JWT)

    mkdir -p config/jwt
    openssl genrsa -out config/jwt/private.pem -aes256 4096
    openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
    Ajouter la valeur de JWT_PASSPHRASE dans le. env

•	Créer des fixtures avec le console (optionnel)
    php bin/console doctrine:fixtures:load


3: lancer le projet 

    (entry point) http:localhost/api 

    pour Créer et authentifier un utilisateur 

    http:localhost/api/login  {email:,password:}
    http:localhost/api/users {email:,password:,role:[ "ROLE_CLIENT", "ROLE_COMMERCANT", "ROLE_ADMIN" ]}
 

