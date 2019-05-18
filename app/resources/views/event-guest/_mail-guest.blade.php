Olá {{ $eventGuest->name }}, você foi convidado por @783jdakh
para participar do evento <b>{{ $event->name }}</b> que ocorrerá no dia {{ $event->start_date }} às xx:xx.

<h4>Informações do evento</h4>

<info>
    {{ $event->description }}
</info>

<address>
    <h6>Endereço</h6> 
    {{ $event->address }}, {{ $event->address_number }} <br>
    {{ $event->district }} <br>
    {{ $event->city }} - {{ $event->uf }} <br>
    {{ $event->cep }}
</address>

<contacts>
    <h6>Contato</h6>
    Telefone: {{ $event->phone }} <br>
    whatsapp: {{ $event->whatsapp }} <br>
    E-mail: {{ $event->email }}
</contacts>

<price>
    <h6>Valor do ingresso</h6>
    {{ $event->value_per_person }} <br>
</price>

<confirm>
    Para confirmar sua participação <a href="#">Clique aqui</a>.
</confirm>