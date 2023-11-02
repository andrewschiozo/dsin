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

        $DaoAgenda = new DaoAgenda();
        $agenda = $DaoAgenda->getAgendamentos();
        
		Response::getInstance()
        ->setData($agenda)
        ->ok();
    }

    public function post()
    {
        $this->checkPerfil();

        $DaoAgenda = new DaoAgenda();
	    $DaoAgenda->servico_id  = Request::getData()->servico;
		$DaoAgenda->usuario_id  = Request::getData()->cliente;
		$DaoAgenda->data  = Request::getData()->data;
		$DaoAgenda->servico_situacao_id  = Request::getData()->status;

        if($DaoAgenda->save())
        {
            Response::getInstance()
				->addMessage('Registro salvo')
				->ok();
        }
    }

    public function put()
	{
		$this->checkPerfil();

		$id = Request::getData()->id;        
		
		$DaoAgenda = new DaoAgenda();
		$Agenda = $DaoAgenda->get($id);
        
		if(!$Agenda)
		{
			Response::getInstance()
					 ->badRequest('Agendamento não encontrado');
		}
		$Agenda = $Agenda[0];

		$DaoAgenda->servico_id 		= property_exists(Request::getData(), 'servico') ? Request::getData()->servico : $Agenda->servico_id;
		$DaoAgenda->usuario_id 	= property_exists(Request::getData(), 'cliente') ? Request::getData()->cliente : $Agenda->usuario;
		$DaoAgenda->data 		= property_exists(Request::getData(), 'data') ? Request::getData()->data : $Agenda->data;
		$DaoAgenda->servico_situacao_id 	= property_exists(Request::getData(), 'status') ? Request::getData()->status : $Agenda->servico_situacao_id;
		
		$DaoAgenda->save($id);

		Response::getInstance()
				->addMessage('Registro salvo')
				->ok();
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
