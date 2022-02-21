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

        //retornando True.... tudo Certo!
        return true;
         
    }

    public function logout() {

        session()->destroy();
    }


    public function pegaUsuarioLogado() {

        /**
         * Não esquecer de compartilhar a instância com services
         */
        if($this->usuario === null) {

            $this->usuario = $this->pegaUsuarioDaSessao();

        }
        /**
         * retornamos o usuaário que foi definido no inicio da classe
         */
        return $this->usuario;
    }

    public function estaLogado () {

        return $this->pegaUsuarioLogado() !== null;
    }

    private function pegaUsuarioDaSessao() {

        if(!session()->has('usuario_id')) {

            return null;
        }
        // instanciamos o model usuario
        $usuarioModel = new App\Models\UsuarioModel();

        //recurpero o usuario de acordo com a sessão 'usuario'_id'
        $usuario = $usuarioModel->find(session()->get('usuario_id')); 
        
        //só retorno o objeto $usuario se o mesmo for encontro e estiver ativo
        if($usuario && $usuario->ativo){

            return $usuario;
        }
    }

    private function logaUsuario(object $usuario){

        $session = session();
        $session->regenerate();
        $session->set('usuario_id',$usuario->id);
    }

}

?>