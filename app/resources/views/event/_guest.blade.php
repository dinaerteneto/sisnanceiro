<tr>
    <td>
        <label class="checkbox vcheck">
            <input type="checkbox" name="EventGuest[{{$guest['id']}}]" value="1">
            <span></span>
        </label>
    </td>
    <td>{{ $guest['person_name'] }}</td>
    <td>{{ $guest['email'] }}</td>
    <td>
            <?php $label = null?>
            @switch( $guest['status_int'])
                @case(1)
                    <?php $label = 'warning'?>
                @break
                @case(2)
                    <?php $label = 'success'?>
                @break
                @case(3)
                    <?php $label = 'danger'?>
                @break
            @endswitch
            <span class="label label-{{ $label }}">
                {{ $guest['status'] }}
            </span>&nbsp;
            
    </td>
    <td>
        <span class="label label-info">
            {{ count($guest['invitedByMe']) }} CONVIDADOS
        </span>

    </td>

</tr>