<?php
/**
 * Template del modal de WhatsApp
 *
 * @package WhatsApp_Flotante
 * @since 1.0.0
 */

// Si este archivo es llamado directamente, abortar
if (!defined('WPINC')) {
    die;
}
?>

<div id="whatsapp-flotante-modal" class="wf-modal" style="display: none;">
    <div class="wf-modal-overlay"></div>
    <div class="wf-modal-content">
        <div class="wf-modal-header">
            <h3><?php _e('Contacta con nosotros', 'whatsapp-flotante'); ?></h3>
            <button type="button" class="wf-modal-close" aria-label="<?php _e('Cerrar', 'whatsapp-flotante'); ?>">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="wf-modal-body">
            <p class="wf-modal-subtitle">
                <?php _e('Selecciona un contacto para iniciar la conversación:', 'whatsapp-flotante'); ?>
            </p>

            <div id="wf-accounts-list" class="wf-accounts-list">
                <?php foreach ($available_accounts as $account): ?>
                    <a href="#" class="wf-account-item" data-phone="<?php echo esc_attr($account['phone']); ?>"
                        data-message="<?php echo esc_attr(isset($account['message']) ? $account['message'] : ''); ?>">
                        <div class="wf-account-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                            </svg>
                        </div>
                        <div class="wf-account-info">
                            <span class="wf-account-label"><?php echo esc_html($account['label']); ?></span>
                            <span
                                class="wf-account-status"><?php echo esc_html(!empty($account['status_message']) ? $account['status_message'] : __('Disponible ahora', 'whatsapp-flotante')); ?></span>
                        </div>
                        <div class="wf-account-arrow">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M7.5 15l5-5-5-5" stroke="currentColor" stroke-width="2" fill="none" />
                            </svg>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <?php if (!empty($disclaimer)): ?>
                <div class="wf-disclaimer">
                    <?php echo wp_kses_post($disclaimer); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>