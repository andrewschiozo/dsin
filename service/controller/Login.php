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
				,'menu' => $this->getMenu()
			]
		];
		
		$jwToken = JWT::encode($payload, Config::JWTKEY);
		Response::getInstance()
				->addData($jwToken, 'token')
				->ok();
	}

	private function getMenu()
	{
		$menus = [];

		$Home = new \stdClass;
		$Home->nome = 'Home';
		$Home->icone = 'fa-solid fa-house';
		$Home->href = 'home/home';
		$menus[] = $Home;

		$Agenda = new \stdClass;
		$Agenda->nome = 'Agenda';
		$Agenda->icone = 'fa-solid fa-list';
		$Agenda->href = 'agenda/agenda';
		$menus[] = $Agenda;

		$Clientes = new \stdClass;
		$Clientes->nome = 'Clientes';
		$Clientes->icone = 'fa-solid fa-users';
		$Clientes->href = 'cliente/cliente';
		$menus[] = $Clientes;

		$Servicos = new \stdClass;
		$Servicos->nome = 'Serviços';
		$Servicos->icone = 'fa-solid fa-scissors';
		$Servicos->href = 'servico/servico';
		$menus[] = $Servicos;

		return $menus;
	}

}
