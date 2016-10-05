<?php

/*
Plugin Name: Hestia slider
Plugin URI: http://plugins-hestia-multimedia.fr/
Description:  Plugin Wordpress permettant de créer un slider avec image et description séparés ainsi que les paramétres de transition pouvant étre réglés.
Author: HESTIA Multimédia - Christophe JEAN
Version: 3.0
Author URI: http://hestia-multimedia.fr/
Date: Wed October 05 2016
Donate link: 
Tags: wordpress, plugin, photo, slider, hestia
Licence: GPL2
*/

/*  
Copyright 2016  HESTIA Multimédia  (email : contact@hestia-multimedia.fr)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require('includes/module_hestia_slider.php');
require('includes/admin_hestia_slider.php');

# Création du menu admin HESTIA Slider dans Wordpress
add_filter( 'pre_get_posts', 'namespace_add_custom_types' );
	function namespace_add_custom_types( $query ) {
	  if( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
	    $query->set( 'post_type', array(
	     'post', 'post-hestia-slider'
			));
		  return $query;
		}
		if (is_admin()) 
		{
			if (function_exists('add_action')) {
				add_action('admin_menu', 'hestia_slider_add_to_menu');
			}
		}
	}

# Création du Custom Post Type HESTIA Slider	
if (function_exists('add_action')) {
	add_action( 'admin_menu', 'hestia_slider_add_to_menu' );
}
	# Création des sous-menus
	function hestia_slider_add_to_menu() 
	{
		if (function_exists('add_submenu_page')) {
			add_submenu_page('edit.php?post_type=post-hestia-slider', 'Réglages', 'Réglages', 'manage_options', 'Réglages', 'hestia_slider_admin_options');
		}
	}
	
# Réglages de base de la page Administration
# Ajout de la taxonomie "Tags"
add_action('init', 'my_custom_init');
    function my_custom_init() {
    $labels_slider = array(
        'name'                  =>	'HESTIA Slider',
        'singular_name'         => 	'Slider',
        'add_new'               => 	'Ajouter un slide',
        'add_new_item'          => 	'Ajouter un slide',
        'edit_item'             => 	'Editer un slide',
        'new_item'              => 	'Nouveau slide',
        'all_items'             => 	'Tous les slides',
        'view_item'             => 	'Voir slides',
        'search_items'          => 	'Chercher slide',
        'not_found'             =>      'Pas de Slide trouvé',
        'not_found_in_trash'    => 	'Pas de Slide trouvé dans la corbeille', 
        'parent_item_colon'     => 	'',
        'menu_name'             => 	'HESTIA Slider'
    );
    $args_slider = array(
        'labels'                =>      $labels_slider,
        'public'                =>      true,
        'publicly_queryable'    =>      true,
        'show_ui'               =>      true, 
        'show_in_menu'          =>      true, 
        'query_var'             =>      true,
        'rewrite'               =>      array('slug' => 'post-hestia-slider', 'with_front' => false),
        'capability_type'       =>      'post',
        'has_archive'           =>      'post-hestia-slider', 
        'hierarchical'          =>      false,
        'menu_position'         =>      null,
        'taxonomies'            =>      array( 'post_tag' ),
        'supports'              =>      array('title', 'editor', 'author', 'thumbnail')
    );
    register_post_type( 'post-hestia-slider',$args_slider );

    # Ajout et paramètrage de la taxonomie "thumbnails"
    $hestia_slider_largeur_image = stripslashes(get_option('hestia_slider_largeur_image'));
    $hestia_slider_hauteur_image = stripslashes(get_option('hestia_slider_hauteur_image'));
    if (function_exists('add_theme_support')) {
        add_theme_support( 'post-thumbnails' );
    }
    set_post_thumbnail_size( $hestia_slider_largeur, $hestia_slider_hauteur_image ); 
    add_image_size( 'miniature-archives', $hestia_slider_largeur_image, 9999 ); 

    # Création de la taxonomie "Sélection module" 
    function add_select_module_box() {
        add_meta_box('selection_module', __('Sélection du module'), 'selection_module_choix', 'post-hestia-slider', 'side', 'core');
    }	
    add_action('admin_menu', 'add_select_module_box');

    # Gestion de la sélection de l'instance du widget utilisé
    function selection_module_choix () {
        $sidebars_widgets = wp_get_sidebars_widgets();
        empty($liste_widgets);
        $select_module_hestia = stripslashes(get_option('select_module_hestia'));
        $tag_slider_hestia = stripslashes(get_option('tag_slider_hestia'));
        ?>
        <select name="select_module_hestia" id="select_module_hestia">
            <?php
            if ( is_array($sidebars_widgets) ) {
                foreach ( $sidebars_widgets as $sidebar => $widgets ) {
                    if ( $sidebar == 'wp_inactive_widgets' )
                    continue;
                    if ( is_array($widgets) ) {
                        # Cherche l'instance du module correspondant au choix de l'utilisateur
                        # et le sélectionne par défaut dans la boite de dialogue
                        foreach ( $widgets as $widget ) {
                            if ( ( _get_widget_id_base($widget) == 'hestia-slider') ) {
                                $liste_widgets[$index_liste] = $widget;
                                $index_liste += 1;
                                $select_module_hestia_values = get_post_custom_values('select_module_hestia');
                                echo '<option value="' . $widget . '"';
                                if ( $widget==$select_module_hestia_values[0] ) { 
                                    echo ' selected="selected"';
                                    $tag_slider_hestia = $widget;
                                };
                                echo '>' . $widget . '</option>';
                            }
                        }	
                    }
                }	
            }
            ?>
        </select>
        <?php		
        echo '<p>' . $tag_slider_hestia . '</p>';
    }

    # Sauvegarde de la taxonomie "Sélection du module HESTIA"
    function mybox_save_postdata( $post_id ) {
        #   ne rien faire en auto-save
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        #   vérification des droits permissions
        if ( 'post-hestia-slider' == $_POST['post_type'] ) {
            if ( !current_user_can( 'edit_page', $post_id ) ) return;
        } else {
            if ( !current_user_can( 'edit_post', $post_id ) ) return;
        }
        #   ajouter éventuellement des vérifications pour les custom post types
        if(isset($_POST['select_module_hestia'])) {
            update_post_meta($post_id, 'select_module_hestia', $_POST['select_module_hestia']);
        }
    }
    add_action('save_post', 'mybox_save_postdata');

    # Insertion automatique, lors de la sauvegarde, du tag nommé comme le choix du module HESTIA  
    function insert_tag( $post_id ) {
        $term_id = term_exists( $_POST['select_module_hestia'], 'post_tag' );
        if ( !$term_id ) $term_id = wp_insert_term( $_POST['select_module_hestia'], 'post_tag' );
        wp_set_object_terms( $post_id, $_POST['select_module_hestia'], 'post_tag' );
        return $term_id;
    }
    add_action( 'save_post', 'insert_tag' );
}
		
# Initialisation du widget
add_action( 'widgets_init','initialisation_widget_hestia_slider');
    function initialisation_widget_hestia_slider () {
        register_widget('widget_hestia_slider');
    }
    class widget_hestia_slider extends WP_widget {
        function widget_hestia_slider() {
            $options = array(
                'classname' 	=>	'widget-hestia-slider',
                'description'	=>	'Widget permettant de créer un module actualités entièrement configurable et pouvant se placer dans toute zone widgetisable'
            );
            $control = array(
                'width'		=>	'200px',
                'height'	=>	''
            );
            $this->WP_widget ('hestia-slider', 'HESTIA Slider', $options, $control);
        }
        function widget ($args,$instance) {
            $index_liste = 0;
            $liste_widgets;
            extract($args);
            extract($instance);
            echo $before_widget;
            $nom_widget_hestia_slider = 'widget-' . $this->id;
            echo '<div class="' . $nom_widget_hestia_slider . ' widget-hestia-slider">';
            hestia_slider ($args, $this->id, $instance['title'], $instance['voir_actus'], $instance['direction'], $instance['vitesse'], $instance['timeout'], $nom_widget_hestia_slider);
            echo '</div>';
            echo $after_widget;
        }
        function update ($new,$old) {
            return $new;
        }
        function form ($instance) {
            $defaut = array(
                'title'             =>	'Actualités',
                'voir_actus'        =>	'Voir toutes les actualités',
                'die'   =>  'Fade',
                'vitesse'  =>  '700',
                'timeout'  =>  '5000',
                'id'                =>	'0'
            );
            wp_parse_args($instance,$defaut);
            if ( isset( $instance['title'] ) ) {
                $title = $instance['title'];
            }
            else {
                $title = __( 'New title', 'text_domain' );
            }
            if ( isset( $instance['voir_actus'] ) ) {
                $voir_actus = $instance['voir_actus'];
            }
            else {
                $voir_actus = __( 'See all news', 'hestia_slider_traduction' );
            }
            if ( isset( $instance['direction'] ) ) {
                $direction = $instance['direction'];
            }
            else {
                $direction = __( 'fade', 'hestia_slider_traduction' );
            }
            if ( isset( $instance['vitesse'] ) ) {
                $vitesse = $instance['vitesse'];
            }
            else {
                $vitesse = 700;
            }
            if ( isset( $instance['timeout'] ) ) {
                $timeout = $instance['timeout'];
            }
            else {
                $timeout = 5000;
            }
            ?>
            <p>
                Nom du module : <h4>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <?php echo $this->id; ?></h4>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
                <br/><br/>
                <label for="<?php echo $this->get_field_id( 'voir_actus' ); ?>"><?php _e( 'Texte du lien vers la liste des slides : <br/> Par défaut : "Voir toutes les actualités"' ); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'voir_actus' ); ?>" name="<?php echo $this->get_field_name( 'voir_actus' ); ?>" type="text" value="<?php echo esc_attr( $voir_actus ); ?>" />
                <h3>Paramètres du slide</h3>
                <label for="<?php echo $this->get_field_id( 'direction' ); ?>"><?php _e( 'Type de défilement du slide :' ); ?></label>     
                <select class="widefat" id="<?php echo $this->get_field_id( 'direction' ); ?>" name="<?php echo $this->get_field_name( 'direction' ); ?>" >
                    <option value="fade" <?php if ($instance[ 'direction' ]=="fade") echo ' selected="selected"'; ?> >Fade - Fondu (par défaut)</option>
                    <option value="scrollLeft" <?php if ($instance[ 'direction' ]=="scrollLeft") echo ' selected="selected"'; ?>>Scroll Left - Défilement vers la gauche</option>
                    <option value="scrollRight" <?php if ($instance[ 'direction' ]=="scrollRight") echo ' selected="selected"'; ?>>Scroll Right - Défilement vers la droite</option>
                    <option value="scrollUp" <?php if ($instance[ 'direction' ]=="scrollUp") echo ' selected="selected"'; ?>>Scroll Up - Défilement vers le haut</option>
                    <option value="scrollDown" <?php if ($instance[ 'direction' ]=="scrollDown") echo ' selected="selected"'; ?>>Scroll Down - Défilement vers le bas</option>
                    <option value="blindX" <?php if ($instance[ 'direction' ]=="blindX") echo ' selected="selected"'; ?>>Blind X - Effet rouleau horizontal</option>
                    <option value="blindY" <?php if ($instance[ 'direction' ]=="blindY") echo ' selected="selected"'; ?>>Blind Y - Effet rouleau vertical</option>
                    <option value="turnUp" <?php if ($instance[ 'direction' ]=="turnUp") echo ' selected="selected"'; ?>>Turn Up - Effet de dés vers le haut</option>
                    <option value="turnDown" <?php if ($instance[ 'direction' ]=="turnDown") echo ' selected="selected"'; ?>>Turn Down - Effet de dés vers le bas</option>
                    <option value="turnLeft" <?php if ($instance[ 'direction' ]=="turnLeft") echo ' selected="selected"'; ?>>Turn Left - Effet de dés vers la gauche</option>
                    <option value="turnRight" <?php if ($instance[ 'direction' ]=="turnRight") echo ' selected="selected"'; ?>>Turn Right - Effet de dés vers la droite</option>
                    <option value="zoom" <?php if ($instance[ 'direction' ]=="zoom") echo ' selected="selected"'; ?>>Zoom - Effet de Zoom</option>
                </select>
                <br/><br/>
                <label for="<?php echo $this->get_field_id( 'vitesse' ); ?>"><?php _e( 'Vitesse de l\'effet entre 2 slides :' ); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'vitesse' ); ?>" name="<?php echo $this->get_field_name( 'vitesse' ); ?>" type="text" value="<?php echo esc_attr( $vitesse ); ?>" />
                <br/><br/>
                <label for="<?php echo $this->get_field_id( 'timeout' ); ?>"><?php _e( 'Durée d\'affichage d\'une slide :' ); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'timeout' ); ?>" name="<?php echo $this->get_field_name( 'timeout' ); ?>" type="text" value="<?php echo esc_attr( $timeout ); ?>" />
            </p>
            <?php 
            echo '<p><a href="/wp-admin/edit.php?post_type=post-hestia-slider&page=Réglages">Cliquez ici pour accéder aux réglages</a></p>';
        }
    }

# Valeurs par default d'installation
register_activation_hook( __FILE__, 'hestia_slider_install' );
    function hestia_slider_install() 
    {
        $hestia_config_install = array (
            'direction'		=>	"fade", 
            'position'		=>	"haut", 
            'vitesse'		=>	"700", 
            'timeout'		=>	"5000", 
            'nb_slides'		=>	"-1", 
            'largeur_module'	=>	"250", 
            'hauteur_module'	=>	"400",
            'largeur_image'	=>	"220", 
            'hauteur_image'	=>	"165", 
            'lien_lire_plus'	=>	"Lire plus..."
        );
        add_option('hestia_config', $hestia_config_install);
        $hestia_mise_en_forme_install = array (
            'visib_titre_module'	=>	"1", 
            'visib_image'		=>	"1", 
            'visib_date'		=>	"1", 
            'visib_titre'		=>	"1", 
            'titre_cliquable'           =>	"1", 
            'visib_bouton_archives'	=>	"1" 
        );
        add_option('hestia_mise_en_forme', $hestia_mise_en_forme_install);
        $activation_optimise_code_install = array (
            'desactive_optimise_code'	=>	"1", 
        );
        add_option('activation_optimise_code', $activation_optimise_code_install);
    }

register_deactivation_hook( __FILE__, 'hestia_slider_deactivation' );
    function hestia_slider_deactivation() 
    {
    }

?>