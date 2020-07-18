## Sobre o sinanceiro

SiSnanceiro é um sistema básico, que fornece as apis necessários para um pequeno ERP, que contemple módulos financeiro e de vendas.

## Instalação
    - Tenha o docker instalado
    - Após clonar o projeto
    - Altere os configurações no .env (copie e o .env.example e renomeio para .env)
    - Rode os comando: 
        - docker build .
        - docker-compose up -d
        - docker exec -it webserver composer install
        - docker exec -it webserver php artisan key:generate
        - docker exec -it webserver php artisan migrate
        - por algum motivo, antes de rodar o comando abaixo, é necessário mudar no .env o valor da variavél DB_HOST para 127.0.0.1 e após rodar o comando retornar o valor para: mysql 
        - docker exec -it webserver php artisan db:seed
        - vá até seu arquivo .host e adicione a linha: 127.0.0.1    dash.sisnanceiro.local.
        - no navegador digite dash.sisnanceiro.local
        - Usuário: main@sisnanceiro.com.br - Senha: secret

## Alguns comandos do docker
    - docker build . --force-rm --no-cache
    - docker ps
    - docker images
    - docker rmi 
    - docker rmi $(docker images -q) 
