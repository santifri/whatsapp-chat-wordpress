<?php
/**
 * Template para una fila de horario
 *
 * @package WhatsApp_Flotante
 * @since 1.0.0
 */

// Si este archivo es llamado directamente, abortar
if (!defined('WPINC')) {
    die;
}

$days_labels = array(
    0 => __('D', 'whatsapp-flotante'), // Domingo
    1 => __('L', 'whatsapp-flotante'), // Lunes
    2 => __('M', 'whatsapp-flotante'), // Martes
    3 => __('X', 'whatsapp-flotante'), // Miércoles
    4 => __('J', 'whatsapp-flotante'), // Jueves
    5 => __('V', 'whatsapp-flotante'), // Viernes
    6 => __('S', 'whatsapp-flotante'), // Sábado
);

$selected_days = isset($schedule['days']) ? $schedule['days'] : array();
?>

<div class="wf-schedule-row" data-schedule-index="<?php echo esc_attr($schedule_index); ?>">
    <div class="wf-schedule-days">
        <label><?php _e('Días', 'whatsapp-flotante'); ?>:</label>
        <div class="wf-days-selector">
            <?php foreach ($days_labels as $day_num => $day_label): ?>
                <label class="wf-day-checkbox">
                    <input type="checkbox"
                        name="accounts[<?php echo esc_attr($account_index); ?>][schedules][<?php echo esc_attr($schedule_index); ?>][days][]"
                        value="<?php echo esc_attr($day_num); ?>" <?php checked(in_array($day_num, $selected_days, true), true); ?>>
                    <span><?php echo esc_html($day_label); ?></span>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="wf-schedule-times">
        <div class="wf-time-field">
            <label><?php _e('Desde', 'whatsapp-flotante'); ?>:</label>
            <input type="time"
                name="accounts[<?php echo esc_attr($account_index); ?>][schedules][<?php echo esc_attr($schedule_index); ?>][start_time]"
                value="<?php echo esc_attr(isset($schedule['start_time']) ? $schedule['start_time'] : '09:00'); ?>"
                required>
        </div>

        <div class="wf-time-field">
            <label><?php _e('Hasta', 'whatsapp-flotante'); ?>:</label>
            <input type="time"
                name="accounts[<?php echo esc_attr($account_index); ?>][schedules][<?php echo esc_attr($schedule_index); ?>][end_time]"
                value="<?php echo esc_attr(isset($schedule['end_time']) ? $schedule['end_time'] : '18:00'); ?>"
                required>
        </div>
    </div>

    <div class="wf-schedule-actions">
        <button type="button" class="button button-link-delete wf-remove-schedule"
            title="<?php _e('Eliminar horario', 'whatsapp-flotante'); ?>">
            <span class="dashicons dashicons-trash"></span>
        </button>
    </div>
</div>