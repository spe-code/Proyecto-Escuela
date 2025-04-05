<?php
// /includes/config.php

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'escuela');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuración general de la aplicación
define('SITE_NAME', 'Sistema Escolar');
define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST'] . str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));

// Iniciar sesión segura
session_start([
    'cookie_lifetime' => 86400, // 1 día
    'cookie_secure'   => isset($_SERVER['HTTPS']), // Solo enviar cookies sobre HTTPS si está disponible
    'cookie_httponly' => true, // Prevenir acceso a cookies via JavaScript
    'use_strict_mode' => true // Mayor seguridad para IDs de sesión
]);

// Conexión a la base de datos
try {
    $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Usar prepared statements nativos
} catch (PDOException $e) {
    // En producción, no mostrar el error real al usuario
    error_log("Error de conexión: " . $e->getMessage());
    die("Error al conectar con la base de datos. Por favor intente más tarde.");
}

// Función para redireccionar
function redirect($url) {
    header("Location: $url");
    exit();
}

// Función para escapar output HTML
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Verificar si es una solicitud AJAX
function is_ajax() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}