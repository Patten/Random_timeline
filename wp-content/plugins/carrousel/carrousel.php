<?php
/*
Plugin Name: Carrousel
Plugin URI:
Description: un super carrousel avec admin fait main
Version: 0.1
Author:ptithom
*/


add_action('init','carrousel_init');
add_action('add_meta_boxes', 'carrousel_metaboxes');
//priority 10 nb argument 2
add_action('save_post', 'carrousel_savepost',10,2);
//personalisation des columns
add_action('manage_edit-slide_columns','carrousel_columnfilter');
//gestion des column des slides
add_action('manage_posts_custom_column','carrousel_imagecolumnfilter');


/*
initialisation du carrousel
*/
function carrousel_init(){

	//modification des labels 
	$labels = array(
		'name' => 'Slide',
		'singular_name' => 'Slide',
		'add_new' => 'Ajouter un slide',
		'add_new_item' => 'Ajouter un nouveau Slide',
		'edit_item' => 'Editer Slide',
		'new_item' => 'Nouvelle Slide',
		'view_slide' => 'Voir le slide',
		'search_items' => 'Rechercher un Slide',
		'not_found' => 'Aucune Slide',
		'not_found_in_trash' => 'Aucun Slide dans la corbeille',
		'Parent_item_colon' => '',
		'menu_name' => 'Slides'
	);

	//modification de l'admin
	// slide -> id custom post type
	register_post_type('slide', array(
		'public'=> true,
		'publicly_queryable' => false,
		'labels' => $labels,
		'menu_position' => 9,
		'capability_type' => 'post',
		'supports' => array('title','thumbnail'),
	));
	
	//ajout format d'image -> true pour cropt
	add_image_size('slider',1000,300,true);
}



/*
permet de géré les metaboxs
*/
function carrousel_metaboxes(){

	add_meta_box('carrousel','lien','carrousel_metaboxe','slide','normal','high');

}

/*
Metabox pour géré les liens
*/
function carrousel_metaboxe($object){

	//vérifiy que le post vient vient du plugin
	wp_nonce_field('carrousel','carrousel_nonce');
	
	?>
		<div class="meta-box-item-title">
			<h4> Lien de ce slide</h4>
		</div>
		<div class="meta-box-item-content">
			<!--affiche le value le post de "_link" et true pour dire qu'il y a qu'une val dans le tableau et sec attr pour échappé les "'"-->
			<input type="text" name="carrousel_link" style="width:100%;" value="<?= esc_attr(get_post_meta($object->ID,'_link', true));?>" />
		</div>
	<?php
	
}

/*
save metabox carrousel
*/
function carrousel_savepost($post_id, $post){

	if(!isset($_POST['carrousel_link'])){
		return $post_id;
	}

	update_post_meta($post_id,'_link',$_POST['carrousel_link']);
	
}


/*
ajouter un column a l'affichage admin des slides
*/
function carrousel_columnfilter($columns){

	$thumb = array('thumbnail' => 'Image');
	$columns = array_slice($columns,0,1) + $thumb + array_slice($columns,1,null);
	return $columns;

}



/*
ajouter les image cliquable dans le column image de l'admin
*/
function carrousel_imagecolumnfilter($column){
	global $post;
	if($column == 'thumbnail'){
		echo edit_post_link(get_the_post_thumbnail($post->ID),null,null,$post->ID);
	}
}



/*
affiche le carrousel
*/
function carousel_show($limit = 10){

	//importe le java scripte
	//enlève du register le Jquery de word press
	wp_deregister_script('jquery');
	// ajouté notre lien Jquery sous le nom "jquery"
	wp_enqueue_script('jquery','https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js', null , '1.7.2',true); 
	// ajouté le scripte caroufredsel qui dépend de "jquery"
	wp_enqueue_script('caroufredsel',plugins_url().'/carrousel/js/jquery.carouFredSel-6.2.0-packed.js', array('jquery') , '6.2.0',true);
	//priorité 30 sinon la fonction est active avant l'appel de jquery	
	add_action('wp_footer','carrousel_script', 30);
	
	
	//écritude de l'HTML
	$slides = new WP_query("post_type=slide&posts_per_page=$limit");
	echo'<div id="slider">';
	while($slides->have_posts()){
		$slides->the_post();
		global $post;
		echo '<a style="display:block; height:300px;margin:auto;" href="'.esc_attr(get_post_meta($post->ID,'_link', true)).'">';
		the_post_thumbnail('slider' ,array('style' => 'width:1000px !important;'));
		echo '</a>';
	}
	echo'</div>';
}



/*
affichage du scripte carrousel
*/
function carrousel_script(){
	?>
	<script type="text/javascript">
		(function($){
			$('#slider').caroufredsel();
		})(jQuery);
	</script>
	<?php
}

?>