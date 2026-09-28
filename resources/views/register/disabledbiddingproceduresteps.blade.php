@extends('layouts.main')
@section('title', '..:: SIGIL - Etapas das Modalidades de Licitações - Inativas::..')

@section('content')

<h1>Etapas do Processo de Licitação</h1>
<div class="table-responsive">

    <table class="table table-hover">
        <thead>
            <tr>

                <th class="align-middle text-left" rowspan="2">Etapa</th>

                <th class="align-middle text-left" rowspan="2">Ações</th>
            </tr>
        </thead>
        @if (isset($biidingProcedureSteps))
        <tbody>


            @foreach ($biidingProcedureSteps as $biidingProcedureSteps)
            <tr>

                <td>{{ $biidingProcedureSteps->step_name }}</td>

                <td scope="row"><a href="/biddingprocedurestep/{id}{{ $biidingProcedureSteps->id }}" scope="row"><a
                            href="/biddingprocedurestepedit/{{ $biidingProcedureSteps->id }}"
                            class="btn btn-success edit-btn"><ion-icon
                                name="create-outline"></ion-icon>Alterar</a>


                </td>
            </tr>
            @endforeach
        </tbody>
        @endif
    </table>

</div>
<a class="btn btn-primary" href="/biddingprocedurestepcreate" role="button">Adicionar Etapa</a>


@endsection

