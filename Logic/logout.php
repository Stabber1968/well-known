<?php
require_once '../Controllers/init.php';

$user = new User();
$user->logout();

Redirect::to('../index.php');