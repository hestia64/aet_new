<?php

# Administration pour changer les valeurs du module
function hestia_slider_admin_options() 
{
		$hestia_config = get_option('hestia_config');
		$hestia_mise_en_forme = get_option('hestia_mise_en_forme');
		$activation_optimise_code = get_option('activation_optimise_code');
	
		if ($_POST['hestia_slider_submit']) 
		{
			$hestia_config = array (
				'direction'		=>	stripslashes ( $_POST['direction'] ), 
				'position'		=>	stripslashes ( $_POST['position'] ), 
				'vitesse'		=>	stripslashes ( $_POST['vitesse'] ), 
				'timeout'		=>	stripslashes ( $_POST['timeout'] ), 
				'nb_slides'		=>	stripslashes ( $_POST['nb_slides'] ), 
				'largeur_module'	=>	stripslashes ( $_POST['largeur_module'] ), 
				'hauteur_module'	=>	stripslashes ( $_POST['hauteur_module'] ),
				'largeur_image'		=>	stripslashes ( $_POST['largeur_image'] ), 
				'hauteur_image'		=>	stripslashes ( $_POST['hauteur_image'] ), 
				'lien_lire_plus'	=>	stripslashes ( $_POST['lien_lire_plus'] )
			);
			update_option('hestia_config', $hestia_config);
		}
	
		if ($_POST['config_slider_submit']) 
		{
			$hestia_mise_en_forme = array (
				'visib_titre_module'		=>	stripslashes ( $_POST['visib_titre_module'] ), 
				'visib_image'			=>	stripslashes ( $_POST['visib_image'] ), 
				'visib_date'			=>	stripslashes ( $_POST['visib_date'] ), 
				'visib_titre'			=>	stripslashes ( $_POST['visib_titre'] ), 
				'titre_cliquable'		=>	stripslashes ( $_POST['titre_cliquable'] ), 
				'visib_bouton_archives'		=>	stripslashes ( $_POST['visib_bouton_archives'] )
			);
			update_option('hestia_mise_en_forme', $hestia_mise_en_forme );
		}
	
		if ($_POST['optimise_code_submit']) 
		{
			$activation_optimise_code = array (
				'desactive_optimise_code'	=>	stripslashes ( $_POST['desactive_optimise_code'] )
			);
			update_option('activation_optimise_code', $activation_optimise_code );
		}
		
		if ($_POST['hestia_slider_defaut']) 
		{
			$hestia_config_defaut = array (
				'direction'		=>	"fade",
				'position'		=>	"haut",
				'vitesse'		=>	"700", 
				'timeout'		=>	"5000", 
				'nb_slides'		=>	"-1", 
				'largeur_module'	=>	"250", 
				'hauteur_module'	=>	"400",
				'largeur_image'		=>	"220", 
				'hauteur_image'		=>	"165", 
				'lien_lire_plus'	=>	"Lire plus..."
			);
			$hestia_config = $hestia_config_defaut;
			update_option('hestia_config', $hestia_config_defaut);
			
			$hestia_mise_en_forme_defaut = array (
				'visib_titre_module'		=>	"1", 
				'visib_image'			=>	"1", 
				'visib_date'			=>	"1", 
				'visib_titre'			=>	"1", 
				'titre_cliquable'		=>	"1", 
				'visib_bouton_archives'		=>	"1", 
				'desactive_optimise_code'	=>	"1"
			);
			$hestia_mise_en_forme = $hestia_mise_en_forme_defaut;
			update_option('hestia_mise_en_forme', $hestia_mise_en_forme_defaut);
	
			$activation_optimise_code_defaut = array (
				'desactive_optimise_code'	=>	"1"
			);
			$activation_optimise_code = $activation_optimise_code_defaut;
			update_option('activation_optimise_code', $activation_optimise_code_defaut );
		}
	?>
	<!-- Code html de la page administration -->
	<h1 style="font-family: georgia, verdana, arial; text-align: center; color: #993333"><?php echo wp_specialchars( "Réglages du plugin Hestia Slider" ) ; ?></h1>
	<form name="config_slider_defaut" method="post" action="" style="width: 300px; margin: 10px auto">
		<input name="hestia_slider_defaut" id="hestia_slider_defaut" class="button-primary" value="Re-initialiser tous les paramètres par défaut" type="submit" style="width: inherit;" />
	</form>
	<!-- Formulaire de saisie des paramètres du module -->
	<div id="contenu_slide" class="" style="font-family: georgia, verdana, arial; width: 100%; min-height: 750px; float: left; margin-top: 10px">
		<div  id="params_slide" class="postbox" style="width: 40%; min-height: 650px; padding: 0px 15px 15px 15px; float: left">
			<h2 class="hndle" style="text-align: center; color: #999999"><span><?php echo wp_specialchars( "Paramètres du module" ) ; ?></h2>
			<form name="hestia_slider_form" method="post" action="">
			<!--	<p><FIELDSET><LEGEND align=top ><h3>Effet du Slider :</h3></LEGEND>
				<select style="width: 250px;" maxlength="50" type="text" name="direction">
					 <option value="fade" <?php if ($hestia_config['direction']=="fade") echo ' selected="selected"'; ?> >Fade - Fondu (par défaut)</option>
					 <option value="scrollLeft" <?php if ($hestia_config['direction']=="scrollLeft") echo ' selected="selected"'; ?>>Scroll Left - Défilement vers la gauche</option>
					 <option value="scrollRight" <?php if ($hestia_config['direction']=="scrollRight") echo ' selected="selected"'; ?>>Scroll Right - Défilement vers la droite</option>
					 <option value="scrollUp" <?php if ($hestia_config['direction']=="scrollUp") echo ' selected="selected"'; ?>>Scroll Up - Défilement vers le haut</option>
					 <option value="scrollDown" <?php if ($hestia_config['direction']=="scrollDown") echo ' selected="selected"'; ?>>Scroll Down - Défilement vers le bas</option>
					 <option value="blindX" <?php if ($hestia_config['direction']=="blindX") echo ' selected="selected"'; ?>>Blind X - Effet rouleau horizontal</option>
					 <option value="blindY" <?php if ($hestia_config['direction']=="blindY") echo ' selected="selected"'; ?>>Blind Y - Effet rouleau vertical</option>
					 <option value="turnUp" <?php if ($hestia_config['direction']=="turnUp") echo ' selected="selected"'; ?>>Turn Up - Effet de dés vers le haut</option>
					 <option value="turnDown" <?php if ($hestia_config['direction']=="turnDown") echo ' selected="selected"'; ?>>Turn Down - Effet de dés vers le bas</option>
					 <option value="turnLeft" <?php if ($hestia_config['direction']=="turnLeft") echo ' selected="selected"'; ?>>Turn Left - Effet de dés vers la gauche</option>
					 <option value="turnRight" <?php if ($hestia_config['direction']=="turnRight") echo ' selected="selected"'; ?>>Turn Right - Effet de dés vers la droite</option>
					 <option value="zoom" <?php if ($hestia_config['direction']=="zoom") echo ' selected="selected"'; ?>>Zoom - Effet de Zoom</option>
				</select>
				</FIELDSET></p>
			-->	<p><FIELDSET><LEGEND align=top ><h3 style="margin-bottom: 5px !important">Position de l'image par rapport au texte :</h3></LEGEND>
					<INPUT type= "radio" name="position" value="haut" <?php if ($hestia_config['position']=="haut") echo ' checked="checked"'; ?> >
					<LABEL for="position"> Image au dessus du texte &nbsp; &nbsp; &nbsp;</LABEL>
					<INPUT type= "radio" name="position" value="bas" <?php if ($hestia_config['position']=="bas") echo ' checked="checked"'; ?> >
					<LABEL for="position"> Image en dessous du texte &nbsp; &nbsp; &nbsp;</LABEL>
					<br/>
					<br/>
					<INPUT type= "radio" name="position" value="gauche" <?php if ($hestia_config['position']=="gauche") echo ' checked="checked"'; ?> >
					<LABEL for="position"> Image à gauche du texte &nbsp; &nbsp; &nbsp;</LABEL>
					<INPUT type= "radio" name="position" value="droite" <?php if ($hestia_config['position']=="droite") echo ' checked="checked"'; ?> >
					<LABEL for="position"> Image à droite du texte &nbsp; &nbsp; &nbsp;</LABEL>
				</FIELDSET></p>
			<!--	<p><FIELDSET><LEGEND align=top ><h3 style="margin-bottom: 5px !important">Vitesse de l&apos;effet :</h3></LEGEND>
					<input  style="width: 45px;" maxlength="4" type="text" value="<?php echo $hestia_config['vitesse'] ?>" name="vitesse" id="vitesse" />
					&nbsp;( Temps en millisecondes - par défaut "700" )
				</FIELDSET></p>
				<p><FIELDSET><LEGEND align=top ><h3 style="margin-bottom: 5px !important">Délai entre 2 slides :</h3></LEGEND>
					<input  style="width: 45px;" maxlength="4" type="text" value="<?php echo $hestia_config['timeout'] ?>" name="timeout" id="timeout" />
					&nbsp;( Temps en millisecondes - par défaut "5000" )
				</FIELDSET></p>
			-->	<p><FIELDSET><LEGEND align=top ><h3 style="margin-bottom: 5px !important">Nombre de slides :</h3></LEGEND>
					<input  style="width: 45px;" maxlength="4" type="text" value="<?php echo $hestia_config['nb_slides'] ?>" name="nb_slides" id="nd_slides" />
					&nbsp;( Entrez le nombre de slides - par défaut "-1" pour toutes )
				</FIELDSET></p>
				<p><FIELDSET><LEGEND align=top ><h3 style="margin-bottom: 5px !important">Largeur du module :</h3></LEGEND>
					<input  style="width: 45px;" maxlength="4" type="text" value="<?php echo $hestia_config['largeur_module'] ?>" name="largeur_module" id="largeur_module" />
					&nbsp;( Entrez la largeur du module en pixels - par défaut "250" )
				</FIELDSET></p>
				<p><FIELDSET><LEGEND align=top ><h3 style="margin-bottom: 5px !important">Hauteur du module :</h3></LEGEND>
					<input  style="width: 45px;" maxlength="4" type="text" value="<?php echo $hestia_config['hauteur_module'] ?>" name="hauteur_module" id="hauteur_module" />
					&nbsp;( Entrez la hauteur du module en pixels - par défaut "400" )
				</FIELDSET></p>
				<p><FIELDSET><LEGEND align=top ><h3 style="margin-bottom: 5px !important">Largeur de l&apos;image à la une :</h3></LEGEND>
					<input  style="width: 45px;" maxlength="4" type="text" value="<?php echo $hestia_config['largeur_image'] ?>" name="largeur_image" id="largeur_image" />
					&nbsp;( Entrez la largeur de l&apos;image à la une en pixels - par défaut "220" )
				</FIELDSET></p>
				<p><FIELDSET><LEGEND align=top ><h3 style="margin-bottom: 5px !important">Hauteur de l&apos;image à la une :</h3></LEGEND>
					<input  style="width: 45px;" maxlength="4" type="text" value="<?php echo $hestia_config['hauteur_image'] ?>" name="hauteur_image" id="hauteur_image" />
					&nbsp;( Entrez la hauteur de l&apos;image à la une en pixels - par défaut "165" )
				</FIELDSET></p>
			<!--	<p><FIELDSET><LEGEND align=top ><h3 style="margin-bottom: 5px !important">Texte du lien "Lire plus..." :</h3></LEGEND>
					<input  style="width: 150px;" maxlength="20" type="text" value="<?php echo $hestia_config['lien_lire_plus'] ?>" name="lien_lire_plus" id="lien_lire_plus" />
					<br/>&nbsp;( Saisissez le texte à afficher - par défaut "Lire plus..." )
				</FIELDSET></p>
			-->	<input name="hestia_slider_submit" id="hestia_slider_submit" class="button-primary" value="Enregistrer les paramètres du module" type="submit" style="float: left;  width: 300px; margin: 10px 10px 10px 10px;" />
			</form>
		</div>
		<!-- Formulaire de saisie des options du module -->
		<div id="options_slide" class="postbox" style="width: 50%; min-height: 250px; padding: 0px 15px 15px 15px; margin: 0px 15px 0px 0px; float: right;">
			<h2 style="text-align: center; color: #999999"><?php echo wp_specialchars( "Mise en forme du module" ) ; ?></h2>
			<form name="config_slider_form" method="post" action="">
				<div id="form_col_gauche" style="width: 50%; float: left;" >
					<p>
					<FIELDSET>
						<LEGEND align=top ><h3>Visibilité du titre du module :</h3></LEGEND>
							<INPUT name="visib_titre_module" type=checkbox value="1" id="visib_titre_module" <?php if($hestia_mise_en_forme['visib_titre_module']=="1") echo ' checked="checked"'; ?>>
							<LABEL for="visib_titre_module">Cochez la case si vous voulez voir le titre du slider.</LABEL> 
					</FIELDSET>
					</p>
					<p>
					<FIELDSET>
						<LEGEND align=top ><h3>Visibilité de l'image à la une :</h3></LEGEND>
							<INPUT name="visib_image" type=checkbox value="1" id="visib_image" <?php if($hestia_mise_en_forme['visib_image']=="1") echo ' checked="checked"'; ?>>
							<LABEL for="visib_image">Cochez la case si vous voulez voir l'image à la une.</LABEL> 
					</FIELDSET>
					</p>
					<p>
					<FIELDSET>
						<LEGEND align=top ><h3>Visibilité du date de l'article :</h3></LEGEND>
							<INPUT name="visib_date" type=checkbox value="1" id="visib_date" <?php if($hestia_mise_en_forme['visib_date']=="1") echo ' checked="checked"'; ?>>
							<LABEL for="visib_date">Cochez la case si vous voulez voir la date de l'article.</LABEL> 
					</FIELDSET>
					</p>
				</div>
				<div id="form_col_droite" style="width: 50%; float: right;" >
					<p>
					<FIELDSET>
						<LEGEND align=top ><h3>Visibilité du titre de l'article :</h3></LEGEND>
							<INPUT name="visib_titre" type=checkbox value="1" id="visib_titre" <?php if($hestia_mise_en_forme['visib_titre']=="1") echo ' checked="checked"'; ?>>
							<LABEL for="visib_titre">Cochez la case si vous voulez voir le titre de l'article.</LABEL> 
					</FIELDSET>
					</p>
					<p>
					<FIELDSET>
						<LEGEND align=top ><h3>Titre cliquable :</h3></LEGEND>
							<INPUT name="titre_cliquable" type=checkbox value="1" id="titre_cliquable" <?php if($hestia_mise_en_forme['titre_cliquable']=="1") echo ' checked="checked"'; ?>>
							<LABEL for="titre_cliquable">Cochez la case si vous voulez que le titre soit cliquable.</LABEL> 
					</FIELDSET>
					</p>
					<p>
					<FIELDSET>
						<LEGEND align=top ><h3>Visibilité du bouton "Toutes les archives" :</h3></LEGEND>
							<INPUT name="visib_bouton_archives" type=checkbox value="1" id="visib_bouton_archives" <?php if($hestia_mise_en_forme['visib_bouton_archives']=="1") echo ' checked="checked"'; ?>>
							<LABEL for="visib_bouton_archives">Cochez la case si vous voulez voir le bouton "Toutes les archives".</LABEL> 
					</FIELDSET>
					</p>
				</div>
				<input name="config_slider_submit" id="config_slider_submit" class="button-primary" value="Enregistrer la mise en forme du module" type="submit" style="float: left;  width: 300px; margin: 10px 10px 10px 10px;" />
			</form>
		</div>
		<!-- Explications sur le fonctionnement du module -->
		<!--
                <div id="details_plugin_slider" class="postbox" style="width: 50%; min-height: 250px; padding: 0px 15px 15px 15px; margin: 15px 15px 0px 0px; float: right;">
			<h2 style="text-align: center; color: #999999"><?php echo wp_specialchars( "Explications" ) ; ?></h2>
			<p><h4>
				Plugin Wordpress permettant de créer un slider avec image et description séparés.<br/>
				<br/>
				Les paramétres de transition, la taille du module et des images peuvent étre réglés.<br/>
				<br/><br/>
				<hr/>
				<br/><br/>
				<span style="color: #993333;">Par défaut, les scripts javascript/jquery sont les dernières versions disponibles et placés à la fin du code afin d'optimiser la rapidité du site.<br/><br/>
				Cependant, il se peut que certains plugins utilisant javascript/jquery ne fonctionnent plus car leur codes n'ont pas été optimisés. <br/><br/>
				</span>
			</h4></p>
			<form name="optimise_code_form" method="post" action="">
				<p>
				<FIELDSET>
					<LEGEND align=top ><h3>Dans ce cas, décochez la case ci-dessous afin de désactiver cette fonction : </h3></LEGEND>
						<INPUT name="desactive_optimise_code" type=checkbox value="1" id="desactive_optimise_code" <?php if($activation_optimise_code['desactive_optimise_code']=="1") echo ' checked="checked"'; ?>>
				</FIELDSET>
				</p>
				<input name="optimise_code_submit" id="optimise_code_submit" class="button-primary" value="Valider l'activation/désactivation du code javascript" type="submit" style="float: left;  width: 300px; margin: 10px 10px 10px 10px;" />
			</form>
		</div>
                -->
	</div>
	<!-- Copyright du module -->
	<div id="copyright_slider" style=" clear: both; float: left; width: 100%; height: 30px; padding-top: 5px">
		<h3 style="text-align: center; color: #993333">Plugin HESTIA Slider v3.0 créé par HESTIA Multimédia &#169; 2016</h3>
	</div>
	<?php 	
}
?>