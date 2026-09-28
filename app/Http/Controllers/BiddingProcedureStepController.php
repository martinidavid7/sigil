<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BiddingProcedureStep;

class BiddingProcedureStepController extends Controller
{
    public function index()
    {

        $biidingProcedureSteps = BiddingProcedureStep::where('enabled', 1)->orderBy('order')->get();

        return view('register.biddingproceduresteps', ['biidingProcedureSteps' => $biidingProcedureSteps]);
    }

    public function disabled()
    {

        $biidingProcedureSteps = BiddingProcedureStep::where('enabled', 0)->orderBy('order')->get();

        return view('register.disabledbiddingproceduresteps', ['biidingProcedureSteps' => $biidingProcedureSteps]);
    }

    public function create()
    {
        return view('register.biddingprocedurestepcreate');
    }

    public function store(Request $request)
    {
        try {

            $biddingprocedurestep = new BiddingProcedureStep;

            $biddingprocedurestep->step_name = $request->step_name;

            $biddingprocedurestep->save();

            return redirect('/biddingproceduresteps');
        } catch (Exception $e) {
            toastr($e->getMessage(), 'error');
        }
    }

    public function edit($id)
    {
        try {


            $biddingprocedurestep = BiddingProcedureStep::findOrFail($id);

            return view('/register.biddingprocedurestepedit', ['biddingprocedurestep' => $biddingprocedurestep]);
        } catch (Exception $e) {
            toastr($e->getMessage(), 'error');
        }
    }


    public function update(Request $request){

        try {

            $data = $request->all();

            //exclui o token e method que esta dando erro
            unset($data['_token']);
            unset($data['_method']);

            BiddingProcedureStep::findOrFail($request->id)->update($data);

            return redirect('/biddingproceduresteps');
        } catch (Exception $e) {
            toastr($e->getMessage(), 'error');
        }

    }
}
