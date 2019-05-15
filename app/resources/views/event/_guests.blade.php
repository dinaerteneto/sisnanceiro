<h1>Lista de convidados</h1>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nome</th>
            <th>E-Mail</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $record)
            <tr>
                <td>{{ $record['name'] }}</td>
                <td>{{ $record['email'] }}</td>
                <td>{{ $record['status'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>