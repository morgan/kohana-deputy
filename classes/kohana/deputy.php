<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Deputy ACL
 * 
 * By default, all resources are denied permission. When access is checked for a given resource, 
 * ACL iterates through each role checking for both allow and deny. Ambiguity is handled by 
 * returning the result of a role with the most explicit definition. Checking for allowed across 
 * the roles will continue if a role returns false. If allow at anytime returns true, the result 
 * will stop with true. If deny at anytime returns true, the result of the check will stop with 
 * false.
 * 
 * Supports PHP 5.2.3 or greater.
 * 
 * @package		Deputy
 * @category	Base
 * @author		Micheal Morgan <micheal@morgan.ly>
 * @copyright	(c) 2011 Micheal Morgan
 * @license		MIT
 */
class Kohana_Deputy
{	
	/**
	 * Wildcard
	 * 
	 * @var		string
	 */
	const WILDCARD = '*';	

	/**
	 * Delimiter
	 * 
	 * @var		string
	 */
	const DELIMITER = '/';
	
	/**
	 * Singleton Pattern
	 * 
	 * @access	public
	 * @return	Deputy
	 */
	public static function instance(array $config = array())
	{
		static $instance;
		
		if ($instance === NULL)
		{
			$instance = new Deputy($config);
		}
		
		return $instance;
	}	
	
	/**
	 * Helper for html link
	 * 
	 * @access	public
	 * @param	string
	 * @param	bool
	 * @return	string|NULL
	 */
	public static function link($uri, $attributes = array(), $check = TRUE)
	{
		return self::instance()->html_link($uri, $attributes, $check);
	}
	
	/**
	 * Parent resource
	 * 
	 * @access	public
	 * @var		Deputy_Resource
	 */
	protected $_resources;
	
	/**
	 * Roles
	 * 
	 * @access	protected
	 * @var		array
	 */
	protected $_roles = array();
	
	/**
	 * Configuration
	 * 
	 * @access	public
	 * @var		array
	 */
	protected $_config = array();
	
	/**
	 * Initialize Account
	 * 
	 * @access	public
	 * @return	void
	 */
	public function __construct(array $config = array())
	{
		// Create root resource
		$this->_resources = new Deputy_Resource;

		// Handle configuration
		$this->_config = Kohana::config('deputy')->as_array() + $config;
		
		// Setup Deputy
		$this->_setup();
	}
	
	/**
	 * Setup
	 * 
	 * @access	protected
	 * @return	void
	 */
	protected function _setup()
	{
		if ($this->_config['autoload'])
		{
			$this->set_resources($this->_config['resources']);	
		}
	}

	/**
	 * Set role
	 * 
	 * @access	public
	 * @param	string
	 * @return	$this
	 */
	public function set_role($name, $role)
	{
		$acl_role = ($role instanceof Deputy_Role) ? $role : Deputy_Role::factory();
		
		if (is_array($role))
		{
			$allow = $deny = array();
			
			if (isset($role['allow']) OR isset($role['deny']))
			{
				$allow = (isset($role['allow'])) ? $role['allow'] : array();
				$deny = (isset($role['deny'])) ? $role['deny'] : array();
			}
			else
			{
				$allow = $role;
			}
			
			$acl_role->allow_many($allow);
			$acl_role->deny_many($deny);
		}
		
		$this->_roles[$name] = $acl_role;
		
		return $this;
	}
	
	/**
	 * Get role
	 * 
	 * @access	public
	 * @return	Deputy_Role
	 */
	public function get_role($name, $default = FALSE)
	{
		if (isset($this->_roles[$name]))
			return $this->_roles[$name];
		else
			return $default;
	}
	
	/**
	 * Get roles
	 * 
	 * @access	public
	 * @return	array
	 */
	public function get_roles()
	{
		return $this->_roles;
	}
	
	/**
	 * Set Roles
	 * 
	 * @access	public
	 * @return	$this
	 */
	public function set_roles(array $roles)
	{
		foreach ($roles as $name => $role)
		{
			$this->set_role($name, $role);
		}
		
		return $this;
	}
	
	/**
	 * Get Resources
	 * 
	 * @access	public
	 * @return	array
	 */
	public function get_resources()
	{
		return $this->_resources;
	}
	
	/**
	 * Is Allowed
	 * 
	 * @access	public
	 * @return	bool
	 */
	public function allowed($uri)
	{		
		foreach ($this->_roles as $role)
		{
			if ($role->is_denied($uri))
				return FALSE;				
			
			if ($role->is_allowed($uri))
				return TRUE;
		}
		
		return FALSE;
	}
	
	/**
	 * Set Resource
	 * 
	 * @access	public
	 * @param	string
	 * @param	Deputy_Resource|NULL
	 * @return	$this
	 */
	public function set($uri, Deputy_Resource $resource = NULL)
	{
		$resource = ($resource) ? $resource : Deputy_Resource::factory(array('uri' => $uri)); 
		
		$segments = explode(self::DELIMITER, $uri);
		$count = count($segments);
		
		$pointer = $this->_resources;
		
		$base = array();
		
		foreach ($segments as $index => $segment)
		{			
			$base[] = $segment;
			
			if ($index + 1 == $count)
			{
				$pointer->set($segment, $resource);
			}
			else
			{
				$parent = $pointer->get($segment);		
				
				if ( ! $parent)
				{
					$parent = Deputy_Resource::factory(array('uri' => implode(self::DELIMITER, $base)));
					
					$pointer->set($segment, $parent);
				}				
				
				$pointer = $parent;
			}
		}
		
		return $this;
	}
	
	/**
	 * Add Resources using array conventions
	 * 
	 * @access	public
	 * @return	$this
	 */
	public function set_resources(array $resources)
	{
		foreach ($resources as $key => $value)
		{
			if ( ! $value instanceof Deputy_Resource)
			{
				$config = array();
				
				if (is_int($key))
				{
					$config['uri'] = $value;
				}
				else if (is_string($value))
				{
					$config['uri'] = $key;
					$config['title'] = $value;
				}
				else if (is_array($value))
				{				
					$value['uri'] = $key;
					
					$config = $value;
				}
				else if (is_bool($value))
				{
					$config['uri'] = $key;
					$config['visible'] = $value;
				}
				
				$value = Deputy_Resource::factory($config);
			}
			
			$uri = is_string($key) ? $key : $value->get_uri();
			
			$this->set($uri, $value);
		}
		
		return $this;
	}	
	
	/**
	 * Traverse Resource tree for child based on URI structure "parent/child/childchild"
	 * 
	 * @access	public
	 * @param	string	URI of resource to retrieve.
	 * @param	bool	Create resource if it does not exist.
	 * @return	Deputy_Resource
	 */
	public function get($uri, $create = TRUE)
	{
		$resource = $this->_resources;

		foreach (explode(self::DELIMITER, $uri) as $segment)
			if ( ! $resource = $resource->get($segment))
				break;
		
		if ( ! $resource && $create)
		{
			$this->set($uri);
			
			$resource = $this->get($uri, FALSE);
		}
		
		return $resource;
	}
	
	/**
	 * Generate link
	 * 
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string|NULL
	 */
	public function html_link($uri, $attributes = array(), $check = TRUE)
	{
		if ($check && ! $this->allowed($uri))
			return NULL;
			
		$resource = $this->get($uri);
		
		return html::anchor($resource->get_uri(), $resource->get_title(), $attributes);
	}

	/**
	 * Get HTML Tree
	 * 
	 * @access	public
	 * @param	Deputy_Resource|NULL
	 * @return	array
	 */
	public function & html_list(Deputy_Resource $resources = NULL)
	{
		$resources = $resources ? $resources : $this->get_resources();
		
		$tree = array();
		
		foreach ($resources as $resource)
		{
			if ($resource->is_visible())
			{
				$key = html::anchor($resource->get_uri(), $resource->get_title());
				$value = array();
				
				if (count($resource) > 0)
				{
					$value = $this->html_list($resource);
				}
				
				$tree[$key] = $value;
			}
		}
		
		return $tree;
	}	
}