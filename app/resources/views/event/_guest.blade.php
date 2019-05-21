<tr>
    <td @if(null !== $child && !empty($child) ) ? style="padding-left: 30px" : null @endif>
        <label class="checkbox vcheck">
            <input type="checkbox" name="EventGuest[ids][]" value="{{$guest['id']}}" class="checkbox-event-guest">
            <span></span>
        </label>
    </td>
    <td @if(null !== $child && !empty($child) ) ? style="padding-left: 30px" : null @endif>{{ $guest['person_name'] }}</td>
    <td @if(null !== $child && !empty($child) ) ? style="padding-left: 30px" : null @endif>{{ $guest['email'] }}</td>
    <td>{{ $guest['status'] }}</td>
    <td>
    </td>
</tr>
@if (isset($guest['children']))
    @foreach ($guest['children'] as $guest)
        <?php $child = true?>
        @include('event/_guest', compact('guest', 'child'))
    @endforeach
@endif