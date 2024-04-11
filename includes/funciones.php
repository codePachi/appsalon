<?php
  function debug($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
  }

  // Escapa / Sanitizar el HTML
  function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
  }

  // Funcion para verificar usuario autenticado
  function isAuth() :void {
    if(!isset($_SESSION)){
      session_start(); 
    } elseif(!isset($_SESSION['login'])) {
      header('Location: /');
    }
  }

  // Funcion para verificar que se este ejecutando solo un session_start (el router ya tiene uno iniciado)
  function isSession() :void {
    if(!isset($_SESSION)){
      session_start(); 
    }
  }

  // Funcion para saber el ultimo elemento
  function esUltimo(string $actual, string $proximo) :bool {
    if($actual !== $proximo){
      return true;
    }
    return false;
  }

  // Funcion para detectar usuario admin
  function isAdmin() :void {
    if(!isset($_SESSION['admin'])){
      header('Location: /');
    }
  }
?>