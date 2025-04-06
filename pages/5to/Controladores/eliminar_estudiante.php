<?php
include '../../../includes/conexion.php'; // Incluye la conexión a la base de datos

if (isset($_GET['id'])) {
    $id = $_GET['id']; // Obtener el ID del estudiante desde la URL

    try {
        // Iniciar una transacción para asegurar la integridad de los datos
        $conn->beginTransaction();

        // Eliminar los datos de la madre
        $sql_madre = "DELETE FROM madre WHERE id_estudiante = :id";
        $stmt_madre = $conn->prepare($sql_madre);
        $stmt_madre->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_madre->execute();

        // Eliminar los datos del padre
        $sql_padre = "DELETE FROM padre WHERE id_estudiante = :id";
        $stmt_padre = $conn->prepare($sql_padre);
        $stmt_padre->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_padre->execute();

        // Eliminar los datos del representante legal
        $sql_representante = "DELETE FROM representante_legal WHERE id_estudiante = :id";
        $stmt_representante = $conn->prepare($sql_representante);
        $stmt_representante->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_representante->execute();

        // Eliminar los datos del estudiante
        $sql_estudiante = "DELETE FROM estudiantes WHERE id_estudiante = :id";
        $stmt_estudiante = $conn->prepare($sql_estudiante);
        $stmt_estudiante->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_estudiante->execute();

        // Confirmar la transacción
        $conn->commit();

        // Respuesta de éxito
        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        // Revertir la transacción en caso de error
        $conn->rollBack();
        // Respuesta de error
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    // Respuesta de error si no se proporciona un ID
    echo json_encode(['status' => 'error', 'message' => 'ID no proporcionado']);
}

$conn = null; // Cerrar la conexión PDO
?>