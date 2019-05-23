## Sobre o sinanceiro

SiSnanceiro é um sistema básico, que fornece as apis necessários para um pequeno ERP, que contemple módulos financeiro e de vendas.

## Instalação
    - Tenha o docker instalado
    - Após clonar o projeto
    - Altere os configurações no .env
    - Rode os comando: 
        - docker build .
        - docker-compose up -d
        - docker exec -it webserver apk --update add --no-cache php7-xmlwriter
        - docker exec -it webserver apk --update add --no-cache php7-fileinfo
        - docker exec -it webserver composer install
        - docker exec -it webserver php artisan key:generate
        - docker exec -it webserver php artisan migrate
        - docker exec -it webserver php artisan db:seed

## Alguns comandos do docker
    - docker build . --force-rm --no-cache
    - docker ps
    - docker images
    - docker rmi 
    - docker rmi $(docker images -q) 
