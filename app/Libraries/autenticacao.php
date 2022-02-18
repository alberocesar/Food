<?php 

/*Essa class cuidará da parte de autenticação na nossa aplicação
*/

class autenticacao {

    private $usuario;

    public function login(string $email, string $password){

        $usuarioModel = new App\Models\UsuarioModel();

        $usuario = $usuarioModel->buscaUsuarioPorEmail($email);

        // se não encontrar usuario por E-mail , retorna falso
        if($usuario == null) {

            return false;
        }
        // se a senha não combinar com o hash retorna falso
        if(!$usuario->verificaPassword($password)){

            return false;

        }

        /* só permetiremos o login dos usaurios ativos */

        if(!$usuario->ativo) {
            return false;
        }

        // Nesse ponto esta tudo Certo e podemos logar o usuario na aplicação invocando o método abaixo
        $this->logaUsuario($usuario);

        return true;
         
    }

    private function logaUsuario(object $usuario){

        $session = session();
        $session->regenerate();
        $session->set('usuario_id',$usuario->id);
    }

}

?>