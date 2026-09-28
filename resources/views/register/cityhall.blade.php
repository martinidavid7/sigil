@extends('layouts.main')
@section('title', '..:: SIGIL - Prefeitura ::..')

@section('content')

    <h1>Prefeitura</h1>
    <div class="overflow-scroll">

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Prefeitura</th>
                    <th scope="col">Responsável</th>
                    <th scope="col">Telefone</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($cityHallFull as $cityHall)
                    <tr>
                        <td>{{ $cityHall->city_hall }}</td>
                        <td>{{ $cityHall->mayor }}</td>
                        <td>{{ $cityHall->phone }}</td>
                        <td scope="row"><a href="/cityhall/{id}{{ $cityHall->id }}" scope="row">
                                <a href="/cityhalledit/{{ $cityHall->id }}" class="btn btn-success edit-btn"><ion-icon
                                        name="create-outline"></ion-icon>Alterar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    @if (count($cityHallFull) == 0)
        <a class="btn btn-primary" href="/cityhallcreate" role="button">Adicionar Prefeitura</a>
    @endif

@endsection
