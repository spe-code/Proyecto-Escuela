<?php
include '../includes/conexion.php';

// Verificar si se recibieron datos
if (!isset($_POST['estudiantes']) || empty($_POST['estudiantes'])) {
    echo json_encode(['success' => false, 'message' => 'No se recibieron estudiantes para promover']);
    exit;
}

// Obtener el próximo período escolar
$añoActual = date('Y');
$periodoSiguiente = $añoActual.'-'.($añoActual+1);

$estudiantes = $_POST['estudiantes'];
$conn->beginTransaction();

try {
    // Actualizar cada estudiante seleccionado
    foreach ($estudiantes as $id_estudiante) {
        // Actualizar el año que cursa y el período escolar
        $sql = "UPDATE estudiantes 
                SET año_cursar_estudiante = 3,
                    periodo_escolar = :periodo_siguiente
                WHERE id_estudiante = :id AND año_cursar_estudiante = 2";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id_estudiante, PDO::PARAM_INT);
        $stmt->bindParam(':periodo_siguiente', $periodoSiguiente);
        $stmt->execute();
    }
    
    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Estudiantes promovidos exitosamente a 3er año para el período '.$periodoSiguiente]);
} catch (PDOException $e) {
    $conn->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error al promover estudiantes: ' . $e->getMessage()]);
}
?>