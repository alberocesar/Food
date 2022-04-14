<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Produto extends BaseController
{
    public function index() {
        
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
