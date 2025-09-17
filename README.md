# Arosports - Dashboard Financiero

Plataforma Integral de Pádel con Dashboard Financiero para SuperAdmin

## Descripción

Sistema de gestión financiera para reservas de pádel que permite a un SuperAdmin administrar clubes, fraccionamientos, empresas y usuarios, además de generar informes financieros detallados con filtros por fechas y tipos de usuario.

## Características

- **Dashboard Financiero**: Visualización en tiempo real de ingresos, comisiones y estadísticas
- **Gestión de Usuarios**: Administración completa de usuarios por tipo (SuperAdmin, Club, Fraccionamiento, Empresa, Usuario)
- **Sistema de Reservas**: Control de reservas con diferentes tipos de entidades
- **Reportes Avanzados**: Informes por rango de fechas y filtros personalizados
- **Gráficas Interactivas**: Charts.js para visualización de datos
- **Diseño Responsivo**: Bootstrap 5 para una experiencia óptima en todos los dispositivos
- **Autenticación Segura**: Sistema de login con password_hash()
- **URLs Amigables**: Sistema de routing personalizado con .htaccess

## Tecnologías Utilizadas

- **Backend**: PHP 7+ (puro, sin framework)
- **Base de Datos**: MySQL 5.7
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework CSS**: Bootstrap 5
- **Gráficas**: Chart.js
- **Autenticación**: Sesiones PHP con password_hash()
- **Servidor Web**: Apache con mod_rewrite

## Requisitos del Sistema

- PHP 7.0 o superior
- MySQL 5.7 o superior
- Apache con mod_rewrite habilitado
- Extensiones PHP requeridas:
  - PDO
  - PDO_MySQL
  - session
  - hash

## Instalación

### 1. Clonar o descargar el proyecto

```bash
git clone https://github.com/danjohn007/arosports.git
cd arosports
```

### 2. Configurar el servidor web

Copiar todos los archivos al directorio web de Apache (ejemplo: `/var/www/html/arosports/` o `htdocs/arosports/`)

### 3. Configurar la base de datos

1. Crear una base de datos MySQL:
```sql
CREATE DATABASE arosports CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Importar el esquema y datos de ejemplo:
```bash
mysql -u root -p arosports < database/arosports_schema.sql
```

### 4. Configurar credenciales de base de datos

Editar el archivo `config/config.php` y actualizar las credenciales:

```php
define('DB_HOST', 'localhost');        // Host de la base de datos
define('DB_NAME', 'arosports');        // Nombre de la base de datos
define('DB_USER', 'tu_usuario');       // Usuario de MySQL
define('DB_PASS', 'tu_contraseña');    // Contraseña de MySQL
```

### 5. Configurar permisos

Asegurar que Apache tenga permisos de lectura en todos los archivos:

```bash
chmod -R 644 *
chmod -R 755 */
chmod 644 .htaccess
```

### 6. Verificar la instalación

1. Acceder a la URL de prueba: `http://tu-servidor/arosports/test-connection`
2. Verificar que todas las pruebas sean exitosas
3. Acceder al login: `http://tu-servidor/arosports/login`

## Configuración de URL Base

El sistema detecta automáticamente la URL base, pero si necesitas configurarla manualmente:

1. Editar `config/config.php`
2. Modificar la función `getBaseUrl()` o definir manualmente:

```php
define('BASE_URL', 'http://tu-servidor/arosports/');
```

## Credenciales por Defecto

### SuperAdmin
- **Email**: admin@arosports.com
- **Contraseña**: password

## Estructura del Proyecto

```
arosports/
├── app/
│   ├── controllers/     # Controladores MVC
│   ├── models/         # Modelos de datos (pendiente)
│   └── views/          # Vistas de la aplicación
│       ├── auth/       # Login y autenticación
│       ├── dashboard/  # Dashboard principal
│       ├── errors/     # Páginas de error
│       ├── includes/   # Header y footer
│       └── test/       # Página de pruebas
├── config/
│   ├── config.php      # Configuración principal
│   └── database.php    # Clase de conexión a BD
├── database/
│   └── arosports_schema.sql  # Esquema y datos
├── public/
│   ├── css/           # Estilos personalizados
│   ├── js/            # JavaScript personalizado
│   └── images/        # Imágenes (vacío)
├── .htaccess          # Configuración Apache
├── index.php          # Punto de entrada y router
└── README.md          # Este archivo
```

## Funcionalidades Principales

### Dashboard Financiero
- Estadísticas en tiempo real
- Gráficas de ingresos mensuales
- Distribución de ingresos por tipo
- Lista de reservas recientes

### Sistema de Gestión
- **Usuarios**: CRUD completo con tipos de usuario
- **Clubes**: Gestión de clubes deportivos
- **Fraccionamientos**: Administración de fraccionamientos
- **Empresas**: Control de empresas corporativas
- **Reservas**: Sistema completo de reservas

### Reportes
- Filtros por fecha (desde/hasta)
- Filtros por tipo de usuario
- Exportación de datos (pendiente)
- Gráficas interactivas

## Base de Datos

### Tablas Principales

- `usuarios`: Gestión de todos los usuarios del sistema
- `clubes`: Información de clubes deportivos
- `fraccionamientos`: Datos de fraccionamientos
- `empresas`: Información corporativa
- `reservas`: Tabla principal para el dashboard financiero

### Datos de Ejemplo

El archivo SQL incluye datos de ejemplo para:
- 1 SuperAdmin
- 3 Clubes
- 3 Fraccionamientos  
- 3 Empresas
- 3 Usuarios particulares
- 20+ Reservas de ejemplo con diferentes tipos y fechas

## Solución de Problemas

### Página en blanco
- Verificar permisos de archivos
- Revisar logs de error de Apache
- Verificar extensiones PHP requeridas

### Error de conexión a base de datos
- Verificar credenciales en `config/config.php`
- Confirmar que MySQL esté ejecutándose
- Verificar que la base de datos exista

### URLs no funcionan
- Verificar que mod_rewrite esté habilitado
- Confirmar que .htaccess tenga los permisos correctos
- Revisar la configuración de BASE_URL

### Error 404 en archivos CSS/JS
- Verificar la estructura de directorios
- Confirmar que BASE_URL esté configurada correctamente

## Desarrollo

Para continuar el desarrollo:

1. Los modelos MVC están pendientes en `app/models/`
2. Se pueden agregar más controladores en `app/controllers/`
3. Las vistas adicionales van en `app/views/`
4. Los archivos estáticos en `public/`

## Licencia

Este proyecto es de código abierto para fines educativos y de desarrollo.

## Soporte

Para reportar problemas o solicitar características:
1. Crear un issue en el repositorio
2. Incluir detalles del servidor y configuración
3. Adjuntar capturas de pantalla si es necesario

---

**Versión**: 1.0.0  
**Última actualización**: <?php echo date('Y-m-d'); ?>
