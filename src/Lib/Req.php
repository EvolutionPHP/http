<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace EvolutionPHP\HTTP\Lib;

class Req
{
	private $request;
	public function __construct()
	{
		$this->request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
	}

	/**
	 * $_GET Request
	 * @return \Symfony\Component\HttpFoundation\InputBag
	 */
	public function _get()
	{
		return $this->request->query;
	}

	/**
	 * $_POST Request
	 * @return \Symfony\Component\HttpFoundation\InputBag
	 */
	public function _post()
	{
		return $this->request->request;
	}

	/**
	 * $_COOKIE Request
	 * @return \Symfony\Component\HttpFoundation\InputBag
	 */
	public function _cookie()
	{
		return $this->request->cookies;
	}

	/**
	 * $_SERVER Request
	 * @return \Symfony\Component\HttpFoundation\ServerBag
	 */
	public function _server()
	{
		return $this->request->server;
	}

	/**
	 * Headers Request
	 *
	 * @return \Symfony\Component\HttpFoundation\HeaderBag
	 */
	public function _headers()
	{
		return $this->request->headers;
	}

	/**
	 * HTTP Request
	 *
	 * @return \Symfony\Component\HttpFoundation\Request
	 */

	public function http()
	{
		return $this->request;
	}

	/**
	 * $_FILES Request
	 * @return \Symfony\Component\HttpFoundation\FileBag
	 */
	public function _files()
	{
		return $this->request->files;
	}
}