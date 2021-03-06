@if($data['status_int'] === 1) 
<div class="text-center">
    <a href="/guest/{{ $data['token_email'] }}/change-status/2" class="change-status">
        <i class="fa fa-thumbs-o-up fa-4x text-info"></i>
        <br>Clique para confirmar sua presença.
    </a>
</div>
@endif 

@if($data['status_int'] === 2) 
<div class="text-center">
    <i class="fa fa-check-circle fa-4x text-success"></i>
    <br>Confirmada em: {{ $data['updated_at'] }}
    <br><a href="/guest/{{ $data['token_email'] }}/change-status/3" class="change-status">Revogar presença</a>
</div>
@endif

@if($data['status_int'] === 3)
<div class="text-center">
    <i class="fa fa-times-circle fa-4x text-danger"></i>
    <br>Sua presença foi negada em: {{ $data['updated_at'] }}
    <br><a href="/guest/{{ $data['token_email'] }}/change-status/2" class="change-status">Aceitar convite</a>
</div>
@endif

<form id="form-2" method="post" action="">
    @csrf
    <input type="hidden" name="EventGuest[token_email]" value="{{ $data['token_email'] }}">
</form>