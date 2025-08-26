<?php
// Configuración de la conexión
$host = "localhost";
$user = "root";
$pass = "";
$db   = "farmacia";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Error: " . $conn->connect_error); }

require_once "controlador/controlador.php";

$controller = new MedicamentoController($conn);
list($lista, $editMedicamento) = $controller->manejarAcciones();

include "vista/vista.php";
