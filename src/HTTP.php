<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace EvolutionPHP\HTTP;

use EvolutionPHP\Instance\Instance;

class HTTP
{
	/**
	 * @return Request|object
	 * @throws \Exception
	 */
	static function request()
	{
		return Instance::get(Request::class);
	}

	/**
	 * @return Response|object
	 * @throws \Exception
	 */
	static function response()
	{
		return Instance::get(Response::class);
	}
}