<?php

namespace App\Http\Controllers;

use App\Models\CityHall;
use App\Models\BiddingProcedure;
use App\Models\Secretary;

use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function index(){

        $secretarys = Secretary::all();


        return view('manager', ['secretarys' => $secretarys]);
    }
}
