<?php
include '../../../includes/conexion.php'; // Incluye la conexión a la base de datos

// Obtener el ID del estudiante desde la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    die("ID del estudiante no proporcionado.");
}

// Consulta para obtener los detalles del estudiante
$sql = "SELECT 
            e.*, 
            m.nombre_madre, 
            m.apellido_madre, 
            m.cedula_madre, 
            m.telefono_local_madre, 
            m.telefono_celular_madre, 
            m.direccion_madre, 
            m.oficio_madre,
            p.nombre_padre, 
            p.apellido_padre, 
            p.cedula_padre, 
            p.telefono_local_padre, 
            p.telefono_celular_padre, 
            p.direccion_padre, 
            p.oficio_padre,
            r.nombre_representante, 
            r.apellido_representante, 
            r.cedula_representante, 
            r.telefono_local_representante, 
            r.telefono_celular_representante, 
            r.parentesco_representante, 
            r.direccion_representante, 
            r.codigo_representante, 
            r.serial_representante
        FROM estudiantes e
        LEFT JOIN madre m ON e.id_estudiante = m.id_estudiante
        LEFT JOIN padre p ON e.id_estudiante = p.id_estudiante
        LEFT JOIN representante_legal r ON e.id_estudiante = r.id_estudiante
        WHERE e.id_estudiante = :id";

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

// Obtener los resultados
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    die("Estudiante no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Estudiante</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Detalles del Estudiante</h1>

        <!-- Datos del Estudiante -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-4">Datos del Estudiante</h5>
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($row['nombre_estudiante']); ?></p>
                        <p><strong>Apellido:</strong> <?php echo htmlspecialchars($row['apellido_estudiante']); ?></p>
                        <p><strong>Cédula:</strong> <?php echo htmlspecialchars($row['cedula_estudiante']); ?></p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Nacionalidad:</strong> <?php echo htmlspecialchars($row['nacionalidad_estudiante']); ?></p>
                        <p><strong>Fecha de Nacimiento:</strong> <?php echo htmlspecialchars($row['fecha_nacimiento_estudiante']); ?></p>
                        <p><strong>Lugar de Nacimiento:</strong> <?php echo htmlspecialchars($row['lugar_nacimiento_estudiante']); ?></p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Teléfono Local:</strong> <?php echo htmlspecialchars($row['telefono_local_estudiante']); ?></p>
                        <p><strong>Teléfono Celular:</strong> <?php echo htmlspecialchars($row['telefono_celular_estudiante']); ?></p>
                        <p><strong>Correo:</strong> <?php echo htmlspecialchars($row['correo_estudiante']); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>Año que Cursa:</strong> <?php echo htmlspecialchars($row['año_cursar_estudiante']); ?></p>
                        <p><strong>Sección:</strong> <?php echo htmlspecialchars($row['seccion_estudiante']); ?></p>
                        <p><strong>Dirección:</strong> <?php echo htmlspecialchars($row['direccion_estudiante']); ?></p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Tipo de Estudiante:</strong> <?php echo htmlspecialchars($row['tipo_estudiante']); ?></p>
                        <p><strong>Padece Enfermedad:</strong> <?php echo htmlspecialchars($row['padece_enfermedad_estudiante']); ?></p>
                        <p><strong>Es Alérgico:</strong> <?php echo htmlspecialchars($row['es_alergico_estudiante']); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Datos de la Madre, Padre y Representante Legal -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-4">Datos de la Madre, Padre y Representante Legal</h5>
                <div class="row">
                    <!-- Madre -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Madre</h6>
                                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($row['nombre_madre']); ?></p>
                                <p><strong>Apellido:</strong> <?php echo htmlspecialchars($row['apellido_madre']); ?></p>
                                <p><strong>Cédula:</strong> <?php echo htmlspecialchars($row['cedula_madre']); ?></p>
                                <p><strong>Teléfono Local:</strong> <?php echo htmlspecialchars($row['telefono_local_madre']); ?></p>
                                <p><strong>Teléfono Celular:</strong> <?php echo htmlspecialchars($row['telefono_celular_madre']); ?></p>
                                <p><strong>Dirección:</strong> <?php echo htmlspecialchars($row['direccion_madre']); ?></p>
                                <p><strong>Oficio:</strong> <?php echo htmlspecialchars($row['oficio_madre']); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Padre -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Padre</h6>
                                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($row['nombre_padre']); ?></p>
                                <p><strong>Apellido:</strong> <?php echo htmlspecialchars($row['apellido_padre']); ?></p>
                                <p><strong>Cédula:</strong> <?php echo htmlspecialchars($row['cedula_padre']); ?></p>
                                <p><strong>Teléfono Local:</strong> <?php echo htmlspecialchars($row['telefono_local_padre']); ?></p>
                                <p><strong>Teléfono Celular:</strong> <?php echo htmlspecialchars($row['telefono_celular_padre']); ?></p>
                                <p><strong>Dirección:</strong> <?php echo htmlspecialchars($row['direccion_padre']); ?></p>
                                <p><strong>Oficio:</strong> <?php echo htmlspecialchars($row['oficio_padre']); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Representante Legal -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Representante Legal</h6>
                                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($row['nombre_representante']); ?></p>
                                <p><strong>Apellido:</strong> <?php echo htmlspecialchars($row['apellido_representante']); ?></p>
                                <p><strong>Cédula:</strong> <?php echo htmlspecialchars($row['cedula_representante']); ?></p>
                                <p><strong>Teléfono Local:</strong> <?php echo htmlspecialchars($row['telefono_local_representante']); ?></p>
                                <p><strong>Teléfono Celular:</strong> <?php echo htmlspecialchars($row['telefono_celular_representante']); ?></p>
                                <p><strong>Parentesco:</strong> <?php echo htmlspecialchars($row['parentesco_representante']); ?></p>
                                <p><strong>Direccion:</strong> <?php echo htmlspecialchars($row['direccion_representante']); ?></p>
                                <p><strong>Código:</strong> <?php echo htmlspecialchars($row['codigo_representante']); ?></p> 
                                <p><strong>Serial:</strong> <?php echo htmlspecialchars($row['serial_representante']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn = null; // Cerrar la conexión PDO
?>