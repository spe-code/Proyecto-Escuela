<?php
// Incluir las bibliotecas FPDF y FPDI
require('../libs/fpdf/fpdf.php');
require('../libs/fpdi/src/autoload.php');

use setasign\Fpdi\Fpdi;

// Conectar a la base de datos
include '../includes/conexion.php';

// Obtener el ID del estudiante desde la URL
$id_estudiante = $_GET['id'];

// Consulta para obtener los datos del estudiante
$sql = "SELECT 
            e.cedula_estudiante, 
            e.nombre_estudiante, 
            e.apellido_estudiante, 
			e.sexo_estudiante,
			e.fecha_nacimiento_estudiante,
			e.lugar_nacimiento_estudiante,
			e.direccion_estudiante,
			e.telefono_local_estudiante,
			e.telefono_celular_estudiante,
			e.año_cursar_estudiante,
			e.correo_estudiante,
			e.seccion_estudiante,
			e.tipo_estudiante,
			e.padece_enfermedad_estudiante,
			e.es_alergico_estudiante,
            m.nombre_madre, 
            m.apellido_madre, 
            p.nombre_padre, 
            p.apellido_padre, 
            r.nombre_representante, 
            r.apellido_representante
        FROM estudiantes e
        LEFT JOIN madre m ON e.id_estudiante = m.id_estudiante
        LEFT JOIN padre p ON e.id_estudiante = p.id_estudiante
        LEFT JOIN representante_legal r ON e.id_estudiante = r.id_estudiante
        WHERE e.id_estudiante = :id_estudiante";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_estudiante', $id_estudiante);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// Crear una nueva instancia de FPDI
$pdf = new Fpdi();

// Cargar la plantilla PDF
$pageCount = $pdf->setSourceFile('../plantillas/PLANILLA_DE_INSCRIPCIÓN.pdf'); // Ruta a tu plantilla
$templateId = $pdf->importPage(1); // Importar la primera página
$pdf->AddPage();
$pdf->useTemplate($templateId);

// Establecer la fuente
$pdf->SetFont('Arial', '');

// Rellenar los campos con los datos
// $pdf->SetXY(50, 50); // Cambia las coordenadas según la posición en tu plantilla
// $pdf->Write(0, $data['cedula_estudiante']);

$pdf->SetXY(35, 69); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['apellido_estudiante']);

$pdf->SetXY(133, 69); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['nombre_estudiante']);

// Marcar "X" según el sexo del estudiante
$pdf->SetXY(43, 76); // Coordenadas para el campo masculino
if ($data['sexo_estudiante'] === 'M') {
    $pdf->Write(0, 'X'); // Marcar con "X" si es masculino
} else {
    $pdf->Write(0, ''); // Dejar vacío si no es masculino
}

$pdf->SetXY(32.8, 76); // Coordenadas para el campo femenino
if ($data['sexo_estudiante'] === 'F') {
    $pdf->Write(0, 'X'); // Marcar con "X" si es femenino
} else {
    $pdf->Write(0, ''); // Dejar vacío si no es femenino
}


//NACIONALIDAD DEL ESTUDIANTE

//cedula

$pdf->SetXY(140, 75); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['cedula_estudiante']);


$pdf->SetXY(55, 81.5); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['fecha_nacimiento_estudiante']);

$pdf->SetXY(140,81.5 ); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['lugar_nacimiento_estudiante']);

$pdf->SetXY(36, 87.5); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['direccion_estudiante']);

$pdf->SetXY(36, 93.5); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['telefono_local_estudiante']);

$pdf->SetXY(90, 93.5); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['telefono_celular_estudiante']);


$pdf->SetXY(146,93 ); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['correo_estudiante']);


$pdf->SetXY(42, 99.5); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['año_cursar_estudiante']);


$pdf->SetXY(88, 99.5); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['seccion_estudiante']);

// Marcar "X" según el sexo del estudiante
$pdf->SetXY(140.4, 99.4); // Coordenadas para el campo 
if ($data['tipo_estudiante'] === 'regular') {
    $pdf->Write(0, 'X'); // Marcar con "X" si es 
} else {
    $pdf->Write(0, ''); // Dejar vacío si no es 
}

$pdf->SetXY(175, 99.4); // Coordenadas para el campo 
if ($data['tipo_estudiante'] === 'repitiente') {
    $pdf->Write(0, 'X'); // Marcar con "X" si es 
} else {
    $pdf->Write(0, ''); // Dejar vacío si no es 
}


// Marcar "X" si padece alguna enfermedad
$pdf->SetXY(78, 105); // Cambia las coordenadas según la posición en tu plantilla
if (!empty($data['padece_enfermedad_estudiante'])) {
    $pdf->Write(0, 'X'); // Marcar con "X" si padece alguna enfermedad
} else {
    $pdf->Write(0, ''); // Dejar vacío si no padece ninguna enfermedad
}

$pdf->SetXY(128, 105); // Cambia las coordenadas según la posición en tu plantilla
if (!empty($data['padece_enfermedad_estudiante'])) {
    $pdf->Write(0, $data['padece_enfermedad_estudiante']); // Marcar con "X" si padece alguna enfermedad
} else {
    $pdf->Write(0, ''); // Dejar vacío si no padece ninguna enfermedad
}


// Marcar "X" si no padece alguna enfermedad
$pdf->SetXY(101, 105); // Cambia las coordenadas según la posición en tu plantilla
if (empty($data['padece_enfermedad_estudiante'])) {
    $pdf->Write(0, 'X'); // Marcar con "X" si padece alguna enfermedad
} else {
    $pdf->Write(0, ''); // Dejar vacío si no padece ninguna enfermedad
}


//alergico

// Marcar "X" si padece alguna enfermedad
$pdf->SetXY(78, 111); // Cambia las coordenadas según la posición en tu plantilla
if (!empty($data['es_alergico_estudiante'])) {
    $pdf->Write(0, 'X'); // Marcar con "X" si padece alguna enfermedad
} else {
    $pdf->Write(0, ''); // Dejar vacío si no padece ninguna enfermedad
}

$pdf->SetXY(128, 111); // Cambia las coordenadas según la posición en tu plantilla
if (!empty($data['es_alergico_estudiante'])) {
    $pdf->Write(0, $data['padece_enfermedad_estudiante']); // Marcar con "X" si padece alguna enfermedad
} else {
    $pdf->Write(0, ''); // Dejar vacío si no padece ninguna enfermedad
}


// Marcar "X" si no padece alguna enfermedad
$pdf->SetXY(101, 111); // Cambia las coordenadas según la posición en tu plantilla
if (empty($data['es_alergico_estudiante'])) {
    $pdf->Write(0, 'X'); // Marcar con "X" si padece alguna enfermedad
} else {
    $pdf->Write(0, ''); // Dejar vacío si no padece ninguna enfermedad
}



// Salida del PDF
$pdf->Output('D', 'planilla_inscripcion.pdf');
?>