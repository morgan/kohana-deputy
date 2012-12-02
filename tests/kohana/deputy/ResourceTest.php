<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Tests Deputy Resource
 *
 * @group		deputy
 * @package		Deputy
 * @category	Tests
 * @author		Micheal Morgan <micheal@morgan.ly>
 * @copyright	(c) 2011-2012 Micheal Morgan
 * @license		MIT
 */
class Kohana_Deputy_Resource_Test extends Unittest_TestCase
{	
	/**
	 * Tests Resource child
	 * 
	 * @covers	Deputy_Resource::factory
	 * @covers	Deputy_Resource::set
	 * @covers	Deputy_Resource::get
	 * @covers	Deputy_Resource::get_uri
	 * @access	public
	 * @return	void
	 */
	public function test_child()
	{
		// Create parent
		$parent = Deputy_Resource::factory(array('uri' => 'forum'));
		
		// Create child
		$child = Deputy_Resource::factory(array('uri' => 'forum/thread'));
		
		// Set child
		$parent->set('thread', $child);

		// Verify parent returns instanceof Deputy_Resource
		$this->assertInstanceOf('Deputy_Resource', $parent->get('thread'));
		
		// Verify child has correct URI
		$this->assertEquals('forum/thread', $parent->get('thread')->uri());
	}	
	
	/**
	 * Test Resource meta
	 * 
	 * @covers	Deputy_Resource::factory
	 * @covers	Deputy_Resource::meta
	 * @covers	Deputy_Resource::set_meta
	 * @covers	Deputy_Resource::get_meta
	 * @access	public
	 * @return	void
	 */
	public function test_meta()
	{
		$default = array('test' => 'hello world');
		
		// create Deputy_Resource
		$resource = Deputy_Resource::factory(array('meta' => $default));

		// test getting all meta
		$this->assertEquals($default, $resource->meta());

		// test from configuration
		$this->assertEquals('hello world', $resource->meta('test'));
		
		// test overriding all meta
		$resource->meta(array('test2' => 'value'));
		
		// test the new meta is present
		$this->assertEquals('value', $resource->meta('test2'));
		
		// test int
		$resource->meta('test3', 3);
		
		// test setting
		$this->assertEquals(3, $resource->meta('test3'));
	}
	
	/**
	 * Tests Resource config conventions
	 * 
	 * @covers			Deputy_Resource::factory
	 * @covers			Deputy_Resource::uri
	 * @covers			Deputy_Resource::title
	 * @covers			Deputy_Resource::is_visible
	 * @dataProvider	provider_resources
	 * @access			public
	 * @return			void
	 */
	public function test_resources($set, $expected)
	{
		// Create Deputy_Resource
		$deputy_resource = Deputy_Resource::factory($set);

		// Check for proper URI - specifically for "uri_override" scenario
		$this->assertEquals($expected['uri'], $deputy_resource->uri());
		
		// Check covention for title
		$this->assertEquals($expected['title'], $deputy_resource->title());
		
		// Verify visibility
		$this->assertEquals($expected['visible'], $deputy_resource->is_visible());
		
		// Check convention for "segment"
		$this->assertEquals($expected['segment'], $deputy_resource->segment());
	}	
	
	/**
	 * Data provider for resources
	 *
	 * @access	public
	 * @return	array
	 */
	public static function provider_resources()
	{
		return array
		(
			array
			(
				array
				(
					'uri'			=> 'forum/thread'
				),
				array
				(
					'uri'			=> 'forum/thread',
					'title'			=> 'Thread',
					'visible'		=> TRUE,
					'segment'		=> 'thread'
				)
			),
			array
			(
				array
				(
					'uri'			=> 'forum/thread/edit',
					'title'			=> 'Edit Thread'
				),
				array
				(
					'uri'			=> 'forum/thread/edit',
					'title'			=> 'Edit Thread',
					'visible'		=> TRUE,
					'segment'		=> 'edit'
				)
			),	
			array
			(
				array
				(
					'uri'			=> 'forum/thread/add',
					'visible'		=> FALSE
				),
				array
				(
					'uri'			=> 'forum/thread/add',
					'title'			=> 'Add',
					'visible'		=> FALSE,
					'segment'		=> 'add'
				)
			),	
			array
			(
				array
				(
					'uri'			=> 'forum',
					'uri_override'	=> 'forum/browse'
				),
				array
				(
					'uri'			=> 'forum/browse',
					'title'			=> 'Forum',
					'visible'		=> TRUE,
					'segment'		=> 'forum'
				)
			),
			array
			(
				array
				(
					'uri'			=> 'forum'
				),
				array
				(
					'uri'			=> 'forum',
					'title'			=> 'Forum',
					'visible'		=> TRUE,
					'segment'		=> 'forum'
				)
			)
		);
	}
}
