<?php
namespace controller;

use util\Request;
use util\Response;
use dao\DaoAgenda;

class Agenda extends PrivateController
{

    public function get()
    {
        $this->checkPerfil();
    }

    public function listar()
    {
        $DaoAgenda = new DaoAgenda();
        $agenda = $DaoAgenda->getByUsuario(Request::getDecodedToken()->data->id);
        
		Response::getInstance()
        ->setData($agenda)
        ->ok();
    }

	public function agendar()
	{
        $DaoAgenda = new DaoAgenda();
		$DaoAgenda->servico_id  = Request::getData()->servico;
		$DaoAgenda->usuario_id  = Request::getDecodedToken()->data->id;
		$DaoAgenda->data  = Request::getData()->data;
        if($DaoAgenda->save())
        {
            Response::getInstance()
				->addMessage('Aguardar confirmação')
				->ok();
        }
	}

}
