<?php
// /includes/auth.php

require_once 'conexion.php';

// Verificar si el usuario está logueado
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

// Proteger páginas que requieren autenticación
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['login_redirect'] = $_SERVER['REQUEST_URI'];
        redirect('/Proyecto-Escuela/index.php');
    }
}

// Función para login
function login($username, $password) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id_admin, username, password_hash, nombre_completo FROM administradores 
                          WHERE username = ? AND activo = 1 LIMIT 1");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    
    if ($admin && password_verify($password, $admin['password_hash'])) {
        // Autenticación exitosa
        $_SESSION['admin_id'] = $admin['id_admin'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_nombre'] = $admin['nombre_completo'];
        
        // Actualizar último acceso
        $conn->prepare("UPDATE administradores SET ultimo_acceso = NOW() WHERE id_admin = ?")
            ->execute([$admin['id_admin']]);
            
        return true;
    }
    
    return false;
}

// Función para logout
function logout() {
    session_unset();
    session_destroy();
    session_start();
}
?>