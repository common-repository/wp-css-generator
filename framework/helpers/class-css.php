<?php

	
	namespace  WPCSSGenerator;	
	/**
	 * Form Helper 
	 * 
	 *  ddoati http://www.w3schools.com/css/css3_2dtransforms.asp
	 * @author 		Goran Petrovic <goran.petrovic@godev.rs>
	 * @package    	WordPress
	 * @subpackage 	The Chameleon
	 * @since 		The Chameleon 3.1.0
	 *
	 * @version 1.0.0
	 *
	 */

	class Helper_CSS{
	

		
	//generate skin for file
	static function generate_minify_CSS( $dev = true, $skin_slug = null){
		
		$config = Config::getInstance();
		
		//get term 		
		$parent_terms = get_terms( array(
			    'taxonomy'  => 'wpcss-group',
			    'hide_empty'=> false,		
				'orderby'   => 'meta_value_num',
				'meta_key'	=>	$config->slug.'priority',
				'order' 	=> 'ASC',
				'parent' 	=> 0
			) );

	$googel_font = get_option('wp_css_generator_css_setting_google_fonts','');
	echo !empty( $googel_font['google_fonts_import']) ? $googel_font['google_fonts_import'] : NULL;
	if($dev):	
	echo " 
	";
	endif;
	foreach ($parent_terms as $key => $p_term) {
		
		//get sub terms
		$terms = get_terms( array(
			    'taxonomy' 	=> 'wpcss-group',
			    'hide_empty'=> false,
				'orderby'   => 'meta_value_num',
				'meta_key'	=> $config->slug.'priority',
				'order' 	=> 'ASC',		
				'parent' 	=> $p_term->term_id
			) );

	//display css
	foreach ($terms as $key => $term) {

		
		$id = str_replace( "-","_",  $term->slug);
		
		//get options
		$options  = get_option('wp_css_generator_css_'.	$id );
		
		//get important
		$important = get_term_meta($term->term_id, $config->slug.'important', TRUE);

if(!empty($options)) :
if(!empty($p_term->description)) :	
if($dev):
echo "
/* =   $p_term->name $term->name $p_term->description
";
echo "-------------------------------------------------------------- */
";	
endif;	
echo $p_term->description.'{';
endif;
if($dev):	
echo " 
";endif;		
echo ( $p_term->description) ? "   ".html_entity_decode($term->description).'{' : html_entity_decode($term->description).'{';
	if($dev):	
	echo " 
	";
	endif;
self::display( $options,  $term->slug, $important, $dev);					
self::color( $options,  $term->slug, $important, $dev);
self::font_family( $options,  $term->slug, $important, $dev);
self::background( $options,  $term->slug, $important, $dev);
if($dev):	
echo " 
";
endif;
echo  ( $p_term->description) ? "   }": "}";
if(!empty($p_term->description)) :
if($dev):	
echo " 
";
endif;	
echo "}";
endif;
	endif;
		}	
	}

	$custom_css = get_option('wp_css_generator_css_setting_css','');
	echo !empty( $custom_css['setting_css']) ? minify_css($custom_css['setting_css']) : NULL;		
}
	

		/**
		 * 	Display 
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 * @var array $value  
		 * @var str $key 
		 **/
		static function display($value = '', $key, $important, $dev){
			if( !empty( $value[$key.'=display'])) :			
				$important = ( $important == 'yes') ? '!important' : '';
				echo 'display:'. $value[$key.'=display'] . $important.';' ;
	if($dev):	
	echo " 
	";
	endif;
			endif;	
		}

		/**
		 * 	Width 
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 * @var array $value  
		 * @var str $key 
		 **/
		static function width( $values, $key ){
			$width  = isset( $values[$key.'=width'] )  		? $values[$key.'=width'] : NULL; 
			$unit   = isset( $values[$key.'=width_unit'] )   ? $values[$key.'=width_unit'] 	: 'px';
			echo ( !empty( $width ) )   ? 'width: ' .  $width . $unit . '; ' : NULL ;	
		}
		
		/**
		 * 	Width 
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 * @var array $value  
		 * @var str $key 
		 **/

		static function height($values, $key){
			$height  = isset( $values[$key.'=height'] )  	   ? $values[$key.'=height'] 	: NULL; 
			$unit    = isset( $values[$key.'=height_unit'] )   ? $values[$key.'=height_unit'] 	: 'px';
			echo ( !empty( $height ) )   ? 'height: ' .  $height . $unit . '; ' : NULL ;			

		}

		/**
		 * 	Color 
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 * @var array $value  
		 * @var str $key 
		 **/
		static function color(	$value = 'transparent', $key, $important, $dev){
			if( !empty( $value[$key.'=color'])) :			
				$important = ( $important == 'yes') ? '!important' : '';
				echo 'color: ' . $value[$key.'=color'] . $important.'; ' ;
	if($dev):	
	echo " 
	";
	endif;
			endif;
		}

		/**
		 * 	Font Family 
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 * @var array $value  
		 * @var str $key 
		 **/
		static function font_family($value, $key, $important, $dev){		
			if( !empty( $value[$key.'=font_family'] ) ) :			
				$important = ( $important == 'yes') ? '!important' : '';			
				echo 'font-family: ' .str_replace(array('font-family:'), array(""), stripslashes( rtrim( $value[$key.'=font_family'], ' ;') ) .''. $important . ';') ;
	if($dev):	
	echo " 
	";
	endif;
			endif;		
		}
		
		
		/**
		 * 	Background 
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 * @var array $value  
		 * @var str $key 
		 **/
 		static function  background($value, $key, $important, $dev){

			$color 		= isset($value[$key.'=bg_color'] )				? $value[$key.'=bg_color']  : NULL;
			$color2 	= isset($value[$key.'=bg_color2'] ) 			? $value[$key.'=bg_color2'] : NULL;
			$image 		= isset($value[$key.'=bg_image'] ) 				? $value[$key.'=bg_image']  : NULL;		
			$repeat 	= isset($value[$key.'=bg_image_repeat'] ) 		? $value[$key.'=bg_image_repeat']  	 : 'no-repeat';
			$position 	= isset($value[$key.'=bg_image_position'] ) 	? $value[$key.'=bg_image_position']  	 : 'left top';
			$attachment	= isset($value[$key.'=bg_image_attachment'] )	? $value[$key.'=bg_image_attachment']  : 'scroll';
			$size 		= isset($value[$key.'=bg_image_size'] ) 		? $value[$key.'=bg_image_size']  		 : 'cover';
			$important = ( $important == 'yes') ? '!important' : '';	

			if ( !empty( $color ) and !empty( $color2 ) ) :

				echo 'background: ' . $color2 . $important .';';
	if($dev):	
	echo " 
	";endif;
				echo 'background: -moz-linear-gradient(top, ' . $color . ' 0%, ' . $color2 . ' 100%)'.$important.'; ';	
	if($dev):	
	echo " 
	";endif;
				echo 'background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, ' . $color . '), color-stop(100%,' . $color2 . '))'.$important.';' ;
	if($dev):	
	echo " 
	";endif;	
				echo 'background: -webkit-linear-gradient(top, ' . $color . ' 0%, ' . $color2 . ' 100%)'.$important.'; ';
	if($dev):	
	echo " 
	";endif;	
				echo 'background: -o-linear-gradient(top, ' . $color . ' 0%, ' . $color2 . ' 100%)'.$important.'; ';
	if($dev):	
	echo " 
	";endif;	
				echo 'background: -ms-linear-gradient(top, '. $color .' 0%, '. $color2 .' 100%)'.$important.'; ';
	if($dev):	
	echo " 
	";endif;			
				echo 'background: linear-gradient(top, '. $color .' 0%, '. $color2 .' 100%)'.$important.'; ';
	if($dev):	
	echo " 
	";endif;	
				echo 'filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='. $color . '. , endColorstr=' . $color2 . ',GradientType=0 )'.$important.'; ';
	if($dev):	
	echo " 
	";endif;

			elseif( !empty( $image ) ) :

				$color = !empty( $color ) ? $color : 'transparent';
				echo 'background:' . $color .' url(' . $image . ') ' . $repeat . ' ' . $position . ' '. $attachment  . $important .'; ' ;
	if($dev):	
	echo " 
	";endif;	
				echo 'background-attachment: ' . $attachment . $important .'; ' ;
	if($dev):	
	echo " 
	";endif;
				echo 'background-size: ' . $size . $important .'; ' ;
	if($dev):	
	echo " 
	";endif;

			elseif (!empty( $color ) ) :

				echo 'background-color: ' . $color . $important . ';' ;
	if($dev):	
	echo " 
	";endif;

			endif;

		}

	
	}
?>