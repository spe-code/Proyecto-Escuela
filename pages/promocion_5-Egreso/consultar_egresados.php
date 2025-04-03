<?php
require_once '../../includes/conexion.php';

// Configuración de paginación
$registrosPorPagina = 10;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $registrosPorPagina;

// Procesar búsquedas y filtros
$filtro_nombre = $_GET['nombre'] ?? '';
$filtro_apellido = $_GET['apellido'] ?? '';
$filtro_año = $_GET['año'] ?? '';
$filtro_periodo = $_GET['periodo'] ?? '';

// Consulta para contar total de registros
$sqlCount = "SELECT COUNT(*) as total FROM egresados WHERE 1=1";
$params = [];
$paramsCount = [];

if (!empty($filtro_nombre)) {
    $sqlCount .= " AND nombre_estudiante LIKE :nombre";
    $paramsCount[':nombre'] = "%$filtro_nombre%";
}

if (!empty($filtro_apellido)) {
    $sqlCount .= " AND apellido_estudiante LIKE :apellido";
    $paramsCount[':apellido'] = "%$filtro_apellido%";
}

if (!empty($filtro_año)) {
    $sqlCount .= " AND año_egreso = :año";
    $paramsCount[':año'] = $filtro_año;
}

if (!empty($filtro_periodo)) {
    $sqlCount .= " AND periodo_escolar LIKE :periodo";
    $paramsCount[':periodo'] = "%$filtro_periodo%";
}

// Ejecutar consulta de conteo
$stmtCount = $conn->prepare($sqlCount);
$stmtCount->execute($paramsCount);
$totalRegistros = $stmtCount->fetchColumn();
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// Consulta principal con paginación
$sql = "SELECT * FROM egresados WHERE 1=1";
$params = [];

if (!empty($filtro_nombre)) {
    $sql .= " AND nombre_estudiante LIKE :nombre";
    $params[':nombre'] = "%$filtro_nombre%";
}

if (!empty($filtro_apellido)) {
    $sql .= " AND apellido_estudiante LIKE :apellido";
    $params[':apellido'] = "%$filtro_apellido%";
}

if (!empty($filtro_año)) {
    $sql .= " AND año_egreso = :año";
    $params[':año'] = $filtro_año;
}

if (!empty($filtro_periodo)) {
    $sql .= " AND periodo_escolar LIKE :periodo";
    $params[':periodo'] = "%$filtro_periodo%";
}

$sql .= " ORDER BY apellido_estudiante, nombre_estudiante";
$sql .= " LIMIT :offset, :limit";

// Preparar y ejecutar consulta principal
$stmt = $conn->prepare($sql);

foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}

$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $registrosPorPagina, PDO::PARAM_INT);
$stmt->execute();
$egresados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Egresados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --color-primario: #3498db;
            --color-secundario:rgb(35, 41, 46);
            --color-fondo: #f8f9fa;
            --color-texto: #333;
            --color-borde: #dee2e6;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--color-fondo);
            color: var(--color-texto);
        }
        
        .navbar-custom {
            color: white;
        }

        .container{
            margin-top: 20px; /* Espacio para el navbar fijo */

        }
        
        .filtros-container {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .btn-buscar {
            background-color: var(--color-primario);
            border-color: var(--color-primario);
        }
        
        .btn-buscar:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        
        .table-container {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .table thead {
            background-color: var(--color-secundario);
            color: white;
        }
        
        .table th {
            font-weight: 500;
        }
        
        .btn-detalle {
            background-color: var(--color-primario);
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            border-radius: 4px;
            transition: all 0.2s;
        }
        
        .btn-detalle:hover {
            background-color: #2980b9;
            transform: translateY(-1px);
        }
        
        .badge-egreso {
            background-color: #27ae60;
            font-weight: normal;
        }
        
        .sin-resultados {
            text-align: center;
            padding: 2rem;
            color: #7f8c8d;
        }
        
        .modal-content {
            border-radius: 10px;
        }
        
        .modal-header {
            background-color: var(--color-secundario);
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        
        .pagination .page-item.active .page-link {
            background-color: var(--color-primario);
            border-color: var(--color-primario);
        }

        .pagination .page-link {
            color: var(--color-secundario);
        }

        .pagination .page-link:hover {
            color: var(--color-primario);
        }
        
        @media (max-width: 768px) {
            .filtros-container .col-md-3, 
            .filtros-container .col-md-2 {
                margin-bottom: 1rem;
            }
        }

        
    </style>
</head>
<body>
   <!-- Navbar (igual que antes) -->
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-custom" style="background-color:rgb(39, 99, 173);">
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

       

    <div class="container">
    <div class="container custom-container">
        <div class="text-center mb-5">
            <h2 class="custom-title mb-2 fs-1">Consulta de Egresados</h2>
            <p class="custom-subtitle text-secondary fs-5">Buscar estudiantes egresados</p>
        </div>
        <!-- Filtros de Búsqueda -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="get" class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" 
                               value="<?= htmlspecialchars($filtro_nombre) ?>" 
                               placeholder="Buscar por nombre">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Apellido</label>
                        <input type="text" class="form-control" name="apellido" 
                               value="<?= htmlspecialchars($filtro_apellido) ?>" 
                               placeholder="Buscar por apellido">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Año Egreso</label>
                        <input type="number" class="form-control" name="año" 
                               value="<?= htmlspecialchars($filtro_año) ?>" 
                               placeholder="Año">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Periodo</label>
                        <input type="text" class="form-control" name="periodo" 
                               value="<?= htmlspecialchars($filtro_periodo) ?>" 
                               placeholder="Ej: 2020-2021">
                    </div>
                    <div class="col-md-2 d-flex align-items-end mb-3">
                        <button type="submit" class="btn btn-primary me-2">Buscar</button>
                        <a href="consultar_egresados.php" class="btn btn-outline-secondary">Limpiar</a>
                    </div>
                    <!-- Campo oculto para mantener otros parámetros -->
                    <input type="hidden" name="pagina" value="1">
                </form>
            </div>
        </div>

        <!-- Resultados -->
        <div class="table-container">
            <?php if (count($egresados) > 0): ?>
                <div class="table-responsive">
                    <table id="tablaEgresados" class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Nacimiento</th>
                                <th>Sexo</th>
                                <th>Egreso</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($egresados as $egresado): ?>
                                <tr>
                                    <td><?= $egresado['id_estudiante'] ?></td>
                                    <td><?= htmlspecialchars($egresado['nombre_estudiante']) ?></td>
                                    <td><?= htmlspecialchars($egresado['apellido_estudiante']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($egresado['fecha_nacimiento_estudiante'])) ?></td>
                                    <td><?= $egresado['sexo_estudiante'] == 'M' ? 'Masculino' : 'Femenino' ?></td>
                                    <td>
                                        <span class="badge badge-egreso"><?= $egresado['año_egreso'] ?></span>
                                        <small class="text-muted d-block"><?= htmlspecialchars($egresado['periodo_escolar']) ?></small>
                                    </td>
                                    <td>
                                    <button cclass="btn-view" 
                                                    onclick="verDetalle(<?= $egresado['id_estudiante'] ?>)"
                                                class="btn btn-detalle" data-bs-toggle="modal" data-bs-target="#detalleModal">
                                            Ver Detalle
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <?php if ($totalPaginas > 1): ?>
                <nav aria-label="Paginación de egresados">
                    <ul class="pagination justify-content-center mt-4">
                        <!-- Botón Anterior -->
                        <li class="page-item <?= $paginaActual <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" 
                               href="?<?= http_build_query(array_merge($_GET, ['pagina' => $paginaActual - 1])) ?>" 
                               aria-label="Anterior">
                                &laquo;
                            </a>
                        </li>
                        
                        <!-- Números de página -->
                        <?php 
                        // Mostrar solo algunas páginas alrededor de la actual
                        $inicio = max(1, $paginaActual - 2);
                        $fin = min($totalPaginas, $paginaActual + 2);
                        
                        if ($inicio > 1) {
                            echo '<li class="page-item"><a class="page-link" href="?'.http_build_query(array_merge($_GET, ['pagina' => 1])).'">1</a></li>';
                            if ($inicio > 2) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                        }
                        
                        for ($i = $inicio; $i <= $fin; $i++): ?>
                            <li class="page-item <?= $i == $paginaActual ? 'active' : '' ?>">
                                <a class="page-link" 
                                   href="?<?= http_build_query(array_merge($_GET, ['pagina' => $i])) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; 
                        
                        if ($fin < $totalPaginas) {
                            if ($fin < $totalPaginas - 1) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                            echo '<li class="page-item"><a class="page-link" href="?'.http_build_query(array_merge($_GET, ['pagina' => $totalPaginas])).'">'.$totalPaginas.'</a></li>';
                        }
                        ?>
                        
                        <!-- Botón Siguiente -->
                        <li class="page-item <?= $paginaActual >= $totalPaginas ? 'disabled' : '' ?>">
                            <a class="page-link" 
                               href="?<?= http_build_query(array_merge($_GET, ['pagina' => $paginaActual + 1])) ?>" 
                               aria-label="Siguiente">
                                &raquo;
                            </a>
                        </li>
                    </ul>
                </nav>

                <div class="text-center text-muted mb-3">
                    Mostrando <?= count($egresados) ?> de <?= $totalRegistros ?> egresados
                </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="sin-resultados">
                    <h4>No se encontraron resultados</h4>
                    <p>Prueba con otros criterios de búsqueda</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal para detalles -->
    <div class="modal fade" id="detalleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Detalles del Egresado</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detalleContent">
                    Cargando información...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function verDetalle(id) {
            $('#detalleContent').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando información...</p>
                </div>
            `);
            
            // Obtener todos los parámetros de la URL actual
            const params = new URLSearchParams(window.location.search);
            
            $.get('obtener_detalle_egresado.php', 
                { 
                    id: id,
                    // Mantener los parámetros de filtro
                    nombre: params.get('nombre') || '',
                    apellido: params.get('apellido') || '',
                    año: params.get('año') || '',
                    periodo: params.get('periodo') || '',
                    pagina: params.get('pagina') || 1
                }, 
                function(data) {
                    $('#detalleContent').html(data);
                }).fail(function() {
                    $('#detalleContent').html(`
                        <div class="alert alert-danger">
                            Error al cargar los detalles. Intente nuevamente.
                        </div>
                    `);
                });
            
            $('#detalleModal').modal('show');
        }
    </script>
</body>
</html>