# Getting Started

## Creating Roles

Defining individual roles is a snap:

	$deputy = Deputy::instance();
	
	$deputy->set_role('admin', array
	(
		'forum',
		'forum/thread/*',
		'forum/post/add'
	));

## Checking Permission

	$deputy = Deputy::instance();

	// Outputs "bool TRUE"
	echo debug::vars($deputy->allowed('forum/thread/add'));
	
	// Outputs "bool FALSE"
	echo debug::vars($deputy->allowed('forum/post/edit'));

## Automatic Navigation

Deputy comes loaded with a powerful Resource class that facilitates the creation of trees. 
[Learn More](resources) about Deputy Resources.

## Setting Up Application

### Step 1: Config

Throughout applications and modules, the following config setup can be used for defining roles and resources:

	return array
	(
		'autoload'	=> TRUE,
		'resources' => array(),
		'roles'		=> array()
	);
	
[Read More](config) about Deputy's config conventions.

### Step 2: Bootstrap

Deputy can be hooked in many places throughout Kohana but it is important you find the appropriate 
place based on the scope of application. For the sake of demonstration, we will initialize Deputy 
within the applications bootstrap. Below demonstrates two ways to set roles.

	$deputy = Deputy::instance();

	// Example setting multiple roles
	$roles = arr::extract(Kohana::config('deputy.roles'), array('user', 'editor'));
	$deputy->set_roles($roles);
	
	// Example setting individual roles
	$deputy->set_role('user', Kohana::config('deputy.roles.user'));
	$deputy->set_role('editor', Kohana::config('deputy.roles.editor'));
	
	// Setting "config/deputy.autoload" to TRUE will perform this by default
	$deputy->set_resources(Kohana::config('deputy.resources'));
	
### Step 3: Check Access

It is generally most appropriate to check access on a per controller basis. Below is an example 
that checks access across this application. This can be tailored based on scope.

	// extend controller	