<?php

namespace App\Models;

use CodeIgniter\Model;

class FormaPagamentoModel extends Model
{
    protected $table            = 'formas_pagamento';
    protected $returnType       = 'App\Entities\FormaPagamento';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'nome', 'ativo'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';
    protected $deletedField  = 'deletado_em';

    protected $validationRules    = [
        'nome'  => 'required|min_length[2]|max_length[120] |is_unique[formas_pagamento.nome]',
    ];

    protected $validationMessages = [
        'nome'        => [
            'required' => 'Esse campo E-mail é obrigatório.',
            'is_unique' => 'Essa forma de pagamento já existe.',
        ],
        
    ];

}
