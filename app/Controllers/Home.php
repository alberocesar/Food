<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('testeci4');
    }

    public function email() {

        $email = \Config\Services::email();

        $email->setFrom('your@example.com', 'Your Name');
        $email->setTo('figob89085@shackvine.com');
        // $email->setCC('another@another-example.com');
        // $email->setBCC('them@their-example.com');

        $email->setSubject('Teste de Email');
        $email->setMessage('Email enviado com sucesso. ');

        if($email->send()) {

            echo 'Email enviado';
        }else {

           echo $email->printDebugger();
        }
    }

}
