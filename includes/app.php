<?php 
require 'config/database.php';
require 'functiones.php';
require __DIR__ . '/../vendor/autoload.php';


// conectarnos a la base de datos
$db = conectarDB();

use Model\ActiveRecord;

ActiveRecord::setDB($db);
