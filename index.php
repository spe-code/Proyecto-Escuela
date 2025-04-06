<?php
// login.php
require_once 'includes/conexion.php';
require_once 'includes/auth.php';

// Si ya está logueado, redirigir al dashboard
if (isLoggedIn()) {
    redirect(SITE_URL . '../pages/index.php');
}


// Procesar el formulario de login
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($username) || empty($password)) {
        $error = 'Por favor ingrese usuario y contraseña';
    } elseif (login($username, $password)) {
        $redirect = $_SESSION['login_redirect'] ?? '/pages/index.php';
        unset($_SESSION['login_redirect']);
        redirect(SITE_URL . '/' . $redirect);
    } else {
        $error = 'Usuario o contraseña incorrectos';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: rgba(41, 60, 167, 0.8); /* SlateBlue con transparencia */
            --secondary-color: rgba(40, 35, 122, 0.6); /* MediumSlateBlue */
            --text-color: #ffffff;
            --focus-color: rgba(255, 255, 255, 0.3);
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: 
             linear-gradient(rgba(25, 50, 70, 0.85), rgba(25, 50, 70, 0.85)),
             url('../Proyecto-Escuela/assets/img/fondo-inicio.jpeg') center/cover fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
        }
        
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .login-container {
            width: 380px;
            padding: 40px 30px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            transition: all 0.3s ease;
        }
        
        .login-container:hover {
            box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.5);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo h3 {
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 5px;
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .logo p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            margin-bottom: 0;
        }
        
        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            height: 50px;
            border-radius: 12px;
            padding-left: 45px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            background: var(--focus-color);
            color: white;
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.1);
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .input-group-text {
            background: transparent;
            border: none;
            position: absolute;
            z-index: 10;
            top: 50%;
            transform: translateY(-50%);
            left: 15px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .btn-primary {
            background: var(--primary-color);
            border: none;
            height: 50px;
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-size: 15px;
            margin-top: 10px;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .alert {
            background: rgba(255, 71, 87, 0.2);
            backdrop-filter: blur(5px);
            color: white;
            border: 1px solid rgba(255, 71, 87, 0.3);
            border-radius: 10px;
        }
        
        .forgot-password {
            text-align: center;
            margin-top: 15px;
        }
        
        .forgot-password a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 13px;
            transition: color 0.3s;
        }
        
        .forgot-password a:hover {
            color: white;
            text-decoration: underline;
        }
        
        /* Efectos adicionales */
        .input-with-icon {
            position: relative;
        }
        
        .floating-label {
            position: absolute;
            pointer-events: none;
            left: 45px;
            top: 15px;
            transition: 0.2s ease all;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }
        
        .form-control:focus ~ .floating-label,
        .form-control:not(:placeholder-shown) ~ .floating-label {
            top: -10px;
            left: 40px;
            font-size: 12px;
            background: var(--primary-color);
            padding: 0 5px;
            border-radius: 5px;
            color: white;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h3><?= SITE_NAME ?></h3>
            <p>Sistema de Administración Escolar</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger mb-3"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="index.php">
            <div class="mb-4 input-with-icon">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" class="form-control ps-5" id="username" name="username" placeholder=" " required autofocus>
                <label for="username" class="floating-label">Usuario</label>
            </div>
            
            <div class="mb-4 input-with-icon">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control ps-5" id="password" name="password" placeholder=" " required>
                <label for="password" class="floating-label">Contraseña</label>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-sign-in-alt me-2"></i> Ingresar
            </button>
            
        </form>
    </div>

    <script>
        // Efecto de animación sutil al cargar
        document.addEventListener('DOMContentLoaded', () => {
            const loginContainer = document.querySelector('.login-container');
            loginContainer.style.opacity = '0';
            loginContainer.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                loginContainer.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                loginContainer.style.opacity = '1';
                loginContainer.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>