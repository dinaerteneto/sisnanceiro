<h1>Lista de convidados</h1>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nome</th>
            <th>E-Mail</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $record)
            <tr>
                <td>{{ $record['person_name'] }}</td>
                <td>{{ $record['email'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>