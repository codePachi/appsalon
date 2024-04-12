<?php 
  require_once __DIR__ . '/../includes/app.php';

  use Controllers\AdminController;
  use Controllers\APIController;
  use Controllers\CitaController;
  use Controllers\LoginController;
  use Controllers\ServicioController;
  use MVC\Router;

  $router = new Router();

  /* LOGIN */

  // Inicio de sesion
  $router->get('/', [LoginController::class, 'login']);
  $router->post('/', [LoginController::class, 'login']);
  $router->get('/logout', [LoginController::class, 'logout']);

  // Recuperar cuenta
  $router->get('/olvidar', [LoginController::class, 'olvidar']);
  $router->post('/olvidar', [LoginController::class, 'olvidar']);
  $router->get('/recuperar', [LoginController::class, 'recuperar']);
  $router->post('/recuperar', [LoginController::class, 'recuperar']);
  
  // Crear cuenta
  $router->get('/crear-cuenta', [LoginController::class, 'crearCuenta']);
  $router->post('/crear-cuenta', [LoginController::class, 'crearCuenta']);

  // Confirmar cuenta
  $router->get('/confirmar-cuenta', [LoginController::class, 'confirmarCuenta']);
  $router->get('/mensaje', [LoginController::class, 'mensaje']);


  /* CITAS */

  $router->get('/cita', [CitaController::class, 'index']);
  
  /* ADMIN */

  $router->get('/admin', [AdminController::class, 'index']);

  // API
  $router->get('/api/servicios',[APIController::class, 'index']);
  $router->post('/api/citas',[APIController::class, 'guardar']);
  // En este caso el metodo a usar no seria post sino delete, este sin embargo no es soportado de forma automatica por http, en caso de usar frameworks se podra cambiar a delete ya que la mayoria lo soportan
  $router->post('/api/eliminar', [APIController::class, 'eliminar']);

  /* CRUD DE SERVICIOS */
  $router->get('/servicios', [ServicioController::class, 'index']);
  $router->get('/servicios/crear', [ServicioController::class, 'crear']);
  $router->post('/servicios/crear', [ServicioController::class, 'crear']);
  $router->get('/servicios/actualizar', [ServicioController::class, 'actualizar']);
  $router->post('/servicios/actualizar', [ServicioController::class, 'actualizar']);
  $router->get('/servicios/eliminar', [ServicioController::class, 'eliminar']);
  $router->post('/servicios/eliminar', [ServicioController::class, 'eliminar']);

  // Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
  $router->comprobarRutas();
?>