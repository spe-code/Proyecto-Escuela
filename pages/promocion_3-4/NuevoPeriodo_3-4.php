<?php
include '../../includes/conexion.php';

// Obtener el período escolar actual (formato: YYYY-YYYY)
$añoActual = date('Y');
$periodoActual = ($añoActual-1).'-'.$añoActual;
$periodoSiguiente = $añoActual.'-'.($añoActual+1);

// Consulta para obtener los estudiantes de primer año del período actual
$sql = "SELECT 
            e.id_estudiante, 
            e.cedula_estudiante, 
            e.nombre_estudiante, 
            e.apellido_estudiante, 
            e.seccion_estudiante, 
            e.tipo_estudiante,
            e.periodo_escolar,
            r.nombre_representante, 
            r.apellido_representante
        FROM estudiantes e
        LEFT JOIN representante_legal r ON e.id_estudiante = r.id_estudiante
        WHERE e.año_cursar_estudiante = 3 
        AND e.periodo_escolar = :periodo_actual";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':periodo_actual', $periodoActual);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promoción de Estudiantes - 3ro a 4to Año</title>
    <link rel="stylesheet" href="../../assets/css/consultas_estudiante.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <!-- Navbar -->
    <!-- Navbar (igual que antes) -->
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-custom fixed-top" style="background-color:rgb(39, 99, 173);">
        <div class="container-fluid">
            <!-- Logo o nombre de la aplicación -->
            <a class="navbar-brand" style="color: white;" href="#">
             <img src="../../assets/img/logotipo-liceo.png" alt="Logo del Liceo" class="d-inline-block align-text-top ms-2" style="height: 30px;">
            <i class=""></i> Liceo B. Albertina Escalona de Suros
            </a>

            <!-- Botón para colapsar el navbar en móviles -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Contenido del navbar -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Opción: Inicio -->
                    <li class="nav-item">
                        <a class="nav-link active" style="color: white;" href="../index.php">
                            <i class="fas fa-home"></i> Inicio
                        </a>
                    </li>

                    <!-- Opción: Inscribir Estudiantes -->
                    <li class="nav-item">
                        <a class="nav-link" style="color: white;" href="../inscripcion.php">
                            <i class="fas fa-user-plus"></i> Inscribir Estudiantes
                        </a>
                    </li>

                    <!-- Opción: Consultar Datos (con menú desplegable) -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" style="color: white;" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-search"></i> Consultar Datos
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="../1ro/consultar_estudiantes_1ro.php" target="_blank">1er AÑO</a></li>
                            <li><a class="dropdown-item" href="../2do/consultar_estudiantes_2do.php" target="_blank">2do AÑO</a></li>
                            <li><a class="dropdown-item" href="../3ro/consultar_estudiantes_3ro.php" target="_blank">3er AÑO</a></li>
                            <li><a class="dropdown-item" href="../4to/consultar_estudiantes_4to.php" target="_blank">4to AÑO</a></li>
                            <li><a class="dropdown-item" href="../5to/consultar_estudiantes_5to.php" target="_blank">5to AÑO</a></li>
                            </ul>
                        </li>


                           <!-- Opción: Nuevo Año Escolar (menú desplegable) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" style="color: white;" href="#" id="navbarDropdownNuevoPeriodo" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-graduate"></i> Nuevo Año Escolar
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownNuevoPeriodo">
                        <li><a class="dropdown-item" href="../promocion_1-2/NuevoPeriodo_1-2.php">1er AÑO</a></li>
                        <li><a class="dropdown-item" href="../promocion_2-3/NuevoPeriodo_2-3.php">2do AÑO</a></li>
                        <li><a class="dropdown-item" href="../promocion_3-4/NuevoPeriodo_3-4.php">3er AÑO</a></li>
                        <li><a class="dropdown-item" href="../promocion_4-5/NuevoPeriodo_4-5.php">4to AÑO</a></li>
                        <li><a class="dropdown-item" href="../promocion_5-Egreso/NuevoPeriodo_5-Egreso.php">5to AÑO</a></li>
                    </ul>
                </li>
                    </ul>

                </div>
            </div>
         </nav> 

<div class="container mt-5">
    <h1 class="text-center mb-4">Promoción de Estudiantes - 3ro a 4to Año</h1>
    
    <!-- Filtros y selección de período -->
    <div class="row mb-4">
        <div class="col-md-4">
            <input type="text" id="filtroNombre" class="form-control" placeholder="Filtrar por nombre">
        </div>
        <div class="col-md-4">
            <input type="text" id="filtroCedula" class="form-control" placeholder="Filtrar por cédula">
        </div>
        <div class="col-md-2">
            <select id="filtroSeccion" class="form-select">
                <option value="">Todas las secciones</option>
                <option value="A">Sección A</option>
                <option value="B">Sección B</option>
                <option value="C">Sección C</option>
                <option value="D">Sección D</option>
            </select>
        </div>
        <div class="col-md-2">
            <select id="periodo_escolar" class="form-select">
                <option value="<?= $periodoActual ?>"><?= $periodoActual ?></option>
                <option value="<?= $periodoSiguiente ?>"><?= $periodoSiguiente ?></option>
            </select>
        </div>
    </div>

    <!-- Botones de acción -->
    <div class="mb-3">
        <button id="seleccionarTodos" class="btn btn-primary btn-sm">Seleccionar Todos</button>
        <button id="deseleccionarTodos" class="btn btn-secondary btn-sm">Deseleccionar Todos</button>
        <button id="promoverSeleccionados" class="btn btn-success btn-sm">Promover Seleccionados</button>
    </div>

    <!-- Tabla de estudiantes -->
    <form id="formPromocion">
        <table class="table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkTodos"></th>
                    <th>Cédula</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Sección</th>
                    <th>Tipo</th>
                    <th>Período</th>
                    <th>Nombre del Representante</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaEstudiantes">
                <?php if (count($rows) > 0): ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><input type="checkbox" name="estudiantes[]" value="<?= $row['id_estudiante'] ?>"></td>
                            <td><?= $row['cedula_estudiante'] ?></td>
                            <td><?= $row['nombre_estudiante'] ?></td>
                            <td><?= $row['apellido_estudiante'] ?></td>
                            <td><?= $row['seccion_estudiante'] ?></td>
                            <td><?= $row['tipo_estudiante'] == 'repitiente' ? 'Repitiente' : 'Regular' ?></td>
                            <td><?= $row['periodo_escolar'] ?></td>
                            <td><?= $row['nombre_representante'] ?> <?= $row['apellido_representante'] ?></td>
                            <td>
                                <a href="../generar_pdf.php?id=<?= $row['id_estudiante'] ?>" class="btn btn-success btn-sm">
                                    <i class="fas fa-file-pdf"></i> Descargar PDF
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="9" class="text-center">No hay estudiantes de tercer año disponibles para promoción en el período <?= $periodoActual ?>.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Función para filtrar la tabla
    function filtrarTabla() {
        var nombre = $('#filtroNombre').val().toLowerCase();
        var cedula = $('#filtroCedula').val().toLowerCase();
        var seccion = $('#filtroSeccion').val().toLowerCase();
        var periodo = $('#periodo_escolar').val();

        $('#tablaEstudiantes tr').each(function() {
            var filaNombre = $(this).find('td:eq(2)').text().toLowerCase();
            var filaCedula = $(this).find('td:eq(1)').text().toLowerCase();
            var filaSeccion = $(this).find('td:eq(4)').text().toLowerCase();
            var filaPeriodo = $(this).find('td:eq(6)').text();

            var coincideNombre = filaNombre.includes(nombre);
            var coincideCedula = filaCedula.includes(cedula);
            var coincideSeccion = (seccion === '' || filaSeccion === seccion);
            var coincidePeriodo = (periodo === '' || filaPeriodo === periodo);

            if (coincideNombre && coincideCedula && coincideSeccion && coincidePeriodo) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    // Aplicar el filtro cuando cambien los filtros
    $('#filtroNombre, #filtroCedula, #filtroSeccion, #periodo_escolar').on('input change', function() {
        filtrarTabla();
    });

    // Seleccionar/deseleccionar todos
    $('#seleccionarTodos').click(function(e) {
        e.preventDefault();
        $('input[name="estudiantes[]"]').prop('checked', true);
    });

    $('#deseleccionarTodos').click(function(e) {
        e.preventDefault();
        $('input[name="estudiantes[]"]').prop('checked', false);
    });

    $('#checkTodos').click(function() {
        $('input[name="estudiantes[]"]').prop('checked', $(this).prop('checked'));
    });

    // Promover estudiantes seleccionados - CÓDIGO AJAX CORREGIDO
    $('#promoverSeleccionados').click(function(e) {
        e.preventDefault();
        
        var seleccionados = $('input[name="estudiantes[]"]:checked').length;
        
        if (seleccionados === 0) {
            Swal.fire({
                title: 'Error',
                text: 'Debe seleccionar al menos un estudiante para promover',
                icon: 'error',
                confirmButtonText: 'Entendido'
            });
            return;
        }

        Swal.fire({
            title: '¿Está seguro?',
            text: `Está a punto de promover ${seleccionados} estudiante(s) a 4to año. ¿Desea continuar?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, promover',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                var estudiantes = [];
                $('input[name="estudiantes[]"]:checked').each(function() {
                    estudiantes.push($(this).val());
                });

                $.ajax({
                    url: 'procesar_promocion.php',
                    type: 'POST',
                    dataType: 'json', // Especificamos que esperamos JSON
                    data: { 
                        estudiantes: estudiantes,
                        nuevo_periodo: $('#periodo_escolar').val() // Enviamos el período seleccionado
                    },
                    success: function(resultado) {
                        if (resultado.success) {
                            Swal.fire({
                                title: 'Éxito',
                                text: resultado.message,
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: resultado.message || resultado.error,
                                icon: 'error',
                                confirmButtonText: 'Entendido'
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Error en AJAX:", textStatus, errorThrown);
                        console.log("Respuesta completa:", jqXHR.responseText);
                        
                        Swal.fire({
                            title: 'Error en el servidor',
                            text: 'Ocurrió un error al procesar la solicitud. Por favor verifica la consola para más detalles.',
                            icon: 'error',
                            confirmButtonText: 'Entendido'
                        });
                    }
                });
            }
        });
    });
});
</script>
</body>
</html>