/**
 * Admin JavaScript for WhatsApp Flotante
 */

(function ($) {
    'use strict';

    let accountIndex = 0;
    let scheduleIndexes = {};

    $(document).ready(function () {
        initializeIndexes();
        initMediaUploader();
        initAccountHandlers();
        initAccountHandlers();
        initScheduleHandlers();
        initTabs();
    });

    /**
     * Inicializar pestañas
     */
    function initTabs() {
        $('.wf-tab-link').on('click', function (e) {
            e.preventDefault();

            // Remover clase activa de todos los links y contenidos
            $('.wf-tab-link').removeClass('active');
            $('.wf-tab-content').removeClass('active');

            // Añadir clase activa al link clickeado
            $(this).addClass('active');

            // Mostrar el contenido correspondiente
            const target = $(this).data('tab');
            $('#' + target).addClass('active');
        });
    }

    /**
     * Inicializar índices basados en cuentas existentes
     */
    function initializeIndexes() {
        $('.wf-account-row').each(function () {
            const index = parseInt($(this).data('index'));
            if (index >= accountIndex) {
                accountIndex = index + 1;
            }

            // Inicializar índice de horarios para esta cuenta
            scheduleIndexes[index] = 0;
            $(this).find('.wf-schedule-row').each(function () {
                const schedIndex = parseInt($(this).data('schedule-index'));
                if (schedIndex >= scheduleIndexes[index]) {
                    scheduleIndexes[index] = schedIndex + 1;
                }
            });
        });
    }

    /**
     * Inicializar el Media Uploader de WordPress
     */
    function initMediaUploader() {
        let mediaUploader;

        $('#select-icon-btn').on('click', function (e) {
            e.preventDefault();

            // Si el uploader ya existe, abrirlo
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            // Crear el media uploader
            mediaUploader = wp.media({
                title: whatsappFlotanteAdmin.mediaTitle,
                button: {
                    text: whatsappFlotanteAdmin.mediaButton
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });

            // Cuando se selecciona una imagen
            mediaUploader.on('select', function () {
                const attachment = mediaUploader.state().get('selection').first().toJSON();

                // Actualizar el campo oculto con el ID
                $('#custom_icon').val(attachment.id);

                // Actualizar la preview
                const previewHtml = '<img src="' + attachment.url + '" alt="' + attachment.alt + '">';
                $('#icon-preview').html(previewHtml);

                // Mostrar botón de eliminar
                $('#remove-icon-btn').show();
            });

            // Abrir el uploader
            mediaUploader.open();
        });

        // Botón para eliminar icono
        $('#remove-icon-btn').on('click', function (e) {
            e.preventDefault();

            // Limpiar el campo oculto
            $('#custom_icon').val('');

            // Restaurar preview por defecto
            const defaultHtml = '<div class="wf-no-icon">' +
                '<span class="dashicons dashicons-format-image"></span>' +
                '<p>Sin icono personalizado</p>' +
                '</div>';
            $('#icon-preview').html(defaultHtml);

            // Ocultar botón de eliminar
            $(this).hide();
        });
    }

    /**
     * Inicializar handlers para cuentas
     */
    function initAccountHandlers() {
        // Añadir cuenta
        $('#add-account-btn').on('click', function () {
            addAccount();
        });

        // Eliminar cuenta (delegado)
        $('#accounts-container').on('click', '.wf-remove-account', function () {
            if (confirm(whatsappFlotanteAdmin.confirmDelete)) {
                $(this).closest('.wf-account-row').remove();
            }
        });
    }

    /**
     * Inicializar handlers para horarios
     */
    function initScheduleHandlers() {
        // Añadir horario (delegado)
        $('#accounts-container').on('click', '.wf-add-schedule', function () {
            const accountIdx = $(this).data('account-index');
            addSchedule(accountIdx);
        });

        // Eliminar horario (delegado)
        $('#accounts-container').on('click', '.wf-remove-schedule', function () {
            if (confirm(whatsappFlotanteAdmin.confirmDeleteSchedule)) {
                $(this).closest('.wf-schedule-row').remove();
            }
        });
    }

    /**
     * Añadir una nueva cuenta
     */
    function addAccount() {
        const template = $('#account-row-template').html();
        const html = template.replace(/\{\{INDEX\}\}/g, accountIndex);

        $('#accounts-container').append(html);

        // Inicializar índice de horarios para esta cuenta
        scheduleIndexes[accountIndex] = 0;

        accountIndex++;
    }

    /**
     * Añadir un nuevo horario a una cuenta
     */
    function addSchedule(accountIdx) {
        const template = $('#schedule-row-template').html();

        // Inicializar índice si no existe
        if (typeof scheduleIndexes[accountIdx] === 'undefined') {
            scheduleIndexes[accountIdx] = 0;
        }

        const scheduleIdx = scheduleIndexes[accountIdx];
        let html = template.replace(/\{\{ACCOUNT_INDEX\}\}/g, accountIdx);
        html = html.replace(/\{\{SCHEDULE_INDEX\}\}/g, scheduleIdx);

        const container = $('.wf-schedules-container[data-account-index="' + accountIdx + '"]');
        container.append(html);

        scheduleIndexes[accountIdx]++;
    }

    /**
     * Validación del formulario antes de enviar
     */
    $('#whatsapp-flotante-form').on('submit', function (e) {
        let isValid = true;
        const errors = [];

        // Validar que cada cuenta tenga etiqueta y teléfono
        $('.wf-account-row').each(function () {
            const label = $(this).find('input[name*="[label]"]').val();
            const phone = $(this).find('input[name*="[phone]"]').val();

            if (!label || !phone) {
                isValid = false;
                errors.push('Todas las cuentas deben tener etiqueta y teléfono.');
                return false; // break
            }
        });

        // Validar horarios
        $('.wf-schedule-row').each(function () {
            const startTime = $(this).find('input[name*="[start_time]"]').val();
            const endTime = $(this).find('input[name*="[end_time]"]').val();
            const daysChecked = $(this).find('input[name*="[days][]"]:checked').length;

            if (daysChecked === 0) {
                isValid = false;
                errors.push('Cada horario debe tener al menos un día seleccionado.');
                return false; // break
            }

            if (startTime >= endTime) {
                isValid = false;
                errors.push('La hora de inicio debe ser menor que la hora de fin.');
                return false; // break
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Errores de validación:\n\n' + errors.join('\n'));
        }

        return isValid;
    });

})(jQuery);
