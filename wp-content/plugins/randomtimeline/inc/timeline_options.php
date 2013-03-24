<?php
/*  Options du plugin:
	A voir dans la partie admin

	plugin version 0.1

	Liste des options :
		timeline_post_category 
		timeline_post_type 
		timeline_show_posts 
		timeline_date_format 
		timeline_text_length
		timeline_include_images 
		timeline_order_posts
		timeline_post_link
*/

// retourne un tableau de toutes les catégories
function timeline_get_categories(){

	$cats = get_categories();
	$categories = array();
	
	$i = 0;
	
	foreach($cats as $cat) {
  		$categories[$cat->cat_name] = $cat->cat_ID;
	}	
	
    return $categories;
    
}

// retourne un tableau contenant les formats de date possibles
function timeline_date_formats(){
		$date_formats = array(
			'March 10, 2012' => 'F j, Y',
			'03.10.2012' => 'm.j.y',
			'2012' => 'Y',
			'March, 2012' => 'F, Y',
			'20 March, 2012' => 'j F, Y'
		);
		
		return $date_formats;
			
}

// retourne tous les types de post enregistrés
function timeline_get_post_types(){
	$post_types = get_post_types('','names'); 
	
	return $post_types;
}

/* 	Mise à jour des options

	Chaque option à besoin d'un nom, 'une valeur par défaut, d'une description, et d'un input_type 
*/
function timeline_set_options(){
	
	$cat_data = timeline_get_categories();
	$post_type_data = timeline_get_post_types();
	$date_data = timeline_date_formats();
	
	$options = array(
		'post_category' => array ( 
			'name' => 'timeline_post_category', 
			'default' => '0', 
			'desc' => 'Sélectionnez une categorie pour votre timeline:', 
			'input_type' => 'dropdown', 
			'data' => $cat_data //tableau à une dimension
			),
		'show_posts' => array ( 
			'name' => 'timeline_show_posts', 
			'default' => '5', 
			'desc' => 'Combien de posts voulez vous afficher ?', 
			'input_type' => 'text'
			),
		'text_length' => array ( 
			'name' => 'timeline_text_length' , 
			'default' => 10, 
			'desc' => 'Combien de mots voulez-vous afficher pour chaque post ? ("-1" pour tout afficher)', 
			'input_type' => 'text', 
			),
		'post_order' => array ( 
			'name' => 'timeline_order_posts' , 
			'default' => 'DESC', 
			'desc' => 'Dans quel ordre voulez vous classer vos posts?', 
			'input_type' => 'dropdown', 
			'data' => array(
				'Croissant' => 'ASC', 
				'Décroissant' => 'DESC') 
			)
		
	);

	return $options;
	
}

//create settings page
function wptimeline_settings() {
	?>
		<div class="wrap">	
			<h2><?php _e('Timeline Settings', WPTIMELINE_UNIQUE); ?></h2>
			<div id="timeline_quick_links">
				<?php /*include('inc/timeline_links.php'); */?>
			</div>
		<?php
		if (isset($_GET['updated']) && $_GET['updated'] == 'true') {
			?>
			<div id="message" class="updated fade"><p><strong><?php _e('Settings Updated', WPTIMELINE_UNIQUE); ?></strong></p></div>
			<?php
		}
		?>
			<form method="post" action="<?php echo esc_url('options.php');?>">
				<div>
					<?php settings_fields('timeline-settings'); ?>
				</div>
				
				<?php
					$options = timeline_set_options();
					
					?>
				<table class="form-table">
				<?php foreach($options as $option){ ?>
					<?php 
						//if option type is a dropdown, do this
						if ( $option['input_type'] == 'dropdown'){ ?>
							<tr valign="top">
				        		<th scope="row"><?php _e($option['desc'], WPTIMELINE_UNIQUE); ?></th>
				        			<td><select id="<?php echo $option['name']; ?>" name="<?php echo $option['name']; ?>">
				        					<?php foreach($option['data'] as $opt => $value){ ?>
												<option <?php if(get_option($option['name']) == $value){ echo 'selected="selected"';}?> name="<?php echo $option['name']; ?>" value="<?php echo $value; ?>"><?php echo $opt ; ?></option>
												<? } //endforeach ?>
										</select>
									</td>
					        </tr>
				    <?php 
				    	//if option type is text, do this
				    	}elseif ( $option['input_type'] == 'text'){ ?>
				    		<tr valign="top">
				        		<th scope="row"><?php _e($option['desc'], WPTIMELINE_UNIQUE); ?></th>
				        			<td><input id="<?php echo $option['name']; ?>" name="<?php echo $option['name']; ?>" value="<?php echo get_option($option['name']); ?>" />
									</td>
					        </tr>
			     <?php 
			     		
			     		}else{} //endif
			     		
			     	} //endforeach ?>
			        
			    </table>
			    <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Enregister', WPTIMELINE_UNIQUE); ?>" /></p>
			</form>
		</div>
	<?php
}

//register settings loops through options
function timeline_register_settings(){




	$options = timeline_set_options(); //get options array
	
	foreach($options as $option){
		delete_option('timeline_date_format');
		register_setting('timeline-settings', $option['name']); //register each setting with option's 'name'
		
		if (get_option($option['name']) === false) {
			add_option($option['name'], $option['default'], '', 'yes'); //set option defaults
		}
	}

	if (get_option('timeline_promote_plugin') === false) {
		add_option('timeline_promote_plugin', '0', '', 'yes');
	}

}

add_action( 'admin_init', 'timeline_register_settings' );



//add settings page
function timeline_settings_page() {	
	add_options_page('Timeline Settings', 'Timeline Settings', 'manage_options', WPTIMELINE_UNIQUE, 'wptimeline_settings');
}
add_action("admin_menu", 'timeline_settings_page');



?>