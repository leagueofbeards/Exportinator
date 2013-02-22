<?php
namespace Habari;
class Exporter extends Plugin
{
  public static function parse(Array $args) {
		$objects = $args['objects'];
		$template_dir = $args['template_location'];
		$export_dir = $args['export_location'];
		$templates = $args['template_types'];

		foreach( $templates as $template ) {
			$file = file_get_contents( $template_dir . '/' . $template . '.html' );
			
			if( is_array( $objects[$template]['content'] ) ) {
				foreach( $objects[$template]['content'] as $post ) {
					foreach($objects[$template]['fields'] as $field ) {
						$file = str_replace( "{" . $field . "}", $post->$field, $temp );
					}
				}
			} else {
				$post = $objects[$template]['content'];
				foreach($objects[$template]['fields'] as $field ) {
					$file = str_replace( '{%' . $field . '%}', $post->$field, $file );
				}
			}
						
			$fp = fopen( Site::get_path('habari') . '/' . $export_dir . '/test/' . $post->slug, 'w' );
			fwrite($fp, $file);
			fclose($fp);
		}		
	}
}
?>
