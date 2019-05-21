@if($data['status_int'] === 1) 
<div class="text-center">
    <i class="fa fa-clock-o fa-4x text-info"></i>
    <br> Aguardando pagamento para confirmar a presença.
</div>
@endif 

@if($data['status_int'] === 2) 
<div class="text-center">
    <i class="fa fa-check-circle fa-4x text-success"></i>
    <br>Pagamento confirmado em: {{ $data['updated_at'] }}
</div>
@endif

@if($data['status_int'] === 3)
<div class="text-center">
    <i class="fa fa-times-circle fa-4x text-danger"></i>
    <br>Sua presença foi negada em: {{ $data['updated_at'] }}
</div>
@endif

<form id="form-2" method="post" action="">
    @csrf
    <input type="hidden" name="EventGuest[token_email]" value="{{ $data['token_email'] }}">
</form>