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

Olá {{ $data['person_name'] }}, você foi convidado @if(!empty($data['invitedBy'])) por <b>{{ $data['invitedBy']['person_name'] }}</b>, @endif
para participar do evento <b>{{ $data['event']['name'] }}</b> que ocorrerá no dia {{ $data['event']['start_date'] }} às {{ $data['event']['start_time'] }}h.

<h4>Informações do evento</h4>

<info>
    {{ $data['event']['description'] }}
</info>

<address>
    <h6>Endereço</h6>
    {{ $data['event']['address'] }}, {{ $data['event']['address_number'] }} <br>
    {{ $data['event']['district'] }} <br>
    {{ $data['event']['city'] }} - {{ $data['event']['uf'] }} <br>
    {{ $data['event']['zipcode'] }}
</address>

@if(!empty($data['event']['phone']) || !empty($data['event']['whatsapp']) || !empty($data['event']['email']) )
<contacts>
    <h6>Contato</h6>
    @if(!empty($data['event']['phone']))
        Telefone: {{ $data['event']['phone'] }} <br>
    @endif
    @if(!empty($data['event']['whatsapp']))
        whatsapp: {{ $data['event']['whatsapp'] }} <br>
    @endif
    @if(!empty($data['event']['email']))
        E-mail: {{ $data['event']['email'] }}
    @endif
</contacts>
@endif

@if(!empty($data['event']['value_per_person']) && !empty($data['event']['responsable_of_payment']))
<price>
    <h6>Valor do ingresso</h6>
    R$ {{ $data['event']['value_per_person'] }} por pessoa.<br>
</price>
@endif

<confirm>
    Para confirmar sua participação <a href="{{ $data['event']['company_url'] }}/guest/{{ $data['token_email'] }}">Clique aqui</a>.
</confirm>