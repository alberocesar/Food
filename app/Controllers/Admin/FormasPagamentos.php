<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Entities\FormaPagamento;

class FormasPagamentos extends BaseController{

    private $formaPagamentoModel;


    public function __construct() {
        
        $this->formaPagamentoModel = new \App\Models\FormaPagamentoModel();

    }

    public function index() {

        $data = [
            'titulo' => 'Listando as Formas de pagamento',
            'formas' => $this->formaPagamentoModel->withDeleted(true)->paginate(10),
            'pager' => $this->formaPagamentoModel->pager,
        ];

        return view('Admin/FormasPagamento/index', $data);

    }
}
