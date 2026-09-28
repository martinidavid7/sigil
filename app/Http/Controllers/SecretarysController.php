<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Secretary;

class SecretarysController extends Controller
{

    public function index()
    {

        $secretarys = Secretary::all();

        return view('register.secretarys', ['secretarys' => $secretarys]);
    }
    public function create()
    {
        return view('register.secretarycreate');
    }


    public function store(Request $request)
    {
        try {

            $secretary = new Secretary;

            $secretary->secretary = $request->secretary;
            $secretary->responsible_name = $request->responsible_name;
            $secretary->address = $request->address;
            $secretary->phone = $request->phone;
            $secretary->neighborhood = $request->neighborhood;
            $secretary->number = $request->number;
            $secretary->zip_code = $request->zip_code;

            $secretary->save();

            toastr()->success('Secretaria Cadastrada com Sucesso!');

            return redirect('/secretarys');
        } catch (Exception $e) {
            toastr($e->getMessage(), 'error');
        }
    }

    public function edit($id)
    {
        try {
            $secretary = Secretary::findOrFail($id);

            return view('/register.secretaryedit', ['secretary' => $secretary]);
        } catch (Exception $e) {
            toastr($e->getMessage(), 'error');
        }
    }
    
    public function update(Request $request)
    {
        try {
            
            $data = $request->all();

            //exclui o token e method que esta dando erro
            unset($data['_token']); 
            unset($data['_method']); 

            Secretary::findOrFail($request->id)->update($data);

            toastr()->success('Data has been saved successfully!');
           

            return redirect('/secretarys');

        } catch (Exception $e) {
            toastr($e->getMessage(), 'error');
        }
    }


    public function destroy($id)
    {

        Secretary::findOrFail($id)->delete();

        return redirect('/secretarys');
    }
}
