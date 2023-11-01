<?php
namespace controller;

use util\Request;
use util\Response;
use dao\DaoServico;

class Servico extends PrivateController
{

	public function listar()
	{
		$DaoServico = new DaoServico();
		
		$servicos = $DaoServico->get();
		Response::getInstance()
				->setData($servicos)
				->ok();
	}

	public function getStatus()
	{
		$this->checkPerfil();
		
		$DaoServico = new DaoServico();
		
		$status = $DaoServico->getStatus();
		Response::getInstance()
				->setData($status)
				->ok();
	}

}
