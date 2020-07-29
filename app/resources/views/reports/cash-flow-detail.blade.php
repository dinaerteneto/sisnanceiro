<div style="padding: 20px">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Forma de pagto</th>
                <th>Descrição</th>
                <th>Valor bruto</th>
                <th>Valor da taxa</th>
                <th>Valor líquido</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr class="{{ $item['net_value_original'] < 0 ? 'text-red' : 'text-blue' }}" >
                    <td> {{ $item['username'] }} </td>
                    <td> Dinheiro </td>
                    <td> {{ $item['note'] }} </td>
                    <td> {{ $item['gross_value'] }} </td>
                    <td> Vl da Taxa </td>
                    <td> {{ $item['net_value'] }} </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
