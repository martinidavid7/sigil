@extends('layouts.main')
@section('title', '..:: SIGIL - Modalidades de Licitações ::..')



@section('content')

    <h1>Modalidades de Licitação</h1>
    <div class="table-responsive">

        <table class="table table-hover">
            <thead>
                <tr>

                    <th class="align-middle text-center" rowspan="2">Modalidade</th>
                    <th class="align-middle text-center" rowspan="2">Prazo</th>
                    <th class="align-middle text-center" colspan="2">Compras e Serviços</th>
                    <th class="align-middle text-center" colspan="2">Obras e Engenharia</th>
                    <th class="align-middle text-center" rowspan="2">Situação</th>
                    <th class="align-middle text-center" rowspan="2">Ações</th>
                </tr>
                <tr>
                    <th class="align-middle text-right">Valor Mínimo</th>
                    <th class="align-middle text-right">Valor Máximo</th>

                    <th class="align-middle text-right">Valor Mínimo</th>
                    <th class="align-middle text-right">Valor Máximo</th>
                </tr>
            </thead>
            @if (isset($biddingProcedures))
                <tbody>


                    @foreach ($biddingProcedures as $biddingProcedure)
                        <tr>

                            <td>{{ $biddingProcedure->mode }}</td>
                            <td>{{ $biddingProcedure->deadline }}</td>
                            <td class="text-right">
                                {{ number_format($biddingProcedure->purchase_services_minimum_value, 2, ',', '.') }}</td>
                            <td class="text-right">
                                {{ number_format($biddingProcedure->purchase_services_maximum_value, 2, ',', '.') }}</td>
                            <td class="text-right">
                                {{ number_format($biddingProcedure->construction_engineering_minimum_value, 2, ',', '.') }}
                            </td>
                            <td class="text-right">
                                {{ number_format($biddingProcedure->construction_engineering_maximum_value, 2, ',', '.') }}
                            </td>
                            <td class="text-center">
                                {{ $biddingProcedure->enabled == 1 ? 'Ativo' : 'Inativo' }}
                            </td>
                            <td scope="row"><a href="/biddingprocedure/{id}{{ $biddingProcedure->id }}" scope="row"><a
                                        href="/biddingprocedureedit/{{ $biddingProcedure->id }}"
                                        class="btn btn-success edit-btn"><ion-icon
                                            name="create-outline"></ion-icon>Alterar</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
        </table>

    </div>
   



@endsection
