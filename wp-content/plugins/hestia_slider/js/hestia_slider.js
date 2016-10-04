/*!
 * Hestia Slider javascript/jquery v2.0
 * http://plugins-hestia-multimedia.fr/
 *
 * Copyright 2016, Christophe JEAN
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://hestia-multimedia.fr
 *
 * Date: Wed October 05 2016
 */
 
 // A partir des paramètres transmis par le fichier module_hestia_slider.php
 // Script d'exécution du slider
(function($){ 
    jQuery(document).ready(function($){
        for (var ix_instance = 0; ix_instance <= Math.floor(Object.keys(Hestia_Slider_Params).length/4); ix_instance++) {
            // Fait plusieurs boucles en fonction du nombre d'instances du module
            $(Hestia_Slider_Params["instances"][ix_instance]).cycle({
                fx: Hestia_Slider_Params["direction"][ix_instance],
                speed: Hestia_Slider_Params["vitesse"][ix_instance],
                timeout: Hestia_Slider_Params["timeout"][ix_instance],
                pause: 1
            });
        };
    });
})(jQuery);   