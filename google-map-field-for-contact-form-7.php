<?php
/*
Plugin Name: Map Field For Contact Form 7
description: Google Map for contact form 7 show as field
Version: 1.0
Author: Gravity Master
License: GPL2
*/

/* Stop immediately if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
   die();
}

/* All constants should be defined in this file. */
if ( ! defined( 'GMFCF_PLUGIN_DIR' ) ) {
   define( 'GMFCF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'GMFCF_PLUGIN_BASENAME' ) ) {
   define( 'GMFCF_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'GMFCF_PLUGIN_URL' ) ) {
   define( 'GMFCF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/* Auto-load all the necessary classes. */
if( ! function_exists( 'GMFCF_class_auto_loader' ) ) {
   
   function GMFCF_class_auto_loader( $class ) {
      
      $includes = GMFCF_PLUGIN_DIR . 'includes/' . $class . '.php';
      
      if( is_file( $includes ) && ! class_exists( $class ) ) {
         include_once( $includes );
         return;
      }
      
   }
}
spl_autoload_register('GMFCF_class_auto_loader');

/* Initialize all modules now. */

if (!function_exists('is_plugin_active')) {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

if ( ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) ) {
   new GMFCF_Backend();
   new GMFCF_Display();
   new GMFCF_Frontend();
}