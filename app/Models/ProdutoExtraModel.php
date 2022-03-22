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

    /**
     * @discrição Recuperar os extras do produto em questão
     * @uso controller Ademin/produtos/extra($id = null)
     * @param int $produto_id
     */

    public function buscaExtrasDoProduto(int $produto_id = null) {

        return $this->select('extras.nome AS extra, produtos_extras.*')
                     ->join('extras', 'extras.id = produtos_extras.extra_id')
                     ->join('produtos', 'produtos.id = produtos_extras.produto_id')
                     ->where('produtos_extras.produto_id', $produto_id)
                     ->findAll();
    }
}
