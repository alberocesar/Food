<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Carrinho extends BaseController {

    private $validacao;
    private $produtoEspecificacaoModel;

    public function __construct() {
 

        $this->validacao = service('validation');

        $this->produtoEspecificacaoModel = new \App\Models\ProdutoEspecificacaoModel();
    }

    public function index() {


    }
   

    public function adicionar() {

        if($this->request->getMethod() === 'post') {


            $produtoPost = $this->request->getPost('produto');

            
            $this->validacao->setRules([
                'produto.slug' => ['label' => 'Produto', 'rules' => 'required|string'],
                'produto.especificacao_id' => ['label' => 'Valor do produto', 'rules' => 'required|greater_than[0]'],
                'produto.preco' => ['label' => 'Valor do produto', 'rules' => 'required|greater_than[0]'],
                'produto.quantidade' => ['label' => 'Quantidade', 'rules' => 'required|greater_than[0]'],
            ]);


            if ($this->validacao->withRequest($this->request)->run()) {

                return redirect()->back()
                                ->with('errors_model', $this->validacao->getErrors())
                                ->with('atencao', 'Por favor verifique os erros abaixo e tente novamente')
                                ->withInput();
            }
            
            /* Validamos a existencia da especificacao id */
            $especificacaoProduto = $this->produtoEspecificacaoModel
                    ->join('medidas', 'medidas.id = produtos_especificacoes.medida_id')
                    ->where('produtos_especificacoes.id', $produtoPost['especificacao_id'])
                    ->first();

                    dd($especificacaoProduto);

                    if ($especificacaoProduto == null) {

                        return redirect()->back()
                                        ->with('fraude', 'Não conseguimos processar a sua solicitação. Por favor, entre em contato com a nossa equipe e informe o código de erro <strong>ERRO-ADD-PROD-1001<strong>');  // FRAUDE NO FORM                                                     
                    }
            

        }else {

            return redirect()->back();
        }
    }
}
