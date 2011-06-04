<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Tests Deputy Resource
 *
 * @package		Deputy
 * @category	Tests
 * @author		Micheal Morgan <micheal@morgan.ly>
 * @copyright	(c) 2011 Micheal Morgan
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
		$this->assertEquals('forum/thread', $parent->get('thread')->get_uri());
	}	
	
	/**
	 * Tests Resource config conventions
	 * 
	 * @covers			Deputy_Resource::factory
	 * @covers			Deputy_Resource::get_uri
	 * @covers			Deputy_Resource::get_title
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
		$this->assertEquals($expected['uri'], $deputy_resource->get_uri());
		
		// Check covention for title
		$this->assertEquals($expected['title'], $deputy_resource->get_title());
		
		// Verify visibility
		$this->assertEquals($expected['visible'], $deputy_resource->is_visible());	
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
					'visible'		=> TRUE
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
					'visible'		=> TRUE
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
					'visible'		=> FALSE
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
					'visible'		=> TRUE
				)
			)
		);
	}
}