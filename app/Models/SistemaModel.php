<?php

namespace App\Models;

use CodeIgniter\Model;

class SistemaModel extends Model
{

	protected $table                = 'sistemas';


	protected $returnType           = 'App\Entities\Sistema';
	protected $useSoftDeletes       = true;

	protected $allowedFields        = [
		'nome',
		'nome',
		'email',
		'imagem',
		'cnpj',
		'cep',
		'telefone',
		'endereco',
		'numero',
		'cidade',
		'estado',
		'ativo'

	];



	// Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'criado_em';
	protected $updatedField         = 'atualizado_em';
	protected $deletedField         = 'deletado_em';

	// Validation

					 //Validações
					 protected $validationRules    = [
					 'nome'     		=> 'required|min_length[4]|max_length[250]',
					 'email'        => 'required|valid_email|is_unique[sistemas.email]',
					 'cnpj'        	=> 'required|exact_length[14]|validaCpf|is_unique[sistemas.cnpj]',
					 'cep'        	=> 'required|exact_length[11]|is_unique[sistemas.cep]',
					 'telefone'   	=> 'required|max_length[15]|is_unique[sistemas.telefone]',
					 'endereco'   	=> 'required|max_length[150]',
					 'numero'   		=> 'required|max_length[150]',
					 'cidade'   		=> 'required|max_length[150]',
					 'estado'   		=> 'required|max_length[150]',
			 ];

			 protected $validationMessages = [

				 'nome'        => [
						'required' => 'O campo Nome e obrigatório',
				],
					 'email'        => [
							 'is_unique' => 'Desculpe esse e-mail já eexiste.',
							 'required' => 'O campo Email e obrigatório.',
					 ],

					 'cnpj'        => [
							'is_unique' => 'Desculpe esse cpf já eexiste.',
							'required' => 'O campo CNPJ e obrigatório.',
					],

					'telefone'        => [

						 'required' => 'O campo Telefone e obrigatório.',
				 ],

			 ];



			 //uso do Controllers do métdo pprocurar  traves do auto complete///
		 		// procurar sistemas no para request usar
		 	   public function procurar($term){

		 			 if($term === null){
		 				  return [];

		 			 }

		 			 return $this->select('id, nome')
		 						->like('nome', $term)
		 						->withDeleted(true)
		 						->get()
		 						->getResult();

		 		 }

				 public function desfazerExclusao(int $id){
						 return $this->protect(false)
						 ->where('id', $id)
						 ->set('deletado_em', null)
						 ->update();
					 }

					 public function recuperTotalEntregadoresAtivo(){
		 	 		 return $this->where('ativo', true)
		 	 								 ->countAllResults();// Pegar objetos inteiros para busca de usuários


		 	 	 }
				 public function BuscaSistemaHome(){
	 	 				return $this->select('sistemas.id, nome,cnpj,imagem,telefone,
						email,cep, endereco, numero,cidade, estado')
	 	 										->groupBy('sistemas.id')
	 	 										->findAll();

	 	 		}

				//  public function recuperaEntregadoresMaisTop(int $quantidade){
				//
	 			// 	return  $this->select('sistemas.id,sistemas.nome, sistemas.imagem, COUNT(*) AS entregas')
	 			// 				->join('pedidos', 'sistemas.id = pedidos.entregador_id')
	 			// 	     	->where('pedidos.situacao', 2)//pedidos entregues
	 			// 				->limit($quantidade)
	 			// 				->groupBy('sistemas.nome')
	 			// 				->orderBy('entregas', 'DESC')
	 			// 			  ->find(); // O Limit só funciona com find()
				//
				//
				//
	 			// }
}
