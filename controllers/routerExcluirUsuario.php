<?php
session_start(); if(!isset($_SESSION['usuario'])) exit;
require_once 'UsuariosController.php';
use Controllers\UsuariosController;

$id = (int)($_GET['id'] ?? 0);
$ctrl = new UsuariosController();
$ctrl->excluir($id);
