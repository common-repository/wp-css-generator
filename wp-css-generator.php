<?php

	/*
	Plugin Name: WP CSS Generator
	Description: Customize you WP page elements easy and fast. Get full control over any element or property.
	Plugin URI: https://wpcssgenerator.com
	Version: 1.1.1
	Author: Goran Petrovic	
	Author URI: https://godev.rs	
	Copyright 2017 WP CSS Generator 
	License: GPL2+
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
	Text Domain: wp-css-generator
	Domain Path: /i18/
	*/

	namespace WPCSSGenerator;
	

	include_once('framework/classes/class-config.php');

	global $config;
	$config 			   = Config::getInstance();	
	$config->slug 		   = 'wp_css_generator_';
	$config->namespace 	   = 'WPCSSGenerator';
	$config->DIR           = plugin_dir_path( __FILE__ );
	$config->URL  		   = plugin_dir_url( __FILE__ );
	$config->basename      = plugin_basename( dirname( __FILE__ ) );
	$config->FILE  		   = __FILE__;	
	
	//include helpers
	foreach (glob( 	$config->DIR .'/framework/helpers/*', GLOB_NOSORT ) as $dir_path) :
		include_once( $dir_path );
	endforeach;

	//incude classes
	foreach (glob( 	$config->DIR .'/framework/classes/*', GLOB_NOSORT ) as $dir_path) :
		include_once( $dir_path );
	endforeach;

	//include parts
	foreach (glob( 	$config->DIR .'/parts/*', GLOB_ONLYDIR ) as $dir_path) :
		$dir = explode('/', $dir_path);	
		$name =  end($dir);
		include_once( $dir_path.'/'.$name.'.php' );
	endforeach;

	//include widgets
	foreach (glob( $config->DIR.'/widgets/*', GLOB_ONLYDIR ) as $dir_path) :
		$dir = explode('/', $dir_path);	
		$name =  end($dir);
		include_once( $dir_path.'/'.$name.'.php' );
	endforeach;
	
	include_once('framework/class-bootstrap.php');

	global $WPCSSGenerator;					
	$WPCSSGenerator = new Bootstrap();
		
	
		
?>