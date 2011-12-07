# Deputy Resources

Resources allow for easy creation of navigation based on users permissions.

## Anatomy of an Resource

Given the power of routes in Kohana, resources were designed to effectively map to an URI. There 
are conventions that make resources flexible and easy.

## Creating Resources

Deputy_Resource uses a unified construct with the following parameters:

	$config = array
	(
		'uri'		=> 'forum',
		'title'		=> 'Forum',
		'visible'	=> TRUE,
		'override'	=> NULL,
		'meta'		=> array('key' => 'value', 'class' => 'image_dashboard')
	);

The only required parameter is the URI. The title is derived from the last segment in the URI. 
Visibility is by default TRUE. Override can be used to specify another URI for the resource.

	$resource = Deputy_Resource::factory(array('uri' => 'forum/thread'));
	
	// Outputs "Thread"
	echo $resource->title();
	
	// Outputs "forum/thread"
	echo $resource->uri();
	
	// Outputs "TRUE"
	var_export($resource->is_visible());

## Children Resources

### Setting

Children resources can be manually added to a parent.

	$parent = Deputy_Resource::factory(array('uri' => 'forum'));
	
	$child = Deputy_Resource::factory(array('uri' => 'forum/thread'));
	
	$deputy_resource->set('thread', $child);

Of course, using the Deputy takes care of the creation of children automatically.

### Getting

Retrieving children are easy. Simply "get" using the segment name:

	$child = $parent->get('thread');

### Traversing

Deputy_Resource extends ArrayIterator allowing for easy traversal of children. A resource can 
essentially be used as an array.

	// Outputs "Thread"
	foreach ($parent as $child)
	{
		echo $child->title();
	}

## Deputy Conventions

Deputy has flexible configuration for defining resources.

	$deputy = Deputy::instance();
	
	$deputy->set_resources(array
	(
		'forum',
		'forum/thread' 			=> 'Threads',
		'forum/thread/add'		=> array('title' => 'Add Thread', 'visible' => TRUE, 'meta' => array('class' => 'image_dashboard')),
		'forum/thread/edit'		=> FALSE,
		'forum/thread/delete'	=> TRUE,
		'forum/post'			=> Deputy_Resource::factory(array('uri' => 'forum/post'))
	));
	
## Meta data

Meta data is useful for specifying additional access information or for generating navigation 
(such as HTML class, id, etc). Meta data can be specified on a per resource basis.

	$deputy_resource->meta('class', 'image_dashboard');
	
	echo HTML::anchor($resource->uri(), $resource->title(), array('class' => $deputy_resource->meta('class')));