<?php
include '../includes/conexion.php';

$sql = "SELECT * FROM representantes";
$stmt = $conn->query($sql);
$representantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h1>Directorio Telefónico de Representantes</h1>";
echo "<table border='1'>";
echo "<tr><th>Nombre</th><th>Teléfono</th><th>Email</th></tr>";
foreach ($representantes as $representante) {
    echo "<tr>";
    echo "<td>" . $representante['nombre'] . "</td>";
    echo "<td>" . $representante['telefono'] . "</td>";
    echo "<td>" . $representante['email'] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>