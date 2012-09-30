<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Tests Deputy Role
 *
 * @group		deputy
 * @package		Deputy
 * @category	Tests
 * @author		Micheal Morgan <micheal@morgan.ly>
 * @copyright	(c) 2011-2012 Micheal Morgan
 * @license		MIT
 */
class Kohana_Deputy_Role_Test extends Unittest_TestCase
{	
	/**
	 * Tests permissions
	 * 
	 * @covers			Deputy_Role::factory
	 * @covers			Deputy_Role::allow_many
	 * @covers			Deputy_Role::get_allow
	 * @covers			Deputy_Role::_get
	 * @covers			Deputy_Role::_set
	 * @dataProvider	provider_allow
	 */
	public function test_allow($set, $expected)
	{
		// Create role
		$acl_role = Deputy_Role::factory();
		
		// Set permissions
		$acl_role->allow_many($set);
	
		// Check permission
		$this->assertEquals($expected, $acl_role->get_allow());
	}	
	
	/**
	 * Data provider for role resources
	 *
	 * @access	public
	 * @return	array
	 */
	public static function provider_allow()
	{
		return array
		(
			array
			(
				array
				(
					'forum',
					'forum/post',
					'forum/post/add',
					'forum/post/edit',
					'forum/thread/' . Deputy::WILDCARD
				),
				array
				(
					'forum'	=> array
					(
						'post'	=> array
						(
							'add'	=> array(),
							'edit'	=> array()
						),
						'thread'	=> array
						(
							Deputy::WILDCARD	=> array()
						)
					)
				)
			),
		);
	}

	/**
	 * Tests deny
	 *
	 * @dataProvider	provider_deny
	 */
	public function test_deny(array $set, $uri, $result)
	{
		// Create role
		$acl_role = Deputy_Role::factory();

		// Set permissions
		$acl_role->deny_many($set);

		// Check permission
		$this->assertEquals($result, $acl_role->is_denied($uri));
	}	

	/**
	 * Data provider for role resources
	 *
	 * @access	public
	 * @return	array
	 */
	public static function provider_deny()
	{
		return array
		(
			array
			(
				array('forum/thread'),
				'forum', 
				FALSE
			),
			array
			(
				array('forum/thread'),
				'forum/thread',
				TRUE
			)
		);
	}
}
