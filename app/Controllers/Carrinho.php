<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Carrinho extends BaseController
{

    private $validacao;
    private $produtoEspecificacaoModel;
    private $extraModel;
    private $produtoModel;
    private $acao;

    public function __construct()
    {


        $this->validacao = service('validation');
        $this->produtoEspecificacaoModel = new \App\Models\ProdutoEspecificacaoModel();
        $this->extraModel = new \App\Models\ExtraModel();
        $this->produtoModel = new \App\Models\ProdutoModel();

        $this->acao = service('router')->methodName();
    }

    public function index()
    {

        $data = [
            'titulo' => 'Meu carrinho de compras'
        ];


        if (session()->has('carrinho') && count(session()->get('carrinho')) > 0) {

            $data['carrinho'] = json_decode(json_encode(session()->get('carrinho')), false);
        }

        return view('Carrinho/index', $data);
    }


    public function adicionar()
    {

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
                    ->with('fraude', 'Não conseguimos processar a sua solicitação. Por favor, entre em contato com a nossa equipe e informe o código de erro <strong>ERRO-ADD-PROD-1001<strong>');  // FRAUDE NO FORM                                                     
            }
            /* Caso o extra Id venha no post validamos a existência do produto */
            if ($produtoPost['extra_id'] && $produtoPost['extra_id'] != "") {


                $extra = $this->extraModel->where('id', $produtoPost['extra_id'])->first();


                if ($extra == null) {

                    return redirect()->back()
                        ->with('fraude', 'Não conseguimos processar a sua solicitação. Por favor, entre em contato com a nossa equipe e informe o código de erro <strong>ERRO-ADD-PROD-2002<strong>');  // FRAUDE NO FORM ...  chave $produtoPost['extra_id']                                                    
                }
            }

            /* Buscamos o produto como objeto */
            $produto = $this->produtoModel->select(['id', 'nome', 'slug', 'ativo'])->where('slug', $produtoPost['slug'])->first();

            /* Validamos a exixtência do produto e se o mesmo está ativo */
            if ($produto == null || $produto->ativo == false) {

                return redirect()->back()
                    ->with('fraude', 'Não conseguimos processar a sua solicitação. Por favor, entre em contato com a nossa equipe e informe o código de erro <strong>ERRO-ADD-PROD-3003<strong>');  // FRAUDE NO FORM    NA CHAVE $PRODUTOPOST ['SLUG']                                                 
            }

            /* Converto o objeto para array */
            $produto = $produto->toArray();

            /*Criado slug composto para indentificar a existência ou nao do item no carrinho na hora de add */
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


                $produtosSlugs = array_column($produtos, 'slug');

                if (in_array($produto['slug'], $produtosSlugs)) {

                    $produtos = $this->atualizaProduto($this->acao, $produto['slug'], $produto['quantidade'], $produtos);

                    session()->set('carrinho', $produtos);
                }
            } else {

                /* Não existe no carrinho..... pode adicionar.... */

                session()->push('carrinho', [$produto]);
            }

            return redirect()->back()->with('sucesso', 'Produto adicionado com sucesso!');
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

                if ($acao === 'atualizar') {

                    $linha['quantidade'] = $quantidade;
                }
            }

            return $linha;
        }, $produtos);

        return $produtos;
    }
}
