<?php
include '../includes/conexion.php'; // Incluye la conexión a la base de datos

// Obtener el ID del estudiante desde la URL
if (isset($_GET['id_estudiante'])) {
    $id_estudiante = $_GET['id_estudiante'];
} else {
    die("ID del estudiante no proporcionado.");
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Registrar Familiares</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery (necesario para Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        h1{
            margin-top: 50px;
        }
        .form-container {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra suave */
            height: 500px;
        }
        .form-container h3 {
            margin-bottom: 25px;
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            text-align: center; /* Centrar el título */
        }
        .form-floating {
            margin-bottom: 1.5rem; /* Espacio entre los inputs */
        }
        .form-floating label {
            color: #6c757d; /* Color de las etiquetas */
            margin: 0px 0px 0px 5px;
        }
        .form-floating input, .form-floating select {
            border-radius: 8px; /* Bordes redondeados */
            border: 1px solid #ced4da; /* Borde suave */
            padding: 10px 12px; /* Margen interno */
        }
        .form-floating input:focus, .form-floating select:focus {
            border-color: #86b7fe; /* Cambiar color del borde al enfocar */
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.25); /* Sombra al enfocar */
        }
        .btn-primary {
            background-color: #0d6efd;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
        }
        .alert {
            margin-top: 20px;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        .alert-success {
            background-color: #d1e7dd;
            border-color: #badbcc;
            color: #0f5132;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c2c7;
            color: #842029;
        }
    </style>
</head>
<body>

       <!-- Navbar estático -->
<nav class="navbar navbar-expand-lg navbar-light navbar-custom" style="background-color: #7AB2D3;">
    <div class="container-fluid">
        <!-- Logo o nombre de la aplicación -->
        <a class="navbar-brand" href="#">
            <i class="fas fa-school"></i> Gestión Escolar
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
                    <a class="nav-link active" href="index.php">
                        <i class="fas fa-home"></i> Inicio
                    </a>
                </li>

                <!-- Opción: Inscribir Estudiantes -->
                <li class="nav-item">
                    <a class="nav-link" href="inscripcion.php">
                        <i class="fas fa-user-plus"></i> Inscribir Estudiantes
                    </a>
                </li>

                <!-- Opción: Consultar Datos (con menú desplegable) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-search"></i> Consultar Datos
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="../pages/1ro/consultar_estudiantes_1ro.php" target="_blank">1er AÑO</a></li>
                        <li><a class="dropdown-item" href="../pages/2do/consultar_estudiantes_2do.php" target="_blank">2do AÑO</a></li>
                        <li><a class="dropdown-item" href="../pages/3ro/consultar_estudiantes_3ro.php" target="_blank">3er AÑO</a></li>
                        <li><a class="dropdown-item" href="../pages/4to/consultar_estudiantes_4to.php" target="_blank">4to AÑO</a></li>
                        <li><a class="dropdown-item" href="../pages/5to/consultar_estudiantes_5to.php" target="_blank">5to AÑO</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>


    <div class="container mt-5">
        <h1 class="text-center mb-4">Registrar Familiares del Estudiante</h1>
        <div class="row">
            <!-- Columna 1: Madre -->
            <div class="col-md-4">
                <div class="form-container">
                    <h3>Registrar Madre</h3>
                    <form id="formMadre">
                        <input type="hidden" name="id_estudiante" value="<?php echo $id_estudiante; ?>">
                        <div class="row">
                            <!-- Fila 1 -->
                            <div class="col-md-6 form-floating mb-4">
                                <input type="text" class="form-control" id="cedula_madre" name="cedula_madre" placeholder="Cédula" required>
                                <label for="cedula_madre">Cédula</label>
                            </div>
                            
                            <div class="form-floating col-md-6 mb-4">
                            <select class="form-select" id="nacionalidad_madre" name="nacionalidad_madre" required>
                            <option value="">Seleccione una opción</option>
                            <option value="Venezolana">Venezolana</option>
                            <option value="Extranjera">Extranjera</option>
                            </select>
                            <label for="nacionalidadestudiante">Nacionalidad</label>
                            <div class="invalid-feedback">Por favor, ingrese una Nacionalidad.</div>
                            </div>


                            
                        
                            <!-- Fila 2 -->
        
                            <div class="col-md-6 form-floating mb-4">
                                <input type="text" class="form-control" id="nombre_madre" name="nombre_madre" placeholder="Nombre" required>
                                <label for="nombre_madre">Nombre</label>
                            </div>

                            <div class="col-md-6 form-floating mb-4">
                                <input type="text" class="form-control" id="apellido_madre" name="apellido_madre" placeholder="Apellido" required>
                                <label for="apellido_madre">Apellido</label>
                            </div>

                            <div class="col-md-6 form-floating mb-4">
                                <input type="text" class="form-control" id="telefono_local_madre" name="telefono_local" placeholder="Teléfono Local">
                                <label for="telefono_local_madre">Teléfono Local</label>
                            </div>
                            <!-- Fila 3 -->
                            <div class="col-md-6 form-floating mb-4">
                                <input type="text" class="form-control" id="telefono_madre" name="telefono_madre" placeholder="Teléfono Celular" required>
                                <label for="telefono_madre">Teléfono Celular</label>
                            </div>
                            <div class="col-md-6 form-floating mb-4">
                                <input type="text" class="form-control" id="oficio_madre" name="oficio" placeholder="Oficio">
                                <label for="oficio_madre">Oficio</label>
                            </div>
                            <!-- Fila 4 -->
                            <div class="col-md-6 form-floating mb-4">
                                <input type="text" class="form-control" id="direccion_madre" name="direccion" placeholder="Dirección">
                                <label for="direccion_madre">Dirección</label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Columna 2: Padre -->
            <div class="col-md-4">
                <div class="form-container">
                    <h3>Registrar Padre</h3>
                    <form id="formPadre">
                        <input type="hidden" name="id_estudiante" value="<?php echo $id_estudiante; ?>">
                        <div class="row">
                            <!-- Fila 1 -->
                            <div class="col-md-6 form-floating mb-4">
                                <input type="text" class="form-control" id="cedula_padre" name="cedula_padre" placeholder="Cédula" required>
                                <label for="cedula_padre">Cédula</label>
                            </div>
                            
                            <div class="form-floating col-md-6 mb-4">
                            <select class="form-select" id="nacionalidad_padre" name="nacionalidad_padre" required>
                            <option value="">Seleccione una opción</option>
                            <option value="Venezolana">Venezolana</option>
                            <option value="Extranjera">Extranjera</option>
                            </select>
                            <label for="nacionalidadestudiante">Nacionalidad</label>
                            <div class="invalid-feedback">Por favor, ingrese una Nacionalidad.</div>
                            </div>


                            <div class="col-md-6 form-floating mb-4">
                                <input type="text" class="form-control" id="nombre_padre" name="nombre_padre" placeholder="Nombre" required>
                                <label for="nombre_padre">Nombre</label>
                            </div>
                           
                            <!-- Fila 2 -->
                            <div class="col-md-6 form-floating mb-4">
                                <input type="text" class="form-control" id="apellido_padre" name="apellido_padre" placeholder="Apellido" required>
                                <label for="apellido_padre">Apellido</label>
                            </div>

                            <div class="col-md-6 form-floating mb-4">
                                <input type="text" class="form-control" id="telefono_local_padre" name="telefono_local" placeholder="Teléfono Local">
                                <label for="telefono_local_padre">Teléfono Local</label>
                            </div>
                            <!-- Fila 3 -->
                            <div class="col-md-6 form-floating mb-4">
                                <input type="text" class="form-control" id="telefono_padre" name="telefono_padre" placeholder="Teléfono Celular" required>
                                <label for="telefono_padre">Teléfono Celular</label>
                            </div>

                            <div class="col-md-6 form-floating mb-4">
                                <input type="text" class="form-control" id="oficio_padre" name="oficio" placeholder="Oficio">
                                <label for="oficio_padre">Oficio</label>
                            </div>
                            <!-- Fila 4 -->
                            <div class="col form-floating mb-4">
                                <input type="text" class="form-control" id="direccion_padre" name="direccion" placeholder="Dirección">
                                <label for="direccion_padre">Dirección</label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Columna 3: Representante Legal -->
            <div class="col-md-4">
                <div class="form-container">
                    <h3>Registrar Representante Legal</h3>
                    <form id="formRepresentante">
                        <input type="hidden" name="id_estudiante" value="<?php echo $id_estudiante; ?>">
                        <div class="row">
                            <!-- Fila 1 -->
                            <div class="col-md-6 form-floating mb-4">
                                <input type="text" class="form-control" id="cedula" name="cedula" placeholder="Cédula" required>
                                <label for="cedula">Cédula</label>
                            </div>

                            <div class="form-floating col-md-6 mb-4">
                            <select class="form-select" id="nacionalidad_representante" name="nacionalidad_representante" required>
                            <option value="">Seleccione una opción</option>
                            <option value="Venezolana">Venezolana</option>
                            <option value="Extranjera">Extranjera</option>
                            </select>
                            <label for="nacionalidadestudiante">Nacionalidad</label>
                            <div class="invalid-feedback">Por favor, ingrese una Nacionalidad.</div>
                            </div>


                            <div class="col-md-6 form-floating mb-4">
                                <input type="text" class="form-control" id="nombre_representate" name="nombre_representante" placeholder="Nombre" required>
                                <label for="nombre_representante">Nombre</label>
                            </div>

                            <div class="col-md-6 form-floating mb-4">
                                <input type="text" class="form-control" id="apellido_representate" name="apellido_representante" placeholder="Apellido" required>
                                <label for="apellido_representante">Apellido</label>
                            </div>


                            <!-- Fila 2 -->
                            <div class="col-md-6 form-floating mb-4">
                                <input type="text" class="form-control" id="telefono_local" name="telefono_local" placeholder="Teléfono Local">
                                <label for="telefono_local">Teléfono Local</label>
                            </div>

                            <div class="col-md-6 form-floating mb-4">
                                <input type="text" class="form-control" id="telefono_celular" name="telefono_celular" placeholder="Teléfono Celular" required>
                                <label for="telefono_celular">Teléfono Celular</label>
                            </div>
                            <div class="col-md-6 form-floating mb-4">
                                <select class="form-select" id="parentesco" name="parentesco" required>
                                    <option value="Madre">Madre</option>
                                    <option value="Padre">Padre</option>
                                    <option value="Tio">Tío</option>
                                    <option value="Tia">Tía</option>
                                    <option value="Abuelo">Abuelo</option>
                                    <option value="Abuela">Abuela</option>
                                    <option value="Hermano">Hermano</option>
                                    <option value="Hermana">Hermana</option>
                                    <option value="Otro">Otro</option>
                                </select>
                                <label for="parentesco">Parentesco</label>
                            </div>
                            <!-- Fila 3 -->
                                <div class="col-md-6 form-floating mb-4">
                                    <input type="text" class="form-control" id="direccion_representante" name="direccion_representante" placeholder="Direccion">
                                    <label for="direccion_representante">Direccion</label>
                                </div>
                                <div class="col-md-6 form-floating mb-4">
                                <input type="text" class="form-control" id="codigo" name="codigo" placeholder="Código">
                                <label for="codigo">Código Patria</label>
                            </div>
                            <!-- Fila 4 -->
                            <div class="col form-floating mb-4">
                                <input type="text" class="form-control" id="serial" name="serial" placeholder="Serial">
                                <label for="serial">Serial Patria</label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Botón para guardar todos los formularios -->
        <div class="text-center mt-4">
            <button type="button" class="btn btn-primary btn-lg" onclick="guardarTodos()">Guardar Todos</button>
        </div>

        <!-- Contenedor para el botón "Registrar nuevo estudiante" (inicialmente oculto) -->
        <div id="botonRegistrarNuevoEstudiante" class="text-center mt-4" style="display: none;">
            <a href="inscripcion.php" class="btn btn-primary">Registrar nuevo estudiante</a>
        </div>


        <!-- Mensaje de éxito o error -->
        <div id="mensaje" class="alert mt-3" style="display: none;"></div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (necesario para AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

function mostrarCamposCarnet() {
    const seleccion = document.getElementById('posee_carnet').value;
    const camposCarnet = document.getElementById('campos_carnet');
    
    if (seleccion === 'si') {
        camposCarnet.style.display = 'flex'; // o 'block' dependiendo de tu layout
    } else {
        camposCarnet.style.display = 'none';
    }
}



         // Validar campos que solo permiten números (Cédula, Teléfono Celular, Teléfono Local, Serial, Código)
    document.querySelectorAll('#cedula_madre, #telefono_madre, #telefono_local_madre, #cedula_padre, #telefono_padre, #telefono_local_padre, #cedula, #telefono_celular, #telefono_local, #serial, #codigo').forEach(input => {
        input.addEventListener('input', function (e) {
            // Expresión regular para permitir solo números
            const regex = /^[0-9]*$/;
            if (!regex.test(this.value)) {
                // Si no coincide, elimina el último carácter ingresado
                this.value = this.value.slice(0, -1);
            }
        });
    });

    // Validar campos que solo permiten letras (Nombre, Apellido, Oficio, Carnet de la Patria)
    document.querySelectorAll('#nombre_madre, #apellido_madre, #oficio_madre, #nombre_padre, #apellido_padre, #oficio_padre, #nombre_representate, #apellido_representate').forEach(input => {
        input.addEventListener('input', function (e) {
            // Expresión regular para permitir solo letras y espacios
            const regex = /^[A-Za-zÁáÉéÍíÓóÚúÜüÑñ\s]*$/;
            if (!regex.test(this.value)) {
                // Si no coincide, elimina el último carácter ingresado
                this.value = this.value.slice(0, -1);
            }
        });
    });


        // Función para guardar todos los formularios
        function guardarTodos() {
    // Obtener ID de estudiante de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const idEstudiante = urlParams.get('id_estudiante');
    
    const datosMadre = $('#formMadre').serialize();
    const datosPadre = $('#formPadre').serialize();
    const datosRepresentante = $('#formRepresentante').serialize();

    $.ajax({
        url: 'guardar_todos.php',
        type: 'POST',
        data: datosMadre + '&' + datosPadre + '&' + datosRepresentante + '&id_estudiante=' + idEstudiante,
        success: function(response) {
            const urlInicio = 'index.php';
            
            // Configuración de la alerta con el ID que ya tenemos de la URL
            Swal.fire({
                title: '¡Registro exitoso!',
                html: `
                    <div class="text-center">
                        <p>Los datos se han guardado correctamente.</p>
                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <a href='generar_pdf.php?id=${idEstudiante}' class='btn btn-success btn-sm' target="_blank">
                                <i class='fas fa-file-pdf'></i> Descargar PDF
                            </a>
                            <button onclick="window.location.href='${urlInicio}'" class='btn btn-info btn-sm'>
                                <i class='fas fa-home'></i> Ir al Inicio
                            </button>
                        </div>
                    </div>
                `,
                icon: 'success',
                showConfirmButton: false,
                allowOutsideClick: false
            });
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error',
                html: `
                    <div class="text-center">
                        <p>Ocurrió un problema al enviar los datos</p>
                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <button onclick="window.location.reload()" class='btn btn-warning btn-sm'>
                                <i class='fas fa-redo'></i> Reintentar
                            </button>
                            <button onclick="window.location.href='index.php'" class='btn btn-info btn-sm'>
                                <i class='fas fa-home'></i> Ir al Inicio
                            </button>
                        </div>
                    </div>
                `,
                icon: 'error',
                showConfirmButton: false
            });
        }
    });
}
    </script>
</body>
</html>