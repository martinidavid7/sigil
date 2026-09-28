<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CityHall;


class CityHallController extends Controller
{
    //
    public function index(){

        $cityHallFull = CityHall::all();

        return view('register.cityhall', ['cityHallFull' => $cityHallFull]);
    }

    public function create()
    {
        return view('register.cityhallcreate');
    }

    public function store(Request $request){

        try {

            $cityHall = new CityHall;

            $cityHall->city_hall = $request->city_hall;
            $cityHall->mayor = $request->mayor;
            $cityHall->address = $request->address;
            $cityHall->number = $request->number;
            $cityHall->phone = $request->phone;
            $cityHall->neighborhood = $request->neighborhood;
            $cityHall->zip_code = $request->zip_code;
            $cityHall->cnpj = $request->cnpj;
            $cityHall->inscricao_estadual = $request->inscricao_estadual;

            $cityHall->save();
   
            return redirect('cityhall');
        } catch (Exception $e) {
            toastr($e->getMessage(), 'error');
        }
    }

    public function edit($id)
    {
        try {
            $cityHall = CityHall::findOrFail($id);



            return view('register.cityhalledit', ['cityHall' => $cityHall]);
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

            CityHall::findOrFail($request->id)->update($data);

            return redirect('cityhall');

        } catch (Exception $e) {
            toastr($e->getMessage(), 'error');
        }
    }


}
