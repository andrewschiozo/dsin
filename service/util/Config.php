<?php

namespace util;

abstract class Config
{
	const SYSDB = ['user' => 'root'
					,'pass' => 'root'
					,'name' => 'u244595210_dsin'
					,'host' => 'localhost'
					,'port' => '3306'
					,'dsn' => 'mysql:host=localhost;dbname=u244595210_dsin;port=3306"'
					,'options' => []
				];

    const JWTKEY = 'dsin@teste App12#';
}