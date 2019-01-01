<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class LangController extends Controller
{
    public function index($lang)
    {
        $langs= ['tr', 'en'];

        if (in_array($lang, $langs))
        {
            Session::put('lang', $lang);
            return Redirect::back()->with('msj', 'Dil Değiştirildi');
        }
    }
}
