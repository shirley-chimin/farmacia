<?php
// Conexión a MySQL
$host = "localhost";
$user = "root";
$pass = "";
$db   = "farmacia";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Error: " . $conn->connect_error); }

// Guardar o actualizar
if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];

    if (!empty($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "UPDATE medicamentos SET nombre='$nombre', descripcion='$descripcion', precio='$precio', stock='$stock', fecha_vencimiento='$fecha_vencimiento' WHERE id=$id";
    } else {
        $sql = "INSERT INTO medicamentos (nombre, descripcion, precio, stock, fecha_vencimiento) VALUES ('$nombre','$descripcion','$precio','$stock','$fecha_vencimiento')";
    }
    $conn->query($sql);
    header("Location: index.php");
    exit;
}


if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM medicamentos WHERE id=$id");
    header("Location: index.php");
    exit;
}


$editMedicamento = null;
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $result = $conn->query("SELECT * FROM medicamentos WHERE id=$id");
    $editMedicamento = $result->fetch_assoc();
}


$result = $conn->query("SELECT * FROM medicamentos ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title> Farmacia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .container { margin-top: 30px; }
        .table th { background-color: #0d6efd; color: white; }
        .btn { border-radius: 20px; }
        .card { border-radius: 15px; box-shadow: 0px 4px 8px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center mb-4 text-primary fw-bold">Gestión de Medicamentos</h1>

    <div class="card p-4 mb-4">
        <h4><?= $editMedicamento ? "Editar Medicamento" : "Agregar Medicamento" ?></h4>
        <form method="post">
            <input type="hidden" name="id" value="<?= $editMedicamento['id'] ?? '' ?>">
            <div class="mb-3">
                <label class="form-label">Nombre:</label>
                <input type="text" name="nombre" class="form-control" value="<?= $editMedicamento['nombre'] ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción:</label>
                <textarea name="descripcion" class="form-control"><?= $editMedicamento['descripcion'] ?? '' ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Precio (Bs):</label>
                <input type="number" step="0.01" name="precio" class="form-control" value="<?= $editMedicamento['precio'] ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Stock:</label>
                <input type="number" name="stock" class="form-control" value="<?= $editMedicamento['stock'] ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Fecha de Vencimiento:</label>
                <input type="date" name="fecha_vencimiento" class="form-control" value="<?= $editMedicamento['fecha_vencimiento'] ?? '' ?>" required>
            </div>
            <button type="submit" name="guardar" class="btn btn-success">
                <?= $editMedicamento ? "Actualizar" : "Guardar" ?>
            </button>
            <?php if ($editMedicamento): ?>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="card p-4">
        <h4 class="mb-3">Lista de Medicamentos</h4>
        <table class="table table-bordered table-hover text-center">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Vencimiento</th>
                <th>Acciones</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nombre'] ?></td>
                    <td><?= $row['descripcion'] ?></td>
                    <td><span class="badge bg-primary">Bs <?= number_format($row['precio'], 2) ?></span></td>
                    <td><?= $row['stock'] ?></td>
                    <td><?= $row['fecha_vencimiento'] ?></td>
                    <td>
                        <a href="?editar=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="?eliminar=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este medicamento?')">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

</body>
</html>
