@extends('layouts.main')
@section('title', '..:: SIGIL - Secretarias ::..')

@section('content')

    <div class="col-md-8 offset-md-2">

        <h1>Criar Nova Secretaria</h1>
        
        <form action="/secretarysstore" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Secretaria</label>
                <input type="text" class="form-control" id="secretary" name="secretary" placeholder="Secretaria" required>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <label for="title">Endereço</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Endereço" required>
                </div>
                <div class="col-md-2">
                    <label for="title">Nº</label>
                    <input type="text" class="form-control" id="number" name="number" placeholder="numero" required>
                </div>
            </div>
            <div class="form-group">
                <label for="title">Bairro</label>
                <input type="text" class="form-control" id="neighborhood" name="neighborhood" placeholder="Bairro" required>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <label for="title">Secretario</label>
                    <input type="text" class="form-control" id="responsible_name" name="responsible_name" placeholder="Secretario" required>
                </div>
                <div class="col-md-4">
                    <label for="title">Telefone</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Telefone" required>
                </div>
            </div>
            <br>
            <input type="submit" class="btn btn-primary" value="Cadastrar Secretaria">
            <a class="btn btn-primary" href="/secretarys" role="button">Voltar</a>

        </form>

    </div>

@endsection
