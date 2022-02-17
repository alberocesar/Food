<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        $usuarioModel = new \App\Models\UsuarioModel;

        $usuario = [

            'nome' => 'Alberto Cesar Marques Amorim',
            'email' => 'Albertocesar@hotmail.com',
            'cpf' => '495.513.710-54',
            'telefone' => '81 - 99609-8382',
            'password_hash' => '123456'
        ];

        $usuarioModel->protect(false)->insert($usuario);

        $usuario = [

            'nome' => 'adasi softwere',
            'email' => 'adasi@hotmail.com',
            'cpf' => '541.770.370-28',
            'telefone' => '81 - 8888-8382',
            'password_hash' => '123456'
        ];

        $usuarioModel->protect(false)->insert($usuario);

        
        
    }
}
