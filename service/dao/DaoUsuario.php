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
        $this->blackList[] = 'senha';
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
    public function getCliente($id = null)
    {
        $queryString = 'SELECT *
                        FROM ' . $this->table . ' WHERE 1 = 1 ';
        $queryString .= is_null($id) ? '' : ' AND id = '. $id;
        
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
    public function save($id = null)
    {
        return $id ? $this->update($id) : $this->insert();
    }

    private function insert()
    {
        
        $queryUsuario = 'INSERT INTO ' . $this->table . ' (nome, email, telefone, perfil, usuario, senha)
        VALUES (:nome, :email, :telefone, :perfil, :usuario, :senha)';
        $usuarioId = false;
        
        try {
            $this->getConn()->beginTransaction();

            $stmt = $this->getConn()->prepare($queryUsuario);
            $stmt->bindValue(':nome', $this->model->nome, \PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->model->email, \PDO::PARAM_STR);
            $stmt->bindValue(':telefone', $this->model->telefone, \PDO::PARAM_INT);
            $stmt->bindValue(':perfil', $this->model->perfil, \PDO::PARAM_STR);
            $stmt->bindValue(':usuario', $this->model->usuario, \PDO::PARAM_STR);
            $stmt->bindValue(':senha', $this->model->senha, \PDO::PARAM_STR);

            $stmt->execute();
            $usuarioId = $this->getConn()->lastInsertId();
                        
            $this->getConn()->commit();            
        } catch (\PDOException $e) {
            $this->getConn()->rollback();
            echo ($e->getMessage());
        }
        return $usuarioId;
    }

    private function update($id)
    {
        $queryUsuario = 'UPDATE ' . $this->table . ' SET nome = :nome
                                                       ,email = :email
                                                       ,telefone = :telefone
                                                       ,perfil = :perfil
                                                       ,usuario = :usuario
                                                       ,senha = :senha
                                                    WHERE id = :id';
        
        try {
            $this->getConn()->beginTransaction();

            $stmt = $this->getConn()->prepare($queryUsuario);
            $stmt->bindValue(':nome', $this->model->nome, \PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->model->email, \PDO::PARAM_STR);
            $stmt->bindValue(':telefone', $this->model->telefone, \PDO::PARAM_INT);
            $stmt->bindValue(':perfil', $this->model->perfil, \PDO::PARAM_STR);
            $stmt->bindValue(':usuario', $this->model->usuario, \PDO::PARAM_STR);
            $stmt->bindValue(':senha', $this->model->senha, \PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

            $stmt->execute();
            
            $this->getConn()->commit();            
        } catch (\PDOException $e) {
            $this->getConn()->rollback();
            echo ($e->getMessage());
        }
        return $id;
    }
}
