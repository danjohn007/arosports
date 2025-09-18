<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'fix360_arosports');
define('DB_USER', 'fix360_arosports');
define('DB_PASS', 'Danjohn007!');
define('DB_CHARSET', 'utf8mb4');

// Application configuration
define('APP_NAME', 'Arosports Dashboard');
define('APP_VERSION', '1.0.0');

// Session configuration
// ¡IMPORTANTE! Este archivo debe incluirse ANTES de cualquier session_start()
// Verificar que la sesión no esté activa antes de configurar ini_set para evitar warnings
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0); // Cambia a 1 si usas HTTPS
}

// Timezone
date_default_timezone_set('America/Mexico_City');

// Auto-detect base URL
function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];
    $path = dirname($script);
    
    // Remove index.php from path if present
    $path = str_replace('/index.php', '', $path);
    
    // Ensure path ends with /
    if ($path !== '/' && substr($path, -1) !== '/') {
        $path .= '/';
    }
    
    return $protocol . $host . $path;
}

define('BASE_URL', getBaseUrl());
?>
