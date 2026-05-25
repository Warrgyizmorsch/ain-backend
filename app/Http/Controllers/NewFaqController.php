<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\FaqUrl;

class NewFaqController extends Controller {
    public function index (){
     
        
        $data = FaqUrl::all();
         
        return view('faqs.newFaq', compact('data'));
    


        // return view ('frontend.faqs.newfaq');
    }
}

