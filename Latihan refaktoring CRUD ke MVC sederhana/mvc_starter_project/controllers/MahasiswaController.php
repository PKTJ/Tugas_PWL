<?php
require_once __DIR__ . '/../models/Mahasiswa.php';

class MahasiswaController {
    private $model;

    public function __construct() {
        $this->model = new Mahasiswa();
    }

    public function index() {
        $data = $this->model->getAll();
        require __DIR__ . '/../views/mahasiswa_list.php';
    }

    public function add() {
        require __DIR__ . '/../views/mahasiswa_add.php';
    }

    public function store() {
        $this->model->create($_POST['nama'], $_POST['nim']);
        header('Location: index.php');
    }

    public function edit($id) {
        $mhs = $this->model->getById($id);
        require __DIR__ . '/../views/mahasiswa_edit.php';
    }

    public function update($id) {
        $this->model->update($id, $_POST['nama'], $_POST['nim']);
        header('Location: index.php');
    }

    public function delete($id) {
        $this->model->delete($id);
        header('Location: index.php');
    }
}
?>
