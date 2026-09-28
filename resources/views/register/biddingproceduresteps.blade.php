@extends('layouts.main')
@section('title', '..:: SIGIL - Modalidades de Licitações ::..')

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
        <tbody id="sortable">
            @foreach ($biidingProcedureSteps as $step)
            <tr id="step-{{ $step->id }}">
                <td>{{ $step->step_name }}</td>
                <td>
                    <a href="/biddingprocedurestepedit/{{ $step->id }}" class="btn btn-success edit-btn">
                        <ion-icon name="create-outline"></ion-icon>Alterar
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
        @endif
    </table>

</div>
<a class="btn btn-primary" href="/biddingprocedurestepcreate" role="button">Adicionar Etapa</a>
<a class="btn btn-primary" href="/disabledbiddingproceduresteps" role="button">Etapas Inativas</a>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
    $(function() {
        $("#sortable").sortable({
            update: function(event, ui) {
                // Pega o ID da linha reordenada
                var movedRowId = ui.item.attr('id'); // Aqui você pega o ID da linha movida
                console.log("Linha movida ID: " + movedRowId);

                // Pega a nova ordem dos IDs
                var idsInOrder = $("#sortable").sortable('toArray').toString();
                console.log("Nova ordem de IDs: " + idsInOrder);

                // Exemplo de como enviar para o servidor (descomentado)
                /* $.ajax({
                    url: '/biddingprocedurestepsorder',
                    type: 'POST',
                    data: {
                        ids: idsInOrder,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log(response);
                    }
                }); */
            }
        });
    });
</script>
