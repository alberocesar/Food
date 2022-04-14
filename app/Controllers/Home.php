<?php

namespace App\Controllers;

class Home extends BaseController {

    private $categoriaModel;
    private $produtoModel;

    public function __construct() {

        $this->categoriaModel = new \App\Models\CategoriaModel();
        $this->produtoModel = new \App\Models\ProdutoModel();
    }

    public function index() {
    
        $data = [
            'titulo' => 'Seja Bem vindo',
            'categorias' => $this->categoriaModel->buscaCategoriasWebHome(),
            'produtos' => $this->produtoModel->buscaProdutosWebHome(),
        ];

        return view('Home/index', $data);
    }


}
