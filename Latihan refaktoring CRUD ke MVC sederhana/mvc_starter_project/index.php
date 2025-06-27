<?php
require_once 'controllers/MahasiswaController.php';

$ctrl = new MahasiswaController();
$action = $_GET['action'] ?? 'index';
$id     = $_GET['id'] ?? null;

switch ($action) {
    case 'add':
        $ctrl->add();
        break;
    case 'store':
        $ctrl->store();
        break;
    case 'edit':
        $ctrl->edit($id);
        break;
    case 'update':
        $ctrl->update($id);
        break;
    case 'delete':
        $ctrl->delete($id);
        break;
    default:
        $ctrl->index();
        break;
}
?>
