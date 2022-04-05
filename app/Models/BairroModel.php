<?php

namespace App\Models;

use CodeIgniter\Model;

class BairroModel extends Model
{
    protected $table            = 'bairros';
    protected $primaryKey       = 'App\Entities\Bairro';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['nome', 'slug', 'valor_entrega', 'ativo'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';
    protected $deletedField  = 'deletado_em';

    // Validation
    protected $validationRules    = [
        'nome'  => 'required|min_length[2]|max_length[120] |is_unique[bairros.nome]',
        'cep'  => 'required|exact_length[9]',
        'valor_entrega'  => 'required',
    ];

    protected $validationMessages = [
        'nome'   => [
            'required' => 'Esse campo Nome é obrigatório.',
            'is_unique' => 'Essa Bairro já existe.',
        ],
        'valor_entrega'   => [
            'required' => 'Esse campo valor de entrega é obrigatório.',
            
        ],
        
    ];

    protected $beforeInsert = ['criaSlug'];
    protected $beforeUpdate = ['criaSlug'];

    protected function criaSlug(array $data) {

        if (isset($data['data']['nome'])) {

            $data['data']['slug'] = mb_url_title($data['data']['nome'], '-', true);

            

        }

        return $data;
    }

    public function procurar($term) {

        if ($term === null) {

            return [];
        }


        return $this->select('id, nome')
                        ->like('nome', $term)
                        ->withDeleted(true)
                        ->get()
                        ->getResult();
    }

    public function desfazerExclusao(int $id) {

        return $this->protect(false)
            ->where('id', $id)
            ->set('deletado_em', null)
              ->update();
        }



}
