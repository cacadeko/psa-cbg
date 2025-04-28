<?php
session_start(); if(!isset($_SESSION['usuario'])) exit;
require_once 'UsuariosController.php';
use Controllers\UsuariosController;

$ctrl = new UsuariosController();
$ctrl->store();
