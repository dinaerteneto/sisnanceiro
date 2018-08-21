@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">

                    @if ($people)
                       <table class="table">
                           <thead>
                               <th>Nome</th>
                               <th>Sexo</th>
                           </thead>
                           <tbody>
                            @foreach ($people as $person)
                            <tr>
                                <td>{{ $person->name }} {{$person->last_name}}</td>
                                <td>{{ $person->gender}} </td>
                            </tr>
                            @endforeach
                           </tbody>
                       </table>
                    @endif


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
