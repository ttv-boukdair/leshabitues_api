API REST (Les habitues)



1: Construire les dockers (ngnix,php et  mysql) avec le console
    docker-compose up -d –build



2: Démarrer le projet Symfony (dossier app)

•	Exécuter composer Install à l'intérieur du dossier app

•	Génération de la clé privé (JWT)

    mkdir -p config/jwt
    openssl genrsa -out config/jwt/private.pem -aes256 4096
    openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
    Ajouter la valeur de JWT_PASSPHRASE dans le. env

•	Créer des fixtures avec le console
   php bin/console doctrine:fixtures:load

3: lancer le projet http:localhost/api 

 

