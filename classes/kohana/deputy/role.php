<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Deputy_Role
 * 
 * @package		Deputy
 * @category	Base
 * @author		Micheal Morgan <micheal@morgan.ly>
 * @copyright	(c) 2011 Micheal Morgan
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
		return $this->_deny;
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
		return $this->_get($this->_allow, $uri);
	}
	
	/**
	 * Is Denied
	 * 
	 * @access	public
	 * @return	bool
	 */
	public function is_denied($uri)
	{
		return $this->_get($this->_deny, $uri);
	}	
	
	/**
	 * Get result of URI
	 * 
	 * @access	protected
	 * @param	array
	 * @param	string
	 * @return	bool
	 */
	protected function _get(array & $array, $uri)
	{
		$segments = explode(Deputy::DELIMITER, $uri);
		$count = count($segments);

		foreach ($segments as $index => $segment)
		{			
			if (isset($array[$segment]))
			{
				if ($index + 1 == $count)
					return TRUE;
				else if ($array =& $array[$segment])
					continue;
			}
			else if (isset($array[Deputy::WILDCARD]))
				return TRUE;
			else
				return FALSE;
		}
		
		return FALSE;
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