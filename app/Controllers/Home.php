<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index() {
    
        $data = [
            'titulo' => 'Seja Bem vindo',

        ];

        return view('Home/index', $data);
    }


}
