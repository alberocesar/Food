<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdutoModel;

class Produto extends BaseController
{
    private $produtoModel;

    public function __construct() {

        $this->produtoModel = new \App\Models\ProdutoModel();
    }

    public function detalhes(string $produto_slug = null) {
    
        if(!$produto_slug || !$produto = $this->produtoModel->where('slug', $produto_slug)->first()) {

            return redirect()->to(site_url('/'));
        }

        $data = [
            'titulo' => "Detalhando o produto $produto->nome",
            'produto' => $produto,
        ];

        return view('Produto/detalhes', $data);
        
    }


    public function imagem(string $imagem = null) {

        if ($imagem) {

            $caminhoImagem = WRITEPATH . 'uploads/produtos/' . $imagem;

            $infoImagem = new \finfo(FILEINFO_MIME);

            $tipoImagem = $infoImagem->file($caminhoImagem);

            header("Content-Type: $tipoImagem");

            header("Content-Length: " . filesize($caminhoImagem));

            readfile($caminhoImagem);

            exit;
        }

    }
}
