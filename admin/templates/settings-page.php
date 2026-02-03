<?php
/**
 * Template de la página de configuración del admin
 *
 * @package WhatsApp_Flotante
 * @since 1.0.0
 */

// Si este archivo es llamado directamente, abortar
if (!defined('WPINC')) {
    die;
}
?>

<div class="wrap whatsapp-flotante-admin">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <?php if (isset($_GET['updated']) && sanitize_text_field(wp_unslash($_GET['updated'])) === 'true'): ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Configuración guardada correctamente.', 'whatsapp-flotante'); ?></p>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['breakpoint_adjusted']) && sanitize_text_field(wp_unslash($_GET['breakpoint_adjusted'])) === 'true'): ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php _e('Nota: El breakpoint de Tablet fue ajustado automáticamente para ser mayor que el breakpoint de Móvil.', 'whatsapp-flotante'); ?></p>
        </div>
    <?php endif; ?>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="whatsapp-flotante-form">
        <?php wp_nonce_field('whatsapp_flotante_save', 'whatsapp_flotante_nonce'); ?>
        <input type="hidden" name="action" value="whatsapp_flotante_save">

        <!-- Botón de guardar superior -->
        <div class="wf-header-actions">
            <?php submit_button(__('Guardar configuración', 'whatsapp-flotante'), 'primary', 'submit', false); ?>
        </div>

        <!-- Sección de icono y posición (2 columnas) -->
        <div class="wf-section">
            <h2><?php _e('Apariencia del botón', 'whatsapp-flotante'); ?></h2>
            <p class="description">
                <?php _e('Personaliza el icono y la posición del botón flotante.', 'whatsapp-flotante'); ?>
            </p>

            <div class="wf-appearance-grid">
                <!-- Columna Izquierda: Icono -->
                <div class="wf-column">
                    <h3><?php _e('Icono', 'whatsapp-flotante'); ?></h3>
                    <p class="description-small"><?php _e('Selecciona una imagen personalizada.', 'whatsapp-flotante'); ?></p>
                    
                    <div class="wf-icon-selector">
                        <input type="hidden" name="custom_icon" id="custom_icon"
                            value="<?php echo esc_attr($custom_icon_id); ?>">

                        <div class="wf-icon-preview" id="icon-preview">
                            <?php if ($custom_icon_url): ?>
                                <img src="<?php echo esc_url($custom_icon_url); ?>"
                                    alt="<?php _e('Icono personalizado', 'whatsapp-flotante'); ?>">
                            <?php else: ?>
                                <div class="wf-no-icon">
                                    <span class="dashicons dashicons-format-image"></span>
                                    <p><?php _e('Sin icono personalizado', 'whatsapp-flotante'); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="wf-icon-buttons">
                            <button type="button" class="button" id="select-icon-btn">
                                <?php _e('Seleccionar imagen', 'whatsapp-flotante'); ?>
                            </button>
                            <button type="button" class="button" id="remove-icon-btn" <?php echo $custom_icon_url ? '' : 'style="display:none;"'; ?>>
                                <?php _e('Eliminar imagen', 'whatsapp-flotante'); ?>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Posición -->
                <div class="wf-column">
                    <h3><?php _e('Ubicación', 'whatsapp-flotante'); ?></h3>
                    <p class="description-small"><?php _e('Elige dónde mostrar el botón.', 'whatsapp-flotante'); ?></p>

                    <div class="wf-position-selector-compact">
                        <?php
                        $positions = array(
                            'bottom-right' => __('Inferior Derecha', 'whatsapp-flotante'),
                            'bottom-left' => __('Inferior Izquierda', 'whatsapp-flotante'),
                        );

                        foreach ($positions as $value => $label):
                        ?>
                            <label class="wf-position-option-compact">
                                <input type="radio" name="icon_position" value="<?php echo esc_attr($value); ?>"
                                    <?php checked($icon_position, $value); ?>>
                                <span class="wf-position-label"><?php echo esc_html($label); ?></span>
                                <div class="wf-mini-preview wf-mini-<?php echo esc_attr($value); ?>"></div>
                            </label>
                        <?php endforeach; ?>
                    </div>

                    <div class="wf-margins-settings">
                        <h4><?php _e('Ajuste de posición (Márgenes)', 'whatsapp-flotante'); ?></h4>
                        
                        <!-- Pestañas -->
                        <div class="wf-tabs-nav">
                            <a href="#" class="wf-tab-link active" data-tab="tab-desktop"><?php _e('Escritorio', 'whatsapp-flotante'); ?></a>
                            <a href="#" class="wf-tab-link" data-tab="tab-tablet"><?php _e('Tablet', 'whatsapp-flotante'); ?></a>
                            <a href="#" class="wf-tab-link" data-tab="tab-mobile"><?php _e('Móvil', 'whatsapp-flotante'); ?></a>
                        </div>

                        <!-- Contenido Desktop -->
                        <div id="tab-desktop" class="wf-tab-content active">
                            <div class="wf-margins-row">
                                <div class="wf-field-inline">
                                    <label for="margin_bottom"><?php _e('Inferior', 'whatsapp-flotante'); ?></label>
                                    <div class="wf-input-group">
                                        <input type="number" name="margin_bottom" id="margin_bottom" 
                                            value="<?php echo esc_attr($margin_bottom); ?>" min="0" step="1" class="small-text">
                                        <select name="margin_bottom_unit" class="wf-unit-selector">
                                            <option value="px" <?php selected($margin_bottom_unit, 'px'); ?>>px</option>
                                            <option value="%" <?php selected($margin_bottom_unit, '%'); ?>>%</option>
                                            <option value="rem" <?php selected($margin_bottom_unit, 'rem'); ?>>rem</option>
                                            <option value="em" <?php selected($margin_bottom_unit, 'em'); ?>>em</option>
                                        </select>
                                    </div>
                                    <span class="wf-saved-value">
                                        <?php printf(__('Actual: %s%s', 'whatsapp-flotante'), esc_html($margin_bottom), esc_html($margin_bottom_unit)); ?>
                                    </span>
                                </div>

                                <div class="wf-field-inline">
                                    <label for="margin_side"><?php _e('Lateral', 'whatsapp-flotante'); ?></label>
                                    <div class="wf-input-group">
                                        <input type="number" name="margin_side" id="margin_side" 
                                            value="<?php echo esc_attr($margin_side); ?>" min="0" step="1" class="small-text">
                                        <select name="margin_side_unit" class="wf-unit-selector">
                                            <option value="px" <?php selected($margin_side_unit, 'px'); ?>>px</option>
                                            <option value="%" <?php selected($margin_side_unit, '%'); ?>>%</option>
                                            <option value="rem" <?php selected($margin_side_unit, 'rem'); ?>>rem</option>
                                            <option value="em" <?php selected($margin_side_unit, 'em'); ?>>em</option>
                                        </select>
                                    </div>
                                    <span class="wf-saved-value">
                                        <?php printf(__('Actual: %s%s', 'whatsapp-flotante'), esc_html($margin_side), esc_html($margin_side_unit)); ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Contenido Tablet -->
                        <div id="tab-tablet" class="wf-tab-content">
                            <div class="wf-margins-row">
                                <div class="wf-field-inline">
                                    <label for="margin_bottom_tablet"><?php _e('Inferior', 'whatsapp-flotante'); ?></label>
                                    <div class="wf-input-group">
                                        <input type="number" name="margin_bottom_tablet" id="margin_bottom_tablet" 
                                            value="<?php echo esc_attr($margin_bottom_tablet); ?>" min="0" step="1" class="small-text">
                                        <select name="margin_bottom_unit_tablet" class="wf-unit-selector">
                                            <option value="px" <?php selected($margin_bottom_unit_tablet, 'px'); ?>>px</option>
                                            <option value="%" <?php selected($margin_bottom_unit_tablet, '%'); ?>>%</option>
                                            <option value="rem" <?php selected($margin_bottom_unit_tablet, 'rem'); ?>>rem</option>
                                            <option value="em" <?php selected($margin_bottom_unit_tablet, 'em'); ?>>em</option>
                                        </select>
                                    </div>
                                    <span class="wf-saved-value">
                                        <?php printf(__('Actual: %s%s', 'whatsapp-flotante'), esc_html($margin_bottom_tablet), esc_html($margin_bottom_unit_tablet)); ?>
                                    </span>
                                </div>

                                <div class="wf-field-inline">
                                    <label for="margin_side_tablet"><?php _e('Lateral', 'whatsapp-flotante'); ?></label>
                                    <div class="wf-input-group">
                                        <input type="number" name="margin_side_tablet" id="margin_side_tablet" 
                                            value="<?php echo esc_attr($margin_side_tablet); ?>" min="0" step="1" class="small-text">
                                        <select name="margin_side_unit_tablet" class="wf-unit-selector">
                                            <option value="px" <?php selected($margin_side_unit_tablet, 'px'); ?>>px</option>
                                            <option value="%" <?php selected($margin_side_unit_tablet, '%'); ?>>%</option>
                                            <option value="rem" <?php selected($margin_side_unit_tablet, 'rem'); ?>>rem</option>
                                            <option value="em" <?php selected($margin_side_unit_tablet, 'em'); ?>>em</option>
                                        </select>
                                    </div>
                                    <span class="wf-saved-value">
                                        <?php printf(__('Actual: %s%s', 'whatsapp-flotante'), esc_html($margin_side_tablet), esc_html($margin_side_unit_tablet)); ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Contenido Mobile -->
                        <div id="tab-mobile" class="wf-tab-content">
                            <div class="wf-margins-row">
                                <div class="wf-field-inline">
                                    <label for="margin_bottom_mobile"><?php _e('Inferior', 'whatsapp-flotante'); ?></label>
                                    <div class="wf-input-group">
                                        <input type="number" name="margin_bottom_mobile" id="margin_bottom_mobile" 
                                            value="<?php echo esc_attr($margin_bottom_mobile); ?>" min="0" step="1" class="small-text">
                                        <select name="margin_bottom_unit_mobile" class="wf-unit-selector">
                                            <option value="px" <?php selected($margin_bottom_unit_mobile, 'px'); ?>>px</option>
                                            <option value="%" <?php selected($margin_bottom_unit_mobile, '%'); ?>>%</option>
                                            <option value="rem" <?php selected($margin_bottom_unit_mobile, 'rem'); ?>>rem</option>
                                            <option value="em" <?php selected($margin_bottom_unit_mobile, 'em'); ?>>em</option>
                                        </select>
                                    </div>
                                    <span class="wf-saved-value">
                                        <?php printf(__('Actual: %s%s', 'whatsapp-flotante'), esc_html($margin_bottom_mobile), esc_html($margin_bottom_unit_mobile)); ?>
                                    </span>
                                </div>

                                <div class="wf-field-inline">
                                    <label for="margin_side_mobile"><?php _e('Lateral', 'whatsapp-flotante'); ?></label>
                                    <div class="wf-input-group">
                                        <input type="number" name="margin_side_mobile" id="margin_side_mobile" 
                                            value="<?php echo esc_attr($margin_side_mobile); ?>" min="0" step="1" class="small-text">
                                        <select name="margin_side_unit_mobile" class="wf-unit-selector">
                                            <option value="px" <?php selected($margin_side_unit_mobile, 'px'); ?>>px</option>
                                            <option value="%" <?php selected($margin_side_unit_mobile, '%'); ?>>%</option>
                                            <option value="rem" <?php selected($margin_side_unit_mobile, 'rem'); ?>>rem</option>
                                            <option value="em" <?php selected($margin_side_unit_mobile, 'em'); ?>>em</option>
                                        </select>
                                    </div>
                                    <span class="wf-saved-value">
                                        <?php printf(__('Actual: %s%s', 'whatsapp-flotante'), esc_html($margin_side_mobile), esc_html($margin_side_unit_mobile)); ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Configuración de Breakpoints -->
                        <div class="wf-breakpoints-section">
                            <h4><?php _e('Puntos de ruptura (Breakpoints)', 'whatsapp-flotante'); ?></h4>
                            <p class="description-small"><?php _e('Define el ancho máximo de pantalla para cada dispositivo.', 'whatsapp-flotante'); ?></p>
                            
                            <div class="wf-breakpoints-grid">
                                <div class="wf-field">
                                    <label for="breakpoint_tablet"><?php _e('Tablet (max-width)', 'whatsapp-flotante'); ?></label>
                                    <input type="number" name="breakpoint_tablet" id="breakpoint_tablet" 
                                        value="<?php echo esc_attr($breakpoint_tablet); ?>" min="0" step="1" class="regular-text wf-breakpoint-input">
                                    <p class="wf-field-help"><?php _e('Ancho máximo para considerar dispositivo Tablet (ej. 1024px)', 'whatsapp-flotante'); ?></p>
                                </div>

                                <div class="wf-field">
                                    <label for="breakpoint_mobile"><?php _e('Móvil (max-width)', 'whatsapp-flotante'); ?></label>
                                    <input type="number" name="breakpoint_mobile" id="breakpoint_mobile" 
                                        value="<?php echo esc_attr($breakpoint_mobile); ?>" min="0" step="1" class="regular-text wf-breakpoint-input">
                                    <p class="wf-field-help"><?php _e('Ancho máximo para considerar dispositivo Móvil (ej. 768px)', 'whatsapp-flotante'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de cuentas -->
        <div class="wf-section">
            <h2><?php _e('Cuentas de WhatsApp', 'whatsapp-flotante'); ?></h2>
            <p class="description">
                <?php _e('Configura las cuentas de WhatsApp que se mostrarán en el botón flotante. Puedes añadir múltiples horarios para cada cuenta.', 'whatsapp-flotante'); ?>
            </p>

            <div id="accounts-container">
                <?php if (!empty($accounts)): ?>
                    <?php foreach ($accounts as $index => $account): ?>
                        <?php include WHATSAPP_FLOTANTE_PLUGIN_DIR . 'admin/templates/account-row.php'; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <button type="button" class="button button-secondary" id="add-account-btn">
                <span class="dashicons dashicons-plus-alt"></span>
                <?php _e('Añadir cuenta', 'whatsapp-flotante'); ?>
            </button>
        </div>

        <!-- Sección de disclaimer -->
        <div class="wf-section">
            <h2><?php _e('Aviso legal / Disclaimer', 'whatsapp-flotante'); ?></h2>
            <p class="description">
                <?php _e('Texto opcional que se mostrará en el modal de WhatsApp (por ejemplo, información sobre protección de datos).', 'whatsapp-flotante'); ?>
            </p>

            <textarea name="disclaimer" id="disclaimer" rows="4"
                class="large-text"><?php echo esc_textarea($disclaimer); ?></textarea>
        </div>

        <?php submit_button(__('Guardar configuración', 'whatsapp-flotante')); ?>
    </form>
</div>

<!-- Template para nueva cuenta (oculto) -->
<script type="text/template" id="account-row-template">
    <?php
    $index = '{{INDEX}}';
    $account = array(
        'label' => '',
        'phone' => '',
        'message' => '',
        'active' => true,
        'schedules' => array(),
    );
    include WHATSAPP_FLOTANTE_PLUGIN_DIR . 'admin/templates/account-row.php';
    ?>
</script>

<!-- Template para nuevo horario (oculto) -->
<script type="text/template" id="schedule-row-template">
    <?php
    $account_index = '{{ACCOUNT_INDEX}}';
    $schedule_index = '{{SCHEDULE_INDEX}}';
    $schedule = array(
        'days' => array(),
        'start_time' => '09:00',
        'end_time' => '18:00',
    );
    include WHATSAPP_FLOTANTE_PLUGIN_DIR . 'admin/templates/schedule-row.php';
    ?>
</script>