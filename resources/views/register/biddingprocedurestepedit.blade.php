@extends('layouts.main')
@section('title', '..:: SIGIL - Etapas da Modalidades de Licitações ::..')

@section('content')

<div class="col-md-8 offset-md-2">

    <h1>Editar Modalidade de Licitação</h1>
    <form action="/biddingprocedurestep/update/{{ $biddingprocedurestep->id }}" method="POST" enctype="multipart/form-data">
        @csrf <!--Exigência do Framework Laravel-->
        @method('PUT')
        <div class="row">
            <div class="col-md-8">
                <label for="title">Etapa</label>
                <input type="text" class="form-control" id="step_name" name="step_name" placeholder="Etapa" required value="{{$biddingprocedurestep->step_name}}">

            </div>
            <div class="col-md-2">
                <label for="title">Situação</label>
                <select class="form-control" id="enabled" name="enabled" aria-label="Default select example">
                    <option value="0">Inativo</option>
                    <option value="1" {{$biddingprocedurestep->enabled == 1 ? "selected='selected'" : ""}}>Ativo</option>

                </select>

            </div>
        </div>
        <br>
        <input type="submit" class="btn btn-primary" value="Atualizar Etapa">
        <a class="btn btn-primary" href="/biddingproceduresteps" role="button">Voltar</a>
    </form>
</div>
@endsection