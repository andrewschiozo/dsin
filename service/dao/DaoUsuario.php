<?php

namespace dao;

use dao\Sistema\DB;
use model\ModelUsuario;

class DaoUsuario extends DB
{
    protected int $id;
	protected string $nome;
	protected string $email;
	protected int $telefone;
	protected string $perfil;
	protected string $usuario;
	protected string $senha;

    public function __construct()
    {
        $this->table = 'usuario';
        $this->model = new ModelUsuario;
        $this->dao = __CLASS__;
        parent::__construct();
    }

    public function setModel(ModelUsuario $ModelUsuario)
    {
        $this->model = $ModelUsuario;
        return $this;
    }

    public function login()
    {
        $queryString = 'SELECT * FROM ' . $this->table . 
                      ' WHERE usuario = :usuario
                          AND senha = :senha';

        try {
            $stmt = $this->getConn()->prepare($queryString);
            $stmt->bindValue(':usuario', $this->model->usuario, \PDO::PARAM_STR);
            $stmt->bindValue(':senha', $this->model->senha, \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchObject(__CLASS__);
        } catch (\PDOException $e) {
            echo ($e->getMessage());
        }
        return $result;
    }
}
