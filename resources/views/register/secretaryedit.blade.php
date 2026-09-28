@extends('layouts.main')
@section('title', '..:: SIGIL - Secretarias ::..')

@section('content')

    <div class="col-md-8 offset-md-2">
        <h1>Atualizar dados da Secretaria</h1>
      
       
        <form action="/secretarys/update/{{ $secretary->id }}" method="POST" enctype="multipart/form-data">
            @csrf <!--Exigência do Framework Laravel-->
            @method('PUT')
            <div class="form-group">
                <label for="title">Secretaria</label>
                <input type="text" class="form-control" id="secretary" name="secretary" placeholder="Secretaria"
                    value="{{ $secretary->secretary }}" required>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <label for="title">Endereço</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Endereço"
                        value="{{ $secretary->address }}" required>
                </div>
                <div class="col-md-2">
                    <label for="title">Nº</label>
                    <input type="text" class="form-control" id="number" name="number" placeholder="numero"
                        value="{{ $secretary->number }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <label for="title">Bairro</label>
                    <input type="text" class="form-control" id="neighborhood" name="neighborhood" placeholder="Bairro"
                        value="{{ $secretary->neighborhood }}" required>
                </div>
                <div class="col-md-4">
                    <label for="title">Bairro</label>
                    <input type="text" class="form-control" id="zip_code" name="zip_code" placeholder="CEP"
                        value="{{ $secretary->zip_code }}" required>
                </div>
            </div>
            <div class="row">

                <div class="col-md-8">
                    <label for="title">Secretario</label>
                    <input type="text" class="form-control" id="responsible_name" name="responsible_name"
                        placeholder="Secretario" value="{{ $secretary->responsible_name }}" required>
                </div>
                <div class="col-md-4">
                    <label for="title">Telefone</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Telefone"
                        value="{{ $secretary->phone }}" required>
                </div>
            </div>
            <br>
            <input type="submit" class="btn btn-primary" value="Salvar">
            <a class="btn btn-primary" href="/secretarys" role="button">Voltar</a>



        </form>

    </div>

@endsection
