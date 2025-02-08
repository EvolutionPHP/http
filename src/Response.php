<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace EvolutionPHP\HTTP;

use EvolutionPHP\HTTP\Lib\Download;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Response
{
	private $status_header = 200;
	private $content_type = '';
	private $cookies = [];

	private $headers = [];

	private $config = [
		'cookie_prefix' => '',
		'cookie_domain' => '',
		'cookie_path' => '/',
		'cookie_secure' => false,
		'cookie_http' => false,
		'charset' => 'UTF-8'
	];


	public function setConfig($config)
	{
		$this->config = $config;
		return $this;
	}

	public function config()
	{
		return $this->config;
	}

	public function set_status_header($code)
	{
		$this->status_header = $code;
		return $this;
	}

	public function set_content_type($mime_type)
	{
		$this->content_type = $mime_type;
		return $this;
	}

	public function setHeader($key, $value=null)
	{
		if(is_array($key)){
			foreach ($key as $k=>$v){
				$this->setHeader($k, $v);
			}
		}else{
			$this->headers[$key] = $value;
		}
		return $this;
	}

	public function set_cookie($name, $value = '', $expire = 0, $domain = '', $path = '/', $prefix = '', $secure = NULL, $httponly = NULL)
	{
		if (is_array($name))
		{
			// always leave 'name' in last place, as the loop will break otherwise, due to $$item
			foreach (array('value', 'expire', 'domain', 'path', 'prefix', 'secure', 'httponly', 'name') as $item)
			{
				if (isset($name[$item]))
				{
					$$item = $name[$item];
				}
			}
		}

		if ($prefix === '' && $this->config['cookie_prefix'] !== '')
		{
			$prefix = $this->config['cookie_prefix'];
		}

		if ($domain == '' && $this->config['cookie_domain'] != '')
		{
			$domain = $this->config['cookie_domain'];
		}

		if ($path === '/' && $this->config['cookie_path'] !== '/')
		{
			$path = $this->config['cookie_path'];
		}

		$secure = ($secure === NULL && $this->config['cookie_secure'] !== NULL)
			? (bool) $this->config['cookie_secure']
			: (bool) $secure;

		$httponly = ($httponly === NULL && $this->config['cookie_http'] !== NULL)
			? (bool) $this->config['cookie_http']
			: (bool) $httponly;

		if ( ! is_numeric($expire) || $expire < 0)
		{
			$expire = time() - 86500;
		}
		else
		{
			$expire = ($expire > 0) ? time() + $expire : 0;
		}
		$this->cookies[] = [
			'name' => $prefix.$name,
			'value' => $value,
			'expire' => $expire,
			'path' => $path,
			'domain' => $domain,
			'secure' => $secure,
			'httponly' => $httponly
		];
		return $this;
	}

	public function javascript($content)
	{
		$this->set_content_type('application/javascript')
			->send($content);
	}

	public function json($content, $status_code='')
	{
		if(is_array($content)){
			$content = json_encode($content);
		}
		if(is_int($status_code)){
			$this->set_status_header($status_code);
		}
		$this->set_content_type('application/json')
			->send($content);
	}

	public function refresh()
	{
		$req = new Request();
		$this->redirect($req->current_url());
	}

	public function redirect($url='', $status=302, $headers=[])
	{
		if($url == ''){
			$url = '/';
		}
		$response = new RedirectResponse($url, $status, $headers);
		if(count($this->cookies) > 0){
			foreach ($this->cookies as $item)
			{
				$cookie = Cookie::create($item['name'])
					->withValue($item['value'])
					->withExpires($item['expire'])
					->withDomain($item['domain'])
					->withPath($item['path'])
					->withSecure($item['secure'])
					->withHttpOnly($item['httponly']);
				$response->headers->setCookie($cookie);
			}
		}

		$response->send();
		exit();
	}

	public function download()
	{
		return new Download($this);
	}

	public function send($content)
	{
		$response = new \Symfony\Component\HttpFoundation\Response();
		if(count($this->cookies) > 0){
			foreach ($this->cookies as $item)
			{
				$cookie = Cookie::create($item['name'])
					->withValue($item['value'])
					->withExpires($item['expire'])
					->withDomain($item['domain'])
					->withPath($item['path'])
					->withSecure($item['secure'])
					->withHttpOnly($item['httponly']);
				$response->headers->setCookie($cookie);
			}
		}

		if($this->content_type != ''){
			$response->headers->set('Content-Type', $this->content_type);
		}

		if(count($this->headers) > 0){
			foreach ($this->headers as $k => $v){
				$response->headers->set($k, $v);
			}
		}

		$response->setCharset($this->config['charset']);
		$response->setContent($content)
			->setStatusCode($this->status_header)
			->send();
		exit();
	}
}