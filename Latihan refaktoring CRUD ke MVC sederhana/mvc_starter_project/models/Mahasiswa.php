<?php
require_once __DIR__ . '/../config.php';

class Mahasiswa {
    public function getAll() {
        global $conn;
        $sql = "SELECT * FROM mahasiswa";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM mahasiswa WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($nama, $nim) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO mahasiswa (nama, nim) VALUES (?, ?)");
        $stmt->bind_param('ss', $nama, $nim);
        return $stmt->execute();
    }

    public function update($id, $nama, $nim) {
        global $conn;
        $stmt = $conn->prepare("UPDATE mahasiswa SET nama = ?, nim = ? WHERE id = ?");
        $stmt->bind_param('ssi', $nama, $nim, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM mahasiswa WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
?>
