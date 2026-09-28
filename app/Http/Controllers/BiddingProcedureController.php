<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BiddingProcedure;
use App\Models\BiddingProcedureStep;




class BiddingProcedureController extends Controller
{
    public function index()
    {
        $biddingProcedures = BiddingProcedure::all();
        return view('register.biddingprocedures', ['biddingProcedures' => $biddingProcedures]);
    }


    public function create()
    {

        $biddingprocedureSteps = BiddingProcedureStep::where([
            ['enabled', '=', 1]
        ])->get();

        return view('register.biddingprocedurecreate', ['biddingprocedureSteps' => $biddingprocedureSteps]);
    }


    public function store(Request $request)
    {

        try {

            $biddingprocedure = new BiddingProcedure;

            $biddingprocedure->mode = $request->mode;
            $biddingprocedure->deadline = $request->deadline;
            $biddingprocedure->purchase_services_minimum_value = $request->purchase_services_minimum_value;
            $biddingprocedure->purchase_services_maximum_value = $request->purchase_services_maximum_value;
            $biddingprocedure->construction_engineering_minimum_value = $request->construction_engineering_minimum_value;
            $biddingprocedure->construction_engineering_maximum_value = $request->construction_engineering_maximum_value;
            $biddingprocedure->steps = $request->steps;
            $biddingprocedure->enabled = $request->enabled;

            $biddingprocedure->save();

            return redirect('/biddingprocedures');
        } catch (Exception $e) {
            toastr($e->getMessage(), 'error');
        }
    }

    public function edit($id)
    {
        try {

            $biddingprocedure = BiddingProcedure::findOrFail($id);


            $biddingprocedureSteps = BiddingProcedureStep::where([
                ['enabled', '=', 1]
            ])->get();



           return view('register.biddingprocedureedit', [
                "biddingprocedure" => $biddingprocedure,
                "biddingprocedureSteps" => $biddingprocedureSteps
           ]);
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


            BiddingProcedure::findOrFail($request->id)->update($data);

            return redirect('/biddingprocedures');
        } catch (Exception $e) {
            toastr($e->getMessage(), 'error');
        }
    }

    public function destroy($id)
    {

        BiddingProcedure::findOrFail($id)->delete();

        return redirect('/biddingprocedures');
    }
}
