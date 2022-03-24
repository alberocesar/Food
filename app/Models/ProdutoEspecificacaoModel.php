<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutoEspecificacaoModel extends Model
{

    protected $table            = 'produtos_especificacoes';
    protected $returnType       = 'object';
    protected $allowedFields    = ['produto_id', 'medida_id', 'preco', 'customizavel'];
    protected $validationRules    = [
        'medida_id'  => 'required|integer',
        'preco' => 'required|greater_than[0]',
    ];

    protected $validationMessages = [
        'medida_id'   => [
            'required' => 'O campo Medida é obrigatório.',
        ],
    ];

}
