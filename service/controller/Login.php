<?php
namespace controller;

use util\Request;
use util\Response;
use util\JWT;
use util\Config;

use dao\DaoUsuario;
use model\ModelUsuario;

class Login extends Controller
{
	public function __construct()
	{
        parent::__construct();
	}

	public function post()
	{
		$Usuario = new ModelUsuario;
		$Usuario->usuario = Request::getData()->usuario ?? '';
		$Usuario->senha = Request::getData()->senha ?? '';
		
		$DaoUsuario = new DaoUsuario;

		$login = $DaoUsuario->setModel($Usuario)->login();
		if(!$login)
		{
				Response::getInstance()
				->addMessage('Credenciais inválidas')
				->unauthorized();
		}

		$DaoUsuario = $login;

		$Now = new \DateTime();
		//Dados do Token
		$payload = [
			'nbf' => $Now->getTimestamp()
			,'exp' => $Now->add(new \DateInterval('P1D'))->getTimestamp()
			,'data' => [
				 'id' => $DaoUsuario->id
				,'nome' => $DaoUsuario->nome
				,'usuario' => $DaoUsuario->usuario
				,'email' => $DaoUsuario->email
				,'telefone' => $DaoUsuario->telefone
				,'perfil' => $DaoUsuario->perfil
			]
		];
		
		$jwToken = JWT::encode($payload, Config::JWTKEY);
		Response::getInstance()
				->addData($jwToken, 'token')
				->ok();
	}
}
