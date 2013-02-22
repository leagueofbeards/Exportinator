Exportinator
============

Exportinator allows you to transform and export content from a [Habari] based site or webapp, in any format you might need, by creating a couple of templates and a writing a few lines of code.

[Habari]: https://github.com/habari

Usage
-----

Download and install the plugin in the normal way. Once it is enabled, you should be good to go on that end. The Exportinator works by grabbing templates from a specified location, parsing them and then writing out the result in whatever location you specify when calling the system.

Creating templates
-----

The templates are plan HTML, with simple template tags sprinked throughout them. The tags use the following pattern: 

`{tag}` or `{title}` or `{content}`

The Exportinator will match these tags to the fields you pass in for each object you want to transform. Speaking of passing information to the Exportinator, here are the options you can pass:

Options  	                                   | Description
---------------------------------------------- | -----------
`connected`										| Whethere or not the objects you are passing in are connected, an example would be if you are exporting an HTML version of a site.
`export_name`									| Exprtinator creates a directory to store the exported files, in. We use this value for that.
`template_types`								| An array containing the names of the templates to use when creating your export.
`template_location`								| The location you want Exportinator to look. Usually in the plugin directory of the plugin you are calling Exprtinator from.
`objects`										| An array containing the data you want to transform and output, along with the fields you want Exportinator to use when matching.
`export_location`								| Where you want the export to live when all is said and done.
`assets`										| Any CSS, images or Javascript you might need to ship with.

A Practical Example
-----

Let's say you have two custom content types, document and page. You use these two document types to create documentation for a project, and now you want to export the documentation as flat HTML. You would call Exportinator like this: 

    $document = Document::get( array('id' => $vars['document_id']) );
    $pages = Pages::get( array('document_id' => $document->id) );

    $objects = array( 
        'document'	=>	array('content' => $document, 'fields' => array('title', 'slug', 'content')), 
        'page'		=>	array('content' => $pages, 'fields' => array('title', 'slug', 'content')) 
    );
	
    $assets = array( 'style.css', 'prettify.css' );

    $args = array(
        'connected'			=>	array('parent' => $document, 'items' => 'page'),
        'export_name'		=>	$document->slug,
        'template_types'	=>	array('document', 'page'),
        'template_location'	=>	__DIR__ . '/export_templates',
        'objects'			=>	$objects,
        'export_location'	=>	'exports',
        'assets'			=>	$assets		
    );

Then pass this multi-dimensional array to the Exporter and you are golden.

    Exporter::parse( $args );
