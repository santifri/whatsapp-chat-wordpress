<?php

/*
 * Plugin Name: WhatsApp Chat para WordPress
 * Plugin URI: https://santifrias.com
 * Description: Botón flotante de WhatsApp con múltiples cuentas, horarios configurables y personalización avanzada
 * Version: 1.0.1
 * Author: Santi Frías
 * Author URI: https://santifrias.com
 * Text Domain: sf-whatsapp-chat-wordpress
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * 
 * Security: Este plugin implementa las mejores prácticas de seguridad de WordPress:
 * - Validación y sanitización de todas las entradas de usuario
 * - Protección CSRF mediante nonces en todos los formularios y AJAX
 * - Escape de salida en todas las plantillas
 * - Verificación de capacidades de usuario
 * - Validación de tipos de archivo para carga de imágenes
 */




// Si este archivo es llamado directamente, abortar
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Definir constantes del plugin
define( 'WHATSAPP_FLOTANTE_VERSION', '1.0.1' );
define( 'WHATSAPP_FLOTANTE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WHATSAPP_FLOTANTE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WHATSAPP_FLOTANTE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Verificar versión mínima de WordPress
 */
function whatsapp_flotante_check_version() {
    global $wp_version;
    
    if ( version_compare( $wp_version, '5.0', '<' ) ) {
        deactivate_plugins( WHATSAPP_FLOTANTE_PLUGIN_BASENAME );
        wp_die(
            __( 'Este plugin requiere WordPress 5.0 o superior.', 'whatsapp-flotante' ),
            __( 'Error de activación del plugin', 'whatsapp-flotante' ),
            array( 'back_link' => true )
        );
    }
}
register_activation_hook( __FILE__, 'whatsapp_flotante_check_version' );

/**
 * Cargar el archivo de la clase principal
 */
require_once WHATSAPP_FLOTANTE_PLUGIN_DIR . 'includes/class-whatsapp-flotante.php';

/**
 * Iniciar el plugin
 */
function run_whatsapp_flotante() {
    $plugin = new WhatsApp_Flotante();
    $plugin->run();
}
run_whatsapp_flotante();
