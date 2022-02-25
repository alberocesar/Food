<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Libraries\Token;

class Usuario extends Entity {
    protected $dates   = [
        'criado_em', 
        'atualizado_em',
        'deletado_em'
    ];

    public function verificaPassword(string $password, string $password_hash) {
        

        return password_verify($password, $password_hash);
    }


    public function iniciaPasswordReset() {

        
        $token = new token();

        $this->reset_token = $token->getValue();

        $this->reset_hash = $token->getHash();

        $this->reset_expira_em = date('Y-m-d H:i:s', time() + 7200); //expira em 2hs a apartir da data e hora atuais
    }
    
}
