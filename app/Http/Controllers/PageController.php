<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    
    //Landing Page
    public function landing(){
        return view('landing');
    }

}
