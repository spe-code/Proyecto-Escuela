<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/conexion.php';

// Procesar el formulario si se envió
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    
    // Validaciones básicas
    if (empty($username) || empty($password) || empty($nombre) || empty($email)) {
        $_SESSION['error'] = "Todos los campos son obligatorios";
    } elseif (strlen($password) < 8) {
        $_SESSION['error'] = "La contraseña debe tener al menos 8 caracteres";
    } else {
        // Hash de la contraseña
        $hash = password_hash($password, PASSWORD_BCRYPT);
        
        try {
            $stmt = $conn->prepare("INSERT INTO administradores (username, password_hash, nombre_completo, email) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $hash, $nombre, $email]);
            
            $_SESSION['success'] = "Administrador registrado exitosamente";
            header("Location: registrar_admin.php");
            exit();
        } catch (connException $e) {
            $_SESSION['error'] = "Error al registrar el administrador: " . $e->getMessage();
        }
    }
}

// Obtener lista de administradores
$admins = $conn->query("SELECT id_admin, username, nombre_completo, email, fecha_creacion, ultimo_acceso, activo FROM administradores ORDER BY fecha_creacion DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(rgba(25, 50, 70, 0.85), rgba(25, 50, 70, 0.85));
            color: white;
            min-height: 100vh;
            padding-bottom: 50px;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            max-width: 800px;
            margin: 50px auto;
        }
        .table-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            margin-top: 30px;
        }
        .table {
            color: white;
        }
        .table th {
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }
        .table td {
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            vertical-align: middle;
        }
        .status-active {
            color: #28a745;
        }
        .status-inactive {
            color: #dc3545;
        }
        .action-btn {
            margin: 0 3px;
            opacity: 0.8;
            transition: opacity 0.3s;
        }
        .action-btn:hover {
            opacity: 1;
        }
    </style>
</head>
<body>
	 <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-custom fixed-top " style="background-color:rgb(39, 99, 173);">
        <div class="container-fluid">
            <!-- Logo o nombre de la aplicación -->
            <a class="navbar-brand" style="color: white;" href="#">
             <img src="../assets/img/logotipo-liceo.png" alt="Logo del Liceo" class="d-inline-block align-text-top ms-2" style="height: 30px;">
            <i class=""></i> Liceo B. Albertina Escalona de Suros
            </a>

            <!-- Botón para colapsar el navbar en móviles -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Contenido del navbar -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Opción: Inicio -->
                    <li class="nav-item">
                        <a class="nav-link active" style="color: white;" href="index.php">
                            <i class="fas fa-home"></i> Inicio
                        </a>
                    </li>

                    <!-- Opción: Inscribir Estudiantes -->
                    <li class="nav-item">
                        <a class="nav-link" style="color: white;" href="inscripcion.php">
                            <i class="fas fa-user-plus"></i> Inscribir Estudiantes
                        </a>
                    </li>

                    <!-- Opción: Consultar Datos (con menú desplegable) -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" style="color: white;" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-search"></i> Consultar Datos
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="../pages/1ro/consultar_estudiantes_1ro.php" target="_blank">1er AÑO</a></li>
                            <li><a class="dropdown-item" href="../pages/2do/consultar_estudiantes_2do.php" target="_blank">2do AÑO</a></li>
                            <li><a class="dropdown-item" href="../pages/3ro/consultar_estudiantes_3ro.php" target="_blank">3er AÑO</a></li>
                            <li><a class="dropdown-item" href="../pages/4to/consultar_estudiantes_4to.php" target="_blank">4to AÑO</a></li>
                            <li><a class="dropdown-item" href="../pages/5to/consultar_estudiantes_5to.php" target="_blank">5to AÑO</a></li>
                            
                        </ul>
                        </li>
                           <li class="nav-item">
                    <a href="registrar_admin.php" class="btn btn-admin" style="color: white;">
                        <i class="fas fa-user-cog"></i> Administrares
            
            </a>
                </li>
                         <!-- Botón de Cerrar Sesión -->
                <li class="nav-item">
                    <a class="nav-link" style="color: white;" href="../includes/logout.php" onclick="return confirm('¿Estás seguro que deseas cerrar sesión?');">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                </li>
                    </ul>
                </div>
            </div>
         </nav>      
    <div class="container pt-4">
        <div class="form-container" >
            <h2 class="text-center mb-4">Registrar Nuevo Administrador</h2>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Nombre de usuario</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="8">
                        <small class="text-muted">Mínimo 8 caracteres</small>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre completo</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                    <a href="index.php" class="btn btn-secondary">Volver al Panel</a>
                </div>
            </form>

            <!-- Lista de Administradores Registrados -->
            <div class="table-container mt-5">
                <h3 class="mb-4">Administradores Registrados</h3>
                
                <?php if (count($admins) > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                
                                    <th>Usuario</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Fecha Registro</th>
                                    <th>Último Acceso</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($admins as $admin): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($admin['username']) ?></td>
                                        <td><?= htmlspecialchars($admin['nombre_completo']) ?></td>
                                        <td><?= htmlspecialchars($admin['email']) ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($admin['fecha_creacion'])) ?></td>
                                        <td><?= $admin['ultimo_acceso'] ? date('d/m/Y H:i', strtotime($admin['ultimo_acceso'])) : 'Nunca' ?></td>
                                        <td>
                                            <span class="<?= $admin['activo'] ? 'status-active' : 'status-inactive' ?>">
                                                <i class="bi bi-circle-fill"></i> <?= $admin['activo'] ? 'Activo' : 'Inactivo' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="editar_admin.php?id=<?= $admin['id_admin'] ?>" class="text-primary action-btn" title="Editar">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <?php if ($admin['id_admin'] != $_SESSION['admin_id']): ?>
                                                <a href="cambiar_estado.php?id=<?= $admin['id_admin'] ?>&estado=<?= $admin['activo'] ? 0 : 1 ?>" class="text-warning action-btn" title="<?= $admin['activo'] ? 'Desactivar' : 'Activar' ?>">
                                                    <i class="bi bi-power"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">No hay administradores registrados</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS y dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>