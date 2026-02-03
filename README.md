# WhatsApp Flotante - Plugin de WordPress

Plugin profesional de WordPress que añade un botón flotante de WhatsApp con múltiples cuentas, horarios configurables y personalización avanzada.

## Características

### ✨ Funcionalidades principales

- **Múltiples cuentas de WhatsApp**: Configura tantas cuentas como necesites
- **Sistema de horarios avanzado**: Define múltiples rangos horarios por cuenta
- **Icono personalizable**: Sube tu propia imagen desde la biblioteca de medios
- **Panel de administración intuitivo**: Interfaz moderna y fácil de usar
- **Diseño responsive**: Funciona perfectamente en móviles y tablets
- **Internacionalización**: Listo para traducir a cualquier idioma

### 📋 Gestión de cuentas

Cada cuenta puede tener:
- Etiqueta descriptiva
- Número de teléfono
- Mensaje predeterminado (opcional)
- Estado activo/inactivo con toggle switch
- Múltiples rangos horarios

### ⏰ Sistema de horarios

- Selección de días de la semana (L, M, X, J, V, S, D)
- Hora de inicio y fin para cada rango
- Múltiples rangos por cuenta
- Si no se configuran horarios, la cuenta está disponible siempre
- Validación automática según la zona horaria de WordPress

### 🎨 Personalización

- Selector de imagen desde la biblioteca de medios de WordPress
- Preview de la imagen seleccionada
- Opción de eliminar y volver al icono por defecto
- Campo para texto de disclaimer/aviso legal

## Instalación

1. Sube la carpeta `Whatsapp` a `/wp-content/plugins/`
2. Activa el plugin desde el menú 'Plugins' en WordPress
3. Ve a 'Ajustes > WhatsApp Flotante' para configurar

## Configuración

### Icono del botón flotante

1. Haz clic en "Seleccionar imagen"
2. Elige una imagen de tu biblioteca de medios o sube una nueva
3. La imagen se mostrará como preview
4. Puedes eliminarla en cualquier momento para volver al icono por defecto

### Añadir cuentas

1. Haz clic en "Añadir cuenta"
2. Rellena los campos:
   - **Etiqueta**: Nombre descriptivo (ej: "Ventas", "Soporte")
   - **Teléfono**: Número con código de país (ej: +34612345678)
   - **Mensaje**: Texto predeterminado (opcional)
3. Activa/desactiva la cuenta con el toggle switch

### Configurar horarios

1. Dentro de cada cuenta, haz clic en "Añadir horario"
2. Selecciona los días de la semana
3. Define la hora de inicio y fin
4. Puedes añadir múltiples rangos horarios
5. Si no añades horarios, la cuenta estará disponible 24/7

### Disclaimer

Añade un texto opcional que se mostrará en el modal (por ejemplo, información sobre protección de datos).

## Estructura del plugin

```
Whatsapp/
├── whatsapp-flotante.php          # Archivo principal
├── includes/
│   ├── class-whatsapp-flotante.php    # Clase principal
│   ├── class-whatsapp-settings.php    # Gestión de opciones
│   ├── class-whatsapp-admin.php       # Panel de administración
│   └── class-whatsapp-frontend.php    # Visualización frontend
├── admin/
│   ├── templates/
│   │   ├── settings-page.php          # Página de configuración
│   │   ├── account-row.php            # Template de cuenta
│   │   └── schedule-row.php           # Template de horario
│   ├── css/
│   │   └── admin-style.css            # Estilos del admin
│   └── js/
│       └── admin-script.js            # JavaScript del admin
├── public/
│   ├── templates/
│   │   ├── floating-button.php        # Botón flotante
│   │   └── modal.php                  # Modal de cuentas
│   ├── css/
│   │   └── frontend-style.css         # Estilos del frontend
│   └── js/
│       └── frontend-script.js         # JavaScript del frontend
├── assets/
│   └── images/
│       └── whatsapp-icon.svg          # Icono por defecto
└── languages/
    └── whatsapp-flotante.pot          # Template de traducción
```

## Arquitectura

El plugin utiliza una arquitectura orientada a objetos con separación de responsabilidades:

- **WhatsApp_Flotante**: Clase principal que orquesta todos los componentes
- **WhatsApp_Settings**: Gestiona opciones, validación y sanitización de datos
- **WhatsApp_Admin**: Maneja el panel de administración y assets del admin
- **WhatsApp_Frontend**: Renderiza el botón flotante y gestiona la visualización

## Validaciones

El plugin incluye validaciones robustas:

- Formato de hora HH:MM válido
- Hora de inicio menor que hora de fin
- Días en rango 0-6 (0=Domingo)
- Teléfono y etiqueta obligatorios
- Validación de attachment ID de imagen
- Migración automática de formatos antiguos

## Zona horaria

El plugin utiliza la zona horaria configurada en WordPress (Ajustes > Generales) para validar si una cuenta debe mostrarse según sus horarios.

## Requisitos

- WordPress 5.0 o superior
- PHP 7.2 o superior

## Soporte

Para reportar bugs o solicitar nuevas funcionalidades, contacta con el desarrollador.

## Licencia

GPL v2 o posterior

## Changelog

### 1.0.0
- Versión inicial
- Múltiples cuentas de WhatsApp
- Sistema de horarios avanzado
- Icono personalizable
- Panel de administración moderno
- Diseño responsive
