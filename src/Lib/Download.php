<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace EvolutionPHP\HTTP\Lib;

use EvolutionPHP\HTTP\Response;

class Download
{
	private $response;
	public function __construct(Response $response)
	{
		$this->response = $response;
	}

	public function write($content, $file_name, $in_line=false)
	{
		$this->download($content, $file_name, $in_line);
	}

	public function file($file_path, $file_name = null, $in_line=false)
	{
		if(file_exists($file_path)){
			$content = file_get_contents($file_path);
			$file_name = is_null($file_name) || $file_name == '' ? basename($file_path) : $file_name;
			$this->download($content, $file_name, $in_line);
		}else{
			throw new \Exception("File not found: $file_path");
		}
	}

	private function download($content, $file_name, $in_line=false)
	{
		if($in_line){
			$this->response->setHeader([
				'Content-Type' => 'application/octet-stream',
				'Content-Disposition' => 'inline; filename="'.$file_name.'"',
				'Content-Transfer-Encoding' => 'binary',
				'Accept-Ranges' => 'bytes'
			]);
		}else{
			$this->response->setHeader([
				'Content-Type' => 'application/octet-stream',
				'Content-Disposition' => 'attachment; filename="'.$file_name.'"',
				'Expires' => '0',
				'Cache-Control' => 'private, no-transform, no-store, must-revalidate',
				'Content-Transfer-Encoding' => 'binary'
			]);
		}

		$this->response->send($content);
	}
}