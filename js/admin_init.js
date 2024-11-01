jQuery(document).ready(function() {
	


	//hide options
/*	jQuery('.wcg-option-item-content').hide();*/

	//toggle options
	jQuery('.wcg-edit-option').live( "click" , function( event ) {
		
		
	 	jQuery(this).parent().parent().parent().next('.wcg-option-item-content').slideToggle();
		jQuery(this).parent().parent().parent().next('.wcg-option-item-content').toggleClass('active');
		jQuery(this).parent().parent().parent().parent().find('.wcg-option-item-header').toggleClass('active');
		return false;
	});
	
	//chekcbox options values
	jQuery('.wcg-checkbox').live( "click" ,function() {
       if (!jQuery(this).is(':checked')) {
		jQuery(this).next('.wcg-checkbox-value').val('no');        
       }else{
		jQuery(this).next('.wcg-checkbox-value').val('yes');
	   }
    });

	//add option
	jQuery('.wcg-add-option').bind( "click" ,function() {
		
		var id_switch = Math.random().toString(36).substr(2, 5);
	
		jQuery( ".wcg-option-clon li .wcg-onoffswitch" ).attr('id', id_switch); 
		jQuery( ".wcg-option-clon li .wcg-onoffswitch-label" ).attr('for', id_switch); 
		
		jQuery( ".wcg-option-clon li" ).clone().appendTo( "ul.wcg-option-items-list" );		
		return false;
	 });
	
	//@media set values from icons 
	jQuery('.wcg-media-query-icon').bind( "click" ,function() {
		var value = jQuery(this).attr( "data-value" );			
		jQuery('#wcg-media-query').val(value);
	 });
	
	
	//sortable
    jQuery("ul.wcg-option-items-list").sortable({
  		handle: '.wcg-order-icon'
	});


	//rename wp menu
	jQuery("li.toplevel_page_wpcssgenerator a .wp-menu-name").html('WP CSS');
	

	//delete parent term
	jQuery('.wcg-delete-parent-group').click(function( event ) {	
		
		if(confirm("Are you sure you want to delete this group?")){
			
	        	var term_id = jQuery(this).attr('data-term-id');
				var redirect = jQuery(this).attr('data-redirect');

					if(term_id) {						
							var data = {
								'action': 'delete_parent_term',
								'data': { term_id: term_id }
							};

							//admin-ajax.php
							jQuery.post(ajaxurl, data, function(response) {

								if (response.status=='success') {	
									//redirect
									window.location.href = redirect; 

								}else{}

							},'json');

					}
	
	    }
	    else{
	        return false;
	    }
	    
		return false;	
	});

	//delete term
	jQuery('.wcg-delete-option').click(function( event ) {	
		
		var term_id = jQuery(this).attr('data-term-id');

		if(term_id) {						
				var data = {
					'action': 'delete_sub_term',
					'data': { term_id: term_id }
				};

				//admin-ajax.php
				jQuery.post(ajaxurl, data, function(response) {
					
					if (response.status=='success') {
						jQuery('li#term_'+term_id).remove();										
					}else{						
						jQuery('li#term_'+term_id).remove();		
					}
						
				},'json');

		}
	
		return false;	
	});
	

	//delete clon option
	jQuery('.wcg-delete-clon-option').live( "click" ,function() { 			
		jQuery(this).parent().parent().parent().parent().remove();		
		return false;
	});
});