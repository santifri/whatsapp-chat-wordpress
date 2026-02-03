<?php
/**
 * Clase principal del plugin WhatsApp Flotante
 *
 * @package WhatsApp_Flotante
 * @since 1.0.0
 */

// Si este archivo es llamado directamente, abortar
if (!defined('WPINC')) {
    die;
}

/**
 * Clase principal que orquesta todos los componentes del plugin
 */
class WhatsApp_Flotante
{

    /**
     * Instancia de la clase Settings
     *
     * @var WhatsApp_Settings
     */
    protected $settings;

    /**
     * Instancia de la clase Admin
     *
     * @var WhatsApp_Admin
     */
    protected $admin;

    /**
     * Instancia de la clase Frontend
     *
     * @var WhatsApp_Frontend
     */
    protected $frontend;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_frontend_hooks();
    }

    /**
     * Cargar las dependencias del plugin
     */
    private function load_dependencies()
    {
        // Cargar clase de configuración
        require_once WHATSAPP_FLOTANTE_PLUGIN_DIR . 'includes/class-whatsapp-settings.php';

        // Cargar clase de administración
        require_once WHATSAPP_FLOTANTE_PLUGIN_DIR . 'includes/class-whatsapp-admin.php';

        // Cargar clase de frontend
        require_once WHATSAPP_FLOTANTE_PLUGIN_DIR . 'includes/class-whatsapp-frontend.php';

        // Instanciar las clases
        $this->settings = new WhatsApp_Settings();
        $this->admin = new WhatsApp_Admin($this->settings);
        $this->frontend = new WhatsApp_Frontend($this->settings);
    }

    /**
     * Definir la configuración regional para internacionalización
     */
    private function set_locale()
    {
        add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
    }

    /**
     * Cargar el dominio de texto del plugin para traducción
     */
    public function load_plugin_textdomain()
    {
        load_plugin_textdomain(
            'whatsapp-flotante',
            false,
            dirname(WHATSAPP_FLOTANTE_PLUGIN_BASENAME) . '/languages/'
        );
    }

    /**
     * Registrar todos los hooks relacionados con el área de administración
     */
    private function define_admin_hooks()
    {
        // Los hooks se registran dentro de la clase Admin
    }

    /**
     * Registrar todos los hooks relacionados con el frontend
     */
    private function define_frontend_hooks()
    {
        // Los hooks se registran dentro de la clase Frontend
    }

    /**
     * Ejecutar el plugin
     */
    public function run()
    {
        // El plugin ya está configurado en el constructor
        // Este método existe para mantener la consistencia con el patrón de diseño
    }
}
