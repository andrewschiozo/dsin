<?php

namespace model;

use model\Sistema\Model;

class ModelAgenda extends Model
{
	public function __set($attr, $val)
	{
		if(property_exists($this, $attr))
            $this->$attr = $val;
	}
}