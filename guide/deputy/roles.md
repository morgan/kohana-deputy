# Roles

## Conventions
	
	$roles['user'] = array
	(
		'allow' => array
		(
			'forum/thread/*'
		),
		'deny'	=> array
		(
			'forum/thread/delete'
		)
	);
	
	ACL::instance()->set_roles($roles);
	
## Check Permission

Here are examples of access based on the roles and resources defined above.

	$acl = ACL::instance();

	// Outputs "TRUE"
	var_export($acl->allowed('forum/thread/edit'));
	
	// Outputs "FALSE"
	var_export($acl->allowed('forum/thread/delete'));