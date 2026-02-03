<?php
/**
 * Clase de configuración y gestión de opciones
 *
 * @package WhatsApp_Flotante
 * @since 1.0.0
 */

// Si este archivo es llamado directamente, abortar
if (!defined('WPINC')) {
    die;
}

/**
 * Gestiona todas las opciones del plugin y validación de datos
 */
class WhatsApp_Settings
{

    /**
     * Nombre de la opción para las cuentas
     *
     * @var string
     */
    const OPTION_ACCOUNTS = 'whatsapp_flotante_accounts';

    /**
     * Nombre de la opción para el icono personalizado
     *
     * @var string
     */
    const OPTION_CUSTOM_ICON = 'whatsapp_flotante_custom_icon';

    /**
     * Nombre de la opción para el disclaimer
     *
     * @var string
     */
    const OPTION_DISCLAIMER = 'whatsapp_flotante_disclaimer';

    /**
     * Nombre de la opción para la posición del icono
     *
     * @var string
     */
    const OPTION_ICON_POSITION = 'whatsapp_flotante_icon_position';

    // Opciones de márgenes
    const OPTION_MARGIN_BOTTOM = 'whatsapp_flotante_margin_bottom';
    const OPTION_MARGIN_SIDE = 'whatsapp_flotante_margin_side';

    // Opciones de unidades de margen
    const OPTION_MARGIN_BOTTOM_UNIT = 'whatsapp_flotante_margin_bottom_unit';
    const OPTION_MARGIN_SIDE_UNIT = 'whatsapp_flotante_margin_side_unit';

    // Opciones Responsive (Tablet)
    const OPTION_MARGIN_BOTTOM_TABLET = 'whatsapp_flotante_margin_bottom_tablet';
    const OPTION_MARGIN_SIDE_TABLET = 'whatsapp_flotante_margin_side_tablet';
    const OPTION_MARGIN_BOTTOM_UNIT_TABLET = 'whatsapp_flotante_margin_bottom_unit_tablet';
    const OPTION_MARGIN_SIDE_UNIT_TABLET = 'whatsapp_flotante_margin_side_unit_tablet';

    // Opciones Responsive (Mobile)
    const OPTION_MARGIN_BOTTOM_MOBILE = 'whatsapp_flotante_margin_bottom_mobile';
    const OPTION_MARGIN_SIDE_MOBILE = 'whatsapp_flotante_margin_side_mobile';
    const OPTION_MARGIN_BOTTOM_UNIT_MOBILE = 'whatsapp_flotante_margin_bottom_unit_mobile';
    const OPTION_MARGIN_SIDE_UNIT_MOBILE = 'whatsapp_flotante_margin_side_unit_mobile';

    // Breakpoints
    const OPTION_BREAKPOINT_TABLET = 'whatsapp_flotante_breakpoint_tablet'; // Desktop -> Tablet
    const OPTION_BREAKPOINT_MOBILE = 'whatsapp_flotante_breakpoint_mobile'; // Tablet -> Mobile

    /**
     * Constructor
     */
    public function __construct()
    {
        // Realizar migración de datos si es necesario
        add_action('admin_init', array($this, 'maybe_migrate_data'));
    }

    // ... (Getters y Setters existentes para Desktop) ...

    /**
     * Obtener margen inferior (Tablet)
     */
    public function get_margin_bottom_tablet()
    {
        return (int) get_option(self::OPTION_MARGIN_BOTTOM_TABLET, 20);
    }

    public function save_margin_bottom_tablet($margin)
    {
        return update_option(self::OPTION_MARGIN_BOTTOM_TABLET, absint($margin));
    }

    /**
     * Obtener margen lateral (Tablet)
     */
    public function get_margin_side_tablet()
    {
        return (int) get_option(self::OPTION_MARGIN_SIDE_TABLET, 20);
    }

    public function save_margin_side_tablet($margin)
    {
        return update_option(self::OPTION_MARGIN_SIDE_TABLET, absint($margin));
    }

    /**
     * Obtener unidad margen inferior (Tablet)
     */
    public function get_margin_bottom_unit_tablet()
    {
        return get_option(self::OPTION_MARGIN_BOTTOM_UNIT_TABLET, 'px');
    }

    public function save_margin_bottom_unit_tablet($unit)
    {
        return update_option(self::OPTION_MARGIN_BOTTOM_UNIT_TABLET, $this->sanitize_unit($unit));
    }

    /**
     * Obtener unidad margen lateral (Tablet)
     */
    public function get_margin_side_unit_tablet()
    {
        return get_option(self::OPTION_MARGIN_SIDE_UNIT_TABLET, 'px');
    }

    public function save_margin_side_unit_tablet($unit)
    {
        return update_option(self::OPTION_MARGIN_SIDE_UNIT_TABLET, $this->sanitize_unit($unit));
    }

    /**
     * Obtener margen inferior (Mobile)
     */
    public function get_margin_bottom_mobile()
    {
        return (int) get_option(self::OPTION_MARGIN_BOTTOM_MOBILE, 20);
    }

    public function save_margin_bottom_mobile($margin)
    {
        return update_option(self::OPTION_MARGIN_BOTTOM_MOBILE, absint($margin));
    }

    /**
     * Obtener margen lateral (Mobile)
     */
    public function get_margin_side_mobile()
    {
        return (int) get_option(self::OPTION_MARGIN_SIDE_MOBILE, 20);
    }

    public function save_margin_side_mobile($margin)
    {
        return update_option(self::OPTION_MARGIN_SIDE_MOBILE, absint($margin));
    }

    /**
     * Obtener unidad margen inferior (Mobile)
     */
    public function get_margin_bottom_unit_mobile()
    {
        return get_option(self::OPTION_MARGIN_BOTTOM_UNIT_MOBILE, 'px');
    }

    public function save_margin_bottom_unit_mobile($unit)
    {
        return update_option(self::OPTION_MARGIN_BOTTOM_UNIT_MOBILE, $this->sanitize_unit($unit));
    }

    /**
     * Obtener unidad margen lateral (Mobile)
     */
    public function get_margin_side_unit_mobile()
    {
        return get_option(self::OPTION_MARGIN_SIDE_UNIT_MOBILE, 'px');
    }

    public function save_margin_side_unit_mobile($unit)
    {
        return update_option(self::OPTION_MARGIN_SIDE_UNIT_MOBILE, $this->sanitize_unit($unit));
    }

    /**
     * Obtener Breakpoint Tablet (max-width)
     */
    public function get_breakpoint_tablet()
    {
        return (int) get_option(self::OPTION_BREAKPOINT_TABLET, 1024);
    }

    public function save_breakpoint_tablet($width)
    {
        return update_option(self::OPTION_BREAKPOINT_TABLET, absint($width));
    }

    /**
     * Obtener Breakpoint Mobile (max-width)
     */
    public function get_breakpoint_mobile()
    {
        return (int) get_option(self::OPTION_BREAKPOINT_MOBILE, 768);
    }

    public function save_breakpoint_mobile($width)
    {
        return update_option(self::OPTION_BREAKPOINT_MOBILE, absint($width));
    }

    /**
     * Helper para sanitizar unidades
     */
    private function sanitize_unit($unit)
    {
        $allowed_units = array('px', '%', 'rem', 'em');
        return in_array($unit, $allowed_units) ? $unit : 'px';
    }

    /**
     * Obtener todas las cuentas
     *
     * @return array Array de cuentas con sus configuraciones
     */
    public function get_accounts()
    {
        $accounts = get_option(self::OPTION_ACCOUNTS, array());

        // Asegurar que siempre sea un array
        if (!is_array($accounts)) {
            $accounts = array();
        }

        return $accounts;
    }

    /**
     * Guardar las cuentas
     *
     * @param array $accounts Array de cuentas a guardar
     * @return bool True si se guardó correctamente
     */
    public function save_accounts($accounts)
    {
        $sanitized_accounts = $this->sanitize_accounts($accounts);
        return update_option(self::OPTION_ACCOUNTS, $sanitized_accounts);
    }

    /**
     * Obtener el ID del icono personalizado
     *
     * @return int ID del attachment o 0 si no hay
     */
    public function get_custom_icon()
    {
        return absint(get_option(self::OPTION_CUSTOM_ICON, 0));
    }

    /**
     * Guardar el ID del icono personalizado
     *
     * @param int $attachment_id ID del attachment
     * @return bool True si se guardó correctamente
     */
    public function save_custom_icon($attachment_id)
    {
        $attachment_id = absint($attachment_id);

        // Verificar que el attachment existe y es una imagen
        if ($attachment_id > 0) {
            if (!wp_attachment_is_image($attachment_id)) {
                return false;
            }
            
            // Verificar que el attachment pertenece al sitio actual
            $post = get_post($attachment_id);
            if (!$post || $post->post_type !== 'attachment') {
                return false;
            }
        }

        return update_option(self::OPTION_CUSTOM_ICON, $attachment_id);
    }

    /**
     * Obtener el texto del disclaimer
     *
     * @return string Texto del disclaimer
     */
    public function get_disclaimer()
    {
        return get_option(self::OPTION_DISCLAIMER, '');
    }

    /**
     * Guardar el texto del disclaimer
     *
     * @param string $disclaimer Texto del disclaimer
     * @return bool True si se guardó correctamente
     */
    public function save_disclaimer($disclaimer)
    {
        $sanitized = wp_kses_post($disclaimer);
        return update_option(self::OPTION_DISCLAIMER, $sanitized);
    }

    /**
     * Obtener la posición del icono
     *
     * @return string Posición (bottom-right, bottom-left)
     */
    public function get_icon_position()
    {
        return get_option(self::OPTION_ICON_POSITION, 'bottom-right');
    }

    /**
     * Guardar la posición del icono
     *
     * @param string $position Posición
     * @return bool True si se guardó correctamente
     */
    public function save_icon_position($position)
    {
        $allowed_positions = array('bottom-right', 'bottom-left');
        if (!in_array($position, $allowed_positions)) {
            $position = 'bottom-right';
        }
        return update_option(self::OPTION_ICON_POSITION, $position);
    }

    /**
     * Obtener margen inferior
     *
     * @return int Margen en píxeles
     */
    public function get_margin_bottom()
    {
        return (int) get_option(self::OPTION_MARGIN_BOTTOM, 20);
    }

    /**
     * Guardar margen inferior
     *
     * @param int $margin Margen en píxeles
     * @return bool True si se guardó correctamente
     */
    public function save_margin_bottom($margin)
    {
        return update_option(self::OPTION_MARGIN_BOTTOM, absint($margin));
    }

    /**
     * Obtener margen lateral
     *
     * @return int Margen en píxeles
     */
    public function get_margin_side()
    {
        return (int) get_option(self::OPTION_MARGIN_SIDE, 20);
    }

    /**
     * Guardar margen lateral
     *
     * @param int $margin Margen en píxeles
     * @return bool True si se guardó correctamente
     */
    public function save_margin_side($margin)
    {
        return update_option(self::OPTION_MARGIN_SIDE, absint($margin));
    }

    /**
     * Obtener unidad de margen inferior
     *
     * @return string Unidad (px, %, rem, em)
     */
    public function get_margin_bottom_unit()
    {
        return get_option(self::OPTION_MARGIN_BOTTOM_UNIT, 'px');
    }

    /**
     * Guardar unidad de margen inferior
     *
     * @param string $unit Unidad
     * @return bool True si se guardó correctamente
     */
    public function save_margin_bottom_unit($unit)
    {
        return update_option(self::OPTION_MARGIN_BOTTOM_UNIT, $this->sanitize_unit($unit));
    }

    /**
     * Obtener unidad de margen lateral
     *
     * @return string Unidad (px, %, rem, em)
     */
    public function get_margin_side_unit()
    {
        return get_option(self::OPTION_MARGIN_SIDE_UNIT, 'px');
    }

    /**
     * Guardar unidad de margen lateral
     *
     * @param string $unit Unidad
     * @return bool True si se guardó correctamente
     */
    public function save_margin_side_unit($unit)
    {
        return update_option(self::OPTION_MARGIN_SIDE_UNIT, $this->sanitize_unit($unit));
    }

    /**
     * Sanitizar y validar el array de cuentas
     *
     * @param array $accounts Array de cuentas sin sanitizar
     * @return array Array de cuentas sanitizado
     */
    public function sanitize_accounts($accounts)
    {
        if (!is_array($accounts)) {
            return array();
        }

        $sanitized = array();

        foreach ($accounts as $account) {
            // Validar que account sea un array
            if (!is_array($account)) {
                continue;
            }

            // Validar campos obligatorios
            if (empty($account['label']) || empty($account['phone'])) {
                continue;
            }

            // Sanitizar y validar el teléfono
            $phone = sanitize_text_field($account['phone']);
            // Eliminar espacios y caracteres no permitidos, manteniendo solo + y dígitos
            $phone = preg_replace('/[^0-9+]/', '', $phone);
            
            // Añadir + si no está presente para formato internacional de WhatsApp
            if (!empty($phone) && $phone[0] !== '+') {
                $phone = '+' . $phone;
            }
            
            // Validar formato de teléfono internacional con código de país requerido
            // WhatsApp requiere formato internacional: +[código país][número]
            // Formato: + seguido de 1-15 dígitos totales (código país + número)
            if (empty($phone) || !preg_match('/^\+\d{1,15}$/', $phone)) {
                continue;
            }

            $sanitized_account = array(
                'id' => isset($account['id']) && !empty($account['id']) ? sanitize_text_field($account['id']) : uniqid('wa_'),
                'label' => sanitize_text_field($account['label']),
                'phone' => $phone,
                'message' => isset($account['message']) ? sanitize_textarea_field($account['message']) : '',
                'status_message' => isset($account['status_message']) ? sanitize_text_field($account['status_message']) : '',
                'active' => isset($account['active']) && $account['active'] === '1',
                'schedules' => array(),
            );

            // Sanitizar horarios si existen
            if (isset($account['schedules']) && is_array($account['schedules'])) {
                foreach ($account['schedules'] as $schedule) {
                    $sanitized_schedule = $this->sanitize_schedule($schedule);
                    if ($sanitized_schedule !== false) {
                        $sanitized_account['schedules'][] = $sanitized_schedule;
                    }
                }
            }

            $sanitized[] = $sanitized_account;
        }

        return $sanitized;
    }

    /**
     * Sanitizar y validar un horario individual
     *
     * @param array $schedule Horario sin sanitizar
     * @return array|false Horario sanitizado o false si es inválido
     */
    private function sanitize_schedule($schedule)
    {
        if (!is_array($schedule)) {
            return false;
        }

        // Validar días
        $days = isset($schedule['days']) && is_array($schedule['days']) ? $schedule['days'] : array();
        $valid_days = array();

        foreach ($days as $day) {
            $day = absint($day);
            if ($day >= 0 && $day <= 6) {
                $valid_days[] = $day;
            }
        }

        // Si no hay días válidos, el horario no es válido
        if (empty($valid_days)) {
            return false;
        }

        // Validar horas
        $start_time = isset($schedule['start_time']) ? sanitize_text_field($schedule['start_time']) : '';
        $end_time = isset($schedule['end_time']) ? sanitize_text_field($schedule['end_time']) : '';

        if (!$this->validate_time_format($start_time) || !$this->validate_time_format($end_time)) {
            return false;
        }

        // Validar que la hora de inicio sea menor que la de fin
        if (!$this->validate_time_range($start_time, $end_time)) {
            return false;
        }

        return array(
            'days' => $valid_days,
            'start_time' => $start_time,
            'end_time' => $end_time,
        );
    }

    /**
     * Validar formato de hora HH:MM
     *
     * @param string $time Hora a validar
     * @return bool True si es válido
     */
    private function validate_time_format($time)
    {
        if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $time)) {
            return false;
        }
        return true;
    }

    /**
     * Validar que la hora de inicio sea menor que la de fin
     *
     * @param string $start_time Hora de inicio
     * @param string $end_time Hora de fin
     * @return bool True si es válido
     */
    private function validate_time_range($start_time, $end_time)
    {
        $start = strtotime($start_time);
        $end = strtotime($end_time);

        return $start < $end;
    }

    /**
     * Verificar si una cuenta está disponible en el momento actual
     *
     * @param array $account Cuenta a verificar
     * @return bool True si está disponible
     */
    public function is_account_available($account)
    {
        // Si la cuenta no está activa, no está disponible
        if (!isset($account['active']) || !$account['active']) {
            return false;
        }

        // Si no hay horarios configurados, está disponible siempre
        if (empty($account['schedules'])) {
            return true;
        }

        // Obtener la zona horaria de WordPress
        $timezone = wp_timezone();
        $current_datetime = new DateTime('now', $timezone);
        $current_day = (int) $current_datetime->format('w'); // 0 = Domingo, 6 = Sábado
        $current_time = $current_datetime->format('H:i');

        // Verificar si el momento actual coincide con alguno de los horarios
        foreach ($account['schedules'] as $schedule) {
            // Verificar si el día actual está en los días configurados
            if (!in_array($current_day, $schedule['days'], true)) {
                continue;
            }

            // Verificar si la hora actual está en el rango
            if ($current_time >= $schedule['start_time'] && $current_time <= $schedule['end_time']) {
                return true;
            }
        }

        return false;
    }

    /**
     * Obtener solo las cuentas disponibles en el momento actual
     *
     * @return array Array de cuentas disponibles
     */
    public function get_available_accounts()
    {
        $all_accounts = $this->get_accounts();
        $available = array();

        foreach ($all_accounts as $account) {
            if ($this->is_account_available($account)) {
                $available[] = $account;
            }
        }

        return $available;
    }

    /**
     * Migrar datos de formatos antiguos si es necesario
     */
    public function maybe_migrate_data()
    {
        $accounts = get_option(self::OPTION_ACCOUNTS, array());

        if (empty($accounts) || !is_array($accounts)) {
            return;
        }

        $needs_migration = false;

        // Verificar si hay cuentas con formato antiguo
        foreach ($accounts as $account) {
            // Si tiene 'days' o 'start_time' directamente (formato antiguo)
            if (isset($account['days']) || isset($account['start_time'])) {
                $needs_migration = true;
                break;
            }
        }

        if (!$needs_migration) {
            return;
        }

        // Realizar migración
        $migrated_accounts = array();

        foreach ($accounts as $account) {
            $migrated_account = array(
                'id' => uniqid('wa_'),
                'label' => isset($account['label']) ? $account['label'] : '',
                'phone' => isset($account['phone']) ? $account['phone'] : '',
                'message' => isset($account['message']) ? $account['message'] : '',
                'active' => isset($account['active']) ? $account['active'] : true,
                'schedules' => array(),
            );

            // Si tiene datos de horario en formato antiguo, convertirlos
            if (isset($account['days']) && isset($account['start_time']) && isset($account['end_time'])) {
                $migrated_account['schedules'][] = array(
                    'days' => is_array($account['days']) ? $account['days'] : array(),
                    'start_time' => $account['start_time'],
                    'end_time' => $account['end_time'],
                );
            }

            $migrated_accounts[] = $migrated_account;
        }

        // Guardar datos migrados
        $this->save_accounts($migrated_accounts);
    }
}
