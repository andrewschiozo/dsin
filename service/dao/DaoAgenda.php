<?php

namespace dao;

use dao\Sistema\DB;
use model\ModelAgenda;

class DaoAgenda extends DB
{
    protected int $id;
	protected int $servico_id;
	protected int $usuario_id;
	protected string $data;
	protected int $servico_situacao_id = 1;

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

    public function getAgendamentos()
    {
        $queryString = 'SELECT agendamento.id
                              ,agendamento.servico_id
                              ,usuario.id as cliente_id
                              ,usuario.nome as cliente
                              ,servico.nome as servico
                              ,agendamento.data
                              ,servico_situacao.nome as situacao
                              ,servico_situacao.id as situacao_id
                        FROM agendamento
                        JOIN servico
                            ON agendamento.servico_id = servico.id
                        JOIN servico_situacao
                            ON agendamento.servico_situacao_id = servico_situacao.id
                        JOIN usuario
                            ON agendamento.usuario_id = usuario.id';
        
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
        
        $queryAgenda = 'INSERT INTO ' . $this->table . ' (servico_id, usuario_id, data, servico_situacao_id)
        VALUES (:servico_id, :usuario_id, :data, :servico_situacao_id)';
        $id = false;
        
        try {
            $this->getConn()->beginTransaction();

            $stmt = $this->getConn()->prepare($queryAgenda);
            $stmt->bindValue(':servico_id', $this->servico_id, \PDO::PARAM_INT);
            $stmt->bindValue(':usuario_id', $this->usuario_id, \PDO::PARAM_INT);
            $stmt->bindValue(':data', $this->data, \PDO::PARAM_STR);
            $stmt->bindValue(':servico_situacao_id', $this->servico_situacao_id, \PDO::PARAM_INT);

            $stmt->execute();
            $id = $this->getConn()->lastInsertId();
                        
            $this->getConn()->commit();            
        } catch (\PDOException $e) {
            $this->getConn()->rollback();
            echo ($e->getMessage());
        }
        return $id;
    }

    private function update($id)
    {
        $queryAgenda = 'UPDATE ' . $this->table . ' SET servico_id = :servico_id
                                                        ,usuario_id = :usuario_id
                                                        ,data = :data
                                                        ,servico_situacao_id = :servico_situacao_id
                                                    WHERE id = :id';
        try {
            $this->getConn()->beginTransaction();

            $stmt = $this->getConn()->prepare($queryAgenda);
            $stmt->bindValue(':servico_id', $this->servico_id, \PDO::PARAM_INT);
            $stmt->bindValue(':usuario_id', $this->usuario_id, \PDO::PARAM_INT);
            $stmt->bindValue(':data', $this->data, \PDO::PARAM_STR);
            $stmt->bindValue(':servico_situacao_id', $this->servico_situacao_id, \PDO::PARAM_INT);
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
