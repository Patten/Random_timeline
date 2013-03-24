<?php
/*
	Plugin Name: Random Timeline
	Description: Affiche une timeline en récupérant aléatoirement 1 article par catégorie.	Ceci permet de changer le contenu de la page à chaque réactualisation de la page 
	Author: LPDW
	Version: 0.1

 */


define('WPTIMELINE_UNIQUE', 'wptimeline');
define('WPTIMELINE_VERSION', 1.0);

//action lors de l'activation 
function timeline_activate(){
 
	//ajoute les catégorie TimeLine dans la base de donnée
	global $wpdb;
	$wpdb->insert( 
					'wp_terms', 
					array( 
					'name' => 'Random Timeline', 
					'slug' => 'randomtimeline',
					'term_group' => 0, 
				), 
				array( 
					'%s', 
					'%s',
					'%d' 
				) 
	);
	echo "<script>alert(\"la variable est nulle\")</script>"; 
	
}
register_activation_hook( __FILE__, 'timeline_activate' );


// paramétrage de l'affichage
function timeline_shortcode($atts){
	$args = shortcode_atts( array(
		      'cat' => '0',
		      'type' => 'post',
		      'show' => 20,
		      'date' => 'Y',
		      'length' => 10,
		      'images' => 'no'
     		), $atts );
     
     return display_timeline($args);
 }
 
add_shortcode('wp-timeline', 'timeline_shortcode');

// Fonction qui récupère les informations
// Retourne un tableau avec 1 seul article par horaire, prêt à être affiché 
function get_info_timeline()
{
		$post_args = array(
			'post_type' => get_option('timeline_post_type'),
			'numberposts' => get_option('timeline_show_posts'),
			'category' => get_option('timeline_post_category'),
			'orderby' => 'post_date',
			'order' => get_option('timeline_order_posts'),
			'post_status' => 'publish',
			'post_link' => get_option('timeline_post_link')
		);

		$posts = get_posts( $post_args );
		$myterms = get_terms('taxonomy-name', 'orderby=none&hide_empty'); 

		// Range les articles par heure
		foreach($posts as $post)
		{
			$hour = wp_get_post_terms($post->ID, 'category', array("fields" => "names"));
			$hour = explode(':', $hour[0]);
			$min  = $hour[1];
			$hour = $hour[0];
			$hour = $hour.$min;
			$hour = (int)$hour;
			
			$sortedTab[$hour][] = $post; 
		}
		
		// Récupère aléatoirement un article par catégorie
		foreach($sortedTab as $hour => $tabValue )
		{
			$chosenArticle = mt_rand(1, count($tabValue)) - 1;
			$result[$hour] = $tabValue[$chosenArticle];	
		}
		
		ksort($result);
		
		// Rajoute la séparation heure minute
		foreach($result as $key => $value)
		{
			$key = (string)$key;
			if (strlen($key) <= 3)
				$hour = substr($key, 0, 1);
			else
				$hour = substr($key, 0, 2);
				

				
			$min = substr($key, -2);
			
			$newKey= $hour.':'.$min;
			$finalResult[$newKey] = $value; 
		}
		
		return $finalResult;

}


//function d'affichage de la timeline
function display_timeline($args){

		$out .=  '<div id="timeline">';
		$out .=   '<ul>';

		$result = get_info_timeline();
		
		foreach ($result as $hour => $post ) : 
			setup_postdata($post);
		
	       		$out .=  '<li><div>';
	        	if( get_option('timeline_post_link') == 'yes'){
	        		//$out .=  '<a href="' . get_permalink($post->ID) . '" title="'.$post->title.'">';
	        		$out .=  '<h3 class="timeline-date">';
	        		$out .=  $hour.' | '.$post->post_title; 
					$out .=  '</h3>';
					//$out .=  '</a>';
	        	}
	        	else{
	            	$out .=  '<h3 class="timeline-date">';
	            	$out .=  $hour; 
					$out .=  '</h3>';
	            }
	            
				if ( get_option('timeline_include_images') == 'yes' ){
					if ( featured_image() == true && has_post_thumbnail( $post->ID ) ){
						$out .=  '<span class="timeline-image">' . get_the_post_thumbnail( $post->ID, 'timeline-thumb' ) . '</span>';
					}
				}
				$out .=  '<span class="timeline-text">'.timeline_text(get_option('timeline_text_length')).'</span>';
				$out .=  '</div></li>';

    	endforeach;

		$out .=  '</ul>';
		$out .=  '</div> <!-- #timeline -->';
		wp_reset_postdata();
		return $out;
}

// Effectue un trim sur le texte passé en paramètre
function timeline_text($limit){
	$str = get_the_content('', true, '');
	//$str = strip_tags($str);
    if(stripos($str," ") && $limit>=0){
    $ex_str = explode(" ",$str);
        if(count($ex_str)>$limit){
            for($i=0;$i<$limit;$i++){
				$str_s.=$ex_str[$i]." ";
            }
			$str_s.="...";
			return $str_s;
        }else{
			return $str;
        }
    }else{
    return $str;
    }
}

//Si les pages ont des shortcodes, on ajoute des styles dans le leader
function has_timeline_shortcode( $posts ) {

        if ( empty($posts) )
            return $posts;

        $shortcode_found = false;

        foreach ($posts as $post) {

            if ( !( stripos($post->post_content, '[wp-timeline') === false ) ) {
                $shortcode_found = true;
                break;
            }
        }

        if ( $shortcode_found ) {
            add_timeline_styles();
        }
        return $posts;
    }

add_action('the_posts', 'has_timeline_shortcode');

//Ajout de styles au leader
function add_timeline_styles(){
	add_action('wp_print_styles', 'timeline_styles');
}

function timeline_styles(){

		wp_register_style($handle = 'timeline', $src = plugins_url('css/timeline.css', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
		wp_enqueue_style('timeline');
}


// ajout des images
function featured_image(){
	if ( !current_theme_supports( 'post_thumbnails' ) ) {
		if ( function_exists( 'add_theme_support' ) ) { 
			
			add_theme_support( 'post-thumbnails' );
			add_image_size( 'timeline-thumb', 80, 9999 ); 
			return true;
		}
	}
	else{
		
		add_image_size( 'timeline-thumb', 80, 9999 ); 
		return true;
	}

}

add_filter('get_the_content', 'do_shortcode');
add_filter('get_the_excerpt', 'do_shortcode');

//Ajout des options à cette page
require_once('inc/timeline_options.php');

?>