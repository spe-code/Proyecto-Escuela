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
            e.nacionalidad_estudiante,
            m.nombre_madre, 
            m.apellido_madre,
            m.cedula_madre,
            m.nacionalidad_madre,
            m.oficio_madre, 
            m.telefono_local_madre,
            m.telefono_celular_madre,
            m.direccion_madre,
            p.cedula_padre,
            p.nacionalidad_padre,
            p.oficio_padre,
            p.telefono_local_padre,
            p.telefono_celular_padre,
            p.direccion_padre,
            p.nombre_padre, 
            p.apellido_padre, 
            r.nombre_representante, 
            r.apellido_representante,
            r.cedula_representante,
            r.nacionalidad_representante,
            r.telefono_local_representante,
            r.telefono_celular_representante,
            r.parentesco_representante,
            r.direccion_representante,
            r.codigo_representante,
            r.serial_representante
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
$pdf->SetXY(79.6, 76); // Coordenadas para el campo femenino
if ($data['nacionalidad_estudiante'] === 'Venezolana') {
    $pdf->Write(0, 'X'); // Marcar con "X" si es Venezonala
} else {
    $pdf->Write(0, ''); // Dejar vacío si no es Extranjera
}


$pdf->SetXY(89.3, 76); // Coordenadas para el campo femenino
if ($data['nacionalidad_estudiante'] === 'Extranjera') {
    $pdf->Write(0, 'X'); // Marcar con "X" si es Venezonala
} else {
    $pdf->Write(0, ''); // Dejar vacío si no es Extranjera
}



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

$pdf->SetXY(91, 93.5); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['telefono_celular_estudiante']);


$pdf->SetXY(146,93 ); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['correo_estudiante']);


$pdf->SetXY(42, 99.5); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['año_cursar_estudiante']);


$pdf->SetXY(88, 99.5); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['seccion_estudiante']);

// Marcar "X" según el sexo del estudiante
$pdf->SetXY(141, 100); // Coordenadas para el campo 
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

// Marcar "X" si padece alguna enfermedad
$pdf->SetXY(78, 111); // Cambia las coordenadas según la posición en tu plantilla
if (!empty($data['es_alergico_estudiante'])) {
    $pdf->Write(0, 'X'); // Marcar con "X" si padece alguna enfermedad
} else {
    $pdf->Write(0, ''); // Dejar vacío si no padece ninguna enfermedad
}

$pdf->SetXY(128, 111); // Cambia las coordenadas según la posición en tu plantilla
if (!empty($data['es_alergico_estudiante'])) {
    $pdf->Write(0, $data['padece_enfermedad_estudiante']); // Marcar con "X" si es Alergico
} else {
    $pdf->Write(0, ''); // Dejar vacío si es Alergico
}


// Marcar "X" si no padece alguna enfermedad
$pdf->SetXY(101, 111); // Cambia las coordenadas según la posición en tu plantilla
if (empty($data['es_alergico_estudiante'])) {
    $pdf->Write(0, 'X'); // Marcar con "X" si es Alergico
} else {
    $pdf->Write(0, ''); // Dejar vacío si es Alergico
}

// Nombre de la madre
$pdf->SetXY(78, 128); // Cambia las coordenadas según la posición en tu plantilla     
$pdf->Write(0, $data['nombre_madre']);

// Apellido de la madre
$pdf->SetXY(108, 128); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['apellido_madre']);

// Cédula de la madre
$pdf->SetXY(47, 133.8); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['cedula_madre']);

//NACIONALIDAD DEL MADRE
$pdf->SetXY(29.5, 134.5); // Coordenadas para el campo femenino
if ($data['nacionalidad_madre'] === 'Venezolana') {
    $pdf->Write(0, 'X'); // Marcar con "X" si es Venezonala
} else {
    $pdf->Write(0, ''); // Dejar vacío si no es Extranjera
}


$pdf->SetXY(38, 134.5); // Coordenadas para el campo femenino
if ($data['nacionalidad_madre'] === 'Extranjera') {
    $pdf->Write(0, 'X'); // Marcar con "X" si es Venezonala
} else {
    $pdf->Write(0, ''); // Dejar vacío si no es Extranjera
}

// Oficio de la madre
$pdf->SetXY(35, 146); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['oficio_madre']);

// Teléfono local de la madre
$pdf->SetXY(111, 133.5); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['telefono_local_madre']);

// Teléfono celular de la madre
$pdf->SetXY(170, 133.5); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['telefono_celular_madre']);

// Dirección de la madre
$pdf->SetXY(35, 140); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['direccion_madre']);

// Nombre del padre
$pdf->SetXY(77, 156); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['nombre_padre']);

// Apellido del padre
$pdf->SetXY(107, 156); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['apellido_padre']);

// Cédula del padre
$pdf->SetXY(47, 161); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['cedula_padre']);

//NACIONALIDAD DEL PADRE
$pdf->SetXY(29.5, 162); 
if ($data['nacionalidad_padre'] === 'Venezolana') {
    $pdf->Write(0, 'X'); // Marcar con "X" si es Venezonala
} else {
    $pdf->Write(0, ''); // Dejar vacío si no es Extranjera
}


$pdf->SetXY(38, 162); 
if ($data['nacionalidad_padre'] === 'Extranjera') {
    $pdf->Write(0, 'X'); // Marcar con "X" si es Venezonala
} else {
    $pdf->Write(0, ''); // Dejar vacío si no es Extranjera
}

//Oficio del padre
$pdf->SetXY(35, 173.5); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['oficio_padre']);

// Teléfono local del padre
$pdf->SetXY(111, 161.5); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['telefono_local_padre']);

// Teléfono celular del padre
$pdf->SetXY(169.5, 161.5); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['telefono_celular_padre']);

// Dirección del padre
$pdf->SetXY(35, 167); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['direccion_padre']);

// Nombre del representante
$pdf->SetXY(53, 183); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['nombre_representante']);

// Apellido del representante
$pdf->SetXY(77, 183); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['apellido_representante']);

// Cédula del representante
$pdf->SetXY(47, 189); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['cedula_representante']);

//NACIONALIDAD DEL REPRESENTANTE
$pdf->SetXY(29.5, 188);
if ($data['nacionalidad_representante'] === 'Venezolana') {
    $pdf->Write(0, 'X'); // Marcar con "X" si es Venezonala
} else {
    $pdf->Write(0, ''); // Dejar vacío si no es Extranjera
}

$pdf->SetXY(38, 188);
if ($data['nacionalidad_representante'] === 'Extranjera') {
    $pdf->Write(0, 'X'); // Marcar con "X" si es Venezonala
} else {
    $pdf->Write(0, ''); // Dejar vacío si no es Extranjera
}

// Teléfono local del representante
$pdf->SetXY(111, 188); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['telefono_local_representante']);

// Teléfono celular del representante
$pdf->SetXY(169.5, 188); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['telefono_celular_representante']);

// Parentesco del representante
$pdf->SetXY(38, 200); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['parentesco_representante']);

// Carnet de la patria del representante
//$pdf->SetXY(111, 198.5); // Cambia las coordenadas según la posición en tu plantilla
//$pdf->Write(0, $data['carnet_patria_representante']);

// Código del representante
$pdf->SetXY(132, 200); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['codigo_representante']);

// Serial del representante
$pdf->SetXY(176, 200); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['serial_representante']);

//Direccion del representante
$pdf->SetXY(38, 195); // Cambia las coordenadas según la posición en tu plantilla
$pdf->Write(0, $data['direccion_representante']);


$nombreArchivo = 'Planilla_Inscripcion_' . 
                 $data['cedula_estudiante'] . '_' . 
                 $data['nombre_estudiante'] . '_' . 
                 $data['apellido_estudiante'] . '_' . 
                 $data['año_cursar_estudiante'] . '_' . 
                 $data['seccion_estudiante'] . '.pdf';

// Reemplazar espacios y caracteres especiales en el nombre del archivo
$nombreArchivo = preg_replace('/[^A-Za-z0-9_.-]/', '_', $nombreArchivo);

// Salida del PDF con el nombre personalizado
$pdf->Output('D', $nombreArchivo);
?>