<li class="message mb-10" id="{{ $newId }}">
    <img src="{{ asset('assets/img/avatars/4.png') }}" alt="" class="img-circle">
    <div class="message-text">
        <input type="text" name="Guest[{{ $newId }}][name]" class="control-form" placeholder="Nome do convidado" />
        <input type="text" name="Guest[{{ $newId }}][email]" class="control-form" placeholder="E-mail do convidado" />
        <a href="/event/guest/delete/{{ $newId }}" class="text-danger delete-guest" rel="tooltip" data-placement="top" title="Remover convidado"><i class="fa fa-times-circle"></i></a>
    </div>
</li>