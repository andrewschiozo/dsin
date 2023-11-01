<?php

namespace model;

use model\Sistema\Model;

class ModelServico extends Model
{
	protected string $nome;
	protected string $descricao;

	public function __set($attr, $val)
	{
		if(property_exists($this, $attr))
            $this->$attr = $val;
	}
}