<?php

# Initialisation des scripts
# Charge les scripts en fin de code par défaut
# Si l'option est désactivée, charge les scripts lors de leurs lancements
if (function_exists('add_action')) {
	add_action( 'init', 'hestia_slider_load_scripts' );
}
	function hestia_slider_load_scripts() 
	{
		# Chargement optimisé des scripts
		$activation_optimise_code = get_option('activation_optimise_code');
		$url_js_plugin = plugins_url( 'js/hestia_slider.js' , dirname(__FILE__) );
		if( !is_admin() && $activation_optimise_code['desactive_optimise_code'] === "1" ){
		//	wp_deregister_script('jquery');
		//	wp_register_script('jquery', 'http://code.jquery.com/jquery-latest.min.js', array(), false, false);
		//	wp_enqueue_script('jquery', false, false, false, false);
			wp_register_script('jquery_cycle', 'http://malsup.github.com/jquery.cycle.all.js', array('jquery'), false, false);
			wp_enqueue_script('jquery_cycle', false, false, false, false);
		//	wp_register_script('hestia_slider', $url_js_plugin, array('jquery'), false, false);
		//	wp_enqueue_script('hestia_slider', false, false, false, false);
		//	remove_action('wp_head', 'wp_print_scripts');
		//	remove_action('wp_head', 'wp_enqueue_scripts', 1);
		//	remove_action('wp_head', 'wp_print_head_scripts', 9);
			
		//	add_action('wp_footer', 'wp_print_scripts', 5);
		//	add_action('wp_footer', 'wp_enqueue_scripts', 5);
		//	add_action('wp_footer', 'wp_print_head_scripts', 5);
		};
	};

$params = array();
	$params['instances'][] = '.widget-' . $instance_hestia . ' .hestia_slider_photo';
	$params['direction'][] = $instance_hestia['direction'];
	$params['vitesse'][] = $instance_hestia['vitesse'];
	$params['timeout'][] = $instance_hestia['timeout'];
	
#Gestion du slider en front page
function hestia_slider($args_hestia, $instance_hestia='1', $widget_title='HESTIA Slider', $lien_voir_actus='Voir toutes les actus', $direction='fade', $vitesse='700', $timeout='5000', $nom_widget) 
{
	global $post;
	global $params;
	
	$hestia_config = get_option('hestia_config');
	$hestia_mise_en_forme = get_option('hestia_mise_en_forme');
	$widget_hestia_slider = get_option('widget-hestia-slider');

	# Configuration des paramètres css du module à partir des informations saisies en Admin
	$css_largeur_module = $hestia_config['largeur_module'];
	$css_hauteur_module = $hestia_config['hauteur_module'];
	$css_largeur_image = $hestia_config['largeur_image'];
	$css_hauteur_image = $hestia_config['hauteur_image'];
	$css_marge_haut_bas_image = 5;
	$css_marge_haut_bas_texte = 5;
	switch ( $hestia_config['position'] ) {
		case "haut":
			$css_largeur_texte = $hestia_config['largeur_module'] - 20;
			$css_hauteur_texte = $hestia_config['hauteur_module'] - $hestia_config['hauteur_image'] - 15;
			$css_position_image = 'float: left; clear: both;';
			$css_position_texte = 'float: left; clear: both;';
			$css_marge_gauche_droite_image = ( $hestia_config['largeur_module'] - $hestia_config['largeur_image'] ) / 2;
			$css_marge_gauche_droite_texte = 10;
			break;
		case "bas":
			$css_largeur_texte = $hestia_config['largeur_module'] - 20;
			$css_hauteur_texte = $hestia_config['hauteur_module'] - $hestia_config['hauteur_image'] - 15;
			$css_position_image = 'float: left; clear: both;';
			$css_position_texte = 'float: left; clear: both;';
			$css_marge_gauche_droite_image = ( $hestia_config['largeur_module'] - $hestia_config['largeur_image'] ) / 2;
			$css_marge_gauche_droite_texte = 10;
			break;
		case "gauche":
			$css_largeur_texte = $hestia_config['largeur_module'] - $hestia_config['largeur_image'] - 25;
			$css_hauteur_texte = $hestia_config['hauteur_module'] - 20;
			$css_position_image = 'float: left;';
			$css_position_texte = 'float: right;';
			$css_marge_gauche_droite_image = 5;
			$css_marge_gauche_droite_texte = 5;
			break;
		case "droite":
			$css_largeur_texte = $hestia_config['largeur_module'] - $hestia_config['largeur_image'] - 25;
			$css_hauteur_texte = $hestia_config['hauteur_module'] - 20;
			$css_position_image = 'float: right;';
			$css_position_texte = 'float: left;';
			$css_marge_gauche_droite_image = 5;
			$css_marge_gauche_droite_texte = 5;
			break;
	}
	
	
	if ( !is_admin() ) { 
		extract($args_hestia);
	};
	if ( $hestia_mise_en_forme['visib_titre_module']=="1" ) { 
		echo $before_title . $widget_title . $after_title;
	};

	# Affichage du module en front-page
	?>
    <div class="hestia_slider_photo css_actus" style="width: 100%; height: auto; margin: 0px auto; overflow: hidden;">
		<?php
		$home_paged = (get_query_var('paged'));
		$args_slider = array(
			'posts_per_page'	=>	$hestia_config['nb_slides'],
			'post_type' 		=> 	'post-hestia-slider',
			'post_status' 		=> 	'publish',
			'orderby'    		=>      'post_date',
			'order'    		=>	'DESC',
			'paged' 		=> 	$home_paged,
			'meta_query' 		=>  array(
				array(
					'key'       => 	'select_module_hestia',
					'value'     =>	$instance_hestia
				)
			)
		);
		$data = new WP_Query($args_slider);
		while ($data->have_posts()) : $data->the_post();
			?>
			<div class="hestia_slider" style="width: 97%; height: auto; padding-left: 3%;">
				<?php
				$entete = '';
				if ( $hestia_mise_en_forme['visib_date']=="1" ) { $date = '<h5 class="css_actus actus_date">' . get_the_time('d.m.Y') . '</h5>'; } else { $date = ''; };
				if ( $hestia_mise_en_forme['titre_cliquable']=="1" ) { $link_titre = '<a class="titre-link" href="' . get_permalink() . '">' . get_the_title() . '</a>'; } else { $link_titre = get_the_title(); };
				if ( $hestia_mise_en_forme['visib_titre']=="1" ) { $titre_slide = '<h5 class="css_actus actus_titre" style="margin: 0px 0px 5px 0px;">' . $link_titre . '</h5>'; } 
				else { $titre_slide = ''; };
				echo $entete  . $date . $titre_slide;
				?>
				<?php
				# Affichage de l'image et du texte des slides
				#
				# Ordre de chargement du code si l'image
				# doit être au dessus du texte
				if ( $hestia_config['position'] != "bas" ) {
					?>
					<div class="image_a_la_une" style="width: 95% !important; height: inherit; margin: 0px 1% 5px 1%; <?php echo $css_position_image; ?> overflow: auto;">
						<?php
						if ( $hestia_mise_en_forme['visib_image'] == "1" ) {
							if ( has_post_thumbnail() ) {
								# si l'article à une miniature
								the_post_thumbnail( 'miniature-archives', array( 'style' => 'width: 100%; height: auto; text-align: center' ) );
							} else {
								# s'il n'en a pas...
								$url_image_defaut = plugins_url( 'images/actus_entete_defaut.jpg' , dirname(__FILE__) );
								?>
								<img src="<?php echo $url_image_defaut; ?>" alt="image à la une" style="width: inherit !important; height: auto; text-align: center;" />
								<?php
							}
						}
					?>
					</div>
				<?php
				}
				global $more;
				$more = 0;
				?>
				<div class="texte_slide" style="text-align: justify; width: 95%; height: auto; margin: 0px 0px 5px 0px; <?php echo $css_position_texte; ?>">
					<?php the_content(__($hestia_config['lien_lire_plus']),FALSE,''); ?>
				</div>
				<?php
				# Ordre de chargement du code si l'image
				# doit être au dessous du texte
				if ( $hestia_config['position'] == "bas" ) {
					?>
					<div class="image_a_la_une" style="width: 95%; height: auto; margin: 0px 0px 5px 0px; <?php echo $css_position_image; ?> overflow: auto;">
						<?php
						if ( $hestia_mise_en_forme['visib_image'] == "1" ) {
							if ( has_post_thumbnail() ) {
								# si l'article à une miniature
								the_post_thumbnail( 'miniature-archives', array( 'style' => 'width: inherit; height: auto; text-align: center' ) );
							} else {
								# s'il n'en a pas...
								$url_image_defaut = plugins_url( 'images/actus_entete_defaut.jpg' , dirname(__FILE__) );
								?>
								<img src="<?php echo $url_image_defaut; ?>" alt="image à la une" style="width: inherit; height: auto; text-align: center;" />
								<?php
							}
						}
					?>
					</div>
				<?php
				}
			?>
			</div>
			<?php
		endwhile;
	wp_reset_postdata();
	echo '</div>';

	# Affichage du bouton "Archives"
	if ( $hestia_mise_en_forme['visib_bouton_archives']=="1" ) { 
	?>
		<div class="voir_actus" style="clear: both; margin-bottom: 10px;">
			<h4>
				<a class="more_news" href="<?php echo get_option('siteurl'); ?>/tag/<?php echo $instance_hestia; ?>">
					<?php echo $lien_voir_actus; ?>
				</a>
			</h4>
		</div>
	<?php 
	};

	# Suite du chargement des scripts
	$params['instances'][] = '#' . $instance_hestia . ' .hestia_slider_photo';
        $instance = '.widget-' . $instance_hestia . ' .hestia_slider_photo';
	$params['direction'][] = $direction;
	$params['vitesse'][] = $vitesse;
	$params['timeout'][] = $timeout;
        # Chargement des scripts
        ?>
        <script type="text/javascript">
            (function($){ 
                jQuery(document).ready(function($){
                    // Fait plusieurs boucles en fonction du nombre d'instances du module
                    $('<?php echo $instance; ?>').cycle({
                        fx: '<?php echo $direction; ?>',
                        speed: <?php echo $vitesse; ?>,
                        timeout: <?php echo $timeout; ?>,
                        pause: 1
                    });
                });
            })(jQuery);
        </script>
        <?php
    }


// ******************************************************************************************
// ************	   Remplace "[...]" (généré automatiquement pour les résumés)	*************
// ************             par une ellipse et le lien configuré dans 		*************
// ************             le lien lire plus... à la fin du résumé		*************
// ************									*************
// ************	 	Pour changer cette valeur dans un sous-template, 	*************
// ************             enlever le filtre et ajoutez votre fonction   	*************
// ************             "the excerpt_length" avec le filter hook.  		*************
// ******************************************************************************************

function hestia_slider_auto_excerpt_more( $more ) {
	global $post;
	$hestia_slider_lire_plus = stripslashes(get_option('hestia_config'));
	return ' &hellip; <a href="'. esc_url( get_permalink() ) . '">'  . $hestia_slider_lire_plus['lien_lire_plus'] . '</a>';
}
add_filter( 'excerpt_more', 'hestia_slider_auto_excerpt_more' );

// ******************************************************************************************
// ************		Ajoute "Continuer la lecture" aux résumés d'articles 	*************
// ************									*************
// ************          Pour changer cette valeur dans un sous-template, 	*************
// ************             enlever le filtre et ajoutez votre fonction   	*************
// ************             "the excerpt_length" avec le filtre hook.  		*************
// ******************************************************************************************

function hestia_slider_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= hestia_slider_auto_excerpt_more();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'hestia_slider_custom_excerpt_more' );

// ******************************************************************************************
// ************		Conserve la mise en forme html des résumés d'articles 	*************
// ******************************************************************************************

function improved_trim_excerpt($text) {
    global $post;
    if ( '' == $text ) {
        $text = get_the_content('');
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]&gt;', $text);
        $text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
        $text = strip_tags($text, '<p><a><strong><br/><font><h2><h3><h4><h5><h6><img><span><ul><li>');
        $excerpt_length = 80;
        $words = explode(' ', $text, $excerpt_length + 1);
        if (count($words)> $excerpt_length) {
            array_pop($words);
            array_push($words, '[...]');
            $text = implode(' ', $words);
        }
    }
    return $text;
}
 
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'improved_trim_excerpt');

?>