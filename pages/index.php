<?php
require_once '../includes/auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sistema de Gestión Escolar</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Estilos personalizados -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: 
             linear-gradient(rgba(25, 50, 70, 0.85), rgba(25, 50, 70, 0.85)),
             url('../assets/img/fondo-inicio.jpeg') center/cover fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
        }

        .welcome-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.18);
            max-height: 400px;
            height: 100%;
            max-width: 1200px;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .welcome-title {
            font-size: 2.5rem;
            color: #fff;
            margin-bottom: 20px;
        }

        .welcome-container p {
            font-size: 1.2rem;
            color: #e0e0e0;
            margin-bottom: 30px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 1rem;
            transition: background-color 0.3s ease, transform 0.3s ease;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 1rem;
            transition: background-color 0.3s ease, transform 0.3s ease;
            color: #fff;
        }

        .btn-secondary:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: rgba(255, 0, 0, 0.6);
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 1rem;
            transition: background-color 0.3s ease, transform 0.3s ease;
            color: #fff;
        }

        .btn-danger:hover {
            background-color: rgba(146, 4, 4, 0.89);
            transform: translateY(-2px);
        }

        .btn-admin {
            background-color: rgba(0, 194, 0, 0.47);
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 1rem;
            transition: background-color 0.3s ease, transform 0.3s ease;
            color: #fff;
        }

        .btn-admin:hover {
            background-color: rgba(4, 131, 4, 0.46);
            color: white;
            transform: translateY(-2px);
        }

        .dropdown-menu {
            background-color: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item {
            color: #333;
            transition: background-color 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .user-info {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            font-size: 0.9rem;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <!-- Botón de cerrar sesión -->
    <div class="logout-btn">
        <a href="../includes/logout.php" class="btn btn-danger">
            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
        </a>
    </div>

    <!-- Información del usuario -->
    <div class="user-info">
        <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['admin_nombre'] ?? 'Administrador'); ?>
    </div>

    <!-- Contenido principal -->
    <div class="welcome-container">
        <h1 class="welcome-title">Bienvenido al Sistema de Gestión Escolar</h1>
        <p>Gestiona la información de los estudiantes de manera eficiente.</p>
        
        <!-- Grupo de botones -->
        <div class="button-group">
            <!-- Botón de Inscribir Estudiante -->
            <a href="inscripcion.php" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Inscribir Estudiante
            </a>

            <!-- Botón de Consultar Datos con Dropdown -->
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-search"></i> Consultar Datos
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="../pages/1ro/consultar_estudiantes_1ro.php">1er AÑO</a></li>
                    <li><a class="dropdown-item" href="../pages/2do/consultar_estudiantes_2do.php">2do AÑO</a></li>
                    <li><a class="dropdown-item" href="../pages/3ro/consultar_estudiantes_3ro.php">3er AÑO</a></li>
                    <li><a class="dropdown-item" href="../pages/4to/consultar_estudiantes_4to.php">4to AÑO</a></li>
                    <li><a class="dropdown-item" href="../pages/5to/consultar_estudiantes_5to.php">5to AÑO</a></li>
                </ul>
            </div>

            <!-- Botón de Nuevo Año Escolar -->
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-graduate"></i> Nuevo Año Escolar
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="../pages/promocion_1-2/NuevoPeriodo_1-2.php">1er AÑO</a></li>
                    <li><a class="dropdown-item" href="../pages/promocion_2-3/NuevoPeriodo_2-3.php">2do AÑO</a></li>
                    <li><a class="dropdown-item" href="../pages/promocion_3-4/NuevoPeriodo_3-4.php">3er AÑO</a></li>
                    <li><a class="dropdown-item" href="../pages/promocion_4-5/NuevoPeriodo_4-5.php">4to AÑO</a></li>
                    <li><a class="dropdown-item" href="../pages/promocion_5-Egreso/NuevoPeriodo_5-Egreso.php">5to AÑO</a></li>
                </ul>
            </div>

            <!-- Botón de Consultar Egresados -->
            <a href="../pages/promocion_5-Egreso/consultar_egresados.php" class="btn btn-secondary">
                <i class="fa-solid fa-graduation-cap fa-shake"></i> Consultar Egresados
            </a>

            <!-- Botón de Registrar Administradores -->
            <a href="registrar_admin.php" class="btn btn-admin">
                <i class="fas fa-user-shield"></i> Registrar Administradores
            </a>
        </div>
    </div>

    <!-- Bootstrap JS y dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <!-- FontAwesome para el icono de egresados -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>