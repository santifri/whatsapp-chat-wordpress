<?php
/**
 * Clase de administración del plugin
 *
 * @package WhatsApp_Flotante
 * @since 1.0.0
 */

// Si este archivo es llamado directamente, abortar
if (!defined('WPINC')) {
    die;
}

/**
 * Gestiona el panel de administración del plugin
 */
class WhatsApp_Admin
{

    /**
     * Instancia de Settings
     *
     * @var WhatsApp_Settings
     */
    private $settings;

    /**
     * Constructor
     *
     * @param WhatsApp_Settings $settings Instancia de la clase Settings
     */
    public function __construct($settings)
    {
        $this->settings = $settings;

        // Registrar hooks
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('admin_post_whatsapp_flotante_save', array($this, 'handle_form_submission'));

        // AJAX Tracking
        add_action('wp_ajax_whatsapp_flotante_track_click', array($this, 'track_click'));
        add_action('wp_ajax_nopriv_whatsapp_flotante_track_click', array($this, 'track_click'));
    }

    /**
     * Registrar clic en una cuenta
     */
    public function track_click()
    {
        // Verificar nonce - wp_verify_nonce handles sanitization internally
        if (!isset($_POST['nonce']) || !wp_verify_nonce(wp_unslash($_POST['nonce']), 'whatsapp_flotante_nonce')) {
            wp_send_json_error('Invalid nonce');
        }

        $account_id = isset($_POST['account_id']) ? sanitize_text_field(wp_unslash($_POST['account_id'])) : '';

        if (empty($account_id)) {
            wp_send_json_error('No account ID provided');
        }

        // Validar que el account_id existe en las cuentas guardadas
        $accounts = $this->settings->get_accounts();
        $valid_account = false;
        
        foreach ($accounts as $account) {
            if (isset($account['id']) && $account['id'] === $account_id) {
                $valid_account = true;
                break;
            }
        }

        if (!$valid_account) {
            wp_send_json_error('Invalid account ID');
        }

        // Obtener estadísticas actuales
        $stats = get_option('whatsapp_flotante_stats', array());

        if (!isset($stats[$account_id])) {
            $stats[$account_id] = 0;
        }

        $stats[$account_id]++;

        update_option('whatsapp_flotante_stats', $stats);

        wp_send_json_success(array('count' => $stats[$account_id]));
    }

    /**
     * Añadir página de configuración al menú de administración
     */
    public function add_admin_menu()
    {
        add_options_page(
            __('WhatsApp Flotante', 'whatsapp-flotante'),
            __('WhatsApp Flotante', 'whatsapp-flotante'),
            'manage_options',
            'whatsapp-flotante',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Registrar configuraciones del plugin
     */
    public function register_settings()
    {
        register_setting('whatsapp_flotante_group', WhatsApp_Settings::OPTION_ACCOUNTS);
        register_setting('whatsapp_flotante_group', WhatsApp_Settings::OPTION_CUSTOM_ICON);
        register_setting('whatsapp_flotante_group', WhatsApp_Settings::OPTION_DISCLAIMER);
        register_setting('whatsapp_flotante_group', WhatsApp_Settings::OPTION_ICON_POSITION);
        register_setting('whatsapp_flotante_group', WhatsApp_Settings::OPTION_MARGIN_BOTTOM);
        register_setting('whatsapp_flotante_group', WhatsApp_Settings::OPTION_MARGIN_SIDE);
        register_setting('whatsapp_flotante_group', WhatsApp_Settings::OPTION_MARGIN_BOTTOM_UNIT);
        register_setting('whatsapp_flotante_group', WhatsApp_Settings::OPTION_MARGIN_SIDE_UNIT);

        // Responsive Tablet
        register_setting('whatsapp_flotante_group', WhatsApp_Settings::OPTION_MARGIN_BOTTOM_TABLET);
        register_setting('whatsapp_flotante_group', WhatsApp_Settings::OPTION_MARGIN_SIDE_TABLET);
        register_setting('whatsapp_flotante_group', WhatsApp_Settings::OPTION_MARGIN_BOTTOM_UNIT_TABLET);
        register_setting('whatsapp_flotante_group', WhatsApp_Settings::OPTION_MARGIN_SIDE_UNIT_TABLET);

        // Responsive Mobile
        register_setting('whatsapp_flotante_group', WhatsApp_Settings::OPTION_MARGIN_BOTTOM_MOBILE);
        register_setting('whatsapp_flotante_group', WhatsApp_Settings::OPTION_MARGIN_SIDE_MOBILE);
        register_setting('whatsapp_flotante_group', WhatsApp_Settings::OPTION_MARGIN_BOTTOM_UNIT_MOBILE);
        register_setting('whatsapp_flotante_group', WhatsApp_Settings::OPTION_MARGIN_SIDE_UNIT_MOBILE);

        // Breakpoints
        register_setting('whatsapp_flotante_group', WhatsApp_Settings::OPTION_BREAKPOINT_TABLET);
        register_setting('whatsapp_flotante_group', WhatsApp_Settings::OPTION_BREAKPOINT_MOBILE);
    }

    /**
     * Cargar assets de administración
     *
     * @param string $hook Página actual del admin
     */
    public function enqueue_admin_assets($hook)
    {
        // Solo cargar en nuestra página de configuración
        if ('settings_page_whatsapp-flotante' !== $hook) {
            return;
        }

        // CSS de administración
        wp_enqueue_style(
            'whatsapp-flotante-admin',
            WHATSAPP_FLOTANTE_PLUGIN_URL . 'admin/css/admin-style.css',
            array(),
            WHATSAPP_FLOTANTE_VERSION
        );

        // JavaScript de administración
        wp_enqueue_script(
            'whatsapp-flotante-admin',
            WHATSAPP_FLOTANTE_PLUGIN_URL . 'admin/js/admin-script.js',
            array('jquery'),
            WHATSAPP_FLOTANTE_VERSION,
            true
        );

        // Cargar WordPress Media Library
        wp_enqueue_media();

        // Localizar script con datos
        wp_localize_script(
            'whatsapp-flotante-admin',
            'whatsappFlotanteAdmin',
            array(
                'confirmDelete' => __('¿Estás seguro de que quieres eliminar esta cuenta?', 'whatsapp-flotante'),
                'confirmDeleteSchedule' => __('¿Estás seguro de que quieres eliminar este horario?', 'whatsapp-flotante'),
                'mediaTitle' => __('Seleccionar icono de WhatsApp', 'whatsapp-flotante'),
                'mediaButton' => __('Usar esta imagen', 'whatsapp-flotante'),
                'days' => array(
                    __('D', 'whatsapp-flotante'),
                    __('L', 'whatsapp-flotante'),
                    __('M', 'whatsapp-flotante'),
                    __('X', 'whatsapp-flotante'),
                    __('J', 'whatsapp-flotante'),
                    __('V', 'whatsapp-flotante'),
                    __('S', 'whatsapp-flotante'),
                ),
            )
        );
    }

    /**
     * Renderizar la página de configuración
     */
    public function render_settings_page()
    {
        // Verificar permisos
        if (!current_user_can('manage_options')) {
            wp_die(__('No tienes permisos para acceder a esta página.', 'whatsapp-flotante'));
        }

        // Obtener datos actuales
        $accounts = $this->settings->get_accounts();
        $custom_icon_id = $this->settings->get_custom_icon();
        $custom_icon_url = $custom_icon_id ? wp_get_attachment_image_url($custom_icon_id, 'thumbnail') : '';
        $disclaimer = $this->settings->get_disclaimer();
        $icon_position = $this->settings->get_icon_position();
        $margin_bottom = $this->settings->get_margin_bottom();
        $margin_side = $this->settings->get_margin_side();
        $margin_bottom_unit = $this->settings->get_margin_bottom_unit();
        $margin_side_unit = $this->settings->get_margin_side_unit();

        // Datos Responsive Tablet
        $margin_bottom_tablet = $this->settings->get_margin_bottom_tablet();
        $margin_side_tablet = $this->settings->get_margin_side_tablet();
        $margin_bottom_unit_tablet = $this->settings->get_margin_bottom_unit_tablet();
        $margin_side_unit_tablet = $this->settings->get_margin_side_unit_tablet();

        // Datos Responsive Mobile
        $margin_bottom_mobile = $this->settings->get_margin_bottom_mobile();
        $margin_side_mobile = $this->settings->get_margin_side_mobile();
        $margin_bottom_unit_mobile = $this->settings->get_margin_bottom_unit_mobile();
        $margin_side_unit_mobile = $this->settings->get_margin_side_unit_mobile();

        // Breakpoints
        $breakpoint_tablet = $this->settings->get_breakpoint_tablet();
        $breakpoint_mobile = $this->settings->get_breakpoint_mobile();

        // Cargar template
        include WHATSAPP_FLOTANTE_PLUGIN_DIR . 'admin/templates/settings-page.php';
    }

    /**
     * Manejar el envío del formulario
     */
    public function handle_form_submission()
    {
        // Verificar permisos
        if (!current_user_can('manage_options')) {
            wp_die(__('No tienes permisos para realizar esta acción.', 'whatsapp-flotante'));
        }

        // Verificar nonce - wp_verify_nonce handles sanitization internally
        if (
            !isset($_POST['whatsapp_flotante_nonce']) ||
            !wp_verify_nonce(wp_unslash($_POST['whatsapp_flotante_nonce']), 'whatsapp_flotante_save')
        ) {
            wp_die(__('Error de seguridad. Por favor, intenta de nuevo.', 'whatsapp-flotante'));
        }

        // Guardar cuentas
        $accounts = isset($_POST['accounts']) ? wp_unslash($_POST['accounts']) : array();
        $this->settings->save_accounts($accounts);

        // Guardar icono personalizado
        $custom_icon = isset($_POST['custom_icon']) ? absint($_POST['custom_icon']) : 0;
        $this->settings->save_custom_icon($custom_icon);

        // Guardar disclaimer
        $disclaimer = isset($_POST['disclaimer']) ? wp_unslash($_POST['disclaimer']) : '';
        $this->settings->save_disclaimer($disclaimer);

        // Guardar posición del icono
        $icon_position = isset($_POST['icon_position']) ? sanitize_text_field(wp_unslash($_POST['icon_position'])) : 'bottom-right';
        $this->settings->save_icon_position($icon_position);

        // Guardar márgenes
        $margin_bottom = isset($_POST['margin_bottom']) ? intval($_POST['margin_bottom']) : 20;
        $this->settings->save_margin_bottom($margin_bottom);

        $margin_side = isset($_POST['margin_side']) ? intval($_POST['margin_side']) : 20;
        $this->settings->save_margin_side($margin_side);

        // Guardar unidades de margen
        $margin_bottom_unit = isset($_POST['margin_bottom_unit']) ? sanitize_text_field(wp_unslash($_POST['margin_bottom_unit'])) : 'px';
        $this->settings->save_margin_bottom_unit($margin_bottom_unit);

        $margin_side_unit = isset($_POST['margin_side_unit']) ? sanitize_text_field(wp_unslash($_POST['margin_side_unit'])) : 'px';
        $this->settings->save_margin_side_unit($margin_side_unit);

        // Guardar Tablet
        $margin_bottom_tablet = isset($_POST['margin_bottom_tablet']) ? intval($_POST['margin_bottom_tablet']) : 20;
        $this->settings->save_margin_bottom_tablet($margin_bottom_tablet);

        $margin_side_tablet = isset($_POST['margin_side_tablet']) ? intval($_POST['margin_side_tablet']) : 20;
        $this->settings->save_margin_side_tablet($margin_side_tablet);

        $margin_bottom_unit_tablet = isset($_POST['margin_bottom_unit_tablet']) ? sanitize_text_field(wp_unslash($_POST['margin_bottom_unit_tablet'])) : 'px';
        $this->settings->save_margin_bottom_unit_tablet($margin_bottom_unit_tablet);

        $margin_side_unit_tablet = isset($_POST['margin_side_unit_tablet']) ? sanitize_text_field(wp_unslash($_POST['margin_side_unit_tablet'])) : 'px';
        $this->settings->save_margin_side_unit_tablet($margin_side_unit_tablet);

        // Guardar Mobile
        $margin_bottom_mobile = isset($_POST['margin_bottom_mobile']) ? intval($_POST['margin_bottom_mobile']) : 20;
        $this->settings->save_margin_bottom_mobile($margin_bottom_mobile);

        $margin_side_mobile = isset($_POST['margin_side_mobile']) ? intval($_POST['margin_side_mobile']) : 20;
        $this->settings->save_margin_side_mobile($margin_side_mobile);

        $margin_bottom_unit_mobile = isset($_POST['margin_bottom_unit_mobile']) ? sanitize_text_field(wp_unslash($_POST['margin_bottom_unit_mobile'])) : 'px';
        $this->settings->save_margin_bottom_unit_mobile($margin_bottom_unit_mobile);

        $margin_side_unit_mobile = isset($_POST['margin_side_unit_mobile']) ? sanitize_text_field(wp_unslash($_POST['margin_side_unit_mobile'])) : 'px';
        $this->settings->save_margin_side_unit_mobile($margin_side_unit_mobile);

        // Guardar Breakpoints con validación conjunta
        $breakpoint_tablet = isset($_POST['breakpoint_tablet']) ? intval($_POST['breakpoint_tablet']) : 1024;
        $breakpoint_mobile = isset($_POST['breakpoint_mobile']) ? intval($_POST['breakpoint_mobile']) : 768;
        
        $breakpoint_adjusted = false;
        // Asegurar que tablet > mobile
        if ($breakpoint_tablet <= $breakpoint_mobile) {
            // Si el usuario intenta establecer tablet <= mobile, usar valores estándar
            $breakpoint_tablet = 1024;
            $breakpoint_mobile = min($breakpoint_mobile, 768);
            $breakpoint_adjusted = true;
        }
        
        // Guardar ambos breakpoints
        update_option(WhatsApp_Settings::OPTION_BREAKPOINT_TABLET, absint($breakpoint_tablet));
        update_option(WhatsApp_Settings::OPTION_BREAKPOINT_MOBILE, absint($breakpoint_mobile));

        // Redirigir con mensaje de éxito
        $redirect_args = array(
            'page' => 'whatsapp-flotante',
            'updated' => 'true',
        );
        
        // Añadir parámetro si se ajustaron los breakpoints
        if ($breakpoint_adjusted) {
            $redirect_args['breakpoint_adjusted'] = 'true';
        }
        
        wp_redirect(add_query_arg(
            $redirect_args,
            admin_url('options-general.php')
        ));
        exit;
    }
}
