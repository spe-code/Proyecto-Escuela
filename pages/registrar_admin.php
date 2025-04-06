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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4a6bff;
            --secondary-color: #f8f9fa;
            --text-color: #2c3e50;
            --border-color: #e0e6ed;
        }
        
        body {
            background-color: #f5f7fa;
            color: var(--text-color);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
        }
        
        .navbar-custom {
            background-color: var(--primary-color) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .form-container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-top: 30px;
        }
        
        .table-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-top: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        
        .table {
            --bs-table-bg: transparent;
        }
        
        .table th {
            background-color: #f8fafc;
            border-bottom: 2px solid var(--border-color);
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }
        
        .table td {
            border-top: 1px solid var(--border-color);
            vertical-align: middle;
            padding: 12px 16px;
        }
        
        .status-active {
            color: #10b981;
            background-color: rgba(16, 185, 129, 0.1);
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
        }
        
        .status-inactive {
            color: #ef4444;
            background-color: rgba(239, 68, 68, 0.1);
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
        }
        
        .action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s;
            margin: 0 2px;
        }
        
        .action-btn:hover {
            background-color: #f1f5f9;
            transform: translateY(-1px);
        }
        
        .form-control, .form-select {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.2s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 107, 255, 0.1);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-primary:hover {
            background-color: #3a5bef;
            transform: translateY(-1px);
        }
        
        .btn-secondary {
            background-color: white;
            color: var(--primary-color);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-secondary:hover {
            background-color: #f8fafc;
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .alert {
            border-radius: 8px;
            padding: 12px 16px;
        }
        
        h2, h3 {
            color: #1e293b;
            font-weight: 600;
        }
        
        .nav-link {
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.2s;
        }
        
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .dropdown-menu {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        
        .dropdown-item {
            padding: 8px 16px;
            border-radius: 4px;
            transition: all 0.2s;
        }
        
        .dropdown-item:hover {
            background-color: #f8fafc;
            color: var(--primary-color);
        }
        
        
        /* Mejoras para móviles */
        @media (max-width: 768px) {
            .form-container, .table-container {
                padding: 20px;
                margin-left: 15px;
                margin-right: 15px;
            }
            
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#">
                <img src="../assets/img/logotipo-liceo.png" alt="Logo del Liceo" class="d-inline-block align-text-top me-2" style="height: 30px;">
                Liceo B. Albertina Escalona de Suros
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="index.php">
                            <i class="fas fa-home me-1"></i> Inicio
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="inscripcion.php">
                            <i class="fas fa-user-plus me-1"></i> Inscribir Estudiantes
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-search me-1"></i> Consultar Datos
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
                        <a href="registrar_admin.php" class="btn btn-outline-light ms-2">
                            <i class="fas fa-user-cog me-1"></i> Administradores
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>      
    
    <div class="container pt-5 mt-4">
        <div class="form-container">
            <h2 class="text-center mb-4"><i class="fas fa-user-plus me-2"></i>Registrar Nuevo Administrador</h2>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="username" class="form-label">Nombre de usuario</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="username" name="username" required placeholder="Ej: admin123">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required minlength="8" placeholder="Mínimo 8 caracteres">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre completo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Ej: Juan Pérez">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="Ej: ejemplo@dominio.com">
                        </div>
                    </div>
                    
                    <div class="col-12 mt-3">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="index.php" class="btn btn-secondary me-md-2"><i class="fas fa-arrow-left me-1"></i> Volver</a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Registrar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Lista de Administradores Registrados -->
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0"><i class="fas fa-users-cog me-2"></i>Administradores Registrados</h3>
                <span class="badge bg-primary"><?= count($admins) ?> registros</span>
            </div>
            
            <?php if (count($admins) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Registro</th>
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
                                    <td><?= date('d/m/Y', strtotime($admin['fecha_creacion'])) ?></td>
                                    <td><?= $admin['ultimo_acceso'] ? date('d/m/Y H:i', strtotime($admin['ultimo_acceso'])) : 'Nunca' ?></td>
                                    <td>
                                        <span class="<?= $admin['activo'] ? 'status-active' : 'status-inactive' ?>">
                                            <i class="fas fa-circle me-1" style="font-size: 8px;"></i>
                                            <?= $admin['activo'] ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="editar_admin.php?id=<?= $admin['id_admin'] ?>" class="action-btn text-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($admin['id_admin'] != $_SESSION['admin_id']): ?>
                                                <a href="cambiar_estado.php?id=<?= $admin['id_admin'] ?>&estado=<?= $admin['activo'] ? 0 : 1 ?>" class="action-btn <?= $admin['activo'] ? 'text-warning' : 'text-success' ?>" title="<?= $admin['activo'] ? 'Desactivar' : 'Activar' ?>">
                                                    <i class="fas fa-power-off"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No hay administradores registrados
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS y dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        // Efecto de carga suave
        document.addEventListener('DOMContentLoaded', function() {
            const containers = document.querySelectorAll('.form-container, .table-container');
            containers.forEach((container, index) => {
                container.style.opacity = '0';
                setTimeout(() => {
                    container.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    container.style.opacity = '1';
                }, index * 100);
            });
        });
    </script>
</body>
</html>