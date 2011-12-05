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
		'uri_override'	=> NULL,
		'segment'		=> NULL,
		'meta'			=> array()
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
	 * Segment
	 * 
	 * @access	protected
	 * @var		string
	 */
	protected $_segment;
	
	/**
	 * Meta data
	 * 
	 * @access	protected
	 * @var		array
	 */
	protected $_meta = array();
	
	/**
	 * Initialize
	 * 
	 * @access	public
	 * @return	void
	 */
	public function __construct(array $config = array())
	{
		$config = Arr::merge(Deputy_Resource::$defaults, $config);
		
		$this->_title	= ($config['title']) ? $config['title'] : Deputy_Resource::humanize($config['uri']);
		$this->_uri		= ($config['uri_override']) ? $config['uri_override'] : $config['uri'];	
		$this->_visible	= $config['visible'];
		$this->_segment	= ($config['segment']) ? $config['segment'] : array_pop(explode('/', $config['uri']));
		$this->_meta	= $config['meta'];	
	}
	
	/**
	 * Get or set title
	 * 
	 * @access	public
	 * @param	mixed	NULL|string
	 * @return	mixed	$this|string
	 */
	public function title($value = NULL)
	{
		if ($value === NULL)
			return $this->_title;
			
		$this->_title = $value;
		
		return $this;
	}
	
	/**
	 * Get or set URI
	 * 
	 * @access	public
	 * @param	mixed	NULL|string
	 * @return	mixed	$this|string
	 */
	public function uri($value = NULL)
	{
		if ($value === NULL)
			return $this->_uri;
			
		$this->_uri = $value;	
			
		return $this;
	}
	
	/**
	 * Get or set Resource visibility
	 * 
	 * @access	public
	 * @param	mixed	NULL|bool
	 * @return	mixed	$this|bool
	 */
	public function is_visible($value = NULL)
	{
		if ($value === NULL)
			return $this->_visible;
			
		$this->_visible = (bool) $value;
		
		return $this;
	}
	
	/**
	 * Segment
	 * 
	 * @access	protected
	 * @return	mixed	string|NULL
	 */
	public function segment($value = NULL)
	{
		if ($value === NULL)
			return $this->_segment;
			
		$this->_segment = $value;
		
		return $this;
	}
	
	/**
	 * Get or set all of meta
	 * 
	 * @access	protected
	 * @return	mixed	string|NULL
	 */
	public function meta(array $value = NULL)
	{
		if ($value === NULL)
			return $this->_meta;

		$this->_meta = $value;
		
		return $this;
	}	
	
	/**
	 * Get Meta
	 * 
	 * @access	public
	 * @param	string
	 * @param	mixed
	 * @return	mixed
	 */
	public function get_meta($key, $default = NULL)
	{
		if (isset($this->_meta[$key]))
			return $this->_meta[$key];
			
		return $default;
	}
	
	/**
	 * Set Meta
	 * 
	 * @access	public
	 * @param	string
	 * @param	mixed
	 * @return	$this
	 */
	public function set_meta($key, $value)
	{
		$this->_meta[$key] = $value;
		
		return $this;
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