<?php
/**
 * Plugin Name: Quantum Addons
 * Description: Elementor Addons Plugin
 * Version: 0.1
 * Author: Abhishek Yesankar
 * Author URI: https://github.com/Abhishek-Yesankar/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: quantum-addons
 * 
 * Elementor tested up to: 3.0.0
 * Elementor Pro tested up to: 3.0.0
 * 
 * 
 * Quantum Addons is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * 
 * Quantum Addons is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Quantum Addons. If not, see https://www.gnu.org/licenses/gpl-3.0.html.
 */

define( 'QUANTUM_DIR', plugin_dir_path( __FILE__ ) );
define( 'QUANTUM_URL', plugin_dir_url( __FILE__ ) );

// Elementor init
function qa_register_elementor_widgets( $widgets_manager )  {
   require_once( __DIR__ . '/widgets/advance-slider.php' );

   $widgets_manager->register( new \advance_slider() );
}

add_action( 'elementor/widgets/register', 'qa_register_elementor_widgets' );


function qa_register_widget_scripts()  {
   wp_register_script( 'advance-slider-script', plugins_url( 'assets/js/advance-slider.js', __FILE__ ), ['jquery', 'elementor-frontend'], time(), true );
}

add_action( 'wp_enqueue_scripts', 'qa_register_widget_scripts' );