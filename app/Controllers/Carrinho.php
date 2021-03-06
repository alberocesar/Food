<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Carrinho extends BaseController
{

    private $validacao;
    private $produtoEspecificacaoModel;
    private $extraModel;
    private $produtoModel;
    private $medidaModel;
    private $acao;



    public function __construct()
    {


        $this->validacao = service('validation');

        $this->produtoEspecificacaoModel = new \App\Models\ProdutoEspecificacaoModel();
        $this->extraModel = new \App\Models\ExtraModel();
        $this->produtoModel = new \App\Models\ProdutoModel();
        $this->medidaModel = new \App\Models\medidaModel();
        $this->acao = service('router')->methodName();
    }

    public function index()
    {

        $data = [
            'titulo' => 'Meu carrinho de compras'
        ];

        if (session()->has('[carrinho]') && count(session()->get('carrinho')) > 0) {

            $data['carrinho'] = json_decode(json_encode(session()->get('carrinho')), false);
        }


        return view('Carrinho/index', $data);

        
    }    

    public function adicionar(){

        if ($this->request->getMethod() === 'post') {


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
                    ->with('fraude', 'N??o conseguimos processar a sua solicita????o. Por favor, entre em contato com a nossa equipe e informe o c??digo de erro <strong>ERRO-ADD-PROD-1001<strong>');  // FRAUDE NO FORM                                                     
            }
            /* Caso o extra Id venha no post validamos a exist??ncia do produto */
            if ($produtoPost['extra_id'] && $produtoPost['extra_id'] != "") {


                $extra = $this->extraModel->where('id', $produtoPost['extra_id'])->first();


                if ($extra == null) {

                    return redirect()->back()
                        ->with('fraude', 'N??o conseguimos processar a sua solicita????o. Por favor, entre em contato com a nossa equipe e informe o c??digo de erro <strong>ERRO-ADD-PROD-2002<strong>');  // FRAUDE NO FORM ...  chave $produtoPost['extra_id']                                                    
                }
            }

            /* Utilizando o Toarray para que possa inserir esse objeto no carrinho */
           $produto = $this->produtoModel->select(['id', 'nome', 'slug', 'ativo'])->where('slug', $produtoPost['slug'])->first()->toArray();

            /* Validamos a exixt??ncia do produto e se o mesmo est?? ativo */
            if ($produto == null || $produto['ativo'] == false) {

                return redirect()->back()
                    ->with('fraude', 'N??o conseguimos processar a sua solicita????o. Por favor, entre em contato com a nossa equipe e informe o c??digo de erro <strong>ERRO-ADD-PROD-3003<strong>');  // FRAUDE NO FORM    NA CHAVE $PRODUTOPOST ['SLUG']                                                 
            }

            /* Converto o objeto para array */
            //$produto = $produto->toArray();

            /*Criado slug composto para indentificar a exist??ncia ou nao do item no carrinho na hora de add */
            $produto['slug'] = mb_url_title($produto['slug'] . '-' . $especificacaoProduto->nome . '-' . (isset($extra) ? 'com extra-' . $extra->nome : ''), '-', true);

            /* Criado o nome do produto a partir da especificacao (ou) do extra */
            $produto['nome'] = $produto['nome'] . ' ' . $especificacaoProduto->nome . ' ' . (isset($extra) ? 'Com extra ' . $extra->nome : '');

            /**Definimos o preco, quantidade e tamanho do produto */
            $preco = $especificacaoProduto->preco + (isset($extra) ? $extra->preco : 0);

            $produto['preco'] = number_format($preco, 2);
            $produto['quantidade'] = (int) $produtoPost['quantidade'];
            $produto['tamanho'] = $especificacaoProduto->nome;


            unset($produto['ativo']);

            if (session()->has('carrinho')) {

                $produtos = session()->get('carrinho');

                /**Recuperar produtodo */

                /* Recupero os produtos do carrinho */
                $produtos = session()->get('carrinho');


                /* Recuperamos apenas os slugs dos produtos do carrinho */
                $produtosSlugs = array_column($produtos, 'slug');

                if (in_array($produto['slug'], $produtosSlugs)) {
                    /* J?? existe o produto no carrinho..... incrementamos a quantidade */

                    $produtos = $this->atualizaProduto($this->acao, $produto['slug'], $produto['quantidade'], $produtos);

                    /* Chamamos a fun????o que incrementa a quantidade do produto caso o mesmo exista no carrinho */

                    $produtos = $this->atualizaProduto($this->acao, $produto['slug'], $produto['quantidade'], $produtos);
                }
            } else {

                /* N??o existe no carrinho..... pode adicionar.... */

                session()->push('carrinho', [$produto]);
            }

            return redirect()->back()->with('sucesso', 'Produto adicionado com sucesso!');
        } else {
            return redirect()->back();
        }

    }

    public function especial()
    {

        if ($this->request->getMethod() === 'post') {

            $produtoPost = $this->request->getPost();


            $this->validacao->setRules([
                'primeira_metade' => ['label' => 'Primeiro produto', 'rules' => 'required|greater_than[0]'],
                'segunda_metade' => ['label' => 'Segundo produto', 'rules' => 'required|greater_than[0]'],
                'tamanho' => ['label' => 'Tamanho do produto', 'rules' => 'required|greater_than[0]'],

            ]);


            if (!$this->validacao->withRequest($this->request)->run()) {

                return redirect()->back()
                    ->with('errors_model', $this->validacao->getErrors())
                    ->with('atencao', 'Por favor verifique os erros abaixo e tente novamente')
                    ->withInput();
            }



            $primeiroProduto = $this->produtoModel->select(['id', 'nome', 'slug'])
                ->where('id', $produtoPost['primeira_metade'])
                ->first();


            if ($primeiroProduto == null) {

                return redirect()->back()
                    ->with('fraude', 'N??o conseguimos processar a sua solicita????o. Por favor, entre em contato com a nossa equipe e informe o c??digo de erro <strong>ERRO-ADD-CUSTOM-1001<strong>');  // FRAUDE NO FORM       $produtoPost['primeira_metade']                                                
            }

            $segundoProduto = $this->produtoModel->select(['id', 'nome', 'slug'])
                ->where('id', $produtoPost['segunda_metade'])
                ->first();


            if ($segundoProduto == null) {

                return redirect()->back()
                    ->with('fraude', 'N??o conseguimos processar a sua solicita????o. Por favor, entre em contato com a nossa equipe e informe o c??digo de erro <strong>ERRO-ADD-CUSTOM-2001<strong>');  // FRAUDE NO FORM       $produtoPost['primeira_metade']                                                
            }


            /*Convertendo os objetos para array */
            $primeiroProduto = $primeiroProduto->toArray();
            $segundoProduto = $segundoProduto->toArray();



            /* Caso o extra Id venha no post validamos a exist??ncia do produto */
            if ($produtoPost['extra_id'] && $produtoPost['extra_id'] != "") {


                $extra = $this->extraModel->where('id', $produtoPost['extra_id'])->first();


                if ($extra == null) {

                    return redirect()->back()
                        ->with('fraude', 'N??o conseguimos processar a sua solicita????o. Por favor, entre em contato com a nossa equipe e informe o c??digo de erro <strong>ERRO-ADD-CUSTOM-3003<strong>');  // FRAUDE NO FORM ...  chave $produtoPost['extra_id']                                                    
                }
            }

            $medida = $this->medidaModel->exibeValor($produtoPost['tamanho']);


            if ($medida['0']->preco == null) {

                return redirect()->back()
                    ->with('fraude', 'N??o conseguimos processar a sua solicita????o. Por favor, entre em contato com a nossa equipe e informe o c??digo de erro <strong>ERRO-ADD-CUSTOM-4004<strong>');  // FRAUDE NO FORM ...  chave $produtoPost['tamanho']                                                    
            }

            /* Criamos o slug composto para identificarmos a exist??ncia ou n??o do item no carrinho na hora de adicionar */
            $produto['slug'] = mb_url_title($medida['0']->nome . '-metade-' . $primeiroProduto['slug'] . '-metade-' . $segundoProduto['slug'] . '-' . (isset($extra) ? 'com extra-' . $extra->nome : ''), '-', true);

            $produto['nome'] = $medida['0']->nome . ' metade ' . $primeiroProduto['nome'] . '-metade-' . $segundoProduto['slug'] . ' ' . (empty($extra) ? 'com extra ' . $extra->nome : '');


            /**Definimos o preco, quantidade e tamanho do produto */
            $preco = $medida['0']->preco + (isset($extra) ? $extra->preco : 0);

            $produto['preco'] = number_format($preco, 2);
            $produto['quantidade'] = 1; //sempre ser?? um
            $produto['tamanho'] = $medida['0']->nome;

            //iniciamos a inserc??o do produto carrinho

            if (session()->has('carrinho')) {
                // Existem inser????o no carrinho na sess??o//zzzz

                // Recupera os intem do carrinho na sess??o//
                $produtos = session()->get('[carrinho]');

                // Recupera os apenas os slug dos prdutos do carrinho//
                $produtosSlugs = array_column($produtos, 'slug');

                if (in_array($produto['slug'], $produtosSlugs)) {

                    // J?? existe o produto no carrinho... incliementamos a quantidade//

                    // chamamos a fun????o inclementea a quantidadede de produto incliementamos a quantidade no carrinho//
                    $produtos = $this->atualizaProduto($this->acao, $produto['slug'], $produto['quantidade'], $produtos);

                    // SSobre escrevemos asess??o carrinho com o array com arra  $produtos que foi iincliementado (alterado) //
                    session()->set('carrinho', $produto);
                } else {

                    /* N??o existe o produto no carrinho... pode  a aadicionar//
													 aadicionarmos no carrinho $produto Notem que  o push adiciona na sess??o  'carrinho' um array [$produto]*/
                    session()->push('carrinho', [$produto]);
                }
            } else {
                // N??o existem inser????o no carrinho na sess??o//

                $produtos[] = $produto;
                session()->set('carrinho', $produtos);
            }

            return redirect()->to(site_url('[carrinho]'))->with('sucesso', 'Produto adicionado com sucesso!');
        } else {
            return redirect()->back();
        }
    }

    private function atualizaProduto(string $acao, string $slug, int $quantidade, array $produtos)
    {


        $produtos = array_map(function ($linha) use ($acao, $slug, $quantidade) {

            if ($linha['slug'] == $slug) {


                if ($acao === 'adicionar') {

                    $linha['quantidade'] += $quantidade;
                }


                if ($acao === 'especial') {

                    $linha['quantidade'] += $quantidade;
                }



                if ($acao === 'atualizar') {


                    $linha['quantidade'] = $quantidade;
                }
            }

            return $linha;
        }, $produtos);

        return $produtos;

    }
}    