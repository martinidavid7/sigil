@extends('layouts.main')
@section('title', '..:: SIGIL - Modalidades ::..')

@section('content')
{{$biddingprocedure->enabled}}
    <div class="col-md-8 offset-md-2">
        <h1>Atualizar dados da Modalidade</h1>
        <form action="/biddingprocedures/update/{{ $biddingprocedure->id }}" method="POST" enctype="multipart/form-data">
            @csrf <!--Exigência do Framework Laravel-->
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <label for="title">Modalidade</label>
                    <input type="text" class="form-control" id="mode" name="mode" placeholder="Modalidade"
                        value="{{ $biddingprocedure->mode }}" required>
                </div>
                <div class="col-md-4">
                    <label for="title">Prazo</label>
                    <input type="text" class="form-control" id="deadline" name="deadline" placeholder="Prazo"
                        value="{{ $biddingprocedure->deadline }}" required>
                </div>
                <div class="col-md-2">
                    <label for="title">Situação</label>
                    <select class="form-control" id="enabled" name="enabled" aria-label="Default select example">
                        <option value="0">Inativo</option>
                        <option value="1" {{$biddingprocedure->enabled == 1 ? "selected='selected'" : ""}}>Ativo</option>
                        
                    </select>
                    <!--
                        <input type="radio" class="form-control" id="active" name="active" placeholder="Ativo"
                            value="{{ $biddingprocedure->active }}" required>
                        -->
                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <br>
                    <h5>Compras e Serviços</h5>
                </div>
                <div class="col-md-6">
                    <label for="title">Valor Mínimo</label>
                    <input type="text" class="form-control" id="purchase_services_minimum_value"
                        name="purchase_services_minimum_value" placeholder="Valor Mínimo"
                        value="{{ $biddingprocedure->purchase_services_minimum_value }}" required>
                </div>
                <div class="col-md-6">
                    <label for="title">Valor Máximo </label>
                    <input type="text" class="form-control" id="purchase_services_maximum_value"
                        name="purchase_services_maximum_value" placeholder="Valor Máximo"
                        value="{{ $biddingprocedure->purchase_services_maximum_value }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <br>
                    <h5>Obras e Engenharia</h5>
                </div>
                <div class="col-md-6">
                    <label for="title">Valor Mínimo</label>
                    <input type="text" class="form-control" id="construction_engineering_minimum_value"
                        name="construction_engineering_minimum_value" placeholder="Valor Mínimo"
                        value="{{ $biddingprocedure->construction_engineering_minimum_value }}" required>
                </div>
                <div class="col-md-6">
                    <label for="title">Valor Máximo </label>
                    <input type="text" class="form-control" id="construction_engineering_maximum_value"
                        name="construction_engineering_maximum_value" placeholder="Valor Máximo"
                        value="{{ $biddingprocedure->construction_engineering_maximum_value }}">
                </div>
            </div>


            <br>
            @if ($biddingprocedureSteps)
                <h5>Etapas</h5>
                <br>

                @foreach ($biddingprocedureSteps as $step)
                    <div class="form-check">
                        @if (isset($biddingprocedure->steps) && in_array($step->id, $biddingprocedure->steps))
                            <input class="form-check-input" type="checkbox" name="steps[]" value="{{ $step->id }}"
                                id="{{ $step->id }}" checked />
                        @else
                            <input class="form-check-input" type="checkbox" name="steps[]" value="{{ $step->id }}"
                                id="{{ $step->id }}" />
                        @endif
                        <label class="form-check-label" for="flexCheckDefault">
                            {{ $step->step_name }}
                        </label>
                    </div>
                @endforeach
                <br>
            @endif
            <input type="submit" class="btn btn-primary" value="Salvar">
            <a class="btn btn-primary" href="/biddingprocedures" role="button">Voltar</a>
        </form>
        <br>
    </div>

@endsection
