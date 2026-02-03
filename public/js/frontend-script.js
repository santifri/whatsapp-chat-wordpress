/**
 * Frontend JavaScript for WhatsApp Flotante
 */

(function ($) {
    'use strict';

    $(document).ready(function () {
        initFloatingButton();
        initModal();
        initAccountClicks();
    });

    /**
     * Inicializar el botón flotante
     */
    function initFloatingButton() {
        $('#whatsapp-flotante-btn').on('click', function () {
            openModal();
        });

        // Soporte para teclado (Enter y Espacio)
        $('#whatsapp-flotante-btn').on('keypress', function (e) {
            if (e.which === 13 || e.which === 32) { // Enter o Espacio
                e.preventDefault();
                openModal();
            }
        });
    }

    /**
     * Inicializar el modal
     */
    function initModal() {
        // Cerrar con el botón X
        $('.wf-modal-close').on('click', function () {
            closeModal();
        });

        // Cerrar al hacer clic en el overlay
        $('.wf-modal-overlay').on('click', function () {
            closeModal();
        });

        // Cerrar con la tecla Escape
        $(document).on('keydown', function (e) {
            if (e.key === 'Escape' && $('#whatsapp-flotante-modal').is(':visible')) {
                closeModal();
            }
        });
    }

    /**
     * Inicializar clics en cuentas
     */
    function initAccountClicks() {
        $('.wf-account-item').on('click', function (e) {
            e.preventDefault();

            const phone = $(this).data('phone');
            const message = $(this).data('message');
            const accountId = $(this).data('account-id');

            // Tracking AJAX
            if (accountId && whatsappFlotanteFrontend.ajax_url) {
                $.ajax({
                    url: whatsappFlotanteFrontend.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'whatsapp_flotante_track_click',
                        account_id: accountId,
                        nonce: whatsappFlotanteFrontend.nonce
                    }
                });
            }

            // Google Analytics 4 Event
            if (typeof gtag === 'function') {
                gtag('event', 'whatsapp_click', {
                    'event_category': 'WhatsApp Flotante',
                    'event_label': $(this).find('.wf-account-label').text(),
                    'account_id': accountId
                });
            }

            openWhatsApp(phone, message);
        });
    }

    /**
     * Abrir el modal
     */
    function openModal() {
        $('#whatsapp-flotante-modal').fadeIn(300);
        $('body').css('overflow', 'hidden'); // Prevenir scroll del body
    }

    /**
     * Cerrar el modal
     */
    function closeModal() {
        $('#whatsapp-flotante-modal').fadeOut(300);
        $('body').css('overflow', ''); // Restaurar scroll del body
    }

    /**
     * Abrir WhatsApp con el número y mensaje
     */
    function openWhatsApp(phone, message) {
        // Limpiar el número de teléfono (eliminar espacios, guiones, etc.)
        const cleanPhone = phone.replace(/[^0-9+]/g, '');

        // Construir la URL de WhatsApp
        let url = 'https://wa.me/' + cleanPhone;

        // Añadir mensaje si existe
        if (message && message.trim() !== '') {
            url += '?text=' + encodeURIComponent(message);
        }

        // Abrir en nueva ventana
        window.open(url, '_blank');

        // Cerrar el modal después de un breve delay
        setTimeout(function () {
            closeModal();
        }, 300);
    }

})(jQuery);
