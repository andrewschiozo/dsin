<?php

namespace model;

use model\Sistema\Model;

class ModelUsuario extends Model
{
	protected string $nome;
	protected string $email;
	protected int $telefone;
	protected string $perfil = 'Cliente';
	protected string $usuario;
	protected string $senha;

	public function __set($attr, $val)
	{
		if(property_exists($this, $attr))
		{
			switch ($attr) {
				case 'email':
					$this->$attr = strtolower($val);
					break;

				case 'senha':
					$this->$attr = sha1($val);
					break;
				
				case 'perfil':
					if(in_array($val, ['Gerente', 'Cliente']))
						$this->$attr = $val;

					break;
				
				default:
					$this->$attr = $val;
					break;
			}
		}
	}

	public function novaSenha()
	{
		$senha = bin2hex(random_bytes(8));
		$this->__set('senha', $senha);
		return $senha;
	}

	public function setSenha($senha)
	{
		$this->senha = $senha;
	}

	public function removeAttr($attr)
	{
		if(property_exists($this, $attr))
		{
			unset($this->$attr);
		}
		return $this;
	}
}