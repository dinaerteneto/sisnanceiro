<tr id="{{ $newId }}" class="tr-new">
    <td>
        <input type="text" name="Guest[{{ $newId }}][name]" class="control-form" placeholder="Nome do convidado" />
    </td>
    <td>
        <input type="text" name="Guest[{{ $newId }}][email]" class="control-form" placeholder="E-mail do convidado" />
    </td>
    <td>&nbsp;</td>
    <td>
        <a href="/event/guest/delete/{{ $newId }}" class="text-danger delete-guest" rel="tooltip" data-placement="top" title="Remover convidado"><i class="fa fa-times-circle"></i></a>
    </td>
</tr>