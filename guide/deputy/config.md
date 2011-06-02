# Configuration

Deputy was designed to auto load Resources and Roles across multiple modules. This allows for 
segmenting large applications into smaller more managable modules.

## Resources

### In "config/acl.php"

	return array
	(
		'resources' => array
		(
			'forum',
			'forum/thread',
			'forum/thread/post'
		)
	);
	
### Loading Config

Deputy purposely does not load the configuration by default. You should hook and load the 
configuration based on the requirements of your application. Below is an example of loading 
the sample from above:

	$acl = ACL::instance();
	
	$acl->set_resources(Kohana::config('acl.resources'));

The above example will load all resources across all modules using Kohana's cascading file system 
convention.

## Roles

### In "config/acl.php"

	return array
	(
		'roles' => array
		(
			'admin'	=> array
			(
				'forum/*'
			)
		)
	);
	
### Loading Config

	$acl = ACL::instance();
	
	$acl->set_roles(Kohana::config('acl.roles'));

