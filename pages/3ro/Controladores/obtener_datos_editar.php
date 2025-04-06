<?php
include '../../../includes/conexion.php'; // Incluye la conexión a la base de datos

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta para obtener los detalles del estudiante y sus relaciones
    $sql = "SELECT 
                e.*, 
                m.*, 
                p.*, 
                r.*
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

    if ($row) {
        // Devolver los datos en formato JSON
        echo json_encode($row);
    } else {
        // Devolver un mensaje de error si no se encuentra el estudiante
        echo json_encode(['error' => 'Estudiante no encontrado.']);
    }
} else {
    // Devolver un mensaje de error si no se proporciona el ID
    echo json_encode(['error' => 'ID no proporcionado.']);
}

// Cerrar la conexión
$conn = null;
?>