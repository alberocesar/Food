<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Carrinho extends BaseController
{
    public function index() {


    }
   

    public function adicionar() {

        if($this->request->getMethod() === 'post'){


            $produtoPost = $this->request->getPost('produto');
            
            dd($produtoPost);

        }else{

            return redirect()->back();
        }
    }
}
