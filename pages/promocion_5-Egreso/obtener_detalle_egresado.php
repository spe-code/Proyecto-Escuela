<?php
require_once '../../includes/conexion.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID de egresado inválido');
}

$idEgresado = $_GET['id'];

$sql = "SELECT * FROM egresados WHERE id_estudiante = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$idEgresado]);
$egresado = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$egresado) {
    die('Egresado no encontrado');
}
?>

<div class="row">
    <div class="col-md-6">
        <h4>Información Personal</h4>
        <ul class="list-group list-group-flush mb-4">
            <li class="list-group-item">
                <strong>ID:</strong> <?= $egresado['id_estudiante'] ?>
            </li>
            <li class="list-group-item">
                <strong>Nombre completo:</strong> 
                <?= htmlspecialchars($egresado['nombre_estudiante'] . ' ' . $egresado['apellido_estudiante']) ?>
            </li>
            <li class="list-group-item">
                <strong>Fecha de nacimiento:</strong> 
                <?= date('d/m/Y', strtotime($egresado['fecha_nacimiento_estudiante'])) ?>
            </li>
            <li class="list-group-item">
                <strong>Sexo:</strong> <?= $egresado['sexo_estudiante'] ?>
            </li>
        </ul>
    </div>
    
    <div class="col-md-6">
        <h4>Información Académica</h4>
        <ul class="list-group list-group-flush mb-4">
            <li class="list-group-item">
                <strong>Año de egreso:</strong> <?= $egresado['año_egreso'] ?>
            </li>
            <li class="list-group-item">
                <strong>Periodo escolar:</strong> <?= htmlspecialchars($egresado['periodo_escolar']) ?>
            </li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <h4>Información de Contacto</h4>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <strong>Dirección:</strong> <?= htmlspecialchars($egresado['direccion_estudiante']) ?>
            </li>
            <li class="list-group-item">
                <strong>Teléfono:</strong> <?= htmlspecialchars($egresado['telefono_estudiante'] ?? 'No registrado') ?>
            </li>
            <li class="list-group-item">
                <strong>Correo electrónico:</strong> 
                <?= !empty($egresado['correo_estudiante']) ? 
                   '<a href="mailto:'.$egresado['correo_estudiante'].'">'.$egresado['correo_estudiante'].'</a>' : 
                   'No registrado' ?>
            </li>
        </ul>
    </div>
</div>