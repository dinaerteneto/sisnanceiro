## Sobre o sinanceiro

SiSnanceiro é um sistema básico, que fornece as apis necessários para um pequeno ERP, que contemple módulos financeiro e de vendas.

## Instalação
    - Tenha o docker instalado
    - Após clonar o projeto
    - Altere os configurações no .env
    - Rode os comando: 
        - docker-build .
        - docker-composer up -d
        - composer install
        - php artisan generate:key
        - php artisan migrate
        - php artisan db:seed
