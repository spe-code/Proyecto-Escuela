<?php
// Incluir conexión y establecer headers primero
include '../../includes/conexion.php';
header('Content-Type: application/json');

// Configuración para producción (ocultar errores)
error_reporting(0);
ini_set('display_errors', 0);

// Verificar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Verificar datos recibidos
if (!isset($_POST['estudiantes'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No se recibió el array de estudiantes']);
    exit;
}

if (empty($_POST['estudiantes'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'El array de estudiantes está vacío']);
    exit;
}

// Obtener el período escolar (del formulario o calcularlo)
$nuevoPeriodo = isset($_POST['nuevo_periodo']) ? $_POST['nuevo_periodo'] : '';
if (empty($nuevoPeriodo)) {
    $añoActual = date('Y');
    $nuevoPeriodo = $añoActual.'-'.($añoActual+1);
}

// Validar y sanitizar IDs de estudiantes
$estudiantes = array_filter($_POST['estudiantes'], function($id) {
    return is_numeric($id) && $id > 0;
});

if (empty($estudiantes)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'IDs de estudiantes no válidos']);
    exit;
}

// Procesar la transacción
$conn->beginTransaction();
$actualizados = 0;
$errores = [];

try {
    $sql = "UPDATE estudiantes 
            SET año_cursar_estudiante = 4,
                periodo_escolar = :nuevo_periodo
            WHERE id_estudiante = :id AND año_cursar_estudiante = 3";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nuevo_periodo', $nuevoPeriodo);
    
    foreach ($estudiantes as $id_estudiante) {
        try {
            $stmt->bindValue(':id', (int)$id_estudiante, PDO::PARAM_INT);
            $stmt->execute();
            $actualizados += $stmt->rowCount();
        } catch (PDOException $e) {
            $errores[] = "Error con estudiante ID $id_estudiante: " . $e->getMessage();
        }
    }
    
    if (empty($errores)) {
        $conn->commit();
        echo json_encode([
            'success' => true,
            'message' => "Se promovieron exitosamente $actualizados estudiantes a 4to año",
            'periodo' => $nuevoPeriodo,
            'actualizados' => $actualizados
        ]);
    } else {
        $conn->rollBack();
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Ocurrieron algunos errores al procesar',
            'error' => implode(" | ", $errores),
            'actualizados' => $actualizados,
            'errores' => count($errores)
        ]);
    }
    
} catch (PDOException $e) {
    $conn->rollBack();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error en la transacción de base de datos',
        'error' => $e->getMessage()
    ]);
}