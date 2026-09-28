@extends('layouts.main')
@section('title', '..:: SIGIL - Modalidades de Licitações ::..')

@section('content')

    <div class="col-md-8 offset-md-2">

        <h1>Criar Nova Modalidade de Licitação</h1>
        <form action="/biddingprocedurestore" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <label for="title">Modalidade</label>
                    <input type="text" class="form-control" id="mode" name="mode" placeholder="Modalidade" required>
                </div>
                <div class="col-md-4">
                    <label for="title">Prazo</label>
                    <input type="text" class="form-control" id="deadline" name="deadline" placeholder="Prazo">
                </div>
                <div class="col-md-2">
                    <label for="title">Situação</label>
                    <select class="form-control" id="enabled" name="enabled" aria-label="Default select example">
                        <option value="0">Inativo</option>
                        <option value="1">Ativo</option>
                    </select>
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
                        name="purchase_services_minimum_value" placeholder="Valor Mínimo" required>
                </div>
                <div class="col-md-6">
                    <label for="title">Valor Máximo</label>
                    <input type="text" class="form-control" id="purchase_services_maximum_value"
                        name="purchase_services_maximum_value" placeholder="Valor Máximo">
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
                        name="construction_engineering_minimum_value" placeholder="Valor Mínimo" required>
                </div>
                <div class="col-md-6">
                    <label for="title">Valor Máximo</label>
                    <input type="text" class="form-control" id="construction_engineering_maximum_value"
                        name="construction_engineering_maximum_value" placeholder="Valor Máximo">
                </div>
            </div>
            <br>
            @if (isset($biddingprocedureSteps))
                <h5>Etapas</h5>
                <br>

                @foreach ($biddingprocedureSteps as $step)
                    <div class="form-check">

                        <input class="form-check-input" type="checkbox" name="steps[]" value="{{ $step->id }}"
                            id="{{ $step->id }}" />

                        <label class="form-check-label" for="flexCheckDefault">
                            {{ $step->step_name }}
                        </label>
                    </div>
                @endforeach
                <br>
            @endif

            <input type="submit" class="btn btn-primary" value="Cadastrar Modalidade">
            <a class="btn btn-primary" href="/biddingprocedures" role="button">Voltar</a>
        </form>
    </div>
    <br>
@endsection
