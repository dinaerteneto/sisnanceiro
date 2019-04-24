## Sobre o sinanceiro

SiSnanceiro é um sistema básico, que fornece as apis necessários para um pequeno ERP, que contemple módulos financeiro e de vendas.

## Instalação
    - Tenha o docker instalado
    - Após clonar o projeto
    - Altere os configurações no .env
    - Rode os comando: 
        - docker build .
        - docker-compose up -d
        - docker exec -it webserver composer install
        - docker exec -it webserverphp artisan generate:key
        - docker exec -it webserverphp artisan migrate
        - docker exec -it webserverphp artisan db:seed

## Alguns comandos do docker
    - docker build . --force-rm --no-cache
    - docker ps
    - docker images
    - docker rmi 
    - docker rmi $(docker images -q) 
