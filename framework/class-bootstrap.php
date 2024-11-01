<?php


namespace WPCSSGenerator;
	
	class Bootstrap{
		
		public $config;
	
		function __construct(){

			global $config;
			$this->config = Config::getInstance();	

			//set parts
			$parts = $this->set_parts();

			//Post Types
			$PostTypes = new Post_Types( $parts );

			//Term Options 
			$TermMeta = new Term_Meta( $parts );

			//CSS 
			$CSS = new Customize_CSS();

			//add style and scripts
			add_action( 'wp_enqueue_scripts', array(&$this, 'scripts_and_styles' ) );
			
			//admin scripts ans styles	
			add_action( 'admin_enqueue_scripts', array(&$this, 'admin_scripts_and_styles' ) );	
		
			//setting link in plugins list
			add_filter( 'plugin_action_links_wp-css-generator/wp-css-generator.php', array(&$this, 'add_action_links' ) );
			
			//load_textdomain
			add_action( 'plugins_loaded', array(&$this, 'load_textdomain')  );
			
			//add wp head
			add_action( 'wp_head', array(&$this, 'style') );

			//add admin scripts
			add_action( 'admin_enqueue_scripts',  array( $this, 'admin_scripts_init' ) );
		 
			//add menu bar
			add_action( 'wp_before_admin_bar_render', array($this, 'wp_admin_bar'), 22 );
			
			//ajax admin delete sub term
			add_action( 'wp_ajax_delete_sub_term',  array($this, 'delete_sub_term' ) );
			
			//ajax admin delete parent term
			add_action( 'wp_ajax_delete_parent_term',  array($this, 'delete_parent_term' ) );
			

		}	
		
	
		/**
		 * Delete parent group 
		 *
		 */
		
		function delete_parent_term(){

			$term_id =  isset( $_POST['data']['term_id'] ) ? $_POST['data']['term_id'] : NULL ;
			if ( !empty( $term_id ) ) :

					
						$subGroups = get_terms( array(
							    'taxonomy' 	 => 'wpcss-group',
							    'hide_empty' => false,
								'parent' 	 => $term_id 
							) );
					
					//delete sub terms
					foreach ($subGroups as $subGroup) :
						
						wp_delete_term( $subGroup->term_id , 'wpcss-group' );
						
					endforeach;
					
					//delete group
			 		wp_delete_term( $term_id , 'wpcss-group' );
			

				$data['status'] = 'success';
			else:
				$data['status'] = 'error';
			endif;

	      	$data['msg'] 	= __('You have successfully delete group.' , 'wp-css-geneartor') ;
			echo json_encode($data);
			wp_die();			

		}
	
		
		/**
		 * Delete sub term
		 *
		 */
		function delete_sub_term(){

			$term_id =  isset( $_POST['data']['term_id'] ) ? $_POST['data']['term_id'] : NULL ;
			if ( !empty( $term_id ) ) :

			 	wp_delete_term( $term_id , 'wpcss-group' );
				$data['status'] = 'success';
			else:
				$data['status'] = 'error';
			endif;

	      	$data['msg'] 	= __('You have successfully delete selektor.' , 'wp-css-geneartor') ;
			echo json_encode($data);
			wp_die();			
		
		}
		
		
		
		/**
		 * Add menu bar
		 *
		 */		
		function wp_admin_bar() {
			global $wp_admin_bar;

			//Add a link called 'CSS Groups'...
			$wp_admin_bar->add_menu(array(
				'id'    => 'wp-css-generator',
				'title' => 'CSS Groups', 
				'href'  => admin_url('admin.php?page=wpcssgenerator')
			));

		
			$wp_admin_bar->add_menu( array(
				'id'    => 'wp-css-generator-add',
				'title' => 'Add Group',
				'href'  => admin_url('admin.php?page=wpcssgenerator-new'),
				'parent'=> 'wp-css-generator'
			));
			
			
			$wp_admin_bar->add_menu( array(
				'id'    => 'wp-css-generator-tools',
				'title' => 'Tools',
				'href'  => admin_url('admin.php?page=wpcssgenerator-tools'),
				'parent'=> 'wp-css-generator'
			));
		}
	
		/**
		 * Load admin init .js and .css files
		 *
		 */
		function admin_scripts_init(){
			
			$page = isset( $_GET['page'] ) ?  $_GET['page'] :  NULL;

			if ( $page == "wpcssgenerator-new" or $page == "wpcssgenerator") :
				wp_enqueue_script(
					'wp-css-generator-sortable',
					$this->config->URL.'js/jquery-sortable.js',
					array( 'jquery' )
				);
			endif;
			
			wp_enqueue_script(
				'wp-css-generator-admin-init',
				$this->config->URL.'js/admin_init.js',
				array( 'jquery' )
			);
			
		}
		


		/**
		 * Display style in heade
		 *
		 * @since 1.0.0
		 */
		function style(){
			echo '<style type="text/css">';
			Helper_CSS::generate_minify_CSS();
			echo '</style>';
		}
		
	
		/**
		 * Load plugin textdomain.
		 *
		 * @since 1.0.0
		 */
		function load_textdomain() {

		  load_plugin_textdomain( 'wp-css-generator', false,  $this->config->DIR .'/i18n' ); 
		}
	
	
		/**
		 * 	Setting link in plugins list
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 *
		 **/
		function add_action_links ( $links ) {
		 	$plugin_links = 
				array(
		 		'<a href="' . admin_url( 'admin.php?page=wpcssgenerator' ) . '">'. __('Settings','wp-css-generator') .'</a>',
		 		);
			return array_merge(  $plugin_links, $links );
		}
		
		
		/**
		 * 	Admin inlude style and scripts
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 *
		 **/
		function admin_scripts_and_styles(){
				
		 	$config = Config::getInstance();
		
			wp_enqueue_style( 'wp-css-generator-admin', $config->URL .'css/admin-style.css' );
		
			wp_enqueue_style( 'wp-css-generator-font-awesome', $config->URL .'css/font-awesome/css/font-awesome.min.css' );
	
		}
	
	


		/**
		 * 	global inlude style and scripts
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 *
		 **/			
		function scripts_and_styles(){

			if ( is_admin() ) :		
				wp_enqueue_style( 'wp-css-generator-admin', $this->config->URL.'css/admin-style.css' );
			endif;

		}
		
		/**
		 * 	Set all parts in to $this->part name  PARTS
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 *
		 **/
		 private function set_parts(){
			
			
			//register all parts like part name == class name	
			foreach (glob( 	$this->config->DIR .'/parts/*', GLOB_ONLYDIR ) as $paths) :

				$dir = explode('/', $paths);	
				$part =  end($dir);

				$this->parts[] = $part;

				//namespace class name, replace the ' fix 
				$class_name = str_replace("'",'',$this->config->namespace."\'".$part);
				
				$this->{$part}  = new $class_name($this);	

				$parts[$part]  	=  $this->{$part};	

			endforeach;
			
			
			return 	$parts;
			
		}
			
		
		/**
		 * 	Set all parts in to $this->part name 
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 *
		 **/
		 function register_widgets(){

			//register all parts like part name == class name	
			foreach (glob( $this->config->DIR.'/widgets/*', GLOB_ONLYDIR ) as $paths) :

				$dir = explode('/', $paths);	
				$widget =  end($dir);

				$this->widegt[] = $widget;

				//class name
				$widget_class_name = $this->config->namespace.'_'.$widget.'_Widget';

					if ( !is_blog_installed() )
							return;

					register_widget( $widget_class_name ) ;	

			endforeach;			

		}

		


	}
	
?>