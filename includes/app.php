<?php
  use Model\ActiveRecord;
  require __DIR__ . '/../vendor/autoload.php';
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__); // Esta linea se encargara de buscar el archivo .env y vincularlo a la variable
  $dotenv->safeLoad(); // El metodo safeLoad() permite leer y cargar las variables de entorno para poder utilizarlas

  require 'funciones.php';
  require 'database.php';

  // Conexion a DB
  ActiveRecord::setDB($db);
?>