<?php

namespace dao;

use dao\Sistema\DB;
use model\ModelAgenda;

class DaoAgenda extends DB
{
    protected int $id;
	protected string $servico_id;
	protected string $usuario_id;
	protected string $data;

    public function __construct()
    {
        $this->table = 'agendamento';
        $this->model = new ModelAgenda;
        $this->dao = __CLASS__;
        parent::__construct();
    }

    public function __set($attr, $val)
	{
		if(property_exists($this, $attr))
            $this->$attr = $val;
	}

    public function setModel(ModelAgenda $ModelAgenda)
    {
        $this->model = $ModelAgenda;
        return $this;
    }
    
    public function getByUsuario($id)
    {
        $queryString = 'SELECT agendamento.id
                              ,agendamento.servico_id
                              ,servico.nome as servico
                              ,agendamento.data
                              ,servico_situacao.nome as situacao
                        FROM agendamento
                        JOIN servico
                            ON agendamento.servico_id = servico.id
                        JOIN servico_situacao
                            ON agendamento.servico_situacao_id = servico_situacao.id
                        WHERE usuario_id = :id';
        
        $result = [];
        try {
            $stmt = $this->getConn()->prepare($queryString);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
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
        
        $queryUsuario = 'INSERT INTO ' . $this->table . ' (servico_id, usuario_id, data)
        VALUES (:servico_id, :usuario_id, :data)';
        $usuarioId = false;
        
        try {
            $this->getConn()->beginTransaction();

            $stmt = $this->getConn()->prepare($queryUsuario);
            $stmt->bindValue(':servico_id', $this->servico_id, \PDO::PARAM_INT);
            $stmt->bindValue(':usuario_id', $this->usuario_id, \PDO::PARAM_INT);
            $stmt->bindValue(':data', $this->data, \PDO::PARAM_STR);

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
