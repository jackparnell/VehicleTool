<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;

class UserInputController extends Controller {


    public function index()
    {

        return View::make('userInput/index');

    }

}
