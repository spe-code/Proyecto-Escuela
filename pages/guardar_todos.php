<?php
include '../includes/conexion.php'; // Incluye la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario de la madre
    $nombre_madre = $_POST['nombre_madre'];
    $apellido_madre = $_POST['apellido_madre'];
    $cedula_madre = $_POST['cedula_madre'];
    $telefono_local_madre = $_POST['telefono_local'];
    $telefono_madre = $_POST['telefono_madre'];
    $direccion_madre = $_POST['direccion'];
    $oficio_madre = $_POST['oficio'];
    $id_estudiante = $_POST['id_estudiante'];

    // Recoger los datos del formulario del padre
    $nombre_padre = $_POST['nombre_padre'];
    $apellido_padre = $_POST['apellido_padre'];
    $cedula_padre = $_POST['cedula_padre'];
    $telefono_local_padre = $_POST['telefono_local'];
    $telefono_padre = $_POST['telefono_padre'];
    $direccion_padre = $_POST['direccion'];
    $oficio_padre = $_POST['oficio'];

    // Recoger los datos del formulario del representante legal
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre_representante'];
    $apellido = $_POST['apellido_representante'];
    $telefono_local = $_POST['telefono_local'];
    $telefono_celular = $_POST['telefono_celular'];
    $parentesco = $_POST['parentesco'];
    $carnet_patria = $_POST['carnet_patria'];
    $codigo = $_POST['codigo'];
    $serial = $_POST['serial'];

// Insertar datos en la tabla madre
$sql_madre = "INSERT INTO madre (
    nombre_madre, 
    apellido_madre, 
    cedula_madre, 
    telefono_local_madre, 
    telefono_celular_madre, 
    direccion_madre, 
    oficio_madre, 
    id_estudiante
) VALUES (
    '$nombre_madre', 
    '$apellido_madre', 
    '$cedula_madre', 
    '$telefono_local_madre', 
    '$telefono_madre', 
    '$direccion_madre', 
    '$oficio_madre', 
    '$id_estudiante'
)";
// Insertar datos en la tabla padre
$sql_padre = "INSERT INTO padre (
    nombre_padre, 
    apellido_padre, 
    cedula_padre, 
    telefono_local_padre, 
    telefono_celular_padre, 
    direccion_padre, 
    oficio_padre, 
    id_estudiante
) VALUES (
    '$nombre_padre', 
    '$apellido_padre', 
    '$cedula_padre', 
    '$telefono_local_padre', 
    '$telefono_padre', 
    '$direccion_padre', 
    '$oficio_padre', 
    '$id_estudiante'
)";
// Insertar datos en la tabla representante_legal
$sql_representante = "INSERT INTO representante_legal (
    cedula_representante, 
    nombre_representante,
    apellido_representante,
    telefono_local_representante, 
    telefono_celular_representante, 
    parentesco_representante, 
    carnet_patria_representante, 
    codigo_representante, 
    serial_representante, 
    id_estudiante
) VALUES (
    '$cedula', 
    '$nombre',
    '$apellido',
    '$telefono_local', 
    '$telefono_celular', 
    '$parentesco', 
    '$carnet_patria', 
    '$codigo', 
    '$serial', 
    '$id_estudiante'
)";

    // Ejecutar las consultas
    if ($conn->query($sql_madre) && $conn->query($sql_padre) && $conn->query($sql_representante)) {
        echo '<div class="alert alert-success">Datos guardados con éxito.</div>';
    } else {
        echo '<div class="alert alert-danger">Error al guardar los datos: ' . $e->getMessage() . '</div>';
    }
}
?>