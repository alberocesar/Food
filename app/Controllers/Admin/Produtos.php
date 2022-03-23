<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\Produto;

class Produtos extends BaseController {

    private $produtoModel;
    private $categoriaModel;
    private $extraModel;
    private $produtoExtraModel;

    public function __construct() {

        $this->produtoModel = new \App\Models\ProdutoModel();
        $this->categoriaModel = new \App\Models\CategoriaModel();

        $this->extraModel = new \App\Models\ExtraModel();
        $this->produtoExtraModel = new \App\Models\ProdutoExtraModel();

    }

    public function index() {

        $data = [
            'titulo' => 'Listando os produtos',
            'produtos' => $this->produtoModel->select('produtos.*, categorias.nome AS categoria')
                    ->join('categorias', 'categorias.id = produtos.categoria_id')
                    ->withDeleted(true)
                    ->paginate(10),
            'pager' => $this->produtoModel->pager,
        ];

        return view('Admin/Produtos/index', $data);  
    }

    public function procurar() {

        if (!$this->request->isAJAX()) {

            exit('Página não encontrada');
        }


        $produtos = $this->produtoModel->procurar($this->request->getGet('term'));

        $retorno = [];


        foreach ($produtos as $produto) {

            $data['id'] = $produto->id;
            $data['value'] = $produto->nome;

            $retorno[] = $data;
        }

        return $this->response->setJSON($retorno);
    }

    public function editar($id = null) {

        $produto = $this->buscaProdutoOu404($id);

        $data = [
            'titulo' => "Editando o produto $produto->nome",
            'produto' => $produto,
            'categorias' => $this->categoriaModel->where('ativo', true)->findAll(),
        ];

        return view('Admin/Produtos/editar', $data);
    }

    public function criar() {

        $produto = new Produto();

        $data = [
            'titulo' => "Criando novo produto",
            'produto' => $produto,
            'categorias' => $this->categoriaModel->where('ativo', true)->findAll()
        ];

        return view('Admin/Produtos/criar', $data);
    }
    public function cadastrar() {

        if ($this->request->getMethod() === 'post') {

            $produto = new Produto($this->request->getPost());

            

            if ($this->produtoModel->save($produto)) {

                return redirect()->to(site_url("admin/produtos/show/" . $this->produtoModel->getInsertID()))
                                ->with('sucesso', "Produto $produto->nome cadastrado com sucesso");
            } else {

                /* Erro de validação */

                return redirect()->back()
                                ->with('errors_model', $this->produtoModel->errors())
                                ->with('atencao', 'Por favor verifique os erros abaixo')
                                ->withInput();
            }
        } else {

            return redirect()->back();
        }

    }
    public function show($id = null) {

        $produto = $this->buscaProdutoOu404($id);

        $data = [
            'titulo' => "Detalhando o produto $produto->nome",
            'produto' => $produto,
        ];

        return view('Admin/Produtos/show', $data);
    }

    public function atualizar($id = null) {

        if($this->request->getMethod() === 'post') {

            $produto = $this->buscaProdutoOu404($id);

            $produto->fill($this->request->getPost());

            if(!$produto->hasChanged()) {

                return redirect()->back()->with('info', 'Não há dados para atualizar');
            }

            if($this->produtoModel->save($produto)) {

                return redirect()->to(site_url("admin/produtos/show/$id"))->with('sucesso', 'Produto atualizado com sucesso');
            
            }else{

                //erro de validação 

                return redirect()->back()
                                ->with('errors_model', $this->produtoModel->errors())
                                ->with('atencao', 'Por favor verifique os erros abaixo')
                                ->withInput();

            }


        }else{
            return redirect()->back();
        }

    }

    

    public function editarImagem($id = null) {


        $produto = $this->buscaProdutoOu404($id);

        if ($produto->deletado_em != null) {

            return redirect()->back()->with('info', 'Não é possível editar a imagem de um produto excluído');
        }


        $data = [
            'titulo' => "Editando a imagem do produto $produto->nome",
            'produto' => $produto,
        ];

        return view('Admin/Produtos/editar_imagem', $data);
    }

    public function upload($id = null) {

        $produto = $this->buscaProdutoOu404($id);

        $imagem = $this->request->getFile('foto_produto');

        if(!$imagem->isValid()) {

            $codigoErro = $imagem->getError();

            if($codigoErro == UPLOAD_ERR_NO_FILE) {

                return redirect()->back()->with('info', 'Nenhum arquivo foi selecionado');
            }
        }

        $tamanhoImagem = $imagem->getSizeByUnit('mb');

        if($tamanhoImagem > 2){ 

            return redirect()->back()->with('info', 'O arquivo selecionado é muito grande. Maximo permitido é 2MB');

        }

        $tipoImagem = $imagem->getMimeType();

        $tipoImagemLimpo = explode('/', $tipoImagem);

        $tiposPermitidos = [
            'jpeg', 'png', 'webp',
        ];

        if(!in_array($tipoImagemLimpo[1], $tiposPermitidos)) {

            return redirect()->back()->with('info', 'O arquivo não tem o formato permitido. Apenas: '. implode(', ', $tiposPermitidos));

        }

        list($largura, $altura) = getimagesize($imagem->getPathname());

        if($largura < "300" and $altura < "300"){

            return redirect()->back()->with('info', 'A imagem não pode ser menor do que 300 x 300 pixels');
        }


//----------------------- A partir desse ponto fazemos o store da imagem -------------------//
        //** Fazendo o store da imagem e recuperando o caminho da mesma */ 
               
        $imagemCaminho = $imagem->store('produtos');

    $imagemCaminho = WRITEPATH . 'uploads/' . $imagemCaminho;

    service('image')
    ->withFile($imagemCaminho)
    ->fit(400, 400, 'center')
    ->save($imagemCaminho);

    $imagemAntiga = $produto->imagem;

    /* atribuindo nova imagem */
    $produto->imagem = $imagem->getName();  


    /* atualizando imagem do produto */

    $this->produtoModel->save($produto); 

    /* caminho da imagem antiga */
    $caminhoImagem = WRITEPATH .'uploads/produtos/'.$imagemAntiga;

    
    if(is_file($caminhoImagem)){

        unlink($caminhoImagem);

    }

    return redirect()->to(site_url("admin/produtos/show/$produto->id"))->with('sucesso','Imagem alterada com sucesso');
}

    public function imagem(string $imagem = null) {

        if ($imagem) {

            $caminhoImagem = WRITEPATH . 'uploads/produtos/' . $imagem;

            $infoImagem = new \finfo(FILEINFO_MIME);

            $tipoImagem = $infoImagem->file($caminhoImagem);

            header("Content-Type: $tipoImagem");

            header("Content-Length: " . filesize($caminhoImagem));

            readfile($caminhoImagem);

            exit;
    }

    }

    public function extras($id = null){

        $produto = $this->buscaProdutoOu404($id);
       
        $data = [
          'titulo' => "Gerenciar os extras do $produto->nome",
          'produto' => $produto,
          'extras' => $this->extraModel->where('ativo', true)->findAll(),
          'produtoExtras' => $this->produtoExtraModel->buscaExtrasDoProduto($produto->id, 10),
          'pager' =>$this->produtoExtraModel->pager,
          
       ];
       return view('Admin/Produtos/extras', $data);
       }


    private function buscaProdutoOu404(int $id = null){

        if(!$id || !$produto = $this->produtoModel->select('produtos.*, categorias.nome AS categoria')
                                                  ->join('categorias','categorias.id = produtos.categoria_id')
                                                  ->where("produtos.id='$id' AND produtos.deletado_em is null")
                                                  ->first()){

            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o produto $id");

        }

        return $produto;

    }
}
