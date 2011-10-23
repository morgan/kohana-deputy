<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Tests Deputy
 *
 * @group		deputy
 * @package		Deputy
 * @category	Tests
 * @author		Micheal Morgan <micheal@morgan.ly>
 * @copyright	(c) 2011 Micheal Morgan
 * @license		MIT
 */
class Kohana_DeputyTest extends Unittest_TestCase
{	
	/**
	 * Tests permissions
	 * 
	 * @covers			Deputy::add_roles
	 * @covers			Deputy::add_role
	 * @covers			Deputy::get_role
	 * @covers			Deputy_Role::get_allow
	 * @covers			Deputy_Role::get_deny
	 * @covers			Deputy_Role::allow_many
	 * @covers			Deputy_Role::deny_many
	 * @covers			Deputy_Role::_get
	 * @covers			Deputy_Role::_set
	 * @dataProvider	provider_roles
	 */
	public function test_roles($set, $expected)
	{
		$deputy = new Deputy;

		$deputy->set_roles($set);
		
		foreach ($expected as $name => $permissions)
		{
			$role = $deputy->get_role($name);
			
			$this->assertEquals
			(
				$permissions['allow'],
				$role->get_allow()
			);
			
			$this->assertEquals
			(
				$permissions['deny'],
				$role->get_deny()
			);			
		}
	}	
	
	/**
	 * Data provider for role resources
	 *
	 * @access	public
	 * @return	array
	 */
	public static function provider_roles()
	{
		return array
		(
			array
			(
				array
				(
					'owner'	=> array
					(
						Deputy::WILDCARD
					),	
					'user'	=> array
					(
						'allow' => array
						(
							'forum/post/' . Deputy::WILDCARD,
							'forum/thread/' . Deputy::WILDCARD
						),
						'deny'	=> array
						(
							'forum/post/edit'
						)					
					)
				),
				array
				(
					'owner'	=> array
					(
						'allow'	=> array
						(
							Deputy::WILDCARD => array()
						),
						'deny'	=> array()
					),
					'user'	=> array
					(
						'allow' => array
						(
							'forum' => array
							(
								'post'		=> array(Deputy::WILDCARD => array()),
								'thread'	=> array(Deputy::WILDCARD => array())
							)
						),
						'deny'	=> array
						(
							'forum' => array('post' => array('edit' => array()))
						)					
					)
				)
			),
			array
			(
				array
				(
					'owner'	=> array
					(
						'allow'	=> array('forum')
					)
				),
				array
				(
					'owner'	=> array
					(
						'allow'	=> array
						(
							'forum' => array()
						),
						'deny'	=> array()
					)
				)
			)
		);
	}	
	
	/**
	 * Tests permissions
	 * 
	 * @access			public
	 * @covers			Deputy::set_resources
	 * @covers			Deputy::set
	 * @covers			Deputy::get
	 * @covers			Deputy_Resource::get_title
	 * @covers			Deputy_Resource::is_visible
	 * @covers			Deputy_Resource::get_uri
	 * @covers			Deputy_Resource::factory
	 * @dataProvider	provider_resources
	 */
	public function test_resources($set, $expected)
	{
		$deputy = new Deputy;

		$deputy->set_resources($set);
		
		foreach ($expected as $uri => $setting)
		{
			$resource = $deputy->get($uri, FALSE);
			
			$this->assertInstanceOf('Deputy_Resource', $resource);

			$this->assertEquals($setting['title'], $resource->get_title());
			
			$this->assertEquals($setting['visible'], $resource->is_visible());

			$this->assertEquals($setting['uri'], $resource->get_uri());			
		}
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
			// Basic nested conventions
			array
			(
				array
				(
					'forum',
					'forum/thread/add'
				),
				array
				(
					'forum'					=> array('title' => 'Forum',	'visible' => TRUE,	'uri' => 'forum'),
					'forum/thread'			=> array('title' => 'Thread',	'visible' => TRUE,	'uri' => 'forum/thread'),				
					'forum/thread/add'		=> array('title' => 'Add',		'visible' => TRUE,	'uri' => 'forum/thread/add')										
				)
			),
			// Testing settings
			array
			(
				array
				(
					'forum'					=> array('uri_override' => 'forum/browse'),
					'forum/thread/add'		=> 'Add Thread',
					'forum/thread/edit'		=> array('title' => 'Edit Thread', 'visible' => FALSE),
					'forum/thread/delete'	=> FALSE,
					'forum/post',
					'forum/post/add'		=> TRUE,
					'forum/post/edit'		=> array('title' => 'Edit Post'),
					'forum/post/delete'		=> Deputy_Resource::factory(array('uri' => 'forum/post/delete'))
				),
				array
				(
					'forum'					=> array('title' => 'Forum',		'visible' => TRUE,	'uri' => 'forum/browse'),
					'forum/thread'			=> array('title' => 'Thread',		'visible' => TRUE,	'uri' => 'forum/thread'),				
					'forum/thread/add'		=> array('title' => 'Add Thread',	'visible' => TRUE,	'uri' => 'forum/thread/add'),
					'forum/thread/edit'		=> array('title' => 'Edit Thread',	'visible' => FALSE,	'uri' => 'forum/thread/edit'),
					'forum/thread/delete'	=> array('title' => 'Delete',		'visible' => FALSE,	'uri' => 'forum/thread/delete'),
					'forum/post'			=> array('title' => 'Post',			'visible' => TRUE,	'uri' => 'forum/post'),				
					'forum/post/add'		=> array('title' => 'Add',			'visible' => TRUE,	'uri' => 'forum/post/add'),
					'forum/post/edit'		=> array('title' =>	'Edit Post',	'visible' => TRUE,	'uri' => 'forum/post/edit'),
					'forum/post/delete'		=> array('title' => 'Delete',		'visible' => TRUE,	'uri' => 'forum/post/delete')								
				)
			)			
		);
	}	
}