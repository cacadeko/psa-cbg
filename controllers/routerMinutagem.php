<?php
session_start();
require_once 'MinutagemController.php';
use Controllers\MinutagemController;

$ctrl = new MinutagemController();
$ctrl->store();
