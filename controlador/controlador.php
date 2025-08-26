<?php
require_once "modelo/modelo.php";

class MedicamentoController {
    private $model;

    public function __construct($db) {
        $this->model = new Medicamento($db);
    }

    public function manejarAcciones() {
        $editMedicamento = null;

        // Guardar o actualizar
        if (isset($_POST['guardar'])) {
            $data = [
                'id' => $_POST['id'] ?? null,
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'precio' => $_POST['precio'],
                'stock' => $_POST['stock'],
                'fecha_vencimiento' => $_POST['fecha_vencimiento']
            ];

            if (!empty($data['id'])) {
                $this->model->actualizar($data);
            } else {
                $this->model->crear($data);
            }
            header("Location: index.php");
            exit;
        }

        // Eliminar
        if (isset($_GET['eliminar'])) {
            $this->model->eliminar($_GET['eliminar']);
            header("Location: index.php");
            exit;
        }

        // Editar
        if (isset($_GET['editar'])) {
            $editMedicamento = $this->model->obtenerPorId($_GET['editar']);
        }

        $lista = $this->model->obtenerTodos();
        return [$lista, $editMedicamento];
    }
}
