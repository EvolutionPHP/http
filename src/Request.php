<?php
namespace EvolutionPHP\HTTP;
use EvolutionPHP\HTTP\Lib\Req;

class Request extends Req
{
	private $ip_address = false;

	// --------------------------------------------------------------------

	/**
	 * Fetch an item from the GET array
	 *
	 * Returns a scalar input value by name.
	 *
	 * @param string|int|float|bool|null $default The default value if the input key does not exist
	 */
	public function get($key = NULL, $xss_clean = NULL)
	{
		if($this->_get()->has($key)){
			return is_array($_GET[$key]) ? $this->_get()->all($key) : $this->_get()->get($key);
		}else{
			return null;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch an item from the POST array
	 *
	 * Returns a scalar input value by name.
	 *
	 * @param string|int|float|bool|null $default The default value if the input key does not exist
	 */
	public function post($key)
	{
		if($this->_post()->has($key)){
			return is_array($_POST[$key]) ? $this->_post()->all($key) : $this->_post()->get($key);
		}else{
			return null;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch an item from POST data with fallback to GET
	 *
	 * Returns a scalar input value by name.
	 *
	 * @param string|int|float|bool|null $default The default value if the input key does not exist
	 */
	public function post_get($key)
	{
		if($result = $this->post($key)){
			return $result;
		}else{
			return $this->get($key);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch an item from GET data with fallback to POST
	 *
	 * Returns a scalar input value by name.
	 *
	 * @param string|int|float|bool|null $default The default value if the input key does not exist
	 */
	public function get_post($key)
	{
		if($result = $this->get($key)){
			return $result;
		}else{
			return $this->post($key);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch an item from the COOKIE array
	 *
	 * @param	mixed	$index		Index for item to be fetched from $_COOKIE
	 * @param	bool	$xss_clean	Whether to apply XSS filtering
	 * @return	mixed
	 */
	public function cookie($key)
	{
		return $this->_cookie()->get($key);
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch an item from the SERVER array
	 *
	 * @param string $key Index for item to be fetched from $_SERVER
	 * @return mixed
	 *
	 *
	 */
	public function server($key)
	{
		return $this->_server()->get($key);
	}

	// --------------------------------------------------------------------

	/**
	 * Get Headers
	 *
	 * @param $key
	 * @return string|null
	 */
	public function headers($key)
	{
		return $this->_headers()->get($key);
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch User Agent string
	 *
	 * @return	string|null	User Agent string or NULL if it doesn't exist
	 */
	public function user_agent()
	{
		return $this->headers('User-Agent');
	}

	// ------------------------------------------------------------------------

	/**
	 * Fetch an item from the php://input stream
	 *
	 * Useful when you need to access PUT, DELETE or PATCH request data.
	 *
	 * @param	string	$index		Index for item to be fetched
	 * @param	bool	$xss_clean	Whether to apply XSS filtering
	 * @return	mixed
	 */
	public function input_stream($index = NULL, $xss_clean = NULL)
	{
		return $this->http()->getContent();
	}


	// --------------------------------------------------------------------

	/**
	 * Fetch the IP Address
	 *
	 * Determines and validates the visitor's IP address.
	 *
	 * @return	string	IP address
	 */
	public function ip_address()
	{
		if ($this->ip_address !== FALSE)
		{
			return $this->ip_address;
		}
		if($this->server('HTTP_CF_CONNECTING_IP')){
			$this->ip_address = $this->server('HTTP_CF_CONNECTING_IP');
			return $this->ip_address;
		}
		$this->ip_address = $this->http()->getClientIp();

		return $this->ip_address;
	}





	// --------------------------------------------------------------------

	/**
	 * Is AJAX request?
	 *
	 * Test to see if a request contains the HTTP_X_REQUESTED_WITH header.
	 *
	 * @return 	bool
	 */
	public function is_ajax()
	{
		return $this->http()->isXmlHttpRequest();
	}


	// --------------------------------------------------------------------
	/**
	 * Get Request Method
	 *
	 * Return the request method
	 *
	 * @param	bool	$upper	Whether to return in upper or lower case
	 *				(default: FALSE)
	 * @return 	string
	 */
	public function method($upper = FALSE)
	{
		$method = $this->http()->getMethod();
		return ($upper)
			? strtoupper($method)
			: strtolower($method);
	}

	/**
	 * Validate Request Method
	 *
	 * @param $method
	 * @return bool
	 */
	public function is_method($method)
	{
		return $this->method() === strtolower($method);
	}

	/**
	 * Validate if request is from HTTPS
	 *
	 * @return bool
	 */
	public function is_https()
	{
		return $this->http()->isSecure();
	}

	/**
	 * Current URL
	 *
	 * @return string
	 */
	public function current_url()
	{
		return ($this->http()->isSecure() ? 'https' : 'http').
			'://'.
			$this->server('HTTP_HOST').
			$this->server('REQUEST_URI');
	}

	public function is_cli()
	{
		if (in_array(PHP_SAPI, ['cli', 'phpdbg'], true)) {
			return true;
		}

		// PHP_SAPI could be 'cgi-fcgi', 'fpm-fcgi'.
		// See https://github.com/codeigniter4/CodeIgniter4/pull/5393
		return ! $this->server('REMOTE_ADDR') && !$this->server('REQUEST_METHOD');
	}
}