<style type="text/css">
    * {
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 12px;
        margin: 6px 0;
        padding: 0;
    }
    h4 {
        font-size: 16px;
        font-weight: bold;
    }
    h6 {
        font-size: 14px;
    }

</style>

<h4>Olá {{ $user->person->firstname }}, seu usuário foi criado com sucesso.</h4>
<h6>Anote seus dados de acesso</h6>

<div>
    Login:{{ $user->email }}<br>
    Senha: {{ $passwordGenerated }}
</div>

<div>
    Para acessar o sistema <a href="http://sisnanceiro.com.br">Clique aqui</a> ou acesse http://sisnanceiro.com.br.
</div>

