<?php
include '../../includes/conexion.php'; // Incluye la conexión a la base de datos

// Consulta para obtener los estudiantes de primer año
$sql = "SELECT 
            e.id_estudiante, 
            e.cedula_estudiante, 
            e.nombre_estudiante, 
            e.apellido_estudiante, 
            e.seccion_estudiante, 
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
        WHERE e.año_cursar_estudiante = 4";

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($sql);
$stmt->execute();

// Obtener los resultados
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Datos de Estudiantes de 4to Año</title>
    <link rel="stylesheet" href="../../assets/css/consultas_estudiante.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery (necesario para Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <link rel="stylesheet" href="../../assets/css/navbar-menu.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top" style="background-color:rgb(39, 99, 173);">
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
                            <li><a class="dropdown-item" href="../2do/consultar_estudiantes_2do.php">2do AÑO</a></li>
                            <li><a class="dropdown-item" href="../3ro/consultar_estudiantes_3ro.php" target="_blank">3er AÑO</a></li>
                            <li><a class="dropdown-item" href="../4to/consultar_estudiantes_4to.php" target="_blank">4to AÑO</a></li>
                            <li><a class="dropdown-item" href="../5to/consultar_estudiantes_5to.php" target="_blank">5to AÑO</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


<div class="container mt-5">
    <h1 class="text-center mb-4">Consultar Datos de Estudiantes de 4to Año</h1>
    
    <!-- Campos de filtro -->
    <div class="filtros">
        <div class="row">
            <div class="col-md-4">
                <input type="text" id="filtroNombre" class="form-control" placeholder="Filtrar por nombre">
            </div>
            <div class="col-md-4">
                <input type="text" id="filtroCedula" class="form-control" placeholder="Filtrar por cédula">
            </div>
            <div class="col-md-4">
                <select id="filtroSeccion" class="form-select">
                    <option value="">Todas las secciones</option>
                    <option value="A">Sección A</option>
                    <option value="B">Sección B</option>
                    <option value="C">Sección C</option>
                    <option value="D">Sección D</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tabla de estudiantes -->
    <table class="table">
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Sección</th>
                <th>Nombre del Representante</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaEstudiantes">
            <?php
            if (count($rows) > 0) {
                foreach ($rows as $row) {
                    echo "<tr>
                            <td>{$row['cedula_estudiante']}</td>
                            <td>{$row['nombre_estudiante']}</td>
                            <td>{$row['apellido_estudiante']}</td>
                            <td>{$row['seccion_estudiante']}</td>
                            <td>{$row['nombre_representante']} {$row['apellido_representante']}</td>
                            <td>
                                <button class='btn btn-info btn-sm btn-consultar' data-id='{$row['id_estudiante']}' data-bs-toggle='modal' data-bs-target='#modalDetalles'>
                                    <i class='fas fa-eye'></i> Consultar
                                </button>
                                <button class='btn btn-warning btn-sm btn-editar' data-id='{$row['id_estudiante']}' data-bs-toggle='modal' data-bs-target='#modalEditar'>
                                    <i class='fas fa-edit'></i> Editar
                                </button>
                                <button class='btn btn-danger btn-sm btn-eliminar' data-id='{$row['id_estudiante']}'>
                                    <i class='fas fa-trash'></i> Eliminar
                                </button>
                                 <a href='../generar_pdf.php?id={$row['id_estudiante']}' class='btn btn-success btn-sm'>
        <i class='fas fa-file-pdf'></i> Descargar PDF
    </a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No hay estudiantes de primer año registrados.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
    <!-- Modal para mostrar los detalles -->
    <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesLabel">Detalles del Estudiante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalDetallesBody">
                    <!-- Aquí se cargarán los detalles dinámicamente -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar los datos -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarLabel">Editar Datos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditar">
                                 <!-- Estudiante -->
                                 <div class="card mb-4">
    <div class="card-body">
        <h6 class="card-subtitle mb-3 text-muted">Datos del Estudiante</h6>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nombreEstudiante" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombreEstudiante" name="nombreEstudiante" required>
                </div>
                <div class="mb-3">
                    <label for="apellidoEstudiante" class="form-label">Apellido</label>
                    <input type="text" class="form-control" id="apellidoEstudiante" name="apellidoEstudiante" required>
                </div>
                <div class="mb-3">
                    <label for="cedulaEstudiante" class="form-label">Cédula</label>
                    <input type="text" class="form-control" id="cedulaEstudiante" name="cedulaEstudiante" required>
                </div>
                
                <div class="form-floating mb-3">
                    <select class="form-select" id="nacionalidad_estudiante" name="nacionalidad_estudiante" required>
                    <option value="">Seleccione una opción</option>
                    <option value="Venezolana">Venezolana</option>
                    <option value="Extranjera">Extranjera</option>
                    </select>
                    <label for="nacionalidadestudiante">Nacionalidad</label>
                    <div class="invalid-feedback">Por favor, ingrese una Nacionalidad.</div>
                </div>

                <div class="mb-3">
                    <label for="sexoEstudiante" class="form-label">Sexo</label>
                    <select class="form-select" id="sexoEstudiante" name="sexoEstudiante" required>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fechaNacimientoEstudiante" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fechaNacimientoEstudiante" name="fechaNacimientoEstudiante" required>
                </div>
                <div class="mb-3">
                    <label for="lugarNacimientoEstudiante" class="form-label">Lugar de Nacimiento</label>
                    <input type="text" class="form-control" id="lugarNacimientoEstudiante" name="lugarNacimientoEstudiante" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="direccionEstudiante" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccionEstudiante" name="direccionEstudiante" required>
                </div>
                <div class="mb-3">
                    <label for="telefonoLocalEstudiante" class="form-label">Teléfono Local</label>
                    <input type="text" class="form-control" id="telefonoLocalEstudiante" name="telefonoLocalEstudiante">
                </div>
                <div class="mb-3">
                    <label for="telefonoCelularEstudiante" class="form-label">Teléfono Celular</label>
                    <input type="text" class="form-control" id="telefonoCelularEstudiante" name="telefonoCelularEstudiante" required>
                </div>
                <div class="mb-3">
                    <label for="correoEstudiante" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correoEstudiante" name="correoEstudiante">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="añoCursarEstudiante" class="form-label">Año que Cursa</label>
                            <input type="number" class="form-control" id="añoCursarEstudiante" name="añoCursarEstudiante" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="seccionEstudiante" class="form-label">Sección</label>
                            <select class="form-select" id="seccionEstudiante" name="seccionEstudiante" required>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="tipoEstudiante" class="form-label">Tipo de Estudiante</label>
                    <select class="form-select" id="tipoEstudiante" name="tipoEstudiante" required>
                        <option value="regular">Regular</option>
                        <option value="repitiente">Repitiente</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="padeceEnfermedad" class="form-label">Enfermedades que Padece</label>
                    <textarea class="form-control" id="padeceEnfermedad" name="padeceEnfermedad" rows="2"></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="esAlergico" class="form-label">Alergias</label>
                    <textarea class="form-control" id="esAlergico" name="esAlergico" rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

                        <!-- Madre -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-3 text-muted">Datos de la Madre</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nombreMadre" class="form-label">Nombre</label>
                                            <input type="text" class="form-control" id="nombreMadre" name="nombreMadre" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="apellidoMadre" class="form-label">Apellido</label>
                                            <input type="text" class="form-control" id="apellidoMadre" name="apellidoMadre" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="cedulaMadre" class="form-label">Cédula</label>
                                            <input type="text" class="form-control" id="cedulaMadre" name="cedulaMadre" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="telefonoLocalMadre" class="form-label">Teléfono Local</label>
                                            <input type="text" class="form-control" id="telefonoLocalMadre" name="telefonoLocalMadre" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="telefonoCelularMadre" class="form-label">Teléfono Celular</label>
                                            <input type="text" class="form-control" id="telefonoCelularMadre" name="telefonoCelularMadre" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="direccionMadre" class="form-label">Dirección</label>
                                            <input type="text" class="form-control" id="direccionMadre" name="direccionMadre" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Padre -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-3 text-muted">Datos del Padre</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nombrePadre" class="form-label">Nombre</label>
                                            <input type="text" class="form-control" id="nombrePadre" name="nombrePadre" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="apellidoPadre" class="form-label">Apellido</label>
                                            <input type="text" class="form-control" id="apellidoPadre" name="apellidoPadre" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="cedulaPadre" class="form-label">Cédula</label>
                                            <input type="text" class="form-control" id="cedulaPadre" name="cedulaPadre" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="telefonoLocalPadre" class="form-label">Teléfono Local</label>
                                            <input type="text" class="form-control" id="telefonoLocalPadre" name="telefonoLocalPadre" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="telefonoCelularPadre" class="form-label">Teléfono Celular</label>
                                            <input type="text" class="form-control" id="telefonoCelularPadre" name="telefonoCelularPadre" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="direccionPadre" class="form-label">Dirección</label>
                                            <input type="text" class="form-control" id="direccionPadre" name="direccionPadre" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Representante Legal -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-3 text-muted">Datos del Representante Legal</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nombreRepresentante" class="form-label">Nombre</label>
                                            <input type="text" class="form-control" id="nombreRepresentante" name="nombreRepresentante" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="apellidoRepresentante" class="form-label">Apellido</label>
                                            <input type="text" class="form-control" id="apellidoRepresentante" name="apellidoRepresentante" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="cedulaRepresentante" class="form-label">Cédula</label>
                                            <input type="text" class="form-control" id="cedulaRepresentante" name="cedulaRepresentante" required>
                                        </div>

                                        <div class="mb-3">
                                        <label for="serial">Serial del Carnet de la Patria</label>
                                            <input type="text" class="form-control" id="serial" name="serial" placeholder="Serial">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="telefonoLocalRepresentante" class="form-label">Teléfono Local</label>
                                            <input type="text" class="form-control" id="telefonoLocalRepresentante" name="telefonoLocalRepresentante" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="telefonoCelularRepresentante" class="form-label">Teléfono Celular</label>
                                            <input type="text" class="form-control" id="telefonoCelularRepresentante" name="telefonoCelularRepresentante" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="parentescoRepresentante" class="form-label">Parentesco</label>
                                            <input type="text" class="form-control" id="parentescoRepresentante" name="parentescoRepresentante" required>
                                        </div>
                                        <div class="mb-3">
                                        <label for="codigo">Código Carnet de la Patria</label>
                                        <input type="text" class="form-control" id="codigo" name="codigo" placeholder="Código">
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="direccion_representante">Direccion</label>
                                    <input type="text" class="form-control" id="direccion_representante" name="direccion_representante" placeholder="Direccion">
                                    </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnActualizar">Actualizar Datos</button>
                </div>
            </div>
        </div>
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

        $('#tablaEstudiantes tr').each(function() {
            var filaNombre = $(this).find('td:eq(1)').text().toLowerCase();
            var filaCedula = $(this).find('td:eq(0)').text().toLowerCase();
            var filaSeccion = $(this).find('td:eq(3)').text().toLowerCase();

            var coincideNombre = filaNombre.includes(nombre);
            var coincideCedula = filaCedula.includes(cedula);
            var coincideSeccion = (seccion === '' || filaSeccion === seccion);

            if (coincideNombre && coincideCedula && coincideSeccion) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    // Aplicar el filtro cuando se escriba en los campos de filtro
    $('#filtroNombre, #filtroCedula, #filtroSeccion').on('input change', function() {
        filtrarTabla();
    });
});
</script>


    <!-- Script para cargar los detalles y el formulario de edición en los modales -->
    <script>
        $(document).ready(function() {
            // Lógica para cargar los detalles en el modal de consultar
            $('.btn-consultar').on('click', function() {
                var id = $(this).data('id'); // Obtener el ID del estudiante

                // Hacer una solicitud AJAX para obtener los detalles
                $.ajax({
                    url: '../4to/Controladores/obtener_detalles.php', // Archivo PHP que obtiene los detalles
                    type: 'GET',
                    data: { id: id },
                    success: function(response) {
                        $('#modalDetallesBody').html(response); // Cargar los detalles en el modal
                    },
                    error: function() {
                        $('#modalDetallesBody').html('<p>Error al cargar los detalles.</p>');
                    }
                });
            });

            // Lógica para cargar los datos en el modal de editar
            $('.btn-editar').on('click', function() {
                var id = $(this).data('id'); // Obtener el ID del estudiante

                // Hacer una solicitud AJAX para obtener los datos del estudiante
                $.ajax({
                    url: '../4to/Controladores/obtener_datos_editar.php', // Archivo PHP que obtiene los datos para editar
                    type: 'GET',
                    data: { id: id },
                    success: function(response) {
                        var datos = JSON.parse(response);

                        // Llenar los campos del modal con los datos obtenidos
                        $('#nombreEstudiante').val(datos.nombre_estudiante);
                        $('#apellidoEstudiante').val(datos.apellido_estudiante);
                        $('#cedulaEstudiante').val(datos.cedula_estudiante);
                        $('#nacionalidad_estudiante').val(datos.nacionalidad_estudiante);
                        $('#fechaNacimientoEstudiante').val(datos.fecha_nacimiento_estudiante);
                        $('#lugarNacimientoEstudiante').val(datos.lugar_nacimiento_estudiante);
                        $('#direccionEstudiante').val(datos.direccion_estudiante);
                        $('#telefonoLocalEstudiante').val(datos.telefono_local_estudiante);
                        $('#telefonoCelularEstudiante').val(datos.telefono_celular_estudiante);
                        $('#correoEstudiante').val(datos.correo_estudiante);
                        $('#añoCursarEstudiante').val(datos.año_cursar_estudiante);
                        $('#seccionEstudiante').val(datos.seccion_estudiante);
                        $('#tipoEstudiante').val(datos.tipo_estudiante);
                        $('#padeceEnfermedad').val(datos.padece_enfermedad_estudiante);
                        $('#esAlergico').val(datos.es_alergico_estudiante);

                        $('#nombreMadre').val(datos.nombre_madre);
                        $('#apellidoMadre').val(datos.apellido_madre);
                        $('#cedulaMadre').val(datos.cedula_madre);
                        $('#telefonoLocalMadre').val(datos.telefono_local_madre);
                        $('#telefonoCelularMadre').val(datos.telefono_celular_madre);
                        $('#direccionMadre').val(datos.direccion_madre);
                        $('#oficioMadre').val(datos.oficio_madre);

                        $('#nombrePadre').val(datos.nombre_padre);
                        $('#apellidoPadre').val(datos.apellido_padre);
                        $('#cedulaPadre').val(datos.cedula_padre);
                        $('#telefonoLocalPadre').val(datos.telefono_local_padre);
                        $('#telefonoCelularPadre').val(datos.telefono_celular_padre);
                        $('#direccionPadre').val(datos.direccion_padre);
                        $('#oficioPadre').val(datos.oficio_padre);

                        $('#nombreRepresentante').val(datos.nombre_representante);
                        $('#apellidoRepresentante').val(datos.apellido_representante);
                        $('#cedulaRepresentante').val(datos.cedula_representante);
                        $('#telefonoLocalRepresentante').val(datos.telefono_local_representante);
                        $('#telefonoCelularRepresentante').val(datos.telefono_celular_representante);
                        $('#parentescoRepresentante').val(datos.parentesco_representante);
                        $('#direccion_representante').val(datos.direccion_representante);
                        $('#codigo').val(datos.codigo_representante);
                        $('#serial').val(datos.serial_representante);
                    },
                    error: function() {
                        alert('Error al cargar los datos.');
                    }
                });
            });

            // Lógica para actualizar los datos
            $('#btnActualizar').on('click', function() {
                var id = $('.btn-editar').data('id'); // Obtener el ID del estudiante

                // Obtener los datos del formulario
                var datos = {
                    nombreEstudiante: $('#nombreEstudiante').val(),
                    apellidoEstudiante: $('#apellidoEstudiante').val(),
                    cedulaEstudiante: $('#cedulaEstudiante').val(),
                    nacionalidad_estudiante: $('#nacionalidad_estudiante').val(),
                    fechaNacimientoEstudiante: $('#fechaNacimientoEstudiante').val(),
                    lugarNacimientoEstudiante: $('#lugarNacimientoEstudiante').val(),
                    direccionEstudiante: $('#direccionEstudiante').val(),
                    telefonoLocalEstudiante: $('#telefonoLocalEstudiante').val(),
                    telefonoCelularEstudiante: $('#telefonoCelularEstudiante').val(),
                    correoEstudiante: $('#correoEstudiante').val(),
                    añoCursarEstudiante: $('#añoCursarEstudiante').val(),
                    seccionEstudiante: $('#seccionEstudiante').val(),
                    tipoEstudiante: $('#tipoEstudiante').val(),
                    padeceEnfermedad: $('#padeceEnfermedad').val(),
                    esAlergico: $('#esAlergico').val(),

                    nombreMadre: $('#nombreMadre').val(),
                    apellidoMadre: $('#apellidoMadre').val(),
                    cedulaMadre: $('#cedulaMadre').val(),
                    telefonoLocalMadre: $('#telefonoLocalMadre').val(),
                    telefonoCelularMadre: $('#telefonoCelularMadre').val(),
                    direccionMadre: $('#direccionMadre').val(),
                    oficioMadre: $('#oficioMadre').val(),

                    nombrePadre: $('#nombrePadre').val(),
                    apellidoPadre: $('#apellidoPadre').val(),
                    cedulaPadre: $('#cedulaPadre').val(),
                    telefonoLocalPadre: $('#telefonoLocalPadre').val(),
                    telefonoCelularPadre: $('#telefonoCelularPadre').val(),
                    direccionPadre: $('#direccionPadre').val(),
                    oficioPadre: $('#oficioPadre').val(),

                    nombreRepresentante: $('#nombreRepresentante').val(),
                    apellidoRepresentante: $('#apellidoRepresentante').val(),
                    cedulaRepresentante: $('#cedulaRepresentante').val(),
                    telefonoLocalRepresentante: $('#telefonoLocalRepresentante').val(),
                    telefonoCelularRepresentante: $('#telefonoCelularRepresentante').val(),
                    parentescoRepresentante: $('#parentescoRepresentante').val(),
                    direccion_representante: $('#direccion_representante').val(),
                    codigo: $('#codigo').val(),
                    serial: $('#serial').val()
                };

                // Enviar los datos al servidor para actualizar
                $.ajax({
    url: '../4to/Controladores/actualizar_datos.php', // Archivo PHP que procesa la actualización
    type: 'POST',
    data: { id: id, datos: datos },
    success: function(response) {
        Swal.fire({
            title: 'Éxito',
            text: 'Datos actualizados correctamente',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            $('#modalEditar').modal('hide'); // Cerrar el modal
            location.reload(); // Recargar la página para ver los cambios
        });
    },
    error: function() {
        Swal.fire({
            title: 'Error',
            text: 'Error al actualizar los datos',
            icon: 'error',
            confirmButtonText: 'Entendido'
        });
    }
});
            });
        });
    </script>
    <script>
    $(document).ready(function() {
        // Lógica para eliminar un estudiante
        $('.btn-eliminar').on('click', function() {
            var id = $(this).data('id'); // Obtener el ID del estudiante

            // Mostrar alerta de confirmación con SweetAlert2
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, hacer una solicitud AJAX para eliminar
                    $.ajax({
                        url: '../4to/Controladores/eliminar_estudiante.php', // Archivo PHP que elimina el estudiante
                        type: 'GET',
                        data: { id: id },
                        success: function(response) {
                            // Mostrar mensaje de éxito
                            Swal.fire(
                                '¡Eliminado!',
                                'El estudiante ha sido eliminado.',
                                'success'
                            ).then(() => {
                                // Recargar la página para ver los cambios
                                location.reload();
                            });
                        },
                        error: function() {
                            // Mostrar mensaje de error
                            Swal.fire(
                                'Error',
                                'Hubo un problema al eliminar el estudiante.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>
</body>
</html>

<?php
$conn = null; // Cerrar la conexión PDO
?>