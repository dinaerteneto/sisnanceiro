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

<h4>Olá Dinaerte,</h4>

<p>
    <b>{{ $user->person->firstname }} {{ $user->person->lastname }}</b> acaba de se cadastrar no <b>SiSnanceiro</b>.
</p>

<h4>PARABÉNS</h4>

<h6>Anote seus dados de acesso</h6>
<div>
    Nome: {{ $user->person->firstname }} <br />
    E-mail: {{ $user->email }}<br />
</div>

<div>
    Para acessar o sistema <a href="http://sisnanceiro.com.br">Clique aqui</a> ou acesse http://sisnanceiro.com.br.
</div>
