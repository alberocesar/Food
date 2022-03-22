<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutoExtraModel extends Model
{
    protected $table            = 'produtos_extras';
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = ['produto_id', 'extra_id'];
    protected $validationRules    = [
        'extra_id'  => 'required|integer',
    ];

    protected $validationMessages = [
        'extra_id'   => [
            'required' => 'Esse campo E-mail é obrigatório.',
        ],
    ];
}
