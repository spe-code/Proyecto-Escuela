<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Inscripción de Estudiantes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery (necesario para Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS -->
     <link rel="stylesheet" href="../assets/css/navbar-menu.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color:rgb(255, 255, 255);
        }
        .container {
            max-width: 800px;
            padding: 20px;
        }
        .form-container {
            background-color:rgb(255, 255, 255);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra suave */
        }
        .form-container h1 {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin-bottom: 25px;
        }
        .form-floating {
            margin-bottom: 1.5rem; /* Espacio entre los inputs */
        }
        .form-floating label {
            color: #6c757d; /* Color de las etiquetas */
        }
        .form-floating input, .form-floating select, .form-floating textarea {
            border-radius: 8px; /* Bordes redondeados */
            border: 1px solid #ced4da; /* Borde suave */
            padding: 10px 12px; /* Margen interno */
        }
        .form-floating input:focus, .form-floating select:focus, .form-floating textarea:focus {
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
        .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .alert {
            margin-top: 20px;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c2c7;
            color: #842029;
        }
        


    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="form-container">
            <h1>Inscripción de Estudiantes</h1>
<?php
include '../includes/conexion.php'; // Asegúrate de que el archivo de conexión sea correcto

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $sexo = $_POST['sexo'];
    $cedula = $_POST['cedula'];
    $nacionalidad_estudiante = $_POST['nacionalidad_estudiante'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $lugar_nacimiento = $_POST['lugar_nacimiento'];
    $direccion = $_POST['direccion'];
    $telefono_local = $_POST['telefono_local'];
    $telefono_celular = $_POST['telefono_celular'];
    $correo = $_POST['correo'];
    $año_cursar = $_POST['año_cursar'];
    $seccion = $_POST['seccion'];
    $tipo_estudiante = $_POST['tipo_estudiante'];
    $padece_enfermedad = $_POST['padece_enfermedad'];
    $es_alergico = $_POST['es_alergico'];
    $periodo_escolar = $_POST['periodo_escolar'];

    // Verificar si la cédula ya existe
    $sql_verificar_cedula = "SELECT cedula_estudiante FROM estudiantes WHERE cedula_estudiante = '$cedula'";
    $resultado = $conn->query($sql_verificar_cedula);

    if ($resultado->rowCount() > 0) {
        // Si la cédula está duplicada, mostrar un modal de alerta
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La cédula ingresada ya está registrada.',
                    confirmButtonText: 'Aceptar'
                });
              </script>";
    } else {
        // Insertar datos en la tabla estudiantes
        $sql_estudiantes = "INSERT INTO estudiantes (
            nombre_estudiante, 
            apellido_estudiante, 
            sexo_estudiante, 
            cedula_estudiante,
            nacionalidad_estudiante, 
            fecha_nacimiento_estudiante, 
            lugar_nacimiento_estudiante, 
            direccion_estudiante, 
            telefono_local_estudiante, 
            telefono_celular_estudiante, 
            correo_estudiante, 
            año_cursar_estudiante, 
            seccion_estudiante, 
            tipo_estudiante, 
            padece_enfermedad_estudiante, 
            es_alergico_estudiante,
            periodo_escolar
        ) VALUES (
            '$nombre', 
            '$apellido', 
            '$sexo', 
            '$cedula', 
            '$nacionalidad_estudiante',
            '$fecha_nacimiento', 
            '$lugar_nacimiento', 
            '$direccion', 
            '$telefono_local', 
            '$telefono_celular', 
            '$correo', 
            '$año_cursar', 
            '$seccion', 
            '$tipo_estudiante', 
            '$padece_enfermedad', 
            '$es_alergico',
            '$periodo_escolar'
        )";

        if ($conn->query($sql_estudiantes)) {
            // Obtener el ID del estudiante recién insertado
            $id_estudiante = $conn->lastInsertId();

            // Redirigir a la página de registro de familiares con el ID del estudiante
            header("Location: registrar_familiares.php?id_estudiante=" . $id_estudiante);
            exit(); // Asegura que el script se detenga después de la redirección
        } else {
            echo '<div class="alert alert-danger mt-3" role="alert">Error al registrar el estudiante: ' . $e->getMessage()  . '</div>';
        }
    }
}
?>
    <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-custom fixed-top" style="background-color:rgb(39, 99, 173);">
        <div class="container-fluid">
            <!-- Logo o nombre de la aplicación -->
            <a class="navbar-brand" style="color: white;" href="#">
             <img src="../assets/img/logotipo-liceo.png" alt="Logo del Liceo" class="d-inline-block align-text-top ms-2" style="height: 30px;">
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
                        <a class="nav-link active" style="color: white;" href="index.php">
                            <i class="fas fa-home"></i> Inicio
                        </a>
                    </li>

                    <!-- Opción: Inscribir Estudiantes -->
                    <li class="nav-item">
                        <a class="nav-link" style="color: white;" href="inscripcion.php">
                            <i class="fas fa-user-plus"></i> Inscribir Estudiantes
                        </a>
                    </li>

                    <!-- Opción: Consultar Datos (con menú desplegable) -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" style="color: white;" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                           <li class="nav-item">
                    <a href="registrar_admin.php" class="btn btn-admin" style="color: white;">
                        <i class="fas fa-user-cog"></i> Administrares
            
            </a>
                </li>
                         <!-- Botón de Cerrar Sesión -->
                <li class="nav-item">
                    <a class="nav-link" style="color: white;" href="../includes/logout.php" onclick="return confirm('¿Estás seguro que deseas cerrar sesión?');">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                </li>
                    </ul>
                </div>
            </div>
         </nav>      

         <div class="container">   
            <form method="POST" class="needs-validation" novalidate>
            
                
                <div class="row">
                <div class="row">
                <div class="row">
                <div class="row">
                <div class="row">
    <!-- Columna 1 -->
<div class="col-md-12">

        <!-- Período Escolar -->
        <div class="form-floating mb-3">
            <select class="form-select" id="periodo_escolar" name="periodo_escolar" required>
                <option value="">Seleccione un período escolar</option>
            </select>
            <label for="periodo_escolar">Período Escolar</label>
            <div class="invalid-feedback">Por favor, selecciona un período escolar.</div>
        </div>
</div>
    <div class="col-md-6">
        
        <!-- Cédula -->
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="cedula" name="cedula" placeholder="Cédula" required>
            <label for="cedula">Cédula</label>
            <div class="invalid-feedback">Por favor, ingresa tu cédula.</div>
        </div>

        <!-- Nombre -->
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
            <label for="nombre">Nombre</label>
            <div class="invalid-feedback">Por favor, ingresa tu nombre.</div>
        </div>


        <!-- Lugar de Nacimiento -->
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="lugar_nacimiento" name="lugar_nacimiento" placeholder="Lugar de Nacimiento" required>
            <label for="lugar_nacimiento">Lugar de Nacimiento</label>
            <div class="invalid-feedback">Por favor, ingresa tu lugar de nacimiento.</div>
        </div>

                <!-- Año a Cursar -->
        <div class="form-floating mb-3">
            <select class="form-select" id="año_cursar" name="año_cursar" required>
                <option value="">Seleccione un año</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <label for="año_cursar">Año a Cursar</label>
            <div class="invalid-feedback">Por favor, selecciona el año a cursar.</div>
        </div>

        <!-- Sección -->
        <div class="form-floating mb-3">
            <select class="form-select" id="seccion" name="seccion" required>
                <option value="">Seleccione una opción</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
            </select>
            <label for="seccion">Sección</label>
            <div class="invalid-feedback">Por favor, selecciona la sección.</div>
        </div>

        <!-- Teléfono Local -->
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="telefono_local" name="telefono_local" placeholder="Teléfono Local">
            <label for="telefono_local">Teléfono Local</label>
        </div>

        <!-- Correo -->
        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo">
            <label for="correo">Correo</label>
        </div>

        <!-- Campo para "¿Es alérgico?" -->
<div class="form-floating mb-3">
    <select class="form-select" id="es_alergico_select" name="es_alergico_select" required>
        <option value="No">No</option>
        <option value="Si">Sí</option>
    </select>
    <label for="es_alergico_select">¿Es alérgico?</label>
</div>

<!-- Campo de texto para describir la alergia (oculto inicialmente) -->
<div class="form-floating mb-3" id="descripcion_alergia_container" style="display: none;">
    <textarea class="form-control" id="es_alergico" name="es_alergico" placeholder="Describa la alergia" style="height: 100px;"></textarea>
    <label for="es_alergico">Describa la alergia</label>
</div>
    </div>

    <!-- Columna 2 -->
    <div class="col-md-6">

    <div class="form-floating mb-3">
        <select class="form-select" id="nacionalidad_estudiante" name="nacionalidad_estudiante" required>
        <option value="">Seleccione una opción</option>
        <option value="Venezolana">Venezolana</option>
        <option value="Extranjera">Extranjera</option>
        </select>
        <label for="nacionalidadestudiante">Nacionalidad</label>
        <div class="invalid-feedback">Por favor, ingrese una Nacionalidad.</div>
    </div>

          <!-- Apellido -->
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido" required>
            <label for="apellido">Apellido</label>
            <div class="invalid-feedback">Por favor, ingresa tu apellido.</div>
        </div>
        

        <!-- Fecha de Nacimiento -->
        <div class="form-floating mb-3">
            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="Fecha de Nacimiento" required>
            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
            <div class="invalid-feedback">Por favor, ingresa tu fecha de nacimiento.</div>
        </div>

        <!-- Sexo -->
        <div class="form-floating mb-3">
            <select class="form-select" id="sexo" name="sexo" required>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
            </select>
            <label for="sexo">Sexo</label>
            <div class="invalid-feedback">Por favor, selecciona tu sexo.</div>
        </div>

        <!-- Tipo de Estudiante -->
        <div class="form-floating mb-3">
            <select class="form-select" id="tipo_estudiante" name="tipo_estudiante" required>
                <option value="regular">Regular</option>
                <option value="repitiente">Repitiente</option>
            </select>
            <label for="tipo_estudiante">Tipo de Estudiante</label>
            <div class="invalid-feedback">Por favor, selecciona el tipo de estudiante.</div>
        </div>

        <!-- Teléfono Celular -->
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="telefono_celular" name="telefono_celular" placeholder="Teléfono Celular" required>
            <label for="telefono_celular">Teléfono Celular</label>
            <div class="invalid-feedback">Por favor, ingresa tu teléfono celular.</div>
        </div>

        <!-- Dirección -->
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección" required>
            <label for="direccion">Dirección</label>
            <div class="invalid-feedback">Por favor, ingresa tu dirección.</div>
        </div>

<!-- Campo para "Padece de una Enfermedad" -->
<div class="form-floating mb-3">
    <select class="form-select" id="padece_enfermedad_select" name="padece_enfermedad_select" required>
        <option value="No">No</option>
        <option value="Si">Sí</option>
    </select>
    <label for="padece_enfermedad_select">¿Padece de alguna enfermedad?</label>
</div>

<!-- Campo de texto para describir la enfermedad (oculto inicialmente) -->
<div class="form-floating mb-3" id="descripcion_enfermedad_container" style="display: none;">
    <textarea class="form-control" id="padece_enfermedad" name="padece_enfermedad" placeholder="Describa la enfermedad" style="height: 100px;"></textarea>
    <label for="padece_enfermedad">Describa la enfermedad</label>
</div>
</div>
                <!-- Botón de envío y Botón de Regresar -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                    <button type="button" class="btn btn-secondary ms-3" onclick="window.location.href = 'index.php';">Inicio</button>
                </div>
            </form>
        </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para generar las opciones del período escolar
        function generarPeriodosEscolares() {
            const select = document.getElementById('periodo_escolar');
            const añoActual = new Date().getFullYear();
            
            // Limpiar opciones existentes (excepto la primera)
            while (select.options.length > 1) {
                select.remove(1);
            }
            
            // Agregar opciones para el año actual y el siguiente
            const opcionActual = `${añoActual-1}-${añoActual}`;
            const opcionSiguiente = `${añoActual}-${añoActual+1}`;
            
            // Crear y agregar opciones
            const optionActual = document.createElement('option');
            optionActual.value = opcionActual;
            optionActual.textContent = opcionActual;
            select.appendChild(optionActual);
            
            const optionSiguiente = document.createElement('option');
            optionSiguiente.value = opcionSiguiente;
            optionSiguiente.textContent = opcionSiguiente;
            select.appendChild(optionSiguiente);
        }
        
        // Llamar a la función al cargar la página
        document.addEventListener('DOMContentLoaded', generarPeriodosEscolares);

        // Obtener el select y el contenedor del campo de texto
        const selectAlergico = document.getElementById('es_alergico_select');
        const descripcionAlergiaContainer = document.getElementById('descripcion_alergia_container');

        // Escuchar cambios en el select
        selectAlergico.addEventListener('change', function () {
            if (this.value === 'Si') {
                // Mostrar el campo de texto si se selecciona "Sí"
                descripcionAlergiaContainer.style.display = 'block';
            } else {
                // Ocultar el campo de texto si se selecciona "No"
                descripcionAlergiaContainer.style.display = 'none';
            }
        });    
        
        // Obtener el select y el contenedor del campo de texto
        const selectEnfermedad = document.getElementById('padece_enfermedad_select');
        const descripcionEnfermedadContainer = document.getElementById('descripcion_enfermedad_container');

        // Escuchar cambios en el select
        selectEnfermedad.addEventListener('change', function () {
            if (this.value === 'Si') {
                // Mostrar el campo de texto si se selecciona "Sí"
                descripcionEnfermedadContainer.style.display = 'block';
            } else {
                // Ocultar el campo de texto si se selecciona "No"
                descripcionEnfermedadContainer.style.display = 'none';
            }
        });

        // Validar campos que solo permiten números (Cédula, Teléfono Celular, Teléfono Local, Año a Cursar)
        document.querySelectorAll('#cedula, #telefono_celular, #telefono_local, #año_cursar').forEach(input => {
            input.addEventListener('input', function (e) {
                // Expresión regular para permitir solo números
                const regex = /^[0-9]*$/;
                if (!regex.test(this.value)) {
                    // Si no coincide, elimina el último carácter ingresado
                    this.value = this.value.slice(0, -1);
                }
            });
        });

        // Validar campos que solo permiten letras (Nombre, Apellido, Sección, Es Alérgico, Padece de una Enfermedad, Lugar de Nacimiento)
        document.querySelectorAll('#nombre, #apellido, #seccion, #es_alergico, #padece_enfermedad, #lugar_nacimiento').forEach(input => {
            input.addEventListener('input', function (e) {
                // Expresión regular para permitir solo letras y espacios
                const regex = /^[A-Za-zÁáÉéÍíÓóÚúÜüÑñ\s]*$/;
                if (!regex.test(this.value)) {
                    // Si no coincide, elimina el último carácter ingresado
                    this.value = this.value.slice(0, -1);
                }
            });
        });

        // Validar el formulario antes de enviarlo
        (function() {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>