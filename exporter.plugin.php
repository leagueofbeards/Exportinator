<?php
namespace Habari;
class Exporter extends Plugin
{
	private static function save($filename, $export_dir, $folder, $file) {
		try {
			$fp = fopen( Site::get_path('user') . '/' . $export_dir . '/' . $folder . '/' . $filename, 'w' );
			if( $fp == false ) {
				$fp = fopen( Site::get_path('user') . '/' . $export_dir . '/' . $folder . '/' . $filename, 'w' );
			} else {
				fwrite($fp, $file);
				fclose($fp);
			}
		} catch( Exception $e ) {
			echo $e->getMessage();
			exit();
		}

	}
	
	public static function parse(Array $args) {
		$objects = $args['objects'];
		$template_dir = $args['template_location'];
		$export_dir = $args['export_location'];
		$templates = $args['template_types'];

		Common::create_dir( Site::get_path('user') . '/' . $export_dir . '/' . $args['export_name'] );
		
		foreach( $templates as $template ) {
			$file = file_get_contents( $template_dir . '/' . $template . '.html' );
			$contents = $objects[$template]['content'];
			if( $contents instanceof Posts ) {
				foreach( $contents as $post ) {
					foreach($objects[$template]['fields'] as $field ) {
						$file = str_replace( "{" . $field . "}", $post->$field, $file );
					}
					
					if( $post->slug == $args['export_name'] ) {
						$filename = 'index.html';
					} else {
						$filename = $post->slug . '.html';
					}
				
					self::save( $filename, $export_dir, $args['export_name'], $file );
				}
			} else {
				$post = $objects[$template]['content'];
				
				foreach($objects[$template]['fields'] as $field ) {
					$file = str_replace( '{%' . $field . '%}', $post->$field, $file );
				}
			}

			if( $post->slug == $args['export_name'] ) {
				$filename = 'index.html';
			} else {
				$filename = $post->slug . '.html';
			}
			
			self::save( $filename, $export_dir, $args['export_name'], $file );
		}
	}
}
?>
