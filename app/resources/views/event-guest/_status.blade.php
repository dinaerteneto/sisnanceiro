@if($data['status_int'] !== 2) 
<div class="text-center">
    <a href="/guest/{{ $data['id'] }}/change-status/2" class="change-status">
        <i class="fa fa-thumbs-o-up fa-4x text-info"></i>
        <br>Clique para confirmar sua presença.
    </a>
</div>
@endif 

@if($data['status_int'] === 2) 
<div class="row">
    <br>Confirmada em: {{ $data['updated_at'] }}
    <br><small>
        <a href="/guest/{{ $data['id'] }}/change-status/3" class="change-status">Revogar presença</a>
    </small>
    <!-- <br><strong>Pagto confirmado em: 15/07/85</strong> -->
</div>
@endif

@if($data['status_int'] === 3)
<div class="text-center">
    <i class="fa fa-times-circle fa-3x text-danger"></i>
    <br>Sua presença foi negada em: {{ $data['updated_at'] }}
    <br><a href="/guest/{{ $data['id'] }}/change-status/2" class="change-status">Aceitar convite</a>
</div>
@endif