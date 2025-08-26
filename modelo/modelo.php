<?php
class Medicamento {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodos() {
        return $this->conn->query("SELECT * FROM medicamentos ORDER BY id DESC");
    }

    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM medicamentos WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function crear($data) {
        $stmt = $this->conn->prepare("INSERT INTO medicamentos (nombre, descripcion, precio, stock, fecha_vencimiento) VALUES (?,?,?,?,?)");
        $stmt->bind_param("ssdis", $data['nombre'], $data['descripcion'], $data['precio'], $data['stock'], $data['fecha_vencimiento']);
        return $stmt->execute();
    }

    public function actualizar($data) {
        $stmt = $this->conn->prepare("UPDATE medicamentos SET nombre=?, descripcion=?, precio=?, stock=?, fecha_vencimiento=? WHERE id=?");
        $stmt->bind_param("ssdisi", $data['nombre'], $data['descripcion'], $data['precio'], $data['stock'], $data['fecha_vencimiento'], $data['id']);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $stmt = $this->conn->prepare("DELETE FROM medicamentos WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
