<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $returnType       = 'App\Entities\Usuario';
    protected $allowedFields    =  ['nome', 'email', 'cpf', 'telefone','is_admin', 'ativo', 'password'];

    //Datas
    protected $useTimestamps  = true;
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';
    protected $dateFormat = 'datetime'; //Para usu com o $userSorfDelete
    protected $useSoftDeletes = true;
    protected $deletedField  = 'deletado_em';
    //Validações
    protected $validationRules    = [
        'nome'  => 'required|min_length[4]|max_length[120]',
        'email' => 'required|valid_email|is_unique[usuarios.email]',
        'cpf'  => 'required|exact_length[14] |validaCpf|is_unique[usuarios.cpf]',
        'telefone'  => 'required',
        'password' => 'required|min_length[6]',
        'password_confirmation' => 'required_with[password]|matches[password_confirmation]'
    ];

    protected $validationMessages = [
        'email'        => [
            'required' => 'Esse campo E-mail é obrigatório.',
            'is_unique' => 'Desculpe. Esse email já existe.',
        ],
        'cpf'        => [
            'required' => 'Esse campo é obrigatório.',
            'is_unique' => 'Desculpe. Esse cpf já existe.',
        ],
        'nome'        => [
            'required' => 'Esse campo  Nome é obrigatório.',
        ],
    ];

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data) {

        if (isset($data['data']['password'])) {

            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);

            unset($data['data']['password_confirmation']);

        }

        return $data;
    }

    /**
     * @uso controller usuarios no metodo procurar com autocomplete 
     * 
     *
     * @param string $term
     * @return array usuario
     */


    public function procurar($term) {

        if($term === null) {

            return [];
        }


        return $this->select('id, nome')
                ->like('nome', $term)
                ->get ()
                ->getResult();
                
                   
    }

    public function desabilitaValidacaoSenha(){
        unset($this->validationRules['password']);
        unset($this->validationRules['password_confirmation']);
    }

    public function desfazerExclusao(int $id) {

    return $this->protect(false)
        ->where('id', $id)
        ->set('deletado_em', null)
          ->update();
    }


    public function buscaUsuarioPorEmail(string $email) {

        return $this->where('email', $email)->first();

    }


    
   

    
}
