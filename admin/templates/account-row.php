<?php
/**
 * Template para una fila de cuenta
 *
 * @package WhatsApp_Flotante
 * @since 1.0.0
 */

// Si este archivo es llamado directamente, abortar
if ( ! defined( 'WPINC' ) ) {
    die;
}
?>

<div class="wf-account-row" data-index="<?php echo esc_attr( $index ); ?>">
    <input type="hidden" name="accounts[<?php echo esc_attr( $index ); ?>][id]" value="<?php echo esc_attr( isset( $account['id'] ) ? $account['id'] : '' ); ?>">
    
    <div class="wf-account-header">
        <div class="wf-account-toggle">
            <label class="wf-switch">
                <input type="checkbox" 
                       name="accounts[<?php echo esc_attr( $index ); ?>][active]" 
                       value="1" 
                       <?php checked( isset( $account['active'] ) ? $account['active'] : true, true ); ?>>
                <span class="wf-slider"></span>
            </label>
        </div>

        <div class="wf-account-fields">
            <div class="wf-field">
                <label><?php _e( 'Etiqueta', 'whatsapp-flotante' ); ?> *</label>
                <input type="text" 
                       name="accounts[<?php echo esc_attr( $index ); ?>][label]" 
                       value="<?php echo esc_attr( isset( $account['label'] ) ? $account['label'] : '' ); ?>" 
                       placeholder="<?php _e( 'Ej: Ventas', 'whatsapp-flotante' ); ?>"
                       required>
            </div>

            <div class="wf-field">
                <label><?php _e( 'Teléfono', 'whatsapp-flotante' ); ?> *</label>
                <input type="text" 
                       name="accounts[<?php echo esc_attr( $index ); ?>][phone]" 
                       value="<?php echo esc_attr( isset( $account['phone'] ) ? $account['phone'] : '' ); ?>" 
                       placeholder="<?php _e( 'Ej: +34612345678', 'whatsapp-flotante' ); ?>"
                       required>
                <small class="wf-field-help"><?php _e( 'Formato internacional: +34 seguido del número (ej: +34612345678)', 'whatsapp-flotante' ); ?></small>
            </div>

            <div class="wf-field wf-field-wide">
                <label><?php _e( 'Mensaje predeterminado', 'whatsapp-flotante' ); ?></label>
                <input type="text" 
                       name="accounts[<?php echo esc_attr( $index ); ?>][message]" 
                       value="<?php echo esc_attr( isset( $account['message'] ) ? $account['message'] : '' ); ?>" 
                       placeholder="<?php _e( 'Ej: Hola, me gustaría obtener más información', 'whatsapp-flotante' ); ?>">
            </div>

            <div class="wf-field wf-field-wide">
                <label><?php _e( 'Mensaje de estado', 'whatsapp-flotante' ); ?></label>
                <input type="text" 
                       name="accounts[<?php echo esc_attr( $index ); ?>][status_message]" 
                       value="<?php echo esc_attr( isset( $account['status_message'] ) ? $account['status_message'] : '' ); ?>" 
                       placeholder="<?php _e( 'Ej: Solicitar cita previa en el taller', 'whatsapp-flotante' ); ?>">
                <small class="wf-field-help"><?php _e( 'Este mensaje se mostrará en el modal bajo el nombre de la cuenta', 'whatsapp-flotante' ); ?></small>
            </div>
        </div>

        <div class="wf-account-actions">
            <?php 
            $stats = get_option('whatsapp_flotante_stats', array());
            $account_id = isset($account['id']) ? $account['id'] : '';
            $clicks = isset($stats[$account_id]) ? $stats[$account_id] : 0;
            ?>
            <span class="wf-stats-badge" title="<?php _e('Clics totales', 'whatsapp-flotante'); ?>">
                <span class="dashicons dashicons-chart-bar"></span>
                <?php echo esc_html($clicks); ?>
            </span>

            <button type="button" class="button button-link-delete wf-remove-account" title="<?php _e( 'Eliminar cuenta', 'whatsapp-flotante' ); ?>">
                <span class="dashicons dashicons-trash"></span>
            </button>
        </div>
    </div>

    <div class="wf-account-schedules">
        <h4><?php _e( 'Horarios de disponibilidad', 'whatsapp-flotante' ); ?></h4>
        <p class="description">
            <?php _e( 'Si no configuras horarios, la cuenta estará disponible siempre. Puedes añadir múltiples rangos horarios.', 'whatsapp-flotante' ); ?>
        </p>

        <div class="wf-schedules-container" data-account-index="<?php echo esc_attr( $index ); ?>">
            <?php if ( ! empty( $account['schedules'] ) ) : ?>
                <?php foreach ( $account['schedules'] as $schedule_index => $schedule ) : ?>
                    <?php 
                    $account_index = $index;
                    include WHATSAPP_FLOTANTE_PLUGIN_DIR . 'admin/templates/schedule-row.php'; 
                    ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <button type="button" class="button button-secondary button-small wf-add-schedule" data-account-index="<?php echo esc_attr( $index ); ?>">
            <span class="dashicons dashicons-plus-alt"></span>
            <?php _e( 'Añadir horario', 'whatsapp-flotante' ); ?>
        </button>
    </div>
</div>
