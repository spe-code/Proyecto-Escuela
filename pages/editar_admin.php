<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/conexion.php';

// Verificar si se recibió ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID de administrador no válido";
    header("Location: registrar_admin.php");
    exit();
}

$admin_id = $_GET['id'];

// Obtener datos del administrador
$stmt = $conn->prepare("SELECT id_admin, username, nombre_completo, email, activo FROM administradores WHERE id_admin = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch();

if (!$admin) {
    $_SESSION['error'] = "Administrador no encontrado";
    header("Location: registrar_admin.php");
    exit();
}

// Procesar el formulario si se envió
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $activo = isset($_POST['activo']) ? 1 : 0;
    
    // Validaciones básicas
    if (empty($username) || empty($nombre) || empty($email)) {
        $_SESSION['error'] = "Todos los campos son obligatorios";
    } else {
        try {
            $stmt = $conn->prepare("UPDATE administradores SET username = ?, nombre_completo = ?, email = ?, activo = ? WHERE id_admin = ?");
            $stmt->execute([$username, $nombre, $email, $activo, $admin_id]);
            
            $_SESSION['success'] = "Administrador actualizado exitosamente";
            header("Location: registrar_admin.php");
            exit();
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error al actualizar el administrador: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(rgba(25, 50, 70, 0.85), rgba(25, 50, 70, 0.85));
            color: white;
            height: 100vh;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            max-width: 500px;
            margin: 50px auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-4">Editar Administrador</h2>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Nombre de usuario</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($admin['username']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($admin['nombre_completo']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="activo" name="activo" <?= $admin['activo'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="activo">Activo</label>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <a href="registrar_admin.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>