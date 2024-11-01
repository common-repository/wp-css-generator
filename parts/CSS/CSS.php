<?php

namespace WPCSSGenerator{
	
	use WPCSSGenerator;

   /**
    * CSS part class   
    *
    * @author Goran Petrovic
    * @since 1.0
    *
    **/
	class CSS extends Part{


		public $view 	 = 'css';
		public $template = 'css';
		public $path 	 = 'parts/CSS/';		
	
		
		function __construct(){
	
		}
		

		/**
		 * 	Create post type 
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 *
		 **/
		function post_type(){

			return	$this->post_type = 
						array(
							'id'		   => 'wpcss',
							'label'		   => 'CSS',
							'single-label' => 'CSS',
							'public'	   => true,
							'has_archive'  => true,
							'hierarchical' => 1,
							'supports'	   => array('title'), 

							'taxonomies'   =>
									array(

										array(
											'id'		   => 'wpcss-group',
											'label'		   => 'Groups', 
											'single-label' => 'Group',
											'hierarchical' => true,
										),
										

									),
							);



		}

		
		/**
		 * 	Set term options for categories and tags
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 *
		 **/
		function term_meta(){

			$options = array('magrins'=>'Margins');


			for ($i=1; $i < 999; $i++) { 
			
				$select_option[$i]= $i;
			}


			$fields[] = array(
					'name'	  => 'priority',
					'type'	  => 'text',
					'title'	  =>  'Priority',
					'choices' => 	$select_option,
					'default' => '1',
					'desc'	  => 'load order', 
					'attr'	  => array('style'=>'width:100px;')
				 );
												
	
			return $this->term_options = array(

											'term_type'	=> array('wpcss-group'),						
											'fileds' 	=>  $fields,

										);//panel  - Front Page

		}

		

	
	}

}
?>