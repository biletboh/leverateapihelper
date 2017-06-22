<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiHomeController extends Controller
{
    public function appData(Request $request){
    return 'ok'; 
    }

    public function userData(Request $request){}
}
