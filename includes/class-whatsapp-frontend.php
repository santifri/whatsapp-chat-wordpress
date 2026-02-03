<?php
/**
 * Clase de frontend del plugin
 *
 * @package WhatsApp_Flotante
 * @since 1.0.0
 */

// Si este archivo es llamado directamente, abortar
if (!defined('WPINC')) {
    die;
}

/**
 * Gestiona la visualización del botón flotante en el frontend
 */
class WhatsApp_Frontend
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
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('wp_footer', array($this, 'render_floating_button'));
    }

    /**
     * Cargar assets de frontend
     */
    public function enqueue_frontend_assets()
    {
        // Solo cargar si hay cuentas disponibles
        $available_accounts = $this->settings->get_available_accounts();

        if (empty($available_accounts)) {
            return;
        }

        // CSS de frontend
        wp_enqueue_style(
            'whatsapp-flotante-frontend',
            WHATSAPP_FLOTANTE_PLUGIN_URL . 'public/css/frontend-style.css',
            array(),
            WHATSAPP_FLOTANTE_VERSION
        );

        // JavaScript de frontend
        wp_enqueue_script(
            'whatsapp-flotante-frontend',
            WHATSAPP_FLOTANTE_PLUGIN_URL . 'public/js/frontend-script.js',
            array('jquery'),
            WHATSAPP_FLOTANTE_VERSION,
            true
        );

        // Obtener URL del icono personalizado
        $custom_icon_id = $this->settings->get_custom_icon();
        $custom_icon_url = '';

        if ($custom_icon_id) {
            $custom_icon_url = wp_get_attachment_image_url($custom_icon_id, 'thumbnail');
        }

        // Si no hay icono personalizado, usar el por defecto
        if (!$custom_icon_url) {
            $custom_icon_url = WHATSAPP_FLOTANTE_PLUGIN_URL . 'assets/images/whatsapp-icon.svg';
        }

        // Preparar datos de cuentas para JavaScript
        $accounts_data = array();
        foreach ($available_accounts as $account) {
            $accounts_data[] = array(
                'id' => isset($account['id']) ? $account['id'] : '',
                'label' => $account['label'],
                'phone' => $account['phone'],
                'message' => isset($account['message']) ? $account['message'] : '',
                'status_message' => isset($account['status_message']) ? $account['status_message'] : '',
            );
        }

        // Localizar script con datos
        wp_localize_script(
            'whatsapp-flotante-frontend',
            'whatsappFlotanteFrontend',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('whatsapp_flotante_nonce'),
                'accounts' => $accounts_data,
                'iconUrl' => $custom_icon_url,
                'iconPosition' => $this->settings->get_icon_position(),
                'marginBottom' => $this->settings->get_margin_bottom(),
                'marginSide' => $this->settings->get_margin_side(),
                'marginBottomUnit' => $this->settings->get_margin_bottom_unit(),
                'marginSideUnit' => $this->settings->get_margin_side_unit(),
            )
        );
    }

    /**
     * Renderizar el botón flotante y el modal
     */
    public function render_floating_button()
    {
        // Solo renderizar si hay cuentas disponibles
        $available_accounts = $this->settings->get_available_accounts();

        if (empty($available_accounts)) {
            return;
        }

        // Obtener URL del icono
        $custom_icon_id = $this->settings->get_custom_icon();
        $icon_url = '';

        if ($custom_icon_id) {
            $icon_url = wp_get_attachment_image_url($custom_icon_id, 'thumbnail');
        }

        if (!$icon_url) {
            $icon_url = WHATSAPP_FLOTANTE_PLUGIN_URL . 'assets/images/whatsapp-icon.svg';
        }

        // Obtener disclaimer
        $disclaimer = $this->settings->get_disclaimer();

        // Obtener posición del icono
        $icon_position = $this->settings->get_icon_position();

        // Obtener márgenes y unidades (Desktop)
        $margin_bottom = $this->settings->get_margin_bottom();
        $margin_side = $this->settings->get_margin_side();
        $margin_bottom_unit = $this->settings->get_margin_bottom_unit();
        $margin_side_unit = $this->settings->get_margin_side_unit();

        // Obtener márgenes y unidades (Tablet)
        $margin_bottom_tablet = $this->settings->get_margin_bottom_tablet();
        $margin_side_tablet = $this->settings->get_margin_side_tablet();
        $margin_bottom_unit_tablet = $this->settings->get_margin_bottom_unit_tablet();
        $margin_side_unit_tablet = $this->settings->get_margin_side_unit_tablet();

        // Obtener márgenes y unidades (Mobile)
        $margin_bottom_mobile = $this->settings->get_margin_bottom_mobile();
        $margin_side_mobile = $this->settings->get_margin_side_mobile();
        $margin_bottom_unit_mobile = $this->settings->get_margin_bottom_unit_mobile();
        $margin_side_unit_mobile = $this->settings->get_margin_side_unit_mobile();

        // Obtener Breakpoints
        $bp_tablet = $this->settings->get_breakpoint_tablet();
        $bp_mobile = $this->settings->get_breakpoint_mobile();

        // Determinar lado (left/right)
        $side = (strpos($icon_position, 'left') !== false) ? 'left' : 'right';

        // Determinar clase de posición
        $position_class = 'wf-position-' . $icon_position;

        // Generar CSS Dinámico
        echo '<style>
            /* Desktop (Default) */
            #whatsapp-flotante-btn {
                bottom: ' . esc_attr($margin_bottom . $margin_bottom_unit) . ';
                ' . esc_attr($side) . ': ' . esc_attr($margin_side . $margin_side_unit) . ';
            }
            
            /* Tablet */
            @media (max-width: ' . esc_attr($bp_tablet) . 'px) {
                #whatsapp-flotante-btn {
                    bottom: ' . esc_attr($margin_bottom_tablet . $margin_bottom_unit_tablet) . ';
                    ' . esc_attr($side) . ': ' . esc_attr($margin_side_tablet . $margin_side_unit_tablet) . ';
                }
            }
            
            /* Mobile */
            @media (max-width: ' . esc_attr($bp_mobile) . 'px) {
                #whatsapp-flotante-btn {
                    bottom: ' . esc_attr($margin_bottom_mobile . $margin_bottom_unit_mobile) . ';
                    ' . esc_attr($side) . ': ' . esc_attr($margin_side_mobile . $margin_side_unit_mobile) . ';
                }
            }
        </style>';

        // Cargar templates
        include WHATSAPP_FLOTANTE_PLUGIN_DIR . 'public/templates/floating-button.php';
        include WHATSAPP_FLOTANTE_PLUGIN_DIR . 'public/templates/modal.php';
    }
}
