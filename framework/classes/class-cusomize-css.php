<?php
	
	//@version 1.1
	
    namespace WPCSSGenerator;

	use WP_Customize_Color_Control;
	use WP_Customize_Image_Control;
	
	class Customize_CSS{
		
		var $customize_style;
	
		static $units = 
			array(
				'px' => 'px', 
				'em' => 'em', 
				'pt' => 'pt', 
				 '%' => '%'
				);




		static $display =
			array(
				''		 	   => ' ---', 
				'block'	 	   => 'Block', 
				'none'	 	   => 'None', 
				'inline' 	   => 'Inline', 
				'inline-block' => 'Inline-Block', 
				'initial'      => 'Initial'
				);				



		static	$standard_fonts = 
			array(
				'' => ' --- ',
				'inherit' =>'Inherit',
				"Arial, Helvetica, sans-serif" => "Arial, Helvetica, sans-serif",
				"Arial,Tahoma,Helvetica,FreeSans,sans-serif" => "Arial, Tahoma, Helvetica, FreeSans, sans-serif",	
				"'Arial Black', Gadget, sans-serif" => "'Arial Black', Gadget, sans-serif",
				"'Bookman Old Style', serif" => "'Bookman Old Style', serif",
				"'Comic Sans MS', cursive" => "'Comic Sans MS', cursive",
				"Courier, monospace" => "Courier, monospace;",
				"'Courier New', Courier, monospace" => "'Courier New', Courier, monospace",										
				"'Courier New', Courier, 'Monaco', 'Lucida Console'"=> "'Courier New', Courier, 'Monaco', 'Lucida Console'", 										
				"Garamond, serif" => "Garamond, serif",
				"Georgia" => "Georgia", 
				"Georgia, serif" => "Georgia, serif",	
				"Georgia, Times, 'Times New Roman', serif" => "Georgia, Times, 'Times New Roman', serif",		
				"Georgia,'Bitstream Vera Serif','Times New Roman',serif" => "Georgia, 'Bitstream Vera Serif','Times New Roman',serif",
				"'Helvetica'" =>"Helvetica",
				"'Helvetica','Arial'" =>"'Helvetica', 'Arial'",								
				"'Helvetica Neue',Helvetica,Arial,sans-seri" =>"'Helvetica Neue', Helvetica,Arial, sans-seri",					
				"Impact, Charcoal, sans-serif" => "Impact, Charcoal, sans-serif",
				"'Lucida Console', Monaco, monospace" => "'Lucida Console', Monaco, monospace",
				"'Lucida Sans Unicode', 'Lucida Grande', sans-serif" =>"'Lucida Sans Unicode', 'Lucida Grande', sans-serif",	
				"'Lucida Grande', 'Lucida Sans', Arial, sans-serif" =>"'Lucida Grande', 'Lucida Sans', Arial, sans-serif",
				"'MS Sans Serif', Geneva, sans-serif" =>"'MS Sans Serif', Geneva, sans-serif",		
				"'MS Serif', 'New York', sans-serif" =>"'MS Serif', 'New York', sans-serif",
				"'Palatino Linotype', 'Book Antiqua', Palatino, serif" =>"'Palatino Linotype', 'Book Antiqua', Palatino, serif",
				"Symbol, sans-serif" =>"Symbol, sans-serif",										
				"Tahoma, Geneva, sans-serif" =>"Tahoma, Geneva, sans-serif;",
				"'Times New Roman', Times, serif" =>"'Times New Roman', Times, serif",										
				"'Trebuchet MS', Helvetica, sans-serif" =>"'Trebuchet MS', Helvetica, sans-serif",
				"Verdana, Geneva, sans-serif" =>"Verdana, Geneva, sans-serif",				
				"Webdings, sans-serif" =>"Webdings, sans-serif",
				"Wingdings, 'Zapf Dingbats', sans-serif" =>"Wingdings, 'Zapf Dingbats', sans-serif",
				);


		/* = Font Weight Options 
		-------------------------------------------------------------- */	
		static $font_weight_options = 
			array(
				''		 =>' --- ',
				'normal' =>'Normal',
				'bold'	 =>'Bold',
				'bolder' =>'Bolder',
				'lighter'=>'Lighter',	
				'100'    =>'100',
				'200'    =>'200',
				'300'    =>'300',
				'400'    =>'400',
				'500'	 =>'500',
				'600'	 =>'600',
				'700'	 =>'700',
				'800'	 =>'800',
				'900'	 =>'900',		
				);	



		/* = Decoration Options   
		-------------------------------------------------------------- */	
		static $decoration_options = 
			array(	
				''				=>' --- ',
				'none'			=>'None',
				'overline'		=>'Overline',
				'line-through'	=>'Line Through',
				'underline'		=>'Underline',	
				'blink'			=>'Blink',	
				);	

		/* = bg_image_repeat    
		-------------------------------------------------------------- */			
		static $bg_image_repeat = 
			array(
				'no-repeat' => 'No Repeat',
				'repeat'	=> 'Tile',
				'repeat-x'  => 'Tile Horizontally',
				'repeat-y'  => 'Tile Vertically'
				);

		/* = bg_image_position    
		-------------------------------------------------------------- */		
		static $bg_image_position = 
			array(
				'left top'      => 'Left Top',
				'left center'   => 'Left Center',
				'left bottom'   => 'Left Bottom',
				'right top'	    => 'Right Top',
				'right center'  => 'Right Center',
				'right bottom'  => 'Right Bottom',
				'center top'    => 'Center Top',
				'center center' => 'Center Center',
				'center bottom'	=> 'Center Bottom'
				);

		/* = bg_image_attachment    
		-------------------------------------------------------------- */		
		static $bg_image_attachment = 
			array(
				'scroll' => 'Scroll',
				'fixed'  => 'Fixed'
				);


		static $border_style = 
			array(
				'solid' 	   => 'Solid',
				'dotted'	   => 'Dotted',
				'double'	   => 'Double',
				'dashed'	   => 'Dashed',
				'groove'	   => 'Groove',
				'ridge'		   => 'Ridge',
				'inset'		   => 'Inset',
				'outset'	   => 'Outset',
				'dotted solid' => 'Dotted Solid',
				'hidden'       => 'Hidden',
				'none'         => 'None',														
				);
	
		static $transition_property = array(
			'all'				=> 'All',
			'background-color'	=>'background-color',
			'color'				=>'color',
			'padding'			=>'padding',
			'margin'			=>'margin',
			'width'				=>'width',
			'height' 			=>'height',
			'border'			=>'border',
			'background' 		=>'background',
			'border-radius'		=>'border-radius',
			'box-shadow'		=>'box-shadow',
			'transform'			=>'transform',
			'decoration'		=>'decoration',
		

		);
	
		static $transition_timing_function = array(

			 'ease' 		=>'ease',
			 'linear' 		=>'linear',
			 'ease-in' 		=>'ease-in',
			 'ease-out' 	=>'ease-out',
			 'ease-in-out' 	=>'ease-in-out',
			 'step-start' 	=>'step-start',
			 'step-end' 	=> 'step-end',
	
		);
		
 		//transform
		static $transform = array(

			 'translate' 	=>'translate exp(50px, 100px)',
			 'rotate' 		=>'rotate exp(20deg)',
			 'scale' 		=>'scale exp(2, 3) or (0.5, 0.5)',
			 'skewX' 		=>'skewX exp(20deg);',
			 'skewY' 		=>'skewY exp(20deg)',
			 'skew' 		=>'skew exp(20deg, 10deg) or (20deg)',
			 'matrix' 		=> 'matrix(1, -0.3, 0, 1, 0, 0)',

		);


		var $slug = 'wp_css_generator_css_';
		var $config;
	
		
		function __construct(){
			
			//get plugin config
			$this->config = Config::getInstance();
			
			//customize_register
			add_action('customize_register', array(&$this, 'register_customize_style_setting'));
		}	
	

		/**
		 * 	Set style options in customize 
		 *
		 *  
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 * @var $wp_customize 
		 *
		 **/
		
		function set_customize_style(){

			$config = Config::getInstance();
	
			$terms = get_terms( array(
				    'taxonomy'   => 'wpcss-group',
				    'hide_empty' => false,
					'orderby'    => 'meta_value_num',
					'meta_key'	 => $this->config->slug.'priority',
					'order' 	 => 'ASC',
				) );
			
			
			foreach ($terms as $key => $term) :
				
				$status = get_term_meta( $term->term_id, $this->config->slug.'status', true );
				
				if($term->parent=="0" and $status=="on") :
	
					//get ganels set slug	
					$panel_ID = $term->slug;
					
					$priority = get_term_meta( $term->term_id, $this->config->slug.'priority', true );
					
					$position  = 1000 - $priority ;
					
					$panels[$panel_ID] = 
							array(
								'id'		=> $term->term_id,
						  		'title' 	=> 'CSS: '.$term->name,
			              		'priority' 	=> 	- $position ,
			              		'panel' 	=> $term->slug,
		            
						);

				else:
					//get term meta
					$metas = get_term_meta( $term->term_id, $config->slug.'css_options', true );
				
					if(	$metas ) :
						//set fileds
						foreach ($metas as $key => $meta) {
					
							if($meta=='yes') :
								$fileds[$term->term_id][] = array(
									'type'	=> $key,
									'class'	=> $term->slug,
									'desc'	=> $term->description,
								);
							endif;
						}
				
					endif;
				
				
					//if have fileds
					if ( isset( $fileds[$term->term_id] ) ) :
						//get sections set parent id
						$sections[$term->parent][] = 
							array(						
								   'title' 	  => $term->name,
	                               'section'  => $term->slug,
	                               'priority' => '1' ,
	                               'fileds'   => isset($fileds[$term->term_id]) ? $fileds[$term->term_id] : NULL 
						
					
							);
					endif;	
					endif;	

			
			endforeach;	
			
			foreach ($panels as $key => $panel) :

				$all_panel[ $key ] = $panel;
				if ( isset( $sections[ $panel['id'] ] ) ) :
					$all_panel[ $key ]['sections'] = $sections[ $panel['id'] ];
				endif;
			endforeach;	
			
				
			$setting = array( 

						'setting' => 
							array(
				                'title' 	=> 'CSS: Settings',
				                'priority' 	=> '-1000',
				                'panel' 	=> 'setting',
				                'sections' 	=> array(
					
				                        '0' => array(
				                                'title' => 'Gogole Fonts',
				                                'section' => 'setting_google_fonts',
				                                'priority' => '1',
				                                'fileds' => array(
				                                        '0' => array(
				                                                'type'  => 'setting_google_fonts',
				                                                'class' => 'setting_google_fonts',
				                                                'label' => '',
				                                                'desc'  => '',
				                                            )

				                                    ),

				                            ),
				

				                    ),

				            ),
				);
			$all_panel   = array_merge($all_panel, 	$setting);

			$this->customize_style  = $all_panel;


		}

		/**
		 * 	Render customize options from parts arrays 
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 * @var $wp_customize 
		 *
		 **/
		function register_customize_style_setting($wp_customize){

			$this->set_customize_style();

			$panels = $this->customize_style;

			foreach ($this->customize_style as $panel_key => $panel) :
			
				$Panel_ID = str_replace( "-","_", sanitize_title(  $this->slug.$panel_key ) );
			
				//add panel
				$this->add_panel($wp_customize, $Panel_ID, $panel['title'], 'sss', $panel['priority']  );

					if ( !empty( $panel['sections'] ) ) :
					foreach ($panel['sections']  as $section_key => $section) :

						$priority = !empty( $section['priority'] ) ?  $section['priority'] : 10; 

						$Section_ID = 	str_replace( "-","_", sanitize_title( $this->slug.$section['section'] ) );

						//add section
						$this->add_section($wp_customize, $Section_ID, $section['title'], '', $Panel_ID, $priority, $section['fileds'][0]['desc']);	

						if(!empty($section['fileds'])) :
							foreach ($section['fileds']  as $filed_key => $filed) :
								
							/*	print_r($filed);*/
								$type 	 = !empty( $filed['type'] ) 	? $filed['type'] 	: 'text';			
								$class 	 = !empty( $filed['class'] ) 	? $filed['class'] 	: '';					
								$label 	 = !empty( $filed['label'] ) 	? $filed['label'] 	: '';	
								$desc 	 = !empty( $filed['desc'] ) 	? $filed['desc'] 	: '';	

								//add filed		
								if ( method_exists($this, $type) ):
									$this->{$type}($wp_customize, $class , $Section_ID, $label, $desc="" );
								endif;

							endforeach;
						endif;
						
					  endforeach;
					  endif;
			endforeach;
			
	

		}
		
		
		/* = add panel 
		-------------------------------------------------------------- */
	    private function add_panel($wp_customize, $id, $title = "Theme Options", $description="", $priority = 10, $capability = "edit_theme_options", $theme_supports = ''){

			$wp_customize->add_panel( 
				str_replace( "-","_", sanitize_title( $id ) ), 
				array(
			   	 	'priority'       => $priority,
				    'capability'     => 'edit_theme_options',
				    'theme_supports' => '',
				    'title'          => $title,
				    'description'    => '',
				) 
			);

		}


		/* = add_section 
		-------------------------------------------------------------- */
		private function add_section($wp_customize, $id = "setting", $title = "Setting", $description = '', $panel = 'Theme Options', $priority = 10, $desc=""){

		   $wp_customize->add_section(
				str_replace( "-","_", sanitize_title( $id ) ), 
				array(
		     	  'title'    	=> $title, 
			      'priority' 	=> $priority,
				  'description'	=> $desc,
				  'panel'  		=> 	str_replace( "-","_", sanitize_title( $panel ) ),
		   		));


		}
		
		
	
		
		/**
		 * 	Display
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 * @var $wp_customize 
		 *
		 **/
		static function display($wp_customize, $class, $section, $label = '', $desc = ""){


			/* = Display Family 
			-------------------------------------------------------------- */		
			$wp_customize->add_setting(
				$section.'['.$class.'=display]', 
				array(
		 		   'default'        	   => '',
			       'capability'     	   => 'edit_theme_options',
			       'type'           	   => 'option',
				   'transport'   		   => 'refresh',
				   'sanitize_callback' 	   => '',
				   'sanitize_js_callback'  => ''
			   	   ));

			$wp_customize->add_control( 
				$class.'=display', 
				array(
		   		 	'label'   	  => 'Display', 
				    'section' 	  => $section,
				    'settings'    => $section.'['.$class.'=display]',
				    'type'        => 'select',
					'choices'     => self::$display,
					'description' => $desc,	
					));
	
		

		}
		
		
		

		/**
		 * 	Color for class
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 * @var $wp_customize 
		 *
		 **/
		private function color($wp_customize, $class, $section, $label = '', $desc = "Color"){


		
			/* =  p
			-------------------------------------------------------------- */
			$wp_customize->add_setting(
				$section.'['.$class.'=color]', 
				array(
			 	  'default'        		  => '',
			       'capability'     	  => 'edit_theme_options',
			       'type'           	  => 'option',
				   'transport'   		  => 'refresh', 
				   'sanitize_callback' 	  => '',
				   'sanitize_js_callback' => ''
					));

			$wp_customize->add_control(	new WP_Customize_Color_Control( 
				$wp_customize,
				$class.'=color', 
				array(
			 	    'label'   	  => "Color" ,
				    'section' 	  => $section,
				    'settings'    => $section.'['.$class.'=color]',
				    'type'        => 'color',
				    'description' => $desc ,
					)));
	
			}
		
		/**
		 * 	font_family
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 * @var $wp_customize 
		 *
		 **/
		private function font_family($wp_customize, $class, $section, $label = '', $desc = ''){



			/* = Font Family 
			-------------------------------------------------------------- */		
			$wp_customize->add_setting(
				$section.'['.$class.'=font_family]', 
				array(
		 		   'default'        	   => '',
			       'capability'     	   => 'edit_theme_options',
			       'type'           	   => 'option',
				   'transport'   		   => 'refresh',
				   'sanitize_callback' 	   => '',
				   'sanitize_js_callback'  => ''
			   	   ));

			$wp_customize->add_control( 
				$class.'=font_family', 
				array(
		   		 	'label'   	  => 'Font Family', 
				    'section' 	  => $section,
				    'settings'    => $section.'['.$class.'=font_family]',
				    'type'        => 'text',
				/*	'choices'     =>  $this->standard_fonts ,*/
					'description' => $desc,	
					'input_attrs' => 
							array(
						        'placeholder' => '"Arial", "Helvetica", sans-serif',

							)	
					));	

			/* = Font Weight
			-------------------------------------------------------------- */
			$wp_customize->add_setting(
				$section.'['.$class.'=font_weight]', 
				array(
			  	   'default'        	  => '',
			       'capability'     	  => 'edit_theme_options',
			       'type'                 => 'option',
				   'transport'   	      => 'refresh', 
				   'sanitize_callback' 	  => '',
				   'sanitize_js_callback' => '',
					));

			$wp_customize->add_control(
				$class.'=font_weight', 
				array(
			   	 	'label'   	=> '',
				    'section' 	=> $section,
				    'settings'  => $section.'['.$class.'=font_weight]',
				    'type'      => 'select',
					'choices'   =>  self::$font_weight_options,
					));


			

		}
		

		/**
		 * 	Background image
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 * @var $wp_customize 
		 *
		 **/
		private function background($wp_customize, $class, $section, $label = '', $desc = ""){
		

			/* = Bg Color 
			-------------------------------------------------------------- */		
			$wp_customize->add_setting(
				$section.'['.$class.'=bg_color]', 
				array(
				   'default'        	   => '',
			       'capability'     	   => 'edit_theme_options',
			       'type'           	   => 'option',
				   'transport'   		   => 'refresh',
				   'sanitize_callback' 	   => '',
				   'sanitize_js_callback'  => ''
			   	   ));

			$wp_customize->add_control(new WP_Customize_Color_Control( 
				$wp_customize,
				$class.'=bg_color', 
				array(
				 	'label'   	  => 'Background',
				    'section' 	  => $section,
				    'settings'    => $section.'['. $class .'=bg_color]',
				    'type'        => 'color',
					'description' => $desc, 	
					)));


			/* = Gradient bottom
			-------------------------------------------------------------- */		
			$wp_customize->add_setting(
				$section.'['.$class.'=bg_color2]', 
				array(
				   'default'        	   => '',
			       'capability'     	   => 'edit_theme_options',
			       'type'           	   => 'option',
				   'transport'   		   => 'refresh',
				   'sanitize_callback' 	   => '',
				   'sanitize_js_callback'  => ''
			   	   ));

			$wp_customize->add_control( new WP_Customize_Color_Control( 
				$wp_customize,
				$class.'=bg_color2', 
				array(
				 	'label'   	  => '',
				    'section' 	  => $section,
				    'settings'    => $section.'['.$class.'=bg_color2]',
				    'type'        => 'color',
					'description' => 'Gradient',	
					)));

			/* = Bg Image
			------------------------------------------------------------- */		
		
		   $wp_customize->add_setting(
		  		$section.'['.$class.'=bg_image]', 
		  		array(
		      	   'default'        	   => '',
		  	       'capability'     	   => 'edit_theme_options',
		  	       'type'           	   => 'option',
		  		   'transport'   		   => 'refresh',
		  		   'sanitize_callback' 	   => '',
		  		   'sanitize_js_callback'  => ''
		  	   	   ));


		  	$wp_customize->add_control( new WP_Customize_Image_Control( 	
		  		$wp_customize, 
		  		$class.'=bg_image', 
		  		array(
		  		    'section' 	  => $section,
		  		    'settings'    => $section.'['.$class.'=bg_image]',
		  		    'type'        => 'image',
		  			)));			
		


			/* = bg_image_position 
			-------------------------------------------------------------- */		
		
		    $wp_customize->add_setting(
		   		$section.'['.$class.'=bg_image_position]', 
		   		array(
		       	   'default'        	   => 'left top',
		   	       'capability'     	   => 'edit_theme_options',
		   	       'type'           	   => 'option',
		   		   'transport'   		   => 'refresh',
		   		   'sanitize_callback' 	   => '',
		   		   'sanitize_js_callback'  => ''
		   	   	   ));

		   	$wp_customize->add_control( 
		   		$section.'['.$class.'=bg_image_position]', 
		   		array(
		      		'label'   	  => '',
		   		    'section' 	  => $section,
		   		    'settings'    => $section.'['.$class.'=bg_image_position]',
		   		    'type'        => 'select',
		   			'choices'     => self::$bg_image_position ,
		   			'description' => '', 
		   			));


			/* = bg_image_repeat 
			-------------------------------------------------------------- */		
		  
		    $wp_customize->add_setting(
		  		$section.'['.$class.'=bg_image_repeat]', 
		  		array(
		      	   'default'        	   => 'no-repeat',
		  	       'capability'     	   => 'edit_theme_options',
		  	       'type'           	   => 'option',
		  		   'transport'   		   => 'refresh',
		  		   'sanitize_callback' 	   => '',
		  		   'sanitize_js_callback'  => ''
		  	   	   ));

		  	$wp_customize->add_control( 
		  		$section.'['.$class.'=bg_image_repeat]', 
		  		array(
		     		'label'   	  => '',
		  		    'section' 	  => $section,
		  		    'settings'    => $section.'['.$class.'=bg_image_repeat]',
		  		    'type'        => 'select',
		  			'choices'     =>  self::$bg_image_repeat ,
		  			'description' => ''	
		  			));


			/* = bg_image_attachment 
			------------------------------------------------------------- */		
		 
		    $wp_customize->add_setting(
		   		$section.'['.$class.'=bg_image_attachment]', 
		   		array(
		       	   'default'        	   => 'scroll',
		   	       'capability'     	   => 'edit_theme_options',
		   	       'type'           	   => 'option',
		   		   'transport'   		   => 'refresh',
		   		   'sanitize_callback' 	   => '',
		   		   'sanitize_js_callback'  => ''
		   	   	   ));

		   	$wp_customize->add_control( 
		   		$section . '[' . $class . '=bg_image_attachment]', 
		   		array(
		      		'label'   	  => '',
		   		    'section' 	  => $section,
		   		    'settings'    => $section .'['. $class .'=bg_image_attachment]',
		   		    'type'        => 'select',
		   			'choices'     =>  self::$bg_image_attachment,
		   			));		
		
	
		    $wp_customize->add_setting(
		   		$section.'['.$class.'=bg_image_size]', 
		   		array(
		       	   'default'        	   => 'cover',
		   	       'capability'     	   => 'edit_theme_options',
		   	       'type'           	   => 'option',
		   		   'transport'   		   => 'refresh',
		   		   'sanitize_callback' 	   => '',
		   		   'sanitize_js_callback'  => ''
		   	   	   ));

		   	$wp_customize->add_control( 
		   		$section . '[' . $class . '=bg_image_size]', 
		   		array(
		      			'label'   	  => '',
		   		    'section' 	  => $section,
		   		    'settings'    => $section .'['. $class .'=bg_image_size]',
		   		    'type'        => 'select',
		   			'choices'     =>  array('auto'=> 'Auto',  'cover'=>'Cover', 'contain'=>'Contain', 'initial'=>'Initial', '100% 100%'=>'100% 100%', '50%'=>'50%' ,'100% 100% cover'=>'100% 100% Cover')
						/*self::$bg_image_attachment,*/
		   			));		

		
		}
		

		//GOOGLE FONTS
		private function setting_google_fonts($wp_customize, $class, $section, $label = '', $desc = "" ){
			
			
				 	/* = Import 
					-------------------------------------------------------------- */		
				    $wp_customize->add_setting($section.'[google_fonts_import]', array(
				       'default'        	   => '',
				       'capability'     	   => 'edit_theme_options',
				       'type'           	   => 'option',
					   'transport'   		   => 'refresh', //postMessage, refresh
					   'sanitize_callback' 	   => '',
					   'sanitize_js_callback'  => ''
				   	   ));

					$wp_customize->add_control( 'google_fonts_import', array(
					    'label'   	  => 'Google Fonts (@import)',
					    'section' 	  => $section,
					    'settings'    => $section.'[google_fonts_import]',
					    'type'        => 'text',
						'description' => '',	
						));
				 		
			
		}
		

		
	}
				
?>