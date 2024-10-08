<?php
header('Content-Type: application/json');

// Configura la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "formulario_medico";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener los medicamentos que no están suspendidos
$sql = "SELECT nombre_medicamento AS name, presentacion AS form FROM medicamentos WHERE suspendido = 0";
$result = $conn->query($sql);

// Verificar si la consulta tuvo éxito
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}

// Crear un array para almacenar los resultados
$medicamentos = array();
while($row = $result->fetch_assoc()) {
    $medicamentos[] = $row;
}

// Convertir el array a JSON y devolverlo
echo json_encode($medicamentos);

// Cerrar la conexión
$conn->close();
?>
