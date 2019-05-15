<li class="message mb-10">
    <img src="{{ asset('assets/img/avatars/4.png') }}" alt="" class="img-circle">
    <div class="message-text">
        <a href="javascript:void(0);" class="username text-blue-dark">{{ $guest['person_name'] }}</a> 
        <span class="font-xs">
            {{ $guest['email'] }}  <br /> 
            <?php $label = null ?>
            @switch( $guest['status_int'])
                @case(1)
                    <?php $label = 'success' ?>
                @break
                @case(2)
                    <?php $label = 'warning' ?>
                @break
                @case(3)
                    <?php $label = 'danger' ?>
                @break
            @endswitch
            <span class="label label-{{ $label }}">            
                {{ $guest['status'] }}
            </span>&nbsp;
            <span class="label label-info"> 
                {{ count($guest['invitedByMe']) }} CONVIDADOS
            </span>
        </span>
    </div>
</li>