<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Deputy Demo
 *
 * @package		Deputy
 * @category	Base
 * @author		Micheal Morgan <micheal@morgan.ly>
 * @copyright	(c) 2011 Micheal Morgan
 * @license		MIT
 */
class Controller_Demo_Deputy extends Controller_Demo 
{
	public function demo_acl()
	{
		$acl = ACL::instance();

		$acl->set_resources(array
		(
			'forum',
			'forum/thread',
			'forum/thread/add',
			'forum/thread/edit',
			'forum/thread/delete',
			'forum/post/read',
			'forum/post/add'
		));
		
		$acl->set_roles(array
		(
			'admin' => array
			(
				'*'
			)
		));
		
		$this->content = View::factory('demo/deputy/acl')->set('acl', $acl);
	}
}