@extends('layouts.main')
@section('title', '..:: SIGIL - Secretarias ::..')



@section('content')

    <h1>Secretarias</h1>
    <div class="overflow-scroll">

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Cód</th>
                    <th scope="col">Secretaria</th>
                    <th scope="col">Responsável</th>
                    <th scope="col">Telefone</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($secretarys as $secretary)
                    <tr>
                        <th scope="row">{{ $secretary->id }}</th>
                        <td>{{ $secretary->secretary }}</td>
                        <td>{{ $secretary->responsible_name }}</td>
                        <td>{{ $secretary->phone }}</td>
                        <td scope="row"><a href="/secretarys/{id}{{ $secretary->id }}" <td scope="row"><a
                                    href="/secretarysedit/{{ $secretary->id }}" class="btn btn-success edit-btn"><ion-icon
                                        name="create-outline"></ion-icon>Alterar</a>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <a class="btn btn-primary" href="/secretaryscreate" role="button">Adicionar Secretaria</a>



@endsection
