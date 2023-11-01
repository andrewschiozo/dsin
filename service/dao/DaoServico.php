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
    
}
