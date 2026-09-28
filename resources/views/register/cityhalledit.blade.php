@extends('layouts.main')
@section('title', '..:: SIGIL - Prefeitura ::..')

@section('content')

    <div class="col-md-8 offset-md-2">
        <h1>Atualizar dados da Prefeitura</h1>


        <form action="/cityhall/update/{{ $cityHall->id }}" method="POST" enctype="multipart/form-data">
            @csrf <!--Exigência do Framework Laravel-->
            @method('PUT')
            <div class="form-group">
                <label for="title">Prefeitura</label>
                <input type="text" class="form-control" id="city_hall" name="city_hall" placeholder="Prefeitura"
                    value="{{ $cityHall->city_hall }}" required>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <label for="title">Endereço</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Endereço"
                        value="{{ $cityHall->address }}" required>
                </div>
                <div class="col-md-2">
                    <label for="title">Nº</label>
                    <input type="text" class="form-control" id="number" name="number" placeholder="numero"
                        value="{{ $cityHall->number }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <label for="title">Bairro</label>
                    <input type="text" class="form-control" id="neighborhood" name="neighborhood" placeholder="Bairro"
                        value="{{ $cityHall->neighborhood }}" required>
                </div>
                <div class="col-md-4">
                    <label for="title">Bairro</label>
                    <input type="text" class="form-control" id="zip_code" name="zip_code" placeholder="CEP"
                        value="{{ $cityHall->zip_code }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="title">CNPJ</label>
                    <input type="text" class="form-control" id="cnpj" name="cnpj" placeholder="CNPJ"
                        value="{{ $cityHall->cnpj }}">
                </div>
                <div class="col-md-6">
                    <label for="title">Inscrição Estadual</label>
                    <input type="text" class="form-control" id="inscricao_estadual" name="inscricao_estadual"
                        placeholder="Inscrição Estadual" value="{{ $cityHall->inscricao_estadual }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <label for="title">Prefeito/a</label>
                    <input type="text" class="form-control" id="mayor" name="mayor"
                        placeholder="Prefeito/a" value="{{ $cityHall->mayor }}" required>
                </div>
                <div class="col-md-4">
                    <label for="title">Telefone</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Telefone"
                        value="{{ $cityHall->phone }}" required>
                </div>
            </div>
            <br>
            <input type="submit" class="btn btn-primary" value="Salvar">
            <a class="btn btn-primary" href="/cityhall" role="button">Voltar</a>



        </form>

    </div>

@endsection
