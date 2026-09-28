@extends('layouts.main')
@section('title', '..:: SIGIL - Etapas da Modalidades de Licitações ::..')

@section('content')

    <div class="col-md-8 offset-md-2">

        <h1>Criar Nova Etapa Modalidade de Licitação</h1>
        <form action="/biddingprocedurestepstore" method="POST">
            @csrf
            <div class="col">  
                    <label for="title">Etapa</label>
                    <input type="text" class="form-control" id="step_name" name="step_name" placeholder="Etapa" required>    
            </div>
            <br>
            <input type="submit" class="btn btn-primary" value="Cadastrar Etapa">
            <a class="btn btn-primary" href="/biddingproceduresteps" role="button">Voltar</a>
        </form>
    </div>
@endsection
