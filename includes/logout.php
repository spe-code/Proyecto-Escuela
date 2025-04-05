<?php
// admin/logout.php
require_once '../includes/auth.php';

logout();
$_SESSION['message'] = 'Has cerrado sesión correctamente';
redirect('/Proyecto-Escuela/index.php');