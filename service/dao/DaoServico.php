<?php

namespace dao;

use dao\Sistema\DB;
use model\ModelServico;

class DaoServico extends DB
{
    protected int $id;
	protected string $nome;
	protected ?string $descricao;

    public function __construct()
    {
        $this->table = 'servico';
        $this->model = new ModelServico;
        $this->dao = __CLASS__;
        parent::__construct();
    }

    public function setModel(ModelServico $ModelServico)
    {
        $this->model = $ModelServico;
        return $this;
    }

    public function getStatus()
    {
        $queryString = 'SELECT *
                        FROM servico_situacao';
        
        $result = [];
        try {
            $stmt = $this->getConn()->prepare($queryString);
            $stmt->execute();
            $result = $stmt->fetchAll();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        return $result;
    }
    
}
