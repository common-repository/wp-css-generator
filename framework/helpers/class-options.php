<?php

namespace WPCSSGenerator;
	

			
	   class WP_CSS_Generator_Options{

		public $config;
				
        public function __construct(){
	
            // register actions
            add_action('admin_init', array(&$this, 'admin_init'));
            add_action('admin_menu', array(&$this, 'add_menu'));
			
			//get confing
			$this->config  =  Config::getInstance();

        } 


		public function set_default_values(){
			
			//chech first_activation 
			$first_activation = get_option('wp_css_generator_first_activation', 'no');
		
			//check body group
			$get_body_group =  get_term_by( 'slug', 'body', 'wpcss-group');
				
			if ( $first_activation!='yes' and empty( $get_body_group ) ) :
			
		  		$default_values = array(
      
		  		    'wcg' => array(
		  		            'group_name' => 'Body',
		  		            'group_slug' => 'body',
		  		            'group_priority' =>'1',
		  		            'title' => array(
		  		                    '0' => 'Background',
		  		                    '1' => 'Text',
		  		                    '2' => 'Hedings',
		  		                    '3' => 'Link',
		  		                    '4' => 'Link Hover',
		  		                ),
      
	  		         
		  		            'slug' => array(
		  		                    '0' => '',
		  		                    '1' => '',
		  		                    '2' => '',
		  		                    '3' => '',
		  		                    '4' => '',
		  		                ),
      
      
		  		            'selector' => array(
		  		                    '0' => 'body',
		  		                    '1' => 'body p, p',
		  		                    '2' => 'h1, h2, h3, h4, h5, h6',
		  		                    '3' => 'a',
		  		                    '4' => 'a:hover',
		  		                ),
      
		  		            'options' => array(
      
		  		                    'color' => array(
		  		                            '0' => 'no',
		  		                            '1' => 'yes',
		  		                            '2' => 'yes',
		  		                            '3' => 'yes',
		  		                            '4' => 'yes',
		  		                        ),
      
		  		                    'font_family' => array(
		  		                            '0' => 'no',
		  		                            '1' => 'yes',
		  		                            '2' => 'yes',
		  		                            '3' => 'no',
		  		                            '4' => 'no',
		  		                        ),
      
		  		                    'background' => array(
		  		                            '0' => 'yes',
		  		                            '1' => 'no',
		  		                            '2' => 'no',
		  		                            '3' => 'no',
		  		                            '4' => 'no',
		  		                        ),
      
		  		                ),
      
		  		            'important' => array(
		  		                    '0' => '',
		  		                    '1' => '',
		  		                    '2' => '',
		  		                    '3' => '',
		  		                    '4' => '',
		  		                ),
      
		  		            'group_desc' => '',
		  		        ),
      
      
		  		);
	  	
			
				$group_name  = !empty( $default_values['wcg']['group_name'] ) ?  $default_values['wcg']['group_name']   : NULL ;	
				$group_slug  = !empty( $default_values['wcg']['group_slug'] ) ?  $default_values['wcg']['group_slug']   : NULL ;	  
				$group_desc  = !empty( $default_values['wcg']['group_desc'] ) ?  $default_values['wcg']['group_desc']   : NULL ;	
				$group_priority = !empty( $default_values['wcg']['group_priority'] ) ?  $default_values['wcg']['group_priority']   : '100' ;	 	
				//save group

				$group_id  = $this->save_css_group( $group_name, $group_slug, $group_desc, $group_priority );

				$subGroup_titles  	 =  $default_values['wcg']['title'];
				$subGroup_slugs  	 =  $default_values['wcg']['slug'];
				$subGroup_selectors  =  $default_values['wcg']['selector'];
			 	$subGroup_css 	     =  $default_values['wcg']['options'];
				$subGroup_important  =  $default_values['wcg']['important'];


				//save sub groups
				$i = 0; 
				foreach ($subGroup_titles as $key => $title) :

					//define name
					$name = !empty( $title ) ? $title : $subGroup_selectors[ $i ];

					//insert sub group
					if ( !empty( $name ) ) :
						$this->save_css_subgroup( 
													$name, 
													$subGroup_slugs[ $i ], 
													$subGroup_selectors[ $i ],
													$group_id, 	
													$subGroup_css,
													$subGroup_important[ $i ], 
													$i
												);
					endif;

				$i++;
				endforeach;
				
				//update option 
				update_option('wp_css_generator_first_activation', 'yes');
			endif;
			
			
		}



		/**
		 * Options init
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 *
		 **/
        public function admin_init(){
	
			if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
		
			$page = isset( $_GET['page'] ) ? $_GET['page']  :  NULL;

			//display views
			if(	$page == "wpcssgenerator") :
					
           		//save groups
				if ( $_POST ) :
				
					//security check
					check_ajax_referer('group_action','security');
						
					$groups = $_POST['wcg']['group_id'];
					$groups_status = $_POST['wcg']['switch'];

					$customize = isset( $_POST['wcg']['customize'] ) ? 'yes' : "";


					$i = 1;
					foreach ($groups as $key => $group_id) :

						//define status
						$status  = isset( $groups_status[ $group_id ] ) ?  $groups_status[ $group_id ] : 'off';

						//status
						update_term_meta($group_id, $this->config->slug.'status', $status);

						//update priority
						update_term_meta($group_id, $this->config->slug.'priority', $i);

					$i++;	
					endforeach;

					//redirect to customize after save
					if ( $customize == "yes" ) :

						$url = admin_url().'customize.php';
						wp_safe_redirect( 	$url  );

					endif;	

				endif;
			
			elseif( $page == "wpcssgenerator-new" )	:
				
				if ( $_POST ) :
					
					//security check
					check_ajax_referer('group_add_action','security');
						
					$group_name  = !empty( $_POST['wcg']['group_name'] ) ?  $_POST['wcg']['group_name']   : NULL ;	
					$group_slug  = !empty( $_POST['wcg']['group_slug'] ) ?  $_POST['wcg']['group_slug']   : NULL ;	  
					$group_desc  = !empty( $_POST['wcg']['group_desc'] ) ?  $_POST['wcg']['group_desc']   : NULL ;	
					$group_priority = !empty( $_POST['wcg']['group_priority'] ) ?  $_POST['wcg']['group_priority']   : '100' ;	 	
					//save group
					$group_id  = $this->save_css_group( $group_name, $group_slug, $group_desc, $group_priority );

					$subGroup_titles  	 =  !empty( $_POST['wcg']['title'] ) ?   $_POST['wcg']['title']   : NULL;
					$subGroup_slugs  	 =  !empty( $_POST['wcg']['slug'] ) ?   $_POST['wcg']['slug']   : NULL;
					$subGroup_selectors  =  !empty( $_POST['wcg']['selector'] ) ?   $_POST['wcg']['selector']   : NULL; 
				 	$subGroup_css 	     =  !empty( $_POST['wcg']['options'] ) ?   $_POST['wcg']['options']   : NULL; 
					$subGroup_important  =  !empty( $_POST['wcg']['important'] ) ?   $_POST['wcg']['important']   : NULL; 


					//save sub groups
					if(!empty($subGroup_titles[0])) :
					$i = 0; 
					foreach ($subGroup_titles as $key => $title) :

						//define name
						$name = !empty( $title ) ? $title : $subGroup_selectors[ $i ];

						//insert sub group
						if ( !empty( $name ) ) :
							$this->save_css_subgroup( 
														$name, 
														$subGroup_slugs[ $i ], 
														$subGroup_selectors[ $i ],
														$group_id, 	
														$subGroup_css,
														$subGroup_important[ $i ], 
														$i
													);
						endif;

					$i++;
					endforeach;
					endif;
					//redirect after save
				    wp_redirect(admin_url('admin.php?page=wpcssgenerator&group_id='.$group_id));
			   endif;
				
			endif;
        }


		/**
		 * Add admin menu 
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 *
		 **/
        public function add_menu(){
		 	$config   = Config::getInstance();	

            // Add a page to manage this plugin's settings
		 	add_menu_page('WP CSS Generator', 'CSS Groups', 'manage_options', 'wpcssgenerator', array(&$this, 'plugin_settings_page'), 	$config->URL.'/img/icon.png');

			add_submenu_page('wpcssgenerator', 'Add New', 'Add New', 'manage_options', 'wpcssgenerator-new', array(&$this, 'plugin_settings_page'));
			
			add_submenu_page('wpcssgenerator', 'Tools', 'Tools', 'manage_options', 'wpcssgenerator-tools', array(&$this, 'plugin_settings_page'));
			
			
        } 
 
		/**
		 * Menu Callback 
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 *
		 **/
        public function plugin_settings_page(){
	
            if ( !current_user_can('manage_options') ){
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

			$page = $_GET['page'];

			//display views
			if(	$page == "wpcssgenerator") :

				$this->set_default_values();

			 	$this->view_groups();

			elseif(	$page == "wpcssgenerator-tools") :
				
				$this->view_tools();
				
			else:

				$this->view_add_new();
					
			endif;
        } 


		

	/**
	 * Save parent group in taxonomy 
	 *
	 * @author Goran Petrovic
	 * @since 1.0
	 *
	 **/
	function save_css_group($name,  $slug = NULL, $desc = "", $priority = 100 ){
		
			$name   = !empty( $name ) ? $name : __('No name', 'wp-css-generator');
			$slug 	= !empty( $slug ) ? $slug : NULL;	
			$desc   = !empty( $desc ) ? $desc : NULL;
	
			//insert new group
			if ( empty( $id ) ) :
				
				 $term_exists = term_exists($name,  'wpcss-group');
				
				//term_exists
				if ( $term_exists !== 0 && $term_exists !== null ) :
				
					$term = wp_update_term( $term_exists['term_id'], 'wpcss-group', 
								array(
								  'name' 		=> $name,
								  'slug' 		=> $name,
								  'description'	=> $desc,
								));
							
					//set term id
					$term_id = 	$term_exists['term_id'];

				else:

					//instert new
					$term = wp_insert_term($name, 'wpcss-group',
						 	 	array(
								  'description'=> $desc,
								   	/*'slug' => 'apple',*/
									/*'parent'=> $perent*/
								  ));
								
								
								
					//set term id		
					$term_id =	$term['term_id'];	
						
				endif;	
							
			//update group
			else:
				
				//get existing term
				$the_term = get_term_by( 'slug', $slug, 'wpcss-group', OBJECT);
				
				$term = wp_update_term( $the_term->term_id, 'wpcss-group', 
							array(
							  'name' 		=> $name,
							  'description'	=> $desc,
							));
							
					//set term id	
					$term_id =	$term['term_id'];	
			endif;
			
			//update priority
			update_term_meta($term_id, $this->config->slug.'priority', $priority);
			
			//status
			update_term_meta($term_id, $this->config->slug.'status', 'on');
			
			return 	$term_id;
		}

		
		/**
		 * Save sub group in taxonomy 
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 *
		 **/
		function save_css_subgroup($name,  $slug = "", $desc = "" , $perent = null, $css = array(), $important = 'no', $i){

				$name   = !empty( $name ) ? $name : __('No name', 'wp-css-generator');
				$slug 	= !empty( $slug ) ? $slug : NULL;
				$desc   = !empty( $desc ) ? $desc : NULL;				
					
				if ( empty( $slug ) ) :
					
					//inster new term
					$term =  wp_insert_term( $name, 	  // the term 
											 'wpcss-group', // the taxonomy
											 	 array(
												    'description'=>  $desc,
												     /*'slug' => 'apple',*/
											    	'parent'=> $perent
												  )
											);
						$term_id =	$term['term_id'];
				else:
					
					//get existing term
					$the_term   = get_term_by( 'slug', $slug, 'wpcss-group', OBJECT);

					//update
					$term = wp_update_term( $the_term->term_id, 'wpcss-group', 
								array(
								  'name' 		=> $name,
								  'description' => $desc,
								));

						
					$term_id =	$term['term_id'];
				endif;
				
				//if have css options
				if ( !empty( $css ) ) :
					foreach ($css as $key => $option) :

						update_term_meta($term_id, 	$this->config->slug.$key, $option[$i]);
					
						$all_options[$key] = $option[$i];
					endforeach;
				
					//update all options
					update_term_meta($term_id, 	$this->config->slug.'css_options', $all_options);
				endif;
				
				if ( !empty( $important) ) :
					//update postion 
					update_term_meta($term_id, 	$this->config->slug.'important', $important);
				endif;
				
				//update postion 
				update_term_meta($term_id, 	$this->config->slug.'priority', $i);
					
				return $term_id;


			}	
			
		
		/**
		 * View add new group and sub groups
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 *
		 **/
		public function save_add_new(){
		
			if ( $_POST ) :

				$group_name  = !empty( $_POST['wcg']['group_name'] ) ?  $_POST['wcg']['group_name']   : NULL ;	
				$group_slug  = !empty( $_POST['wcg']['group_slug'] ) ?  $_POST['wcg']['group_slug']   : NULL ;	  
				$group_desc  = !empty( $_POST['wcg']['group_desc'] ) ?  $_POST['wcg']['group_desc']   : NULL ;	
				$group_priority = !empty( $_POST['wcg']['group_priority'] ) ?  $_POST['wcg']['group_priority']   : '100' ;	 	
				//save group
				$group_id  = $this->save_css_group( $group_name, $group_slug, $group_desc, $group_priority );

				$subGroup_titles  	 =  $_POST['wcg']['title'];
				$subGroup_slugs  	 =  $_POST['wcg']['slug'];
				$subGroup_selectors  =  $_POST['wcg']['selector'];
			 	$subGroup_css 	     =  $_POST['wcg']['options'];
				$subGroup_important  =  $_POST['wcg']['important'];
				
				
				//save sub groups
				$i = 0; 
				foreach ($subGroup_titles as $key => $title) :
					
					//define name
					$name = !empty( $title ) ? $title : $subGroup_selectors[ $i ];
					
					//insert sub group
					if ( !empty( $name ) ) :
						$this->save_css_subgroup( 
													$name, 
													$subGroup_slugs[ $i ], 
													$subGroup_selectors[ $i ],
													$group_id, 	
													$subGroup_css,
													$subGroup_important[ $i ], 
													$i
												);
					endif;
					
				$i++;
				endforeach;
		
			
				//redirect after save
			 	wp_safe_redirect(admin_url('admin.php?page=wpcssgenerator&group_id='.$group_id));
		   endif;
		
				
		}
		
		
		/**
		 * View groups
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 *
		 **/
		public function view_groups(){ 	
			
			//get config
			$config  =  Config::getInstance();	?>
	
				<div class="wrap">
					<h1 class="wp-heading-inline"><?php _e('CSS Groups', 'wp-css-generator') ?></h1>
					<hr class="wp-header-end">

				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-2">

						<!-- Content -->
						<div id="post-body-content">
								
							<!--Options Wrap-->	
							<div class="wp_css_generator wcg-options">

								<!-- Header -->
								<div class="wp_css_generator wcg-option-header">
									<div class="wp_css_generator wcg-option-item-header-label"><strong><?php _e('Order', 'wp-css-generator') ?></strong></div>
									<div class="wp_css_generator wcg-option-item-header-label"><strong><?php _e('Group', 'wp-css-generator') ?></strong></div>									
									<div class="wp_css_generator wcg-option-item-header-label"><strong><?php _e('Status', 'wp-css-generator') ?></strong></div>
									<div class="wp_css_generator wcg-option-item-header-label"><strong><?php _e('Viewport', 'wp-css-generator') ?></strong></div>
								</div><!-- Header -->
								
								<!-- List of items -->
								<div class="wp_css_generator wcg-option-items">
									
									<form id="group_order_form" action="" method="POST" accept-charset="utf-8">
										
										<?php 
										//secure filed
										wp_nonce_field( 'group_action', 'security' );  ?>
										
										<!-- List of items -->
										<ul class="wp_css_generator wcg-option-items-list">
										
											<?php 
										
												//get term 		
												$groups = get_terms( array(
													    'taxonomy' => 'wpcss-group',
													    'hide_empty' => false,
														'orderby'   =>'meta_value_num',
														'meta_key'	=> $this->config->slug.'priority',
														'order' 	=>'ASC',													
														'parent' => 0
													) );
											
										
											foreach ( $groups as $group ) : 
											
												//priority
												$group_priority = get_term_meta($group->term_id, $this->config->slug.'priority', true); 
										
												//status
												$group_status = get_term_meta($group->term_id, $this->config->slug.'status', true);
												$group_status = !empty( $group_status ) ? $group_status : 'off';
											
												$group_last_edit = isset( $_GET['group_id'] ) ? $_GET['group_id'] : ''; ?>

												<!-- Item -->
												<li class="wp_css_generator wcg-option-item">
													<input type="hidden" name="wcg[group_id][]" value="<?php echo $group->term_id ?>">
													<input type="hidden" name="wcg[group_priority][]" value="<?php echo $group_priority ?>">
												
													<!-- Item Header -->
													<div class="wp_css_generator wcg-option-item-header <?php echo ($group_last_edit == $group->term_id ) ? ' last-edit' : ''; ?> ">
														<div class="wp_css_generator wcg-option-item-header-label"><div class="wcg-order-icon "><i class="fa fa-sort fa-lg" aria-hidden="true"></i></div></div>
														<div class="wp_css_generator wcg-option-item-header-label"><a href="<?php echo admin_url('admin.php').'?page=wpcssgenerator-new&edit='. $group->term_id  ?>"><strong><?php echo $group->name ?></strong></a> <br /> <span class="wcg-actions"><a href="<?php echo admin_url('admin.php').'?page=wpcssgenerator-new&edit='. $group->term_id  ?>"><?php _e('Edit','wp-css-generator') ?></a></div>
														<div class="wp_css_generator wcg-option-item-header-label">
												
															 <div class="onoffswitch">
															        <input type="checkbox" name="wcg[switch][<?php echo $group->term_id ?>]" class="onoffswitch-checkbox" id="group_onoffswitch<?php echo $group->term_id  ?>" <?php checked( 'on', $group_status, true ); ?>  >
															        <label class="onoffswitch-label" for="group_onoffswitch<?php echo $group->term_id  ?>">
															            <span class="onoffswitch-inner"></span>
															            <span class="onoffswitch-switch"></span>
															        </label>
															    </div>
												
														</div>
														<div class="wp_css_generator wcg-option-item-header-label"><?php echo $group->description ?></div>
													</div><!--item-header-->
										
												</li>	<!--item-->	

											<?php endforeach; ?>

										</ul><!-- List of items -->		
									</form>
								</div><!--wcg-option-items-->	
								
								<!-- wcg-option-footer-->
								<div class="wp_css_generator wcg-option-footer wcg-option-footer-group-page">
										<a href="<?php echo admin_url('admin.php') ?>?page=wpcssgenerator-new"><button class="button button-primary button-large">+ <?php _e('Add Group','wp-css-generator') ?></button></a>
								</div><!-- wcg-option-footer-->		
													
						</div><!--Options Wrap-->
										
						</div><!-- Content -->
							
						<div id="postbox-container-1" class="postbox-container">

						<!--Sidebar-->
						<div id="side-sortables" class="meta-box-sortables">

								<div class="inside">

											<!--postbox-->
											<div id="submitdiv" class="postbox " >
												
												<!--title-->
												<h2 class="hndle ui-sortable-handle"><span><?php _e('Publish','wp-css-generator') ?></span></h2>
												<!--title-->
											
												<!--minor-publishing-->
												<div id="minor-publishing" style="padding:10px;">
										
														<p><?php _e('After re-order or change status of group don\'t forget to save changes.','wp-css-generator') ?></p>
														<br /><br />	
														<button type="submit" form="group_order_form"  class="button button-primary button-large add-field" name="wcg[customize]" style="margin-left:10px;"><?php _e('Customize','wp-css-generator') ?></button>
														<button type="submit" form="group_order_form" class="button button-primary button-large add-field"  style=""><?php _e('Save','wp-css-generator') ?></button>
														<br /><br />
												
												</div><!--minor-publishing-->
											</div><!--postbox-->
									
											
											
											<!--postbox-->
											<div id="submitdiv" class="postbox " >
												
												<!--title-->
												<h2 class="hndle ui-sortable-handle"><span>WP CSS Generator</span></h2>
												<!--title-->
											
												<!--minor-publishing-->
												<div id="minor-publishing" style="padding:10px;">
												

														<p><a href="https://wpcssgenerator.com/documentation/"><?php _e('Getting Started', 'wp-css-generator') ?></a></p>
													
														<p><a href="https://wpcssgenerator.com/pro/"><?php _e('Pro Version', 'wp-css-generator') ?></a></p>
														
													    <p><i><?php _e('Created by', 'wp-css-generator') ?> Goran Petrovic</i></p>

												</div><!--minor-publishing-->
											</div><!--postbox-->
											
											
								</div><!--inside-->
							
								
									
							</div>	<!--Sidebar-->		
						
					</div>
				</div>	
			</div>
		<?php }


		/**
		 * View add/edit group
		 *
		 * @author Goran Petrovic
		 * @since 1.0
		 *
		 **/
		public function view_add_new(){ 
				$group = null;
				$config  =  Config::getInstance();
			
				if (!empty($_GET['edit'])) :
					$group = get_term_by('id', $_GET['edit'], 'wpcss-group');	
					$group_priority = get_term_meta($group->term_id, $config->slug.'priority', true);
				endif;
				
				$group_id   = !empty($group) ? $group->term_id : '' ;
				$group_name = !empty($group) ? $group->name : '' ;
				$group_slug = !empty($group) ? $group->slug : '' ;
				$group_desc = !empty($group) ? $group->description : '' ;
				$group_priority = !empty( $group_priority ) ? $group_priority  : 100 ;
		
				?>

			<!--wrap-->
			<div class="wrap">
				<h1 id="naslov" class="wp-heading-inline"><?php echo ( $group_id ) ? __('Edit CSS Group', 'wp-css-generator') : __('Add New CSS Group', 'wp-css-generator'); ?></h1>
				<hr class="wp-header-end">
			
			<!--#poststuff-->
			<div id="poststuff">
				
				<!--metabox-holder-->
				<div id="post-body" class="metabox-holder columns-2">

					<!--OPEN FROM-->
					<form id="myform" action="" method="POST" accept-charset="utf-8">
						
						<?php 
						//secure filed
						wp_nonce_field( 'group_add_action', 'security' );  ?>
					
					<!--Group title-->	
					<div id="titlewrap">
						<input class="wcg-group-title" name="wcg[group_name]" size="30" value="<?php echo $group_name ?>" id="title" spellcheck="true" autocomplete="off" type="text" placeholder="<?php _e('Enter title here','wp-css-generator') ?>" required <?php echo !empty($_GET['edit']) ? 'readonly' : '' ?> >
						<input type="hidden" name="wcg[group_slug]" value="<?php echo $group_slug ?>">
						<input type="hidden" name="wcg[group_priority]" value="<?php echo $group_priority ?>">
					</div><!--Group title-->	

				
					<!--OPTIONS-->
					<div class="wp_css_generator wcg-options">
						
						<!-- Header -->
						<div class="wp_css_generator wcg-option-header">
							<div class="wp_css_generator wcg-option-item-header-label"><strong><?php _e('Order','wp-css-generator') ?></strong></div>
							<div class="wp_css_generator wcg-option-item-header-label"><strong><?php _e('Label','wp-css-generator') ?></strong></div>
							<div class="wp_css_generator wcg-option-item-header-label50"><strong><?php _e('CSS Selectors','wp-css-generator') ?></strong></div>
						<!--	<div class="wp_css_generator wp_css_generator-option-header-item"><strong>Options</strong></div>-->
						</div><!-- Header -->

									
						<!-- List of items -->
						<div class="wp_css_generator wcg-option-items">
	
							<ul class="wcg-option-items-list">	

								<?php 
								
								
								if ( !empty( $group_id ) ) : 
									
									$subGroups = get_terms( array(
										    'taxonomy' 	 => 'wpcss-group',
										    'hide_empty' => false,
											'orderby'    => 'meta_value_num',
											'meta_key'	 => $this->config->slug.'priority',
											'order' 	 => 'ASC',
											'parent' 	 => $group_id 
										) );
								
								
								foreach ($subGroups as $subGroup) : 	?>

								<!-- Item -->
								<li id="term_<?php echo $subGroup->term_id ?>" class="wp_css_generator wcg-option-item ">

									<div class="wp_css_generator wcg-option-item-header">
										<div class="wp_css_generator wcg-option-item-header-label"><div class="wcg-order-icon"><i class="fa fa-sort fa-lg" aria-hidden="true"></i></div></div>
										<div class="wp_css_generator wcg-option-item-header-label"><a href="#"><strong class="wcg-edit-option"><?php echo $subGroup->name ?></strong></a> <br /> <span class="wcg-actions"><a href="#" class="wcg-edit-option"><?php _e('Edit','wp-css-generator') ?></a>&nbsp; <a href="#" class="wcg-delete-option" data-term-id="<?php echo $subGroup->term_id ?>" data-url="<?php echo $config->URL ?>/ajax/delete_sub_term.php"><?php _e('Delete','wp-css-generator') ?></a></span></div>
										<div class="wp_css_generator wcg-option-item-header-label"><?php echo $subGroup->description ?></div>
									</div><!--item-header-->

									<!-- wcg-option-item-content-->
									<div class="wp_css_generator wcg-option-item-content">
																	
										<div class="wp_css_generator wcg-field-group">
											<div class="wp_css_generator wcg-field-label">
												<label><strong><?php _e('Label','wp-css-generator') ?></strong> <span style="color:red">*</span></label>
												<p><?php _e('This label will be visible in Customize setting as group option.', 'wp-css-generator') ?></p>
											</div>
											<div class="wp_css_generator wcg-field">
												<input type="text"   name="wcg[title][]" value="<?php echo $subGroup->name ?>" placeholder="<?php _e('Enter label here','wp-css-generator') ?>" required>
												<input type="hidden" name="wcg[slug][]"  value="<?php echo $subGroup->slug ?>">
												<br />
											</div>
										</div><!--wcg-field-group-->

										<div class="wp_css_generator wcg-field-group">
											<div class="wp_css_generator wp_css_generator-field-label wcg-field-label">
												<label><strong><?php _e('CSS Selectors','wp-css-generator') ?></strong> <span style="color:red">*</span></label>
												<p><?php _e('Insert CSS selector as classes, IDs or pseudo-classes.', 'wp-css-generator') ?></p>
												<a href="http://www.w3schools.com/cssref/css_selectors.asp" target="_blank"><?php _e('Help', 'wp-css-generator') ?></a>
												
												</p>
											</div>

											<div class="wp_css_generator wcg-field">
												<textarea name="wcg[selector][]" placeholder="<?php _e('Enter CSS selectors here for this group of options', 'wp-css-generator') ?>" required><?php echo $subGroup->description ?></textarea>
												<p><i><?php _e('Example( .header, #header, #header:hover or .header a:first-childe )', 'wp-css-generator') ?></i></p>
										
												<br />
											</div>
										</div><!--wcg-field-group-->
										
									
										<div class="wp_css_generator wcg-field-group">
											<div class="wp_css_generator wcg-field-label">
												<label><strong><?php _e('Options', 'wp-css-generator') ?></strong> <span style="color:red">*</span></label>
												<p><?php _e('Choice CSS options for the selectors.', 'wp-css-generator') ?></p>
												<a href="http://www.w3schools.com/css/" target="_balnk"><?php _e('Help', 'wp-css-generator') ?></a>
											</div><!--	wp_css_generator-field-label-->


											<?php
												//get css options 
												$css_options =	get_term_meta($subGroup->term_id, $this->config->slug.'css_options', true); 	?>
											<div class="wp_css_generator wcg-field">
												
												<label>
													<?php $css_options['display'] = isset( $css_options['display']) ?  $css_options['display'] : 'no'; ?>
													<input  class="wcg-checkbox" type="checkbox"  value="yes" > <?php _e('Display', 'wp-css-generator') ?>
													<input class="wcg-checkbox-value" type="hidden" name="wcg[options][display][]" value="no">
												</label>
												<br />
												
												<label>
													<?php $css_options['color'] = isset( $css_options['color']) ?  $css_options['color'] : 'no'; ?>
													<input  class="wcg-checkbox" type="checkbox"  value="yes" <?php checked( 'yes', $css_options['color'], true ); ?> > <?php _e('Color', 'wp-css-generator') ?>
													<input class="wcg-checkbox-value" type="hidden" name="wcg[options][color][]" value="<?php echo isset( $css_options['color']) ?  $css_options['color'] : 'no'; ?>">
												</label>
												<br />
												<label>
													<?php $css_options['font_family'] = isset( $css_options['font_family']) ?  $css_options['font_family'] : 'no'; ?>
													<input class="wcg-checkbox" type="checkbox" value="yes" <?php checked( 'yes', $css_options['font_family'], true ); ?> > <?php _e('Font-Family', 'wp-css-generator') ?>
													<input class="wcg-checkbox-value" type="hidden" name="wcg[options][font_family][]" value="<?php echo isset( $css_options['font_family']) ?  $css_options['font_family'] : 'no'; ?>">
												</label>
												<br />
												<label>
													<?php $css_options['background'] = isset( $css_options['background']) ?  $css_options['background'] : 'no'; ?>
													<input class="wcg-checkbox" type="checkbox" value="yes" <?php checked( 'yes', $css_options['background'], true ); ?>> <?php _e('Background', 'wp-css-generator') ?>
													<input class="wcg-checkbox-value" type="hidden" name="wcg[options][background][]" value="<?php echo isset( $css_options['background']) ?  $css_options['background'] : 'no'; ?>">
												</label>
												<br /><br />
												<i><?php _e('More CSS options available in <a href="https://wpcssgenerator.com">PRO version</a>', 'wp-css-generator') ?></i>
											</div><!--	wcg-field-->

										</div><!--wcg-field-group-->
										
										
										<div class="wp_css_generator wcg-field-group wcg-field-group-last-child">
									
											<div class="wp_css_generator wcg-field-label">
												<label><strong><?php _e('Important', 'wp-css-generator') ?></strong></label>
												<p><?php _e('Make this options group as !important;', 'wp-css-generator') ?></p>
											</div><!-- wcg-field-label-->

											<div class="wp_css_generator wcg-field">
												
												<?php
													//get important
													$important = get_term_meta($subGroup->term_id, $this->config->slug.'important', true); ?>
												
												<label>
													<input  class="wcg-checkbox" type="checkbox"  value="yes" <?php checked( 'yes', $important, true ); ?>> !important;
													<input class="wcg-checkbox-value" type="hidden" name="wcg[important][]" value="<?php echo isset( $important ) ?  $important : 'no'; ?>">
												</label>
												
												
												
											</div><!--	wcg-field-->
										</div><!--wcg-field-group-->
										
									</div><!-- wcg-option-item-content-->
										
								</li><!--item-->
								
								<?php	endforeach;
								
								endif;?>
						
							</ul><!--wcg-option-list-->
									
									
							<!-- wcg-option-footer-->
							<div class="wp_css_generator wcg-option-footer" style="">
								<button class="button button-primary button-large wcg-add-option">+ <?php _e('Add Selector', 'wp-css-generator') ?></button>
							</div><!-- wcg-option-footer-->

						</div><!-- wcg-options-->							
				

						<!--Responsive wcg-options-->
						<div class="wp_css_generator wcg-options" style="margin-top:20px;">
							
							<!-- wcg-option-header-->
							<div class="wp_css_generator wcg-option-header">
								<div class="wp_css_generator wcg-option-header-item"><strong><?php _e('Viewport', 'wp-css-generator') ?></strong></div>
							</div><!-- wcg-option-header-->

							<!--wcg-option-items-->
							<div class="wp_css_generator wcg-option-items">

								<!-- wcg-option-item-content -->
								<ul class="wp_css_generator wcg-option-item-content active">
									
									<!-- wcg-option-item -->
									<li class="wp_css_generator wcg-option-item"> 
										
										<div class="wp_css_generator wcg-field-group">
											<div class="wp_css_generator wcg-field-label">
												<label><strong>@media</strong></label>	
												<p><?php _e('The @media rule is used to define different style rules for different media types/devices.' ,'wp-css-generator') ?>
													<br />	<br /><a href="http://www.w3schools.com/cssref/css3_pr_mediaquery.asp" target="_blank"><?php _e('Help', 'wp-css-generator') ?></a>
												</p>
											</div>
											<div class="wp_css_generator wcg-field">
												<p><input id="wcg-media-query" name="wcg[group_desc]" type="text" value="<?php echo $group_desc ?>" placeholder="<?php _e('Enter media query here', 'wp-css-generator') ?>"></p>
											
												<i class="fa fa-desktop fa-lg wcg-media-query-icon" aria-hidden="true" data-value="@media (max-width: 1600px)"></i>
												
												<i class="fa fa-laptop fa-lg wcg-media-query-icon" aria-hidden="true" data-value="@media (max-width: 1200px)"></i>
												
												<i class="fa fa-tablet fa-lg wcg-media-query-icon" aria-hidden="true" data-value="@media (max-width: 850px)"></i>
												
												<i class="fa fa-mobile fa-lg wcg-media-query-icon" aria-hidden="true" data-value="@media (max-width: 350px)"></i>

											</div>
										</div><!--wcg-field-group-->
									
									 </li><!--wcg-option-item-->

								</ul><!-- wcg-option-item-content -->	
		
							</div><!--wcg-option-items-->
	
				 		</div><!-- wcg-options -->
				
					</div><!--metabox-holder-->
					
				</form>	<!-- CLOSE FORM	-->
				
				<!--divider-->
				<div id="postbox-container-1" class="postbox-container">

					<!-- Sidebar -->
					<div id="side-sortables" class="meta-box-sortables">
						
						<!--inside-->
						<div class="inside">
							
							<!--postbox-->
							<div id="submitdiv" class="postbox">
								<h2 class="hndle ui-sortable-handle"><span><?php _e('Publish', 'wp-css-generator') ?></span></h2>	
								<!--#minor-publishing-->
								<div id="minor-publishing">
									<div id="misc-publishing-actions" style="padding-left:10px;">
										<p><?php _e('After edit don\'t forget to save your options.', 'wp-css-generator') ?> </p>
									</div>
								</div><!--#minor-publishing-->
							
								<!-- Publish -->
								<div id="major-publishing-actions">	
									<?php if ( !empty( $group_id ) ) : ?>
										<p><a href="#" class="wcg-delete-parent-group" data-term-id="<?php echo $group_id ?>" data-redirect="<?php echo admin_url() ?>admin.php?page=wpcssgenerator" style="color:red; float:left;">Delete</a></p>						
									<?php endif; ?>
									<div id="publishing-action">
									
										<span class="spinner"></span>
										<input name="publish" id="publish" class="button button-primary button-large" value="<?php _e('Publish', 'wp-css-generator') ?>" type="submit" form="myform" ></div>
										<div class="clear"></div>
									</div>
								</div><!--major-publishing-actions-->
							</div>	<!--postbox-->
		
							<!--postbox-->
							<div id="submitdiv" class="postbox " >
								
								<!--title-->
								<h2 class="hndle ui-sortable-handle"><span>WP CSS Generator</span></h2>
								<!--title-->
							
								<!--minor-publishing-->
								<div id="minor-publishing" style="padding:10px;">
									<p><a href="https://wpcssgenerator.com/documentation/"><?php _e('Getting Started', 'wp-css-generator') ?></a></p>
									<p><a href="https://wpcssgenerator.com/pro/"><?php _e('Pro Version', 'wp-css-generator') ?></a></p>
								    <p><i><?php _e('Created by', 'wp-css-generator') ?> Goran Petrovic</i></p>
								</div><!--minor-publishing-->
							</div><!--postbox-->

					</div>	<!--inside-->	
													
				</div>	<!--meta-box-sortables-->
	
			</div><!--#poststuff-->	
		</div><!--wrap-->	
			

			<!-- CLONE -->
			<div style="display:none;" class="wcg-option-clon">

					<!-- Item -->
					<li class="wp_css_generator wcg-option-item">

						<div class="wp_css_generator wcg-option-item-header active">
							<div class="wp_css_generator wcg-option-item-header-label"><div class="wcg-order-icon"><i class="fa fa-sort fa-lg" aria-hidden="true"></i></div></div>
							<div class="wp_css_generator wcg-option-item-header-label"><a href="#"><strong class="wcg-edit-option"><?php _e('New label', 'wp-css-generator') ?></strong></a> <br /> <span class="wcg-actions"><a href="#" class="wcg-edit-option"><?php _e('Edit', 'wp-css-generator') ?></a> <a href="#" class="wcg-delete-clon-option"><?php _e('Delete', 'wp-css-generator') ?></a></div>
							<div class="wp_css_generator wcg-option-item-header-label"></div>
						<!--	<div class="wp_css_generator wp_css_generator-option-header-item">3</div>-->
						</div><!--item-header-->

						<!-- wcg-option-item-content-->
						<div class="wp_css_generator wcg-option-item-content active">
														
								<div class="wp_css_generator wcg-field-group">
									<div class="wp_css_generator wcg-field-label">
										<label><strong><?php _e('Label','wp-css-generator') ?></strong> <span style="color:red">*</span></label>
										<p><?php _e('This label will be visible in Customize setting as group option.', 'wp-css-generator') ?></p>
									</div>
									<div class="wp_css_generator wcg-field">
										<input type="text"   name="wcg[title][]" value="" placeholder="<?php _e('Enter label here','wp-css-generator') ?>" required>
										<input type="hidden" name="wcg[slug][]"  value="">
										<br />
									</div>
								</div><!--wcg-field-group-->

								<div class="wp_css_generator wcg-field-group">
									<div class="wp_css_generator wp_css_generator-field-label wcg-field-label">
										<label><strong><?php _e('CSS Selectors','wp-css-generator') ?></strong> <span style="color:red">*</span></label>
										<p><?php _e('Insert CSS selector as classes, IDs or pseudo-classes.', 'wp-css-generator') ?></p>
										<a href="http://www.w3schools.com/cssref/css_selectors.asp" target="_blank"><?php _e('Help', 'wp-css-generator') ?></a>
										
										</p>
									</div>

									<div class="wp_css_generator wcg-field">
										<textarea name="wcg[selector][]" placeholder="<?php _e('Enter CSS selectors here for this group of options', 'wp-css-generator') ?>" required></textarea>
										<p><i><?php _e('Example( .header, #header, #header:hover or .header a:first-childe )', 'wp-css-generator') ?></i></p>
								
										<br />
									</div>
								</div><!--wcg-field-group-->
								
							
								<div class="wp_css_generator wcg-field-group">
									<div class="wp_css_generator wcg-field-label">
										<label><strong><?php _e('Options', 'wp-css-generator') ?></strong> <span style="color:red">*</span></label>
										<p><?php _e('Choice CSS options for the selectors.', 'wp-css-generator') ?></p>
										<a href="http://www.w3schools.com/css/" target="_balnk"><?php _e('Help', 'wp-css-generator') ?></a>
									</div><!--	wp_css_generator-field-label-->
								
									<div class="wp_css_generator wcg-field">
										<label>
											<input  class="wcg-checkbox" type="checkbox"  value="yes" > <?php _e('Display', 'wp-css-generator') ?>
											<input class="wcg-checkbox-value" type="hidden" name="wcg[options][display][]" value="no">
										</label>
										<br />
										<label>
											<input  class="wcg-checkbox" type="checkbox"  value="yes" > <?php _e('Color', 'wp-css-generator') ?>
											<input class="wcg-checkbox-value" type="hidden" name="wcg[options][color][]" value="no">
										</label>
										<br />
										<label>
											<input class="wcg-checkbox" type="checkbox" value="yes" > <?php _e('Font-Family', 'wp-css-generator') ?>
											<input class="wcg-checkbox-value" type="hidden" name="wcg[options][font_family][]" value="no">
										</label>
										<br />
										<label>
											<input class="wcg-checkbox" type="checkbox" value="yes"> <?php _e('Background', 'wp-css-generator') ?>
											<input class="wcg-checkbox-value" type="hidden" name="wcg[options][background][]" value="no">
										</label>
										<br /><br />
										<i><?php _e('More CSS options available in <a href="https://wpcssgenerator.com">PRO version</a>', 'wp-css-generator') ?></i>
									</div><!--	wcg-field-->

								</div><!--wcg-field-group-->
								
								
								<div class="wp_css_generator wcg-field-group wcg-field-group-last-child">
							
									<div class="wp_css_generator wcg-field-label">
										<label><strong><?php _e('Important', 'wp-css-generator') ?></strong></label>
										<p><?php _e('Make this options group as !important;', 'wp-css-generator') ?></p>
									</div><!-- wcg-field-label-->

									<div class="wp_css_generator wcg-field">
										<label>
											<input  class="wcg-checkbox" type="checkbox"  value="yes"> !important;
											<input class="wcg-checkbox-value" type="hidden" name="wcg[important][]" value="no">
										</label>			
									</div><!--	wcg-field-->
								</div><!--wcg-field-group-->
							
							
						</div><!-- wcg-option-item-content-->
							
					</li><!--item-->

			<div><!--	hidden-->
		<?php }

	/**
	 * View tools
	 *
	 * @author Goran Petrovic
	 * @since 1.0
	 *
	 **/
	function view_tools(){ ?>
		
					<div class="wrap">
						<h1 class="wp-heading-inline"><?php _e('CSS Tools','wp-css-generator') ?></h1>
						<hr class="wp-header-end">

					<div id="poststuff">
						<div id="post-body" class="metabox-holder columns-2">

							<!-- Content -->
							<div id="post-body-content">

									<!--Responsive wcg-options-->
									<div class="wp_css_generator wcg-options" style="">

										<!-- wcg-option-header-->
										<div class="wp_css_generator wcg-option-header">
											<div class="wp_css_generator wcg-option-header-item"><strong>CSS</strong></div>
										</div><!-- wcg-option-header-->

										<!--wcg-option-items-->
										<div class="wp_css_generator wcg-option-items" style="padding:20px;">
	
											<p><label><strong>Minify</strong></label>
											<textarea name="Name" rows="4"  style="width:100%" readonly><?php Helper_CSS::generate_minify_CSS(false) ?></textarea>
											</p>
										
											<p>
											<label><strong>Developer</strong></label>
											<textarea name="Name" rows="80"  style="width:100%" readonly><?php Helper_CSS::generate_minify_CSS(true) ?></textarea>	</p>

										</div><!--wcg-option-items-->
							 		</div><!-- wcg-options -->	

							</div><!-- Content -->

							<div id="postbox-container-1" class="postbox-container">

							<!--Sidebar-->
							<div id="side-sortables" class="meta-box-sortables">

									<div class="inside">

												<!--postbox-->
												<div id="submitdiv" class="postbox " >

													<!--title-->
													<h2 class="hndle ui-sortable-handle"><span>WP CSS Generator</span></h2>
													<!--title-->

													<!--minor-publishing-->
													<div id="minor-publishing" style="padding:10px;">

														<p><a href="https://wpcssgenerator.com/documentation/"><?php _e('Getting Started', 'wp-css-generator') ?></a></p>

														<p><a href="https://wpcssgenerator.com/pro/"><?php _e('Pro Version', 'wp-css-generator') ?></a></p>

													    <p><i><?php _e('Created by', 'wp-css-generator') ?> Goran Petrovic</i></p>

													</div><!--minor-publishing-->
												</div><!--postbox-->


									</div><!--inside-->



								</div>	<!--Sidebar-->		

						</div>
					</div>	
				</div>
		
	<?php }



    } // END class WP_CSS_Generator_Options


	if (is_admin()) :
		$WP_CSS_Generator_Options = new WP_CSS_Generator_Options();
	endif;

	?>