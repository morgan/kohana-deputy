<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Deputy_Resource
 * 
 * @package		Deputy
 * @category	Base
 * @author		Micheal Morgan <micheal@morgan.ly>
 * @copyright	(c) 2011 Micheal Morgan
 * @license		MIT
 */
class Kohana_Deputy_Resource extends ArrayIterator
{	
	/**
	 * Resource Defaults
	 * 
	 * @static
	 * @access	public
	 * @var		array
	 */
	public static $defaults = array
	(
		'title'			=> NULL,
		'visible'		=> TRUE,
		'uri'			=> NULL,
		'uri_override'	=> NULL
	);
		
	/**
	 * Factory pattern
	 * 
	 * @access	public
	 * @return	Deputy_Resource
	 */
	public static function factory(array $config = array())
	{
		return new Deputy_Resource($config);
	}
	
	/**
	 * Convert URI segment into human readable title
	 * 
	 * @access	public
	 * @return	string
	 */
	public static function humanize($uri)
	{
		$segments = explode(Deputy::DELIMITER, $uri);

		$segments = explode(' ', Inflector::humanize(end($segments)));
		
		$title = '';
		
		foreach ($segments as $segment)
		{
			$segment = ucfirst($segment);
			
			$title .= ($title === '') ? $segment : ' ' . $segment;
		}
		
		return $title;
	}	

	/**
	 * Title
	 * 
	 * @access	protected
	 * @var		string
	 */
	protected $_title = NULL;	
	
	/**
	 * URI
	 * 
	 * @access	protected
	 * @var		string
	 */
	protected $_uri = NULL;
	
	/**
	 * Visible
	 * 
	 * @access	protected
	 * @var		bool
	 */
	protected $_visible = FALSE;
	
	/**
	 * Initialize
	 * 
	 * @access	public
	 * @return	void
	 */
	public function __construct(array $config = array())
	{
		$config += static::$defaults;
		
		$this->_title	= ($config['title']) ? $config['title'] : self::humanize($config['uri']);
		$this->_uri		= ($config['uri_override']) ?: $config['uri'];	
		$this->_visible	= $config['visible'];			
	}
	
	/**
	 * Get title
	 * 
	 * @access	public
	 * @return	NULL|string
	 */
	public function get_title()
	{
		return $this->_title;
	}
	
	/**
	 * Get URI
	 * 
	 * @access	public
	 * @return	NULL|string
	 */
	public function get_uri()
	{
		return $this->_uri;
	}
	
	/**
	 * Whether or not resource is visible
	 * 
	 * @access	public
	 * @return	bool
	 */
	public function is_visible()
	{
		return $this->_visible;
	}
	
	/**
	 * Set Resource
	 * 
	 * @access	public
	 * @return	$this
	 */
	public function set($name, Deputy_Resource $resource)
	{
		$this->offsetSet($name, $resource);
		
		return $this;
	}
	
	/**
	 * Get Resource
	 * 
	 * @access	public
	 * @return	Deputy_Resource|bool
	 */
	public function get($name)
	{
		if ($this->offsetExists($name))
			return $this->offsetGet($name);
			
		return FALSE;
	}
}