## Sobre o sinanceiro

SiSnanceiro é um sistema básico, que fornece as apis necessários para um pequeno ERP, que contemple módulos financeiro e de vendas.

## Road Map - MVP
    - Multi-tenancy
    - Unidades
    - Clientes
    - Fornecedores
    - Financeiro
        - Contas a pagar
        - Contas a receber
        - Cadastro de contas bancárias
        - Cadastro de prazos taxas por meio de pagamento
        - Centro de custo
    - Vendas
    - Disparo de e-mail para informar o cliente que a parcela esta em aberto
    - Disparo de sms

## Instalação
    Eu recomendo que você tenha o docker instalado
    - Após clonar o projeto
    - Cria um banco de dados com o nome de sisnanceiro
    - Altere os configurações no .env
    - Rode os comando: 
        - docker-build .
        - docker-composer up -d
        - composer install
        - php artisan generate:key
        - php artisan migrate
        - php artisan db:seed
