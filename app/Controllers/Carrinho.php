<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Carrinho extends BaseController {

    private $validacao;
    private $produtoEspecificacaoModel;
    private $extraModel;
    private $produtoModel;

    public function __construct() {
 

        $this->validacao = service('validation');
        $this->produtoEspecificacaoModel = new \App\Models\ProdutoEspecificacaoModel();
        $this->extraModel = new \App\Models\ExtraModel();
        $this->produtoModel = new \App\Models\ProdutoModel();
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


            if (!$this->validacao->withRequest($this->request)->run()) {

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

                    

                    if ($especificacaoProduto == null) {

                        return redirect()->back()
                                        ->with('fraude', 'Não conseguimos processar a sua solicitação. Por favor, entre em contato com a nossa equipe e informe o código de erro <strong>ERRO-ADD-PROD-1001<strong>');  // FRAUDE NO FORM                                                     
                    }
            /* Caso o extra Id venha no post validamos a existência do produto */        
            if($produtoPost['extra_id'] && $produtoPost['extra_id'] != "") {

                $extra = $this->extraModel->where('id', $produtoPost['extra_id'])->first();

                if ($extra == null) {

                    return redirect()->back()
                                    ->with('fraude', 'Não conseguimos processar a sua solicitação. Por favor, entre em contato com a nossa equipe e informe o código de erro <strong>ERRO-ADD-PROD-2002<strong>');  // FRAUDE NO FORM CHAVE $produtoPost['extra_id']                                                     
                }

            }
            /* Estou utilizando o Toarray() para que eu possa inserir o objeto no carrinho no formato adequado*/
            $produto = $this->produtoModel->select(['id', 'nome', 'slug', 'ativo'])->where('slug', $produtoPost['slug'])->first();

            /* Validamos a exixtência do produto e se o mesmo está ativo */
            if ($produto == null || $produto->ativo == false) {

                return redirect()->back()
                                ->with('fraude', 'Não conseguimos processar a sua solicitação. Por favor, entre em contato com a nossa equipe e informe o código de erro <strong>ERRO-ADD-PROD-3003<strong>');  // FRAUDE NO FORM    NA CHAVE $PRODUTOPOST ['SLUG']                                                 
            }

            $produto = $produto->toArray();
            /* Criado o slug composto para indentificar os itens no carrinho */
            $produto['slug'] = mb_url_title($produto['slug']. '-' . $especificacaoProduto->nome. '-' . (isset($extra) ? 'com extra-' . $extra->nome : ''), '-', true);

            $produto['nome'] = $produto['nome']. '  ' . $especificacaoProduto->nome. ' '. (isset($extra) ? 'com extra-' . $extra->nome : '');




        }else {

            return redirect()->back();
        }
    }
}
