<?php
include '../../includes/conexion.php'; // Incluye la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el ID del estudiante y los datos del formulario
    $id = $_POST['id'];
    $datos = $_POST['datos'];

    // Función para construir consultas UPDATE dinámicas
    function buildUpdateQuery($table, $fields, $id) {
        $updates = [];
        foreach ($fields as $field => $value) {
            if ($value !== null && $value !== '') {
                $updates[] = "$field = '$value'";
            }
        }
        if (!empty($updates)) {
            return "UPDATE $table SET " . implode(", ", $updates) . " WHERE id_estudiante = '$id'";
        }
        return null;
    }

    // Actualizar datos del estudiante
    $fieldsEstudiante = [
        'nombre_estudiante' => $datos['nombreEstudiante'],
        'apellido_estudiante' => $datos['apellidoEstudiante'],
        'cedula_estudiante' => $datos['cedulaEstudiante'],
        'fecha_nacimiento_estudiante' => $datos['fechaNacimientoEstudiante'],
        'lugar_nacimiento_estudiante' => $datos['lugarNacimientoEstudiante'],
        'direccion_estudiante' => $datos['direccionEstudiante'],
        'telefono_local_estudiante' => $datos['telefonoLocalEstudiante'],
        'telefono_celular_estudiante' => $datos['telefonoCelularEstudiante'],
        'correo_estudiante' => $datos['correoEstudiante'],
        'año_cursar_estudiante' => $datos['añoCursarEstudiante'],
        'seccion_estudiante' => $datos['seccionEstudiante'],
        'tipo_estudiante' => $datos['tipoEstudiante'],
        'padece_enfermedad_estudiante' => $datos['padeceEnfermedad'],
        'es_alergico_estudiante' => $datos['esAlergico']
    ];

    $sqlEstudiante = buildUpdateQuery('estudiantes', $fieldsEstudiante, $id);
    if ($sqlEstudiante) {
        if ($conn->query($sqlEstudiante) === TRUE) {
            echo "Datos del estudiante actualizados correctamente.<br>";
        } else {
            echo "Error al actualizar datos del estudiante: " . $e->getMessage() . "<br>";
        }
    }

    // Actualizar datos de la madre
    $fieldsMadre = [
        'nombre_madre' => $datos['nombreMadre'],
        'apellido_madre' => $datos['apellidoMadre'],
        'cedula_madre' => $datos['cedulaMadre'],
        'telefono_local_madre' => $datos['telefonoLocalMadre'],
        'telefono_celular_madre' => $datos['telefonoCelularMadre'],
        'direccion_madre' => $datos['direccionMadre'],
        'oficio_madre' => $datos['oficioMadre']
    ];

    $sqlMadre = buildUpdateQuery('madre', $fieldsMadre, $id);
    if ($sqlMadre) {
        if ($conn->query($sqlMadre) === TRUE) {
            echo "Datos de la madre actualizados correctamente.<br>";
        } else {
            echo "Error al actualizar datos de la madre: " . $e->getMessage() . "<br>";
        }
    }

    // Actualizar datos del padre
    $fieldsPadre = [
        'nombre_padre' => $datos['nombrePadre'],
        'apellido_padre' => $datos['apellidoPadre'],
        'cedula_padre' => $datos['cedulaPadre'],
        'telefono_local_padre' => $datos['telefonoLocalPadre'],
        'telefono_celular_padre' => $datos['telefonoCelularPadre'],
        'direccion_padre' => $datos['direccionPadre'],
        'oficio_padre' => $datos['oficioPadre']
    ];

    $sqlPadre = buildUpdateQuery('padre', $fieldsPadre, $id);
    if ($sqlPadre) {
        if ($conn->query($sqlPadre) === TRUE) {
            echo "Datos del padre actualizados correctamente.<br>";
        } else {
            echo "Error al actualizar datos del padre: " . $e->getMessage() . "<br>";
        }
    }

    // Actualizar datos del representante legal
    $fieldsRepresentante = [
        'nombre_representante' => $datos['nombreRepresentante'],
        'apellido_representante' => $datos['apellidoRepresentante'],
        'cedula_representante' => $datos['cedulaRepresentante'],
        'telefono_local_representante' => $datos['telefonoLocalRepresentante'],
        'telefono_celular_representante' => $datos['telefonoCelularRepresentante'],
        'parentesco_representante' => $datos['parentescoRepresentante'],
        'carnet_patria_representante' => $datos['carnetPatria'],
        'codigo_representante' => $datos['codigo'],
        'serial_representante' => $datos['serial']
    ];

    $sqlRepresentante = buildUpdateQuery('representante_legal', $fieldsRepresentante, $id);
    if ($sqlRepresentante) {
        if ($conn->query($sqlRepresentante) === TRUE) {
            echo "Datos del representante legal actualizados correctamente.<br>";
        } else {
            echo "Error al actualizar datos del representante legal: " . $e->getMessage() . "<br>";
        }
    }

    echo "Datos actualizados correctamente.";
} else {
    echo "Error: Método de solicitud no válido.";
}

// Cerrar la conexión
?>