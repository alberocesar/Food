<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Entities\Bairro;

class Bairros extends BaseController {

    private $bairroModel;

    public function __construct() {

        $this->bairroModel = new \App\Models\BairroModel();
    }


    public function index() {

        $data = [
            'titulo' => "Listando os bairros atendidos",
            'bairros' => $this->bairroModel->withDeleted(true)->orderBy('nome', 'ASC')->paginate(10),
            'pager' => $this->bairroModel->pager,
        ];

        return view('Admin/Bairros/index', $data);
    }

    public function procurar() {

        if (!$this->request->isAJAX()) {

            exit('Página não encontrada');
        }


        $bairros = $this->bairroModel->procurar($this->request->getGet('term'));

        $retorno = [];


        foreach ($bairros as $bairro) {

            $data['id'] = $bairro->id;
            $data['value'] = $bairro->nome;

            $retorno[] = $data;
        }

        return $this->response->setJSON($retorno);
    }

    public function criar() {

        $bairro = new Bairro();


        $data = [
            'titulo' => "Cadastrando novo bairro",
            'bairro' => $bairro,
        ];

        return view('Admin/Bairros/criar', $data);
    }
}
