<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function Index(){
    	return view('frontpages.index');
    }
    public function Home(){
        return view('index');
    }
    public function Contact(){
    	return view('frontpages.contact-us');
    }
}
