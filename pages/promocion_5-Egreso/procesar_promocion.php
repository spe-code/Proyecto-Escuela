<?php
// Limpiar cualquier salida anterior
ob_start();

// Configuración estricta para JSON
header('Content-Type: application/json; charset=utf-8');

try {
    // Validar método HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido', 405);
    }

    // Incluir conexión después de los headers
    require_once '../../includes/conexion.php';
    
    // Validar entrada
    if (empty($_POST['estudiantes']) || !is_array($_POST['estudiantes'])) {
        throw new Exception('No se recibieron estudiantes válidos', 400);
    }

    $estudiantes = $_POST['estudiantes'];
    $añoEgreso = date('Y');
    $resultados = [];

    // Validar conexión a la base de datos
    if (!$conn) {
        throw new Exception('Error de conexión a la base de datos', 500);
    }

    $conn->beginTransaction();

    foreach ($estudiantes as $id_estudiante) {
        try {
            // Validar ID del estudiante
            if (!is_numeric($id_estudiante)) {
                throw new Exception("ID de estudiante inválido: $id_estudiante");
            }

            // Verificar estudiante
            $sqlVerificar = "SELECT id_estudiante, periodo_escolar FROM estudiantes WHERE id_estudiante = ? AND año_cursar_estudiante = 5";
            $stmtVerificar = $conn->prepare($sqlVerificar);
            if (!$stmtVerificar->execute([$id_estudiante])) {
                throw new Exception("Error al verificar estudiante ID: $id_estudiante");
            }

            $datosEstudiante = $stmtVerificar->fetch(PDO::FETCH_ASSOC);
            if (!$datosEstudiante) {
                throw new Exception("Estudiante ID $id_estudiante no está en 5to grado o no existe");
            }

            // Insertar en egresados (con columnas coincidentes)
            $sqlInsert = "INSERT INTO egresados 
                         (id_estudiante, nombre_estudiante, apellido_estudiante, 
                          fecha_nacimiento_estudiante, sexo_estudiante, 
                          direccion_estudiante, telefono_estudiante, correo_estudiante, 
                          periodo_escolar, año_egreso)
                         SELECT 
                            e.id_estudiante, 
                            e.nombre_estudiante, 
                            e.apellido_estudiante, 
                            e.fecha_nacimiento_estudiante, 
                            e.sexo_estudiante, 
                            e.direccion_estudiante, 
                            COALESCE(e.telefono_celular_estudiante, e.telefono_local_estudiante) as telefono_estudiante,
                            e.correo_estudiante,
                            e.periodo_escolar,
                            ?
                         FROM estudiantes e
                         WHERE e.id_estudiante = ?";
            
            $stmtInsert = $conn->prepare($sqlInsert);
            if (!$stmtInsert->execute([$añoEgreso, $id_estudiante])) {
                throw new Exception("Error al insertar egresado ID: $id_estudiante");
            }

            // Eliminar de estudiantes
            $sqlDelete = "DELETE FROM estudiantes WHERE id_estudiante = ?";
            $stmtDelete = $conn->prepare($sqlDelete);
            if (!$stmtDelete->execute([$id_estudiante])) {
                throw new Exception("Error al eliminar estudiante ID: $id_estudiante");
            }

            $resultados[] = ['id' => $id_estudiante, 'status' => 'success'];

        } catch (Exception $e) {
            $resultados[] = [
                'id' => $id_estudiante, 
                'status' => 'error', 
                'message' => $e->getMessage()
            ];
        }
    }

    $conn->commit();

    // Limpiar buffer y enviar respuesta
    ob_end_clean();
    echo json_encode([
        'success' => true,
        'message' => 'Egreso completado',
        'results' => $resultados,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit;

} catch (PDOException $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    ob_end_clean();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error en la base de datos',
        'error' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
    exit;
} catch (Exception $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    ob_end_clean();
    http_response_code(is_int($e->getCode()) ? $e->getCode() : 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
    exit;
}
?>