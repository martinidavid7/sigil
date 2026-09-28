@extends('layouts.main')
@section('title', '..:: SIGIL - Secretarias ::..')

@section('content')

    <div class="col-md-8 offset-md-2">

        <h1>Cadastrar Prefeitura</h1>

        <form action="/cityhallstore" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Prefeitura</label>
                <input type="text" class="form-control" id="city_hall" name="city_hall" placeholder="Prefeitura" required>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <label for="title">Endereço</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Endereço"
                        required>
                </div>
                <div class="col-md-2">
                    <label for="title">Nº</label>
                    <input type="text" class="form-control" id="number" name="number" placeholder="numero" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <label for="title">Bairro</label>
                    <input type="text" class="form-control" id="neighborhood" name="neighborhood" placeholder="Bairro"
                        required>
                </div>
                <div class="col-md-4">
                    <label for="title">CEP</label>
                    <input type="text" class="form-control" id="zip_code" name="zip_code" placeholder="CEP" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="title">CNPJ</label>
                    <input type="text" class="form-control" id="cnpj" name="cnpj" placeholder="CNPJ" >
                </div>
                <div class="col-md-6">
                    <label for="title">Inscrição Estadual</label>
                    <input type="text" class="form-control" id="inscricao_estadual" name="inscricao_estadual" placeholder="Inscrição Estadual" >
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <label for="title">Prefeito Municipal</label>
                    <input type="text" class="form-control" id="mayor" name="mayor" placeholder="Prefeito" required>
                </div>
                <div class="col-md-4">
                    <label for="title">Telefone</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Telefone"
                        required>
                </div>
            </div>
            <br>
            <input type="submit" class="btn btn-primary" value="Cadastrar Prefeitura">
            <a class="btn btn-primary" href="/cityhall" role="button">Voltar</a>

        </form>

    </div>

@endsection
