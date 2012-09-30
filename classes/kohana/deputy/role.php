<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Deputy_Role
 * 
 * @package		Deputy
 * @category	Base
 * @author		Micheal Morgan <micheal@morgan.ly>
 * @copyright	(c) 2011-2012 Micheal Morgan
 * @license		MIT
 */
class Kohana_Deputy_Role
{	
	/**
	 * Factory pattern
	 * 
	 * @static
	 * @access	public
	 * @return	Deputy_Role
	 */
	public static function factory()
	{
		return new Deputy_Role;
	}
	
	/**
	 * Allowed
	 * 
	 * @access	protected
	 * @var		array
	 */
	protected $_allow = array();
	
	/**
	 * Denied
	 * 
	 * @access	protected
	 * @var		array
	 */
	protected $_deny = array();

	/**
	 * Denied URIs
	 * 
	 * @access	protected
	 * @var		array
	 */
	protected $_deny_uri = array();

	/**
	 * Cache of allowed
	 * 
	 * @access	public
	 * @var		array
	 */
	protected $_allowed = array();
	
	/**
	 * Cache of denied
	 * 
	 * @access	public
	 * @var		array
	 */
	protected $_denied = array();

	/**
	 * Allow
	 * 
	 * @access	public
	 * @return	$this
	 */
	public function allow($uri)
	{
		$this->_set($this->_allow, $uri);
		
		return $this;
	}
	
	/**
	 * Get Allow
	 * 
	 * @access	public
	 * @return	array
	 */
	public function get_allow()
	{
		return $this->_allow;
	}
	
	/**
	 * Allow Many
	 * 
	 * @access	public
	 * @return	$this
	 */
	public function allow_many(array $collection)
	{
		foreach ($collection as $uri)
		{
			$this->allow($uri);
		}

		return $this;
	}
	
	/**
	 * Deny
	 * 
	 * @access	public
	 * @return	$this
	 */
	public function deny($uri)
	{
		$this->_set($this->_deny, $uri);

		$this->_deny_uri[$uri] = TRUE;

		return $this;
	}
	
	/**
	 * Get Deny
	 * 
	 * @access	public
	 * @return	array
	 */
	public function get_deny()
	{
		return $this->_deny_uri;
	}

	/**
	 * Deny Many
	 * 
	 * @access	public
	 * @return	$this
	 */
	public function deny_many(array $collection)
	{
		foreach ($collection as $uri)
		{
			$this->deny($uri);
		}

		return $this;
	}

	/**
	 * Is Allowed
	 * 
	 * @access	public
	 * @return	bool
	 */
	public function is_allowed($uri)
	{
		if ( ! isset($this->_allowed[$uri]))
		{
			$segments = explode(Deputy::DELIMITER, $uri);
			$count = count($segments);

			$array =& $this->_allow;

			foreach ($segments as $index => $segment)
			{
				if (isset($array[$segment]))
				{
					if ($index + 1 == $count)
						return $this->_allowed[$uri] = TRUE;
					else if ($array =& $array[$segment])
						continue;
				}
				else if (isset($array[Deputy::WILDCARD]))
					return $this->_allowed[$uri] = TRUE;
				else
					break;
			}

			$this->_allowed[$uri] = FALSE;
		}

		return $this->_allowed[$uri];
	}
	
	/**
	 * Is Denied
	 * 
	 * @access	public
	 * @return	bool
	 */
	public function is_denied($uri)
	{
		if ( ! isset($this->_denied[$uri]))
		{
			if (isset($this->_deny_uri[$uri]))
				return $this->_denied[$uri] = TRUE;

			$segments = explode(Deputy::DELIMITER, $uri);
			$count = count($segments);

			$array =& $this->_deny;

			// Check for wildcard
			foreach ($segments as $index => $segment)
			{
				if (isset($array[$segment]))
				{
					if ($index + 1 == $count)
						break;
					else if ($array =& $array[$segment])
						continue;
				}
				else if (isset($array[Deputy::WILDCARD]))
					return $this->_denied[$uri] = TRUE;
				else
					break;
			}

			$this->_denied[$uri] = FALSE;
		}

		return $this->_denied[$uri];
	}

	/**
	 * Set URI
	 * 
	 * @access	protected
	 * @param	array
	 * @param	string
	 * @return	void
	 */
	protected function _set(array & $array, $uri)
	{
		foreach (explode(Deputy::DELIMITER, $uri) as $segment)
		{
			if ( ! isset($array[$segment]))
			{
				$array[$segment] = array();
			}

			$array =& $array[$segment];
		}
	}
}
