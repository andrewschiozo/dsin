<?php
namespace controller;

use util\Request;
use util\Response;
use dao\DaoUsuario;

class Usuario extends PrivateController
{

	public function get()
	{
        $this->checkPerfil();
        
        $id = property_exists(Request::getData(), 'id') ? Request::getData()->id : null;
        $allColumns = property_exists(Request::getData(), 'allColumns') ? Request::getData()->allColumns : false;

		$DaoUsuario = new DaoUsuario();
		$usuarios = $DaoUsuario->get($id);
        
		Response::getInstance()
				->setData($usuarios)
				->ok();
	}

	public function post()
	{
		
		$this->checkPerfil();

		$DaoUsuario = new DaoUsuario();
		
		$DaoUsuario->model->nome 	= Request::getData()->nome;
		$DaoUsuario->model->email 	= Request::getData()->email;
		$DaoUsuario->model->telefone 	= Request::getData()->telefone;
		$DaoUsuario->model->usuario = property_exists(Request::getData(), 'usuario') ? Request::getData()->usuario : $DaoUsuario->model->email;
		$DaoUsuario->model->perfil 	= property_exists(Request::getData(), 'perfil') ? Request::getData()->perfil : 'Cliente';
		
		$senha = '';
		if(property_exists(Request::getData(), 'senha'))
		{
			$senha = $DaoUsuario->model->senha = Request::getData()->senha;
		}
		else
		{
			$senha = $DaoUsuario->model->novaSenha();
		}
		
		$DaoUsuario->save();
        
		Response::getInstance()
				->addData($senha, 'senha')
				->ok();
	}

	public function put()
	{
		$this->checkPerfil();

		$id = Request::getData()->id;
		
		$DaoUsuario = new DaoUsuario();
		$Usuario = $DaoUsuario->get($id);

		if(!$Usuario)
		{
			Response::getInstance()
					 ->badRequest('Usuário não encontrado');
		}
		$Usuario = $Usuario[0];
		
		$DaoUsuario->model->nome 		= property_exists(Request::getData(), 'nome') ? Request::getData()->nome : $Usuario->nome;
		$DaoUsuario->model->usuario 	= property_exists(Request::getData(), 'usuario') ? Request::getData()->usuario : $Usuario->usuario;
		$DaoUsuario->model->email 		= property_exists(Request::getData(), 'email') ? Request::getData()->email : $Usuario->email;
		$DaoUsuario->model->telefone 	= property_exists(Request::getData(), 'telefone') ? Request::getData()->telefone : $Usuario->telefone;
		$DaoUsuario->model->perfil 		= property_exists(Request::getData(), 'perfil') ? Request::getData()->perfil : $Usuario->perfil;
		

		if(property_exists(Request::getData(), 'senha'))
		{
			$DaoUsuario->model->senha = Request::getData()->senha;
		}
		else
		{
			$DaoUsuario->model->setSenha($Usuario->senha);
		}

		$DaoUsuario->save($id);

		Response::getInstance()
				->addMessage('Registro salvo')
				->ok();
	}
}
