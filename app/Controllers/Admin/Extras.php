<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\Extra;

class Extras extends BaseController {

    private $extraModel;

    public function __construct() {
    
        $this->extraModel = new \App\Models\ExtraModel();
    }

    public function index () {

        $data = [
            'titulo' => 'Listando os extras de produtos',
            'extras' => $this->extraModel->withDeleted(true)->paginate(10),
            'pager' => $this->extraModel->pager
        ];

        return view('Admin/Extras/index', $data);
    }

    public function procurar() {

        if (!$this->request->isAJAX()) {

            exit('Página não encontrada');
        }


        $extras = $this->extraModel->procurar($this->request->getGet('term'));

        $retorno = [];


        foreach ($extras as $extra) {

            $data['id'] = $extra->id;
            $data['value'] = $extra->nome;

            $retorno[] = $data;
        }

        return $this->response->setJSON($retorno);
    }

    private function buscaExtraOu404(int $id = null) {

        if (!$id || !$extra = $this->extraModel->withDeleted(true)->where('id', $id)->first()) {

            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o extra $id");
        }

        return $extra;
    }

    public function show($id = null) {

        

        $extra = $this->buscaextraOu404($id);

        $data = [
            'titulo' => "Detalhando o extra $extra->nome",
            'extra' => $extra,
        ];

        return view('Admin/Extras/show', $data);
    }
   
}
