php
<?php
include 'conexion.php';

$id = $_GET['id'];

$sql = "SELECT estudiantes.*, representantes.nombre as nombre_representante, representantes.telefono, representantes.email 
        FROM estudiantes 
        JOIN representantes ON estudiantes.id_representante = representantes.id 
        WHERE estudiantes.id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id]);
$estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

if ($estudiante) {
    echo "<h1>Ficha de Inscripción</h1>";
    echo "<p><strong>Nombre:</strong> " . $estudiante['nombre'] . "</p>";
    echo "<p><strong>Cédula:</strong> " . $estudiante['cedula'] . "</p>";
    echo "<p><strong>Fecha de Nacimiento:</strong> " . $estudiante['fecha_nacimiento'] . "</p>";
    echo "<p><strong>Grado:</strong> " . $estudiante['grado'] . "</p>";
    echo "<p><strong>Representante:</strong> " . $estudiante['nombre_representante'] . "</p>";
    echo "<p><strong>Teléfono:</strong> " . $estudiante['telefono'] . "</p>";
    echo "<p><strong>Email:</strong> " . $estudiante['email'] . "</p>";
} else {
    echo "Estudiante no encontrado.";
}
?>