<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Tests Deputy Role
 *
 * @package		Deputy
 * @category	Tests
 * @author		Micheal Morgan <micheal.morgan@standvertical.com>
 * @copyright	(c) 2007-2011 Stand Vertical Inc
 */
class Kohana_Deputy_Role_Test extends Unittest_TestCase
{	
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
		$acl_role = Deputy_Role::factory();
		$acl_role->allow_many($set);
	
		$this->assertEquals
		(
			$expected,
			$acl_role->get_allow()
		);		
	}
}