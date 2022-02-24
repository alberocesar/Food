<?php  

namespace App\Libraries;


class token {

    private $token;

    public function __construct($token = null) {

        if($token === null){

            $this->token = bin2hex(random_bytes(16));

        }else{

            $this->token = $token;
        }
    }

    public function getValue() {

        return $this->token;
    }


    public function getHasg() {


        return hash_hmac('sha256', $this->token, env('CHAVE_SECRETA_ATIVACAI_CONTA'));
    }
}




?>
