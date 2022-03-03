<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Password extends BaseController{

    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new \App\Models\UsuarioModel();

    }

    public function esqueci() {

        $data = [
            'titulo' => 'Esqueci Minha senha',
        ];

        return view('Password/esqueci', $data);
    }

    public function processaEsqueci() {

        if($this->request->getMethod() === 'post') {

            $usuario = $this->usuarioModel->buscaUsuarioPorEmail($this->request->getPost('email'));

            if($usuario === null || !$usuario->ativo) {

                return redirect()->to(site_url('password/esqueci'))
                ->with('atencao', 'Não encontramos uma conta valida com esse email')
                ->withInput();

            }  

            $usuario->iniciaPasswordReset();

            $this->usuarioModel->save($usuario);


            /*
            precisamos atualizar o modelo Usuario 
            */

            $this->enviaEmailRedefinicaoSenha($usuario);

            return redirect()->to(site_url('login'))->with('sucesso', 'Email de redefinição de senha enviado para a caixa de entrada');
            
        }else {

            return redirect()->back();
        }
    }

    private function enviaEmailRedefinicaoSenha(object $usuario) {

        $email = service('email');

        $email->setFrom('no-replay@fooddelivery.com.br', 'Food Delivery');
        $email->setTo($usuario->email);
        

        $email->setSubject('Redefinição de senha');

        $mensagem = view('Password/reset_email', ['token' => $usuario->reset_token]);

        $email->setMessage($mensagem);

        $email->send();   
    }
    
      
    
}
