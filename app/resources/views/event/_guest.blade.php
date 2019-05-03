<li class="message mb-10">
    <img src="{{ asset('assets/img/avatars/4.png') }}" alt="" class="img-circle">
    <div class="message-text">
        <a href="javascript:void(0);" class="username text-blue-dark">{{ $guest['person_name'] }}</a> 
        <span class="font-xs">
            {{ $guest['email'] }}  <br /> 
            <span class="label label-info">4 CONVIDADOS</span>
                {{$label = 'success'}}
                @switch($guest['status'])
                    @case('AGUARDANDO')
                        {$label = 'success'}
                        @break
                    @case('CONFIRMADO')
                        {$label = 'info'}
                        @break
                    @case('NEGADO')
                        {$label = 'danger'}
                        @break
                @endswitch
            <span class="label label-{{ $label }}">            
            </span>
        </span>
        <time class="p-relative d-block margin-top-5"> R$ 60,00 </time> 
    </div>
</li>