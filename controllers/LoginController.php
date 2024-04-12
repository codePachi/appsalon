<?php
  namespace Controllers;

  use Model\Usuario;
  use MVC\Router;
  use Classes\Email;

  class LoginController {

    // Funcion de login
    public static function login(Router $router){
      $alertas = [];
      $auth = new Usuario;

      if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $auth = new Usuario($_POST);
        $alertas = $auth->validarLogin();

        if(empty($alertas)){
          // Comprobamos si el usuario existe
          $usuario = Usuario::where('email', $auth->email);

          if($usuario){
            // Verificamos el password
            if($usuario->vHashPassword($auth->password)){
              session_start();

              // Autenticamos usuario
              $_SESSION['id'] = $usuario->id;
              $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
              $_SESSION['email'] = $usuario->email;
              $_SESSION['login'] = true;

              // Redireccionamos
              if($usuario->admin === "1"){
                $_SESSION['admin'] = $usuario->admin ?? null;
                header('Location: /admin');
              } else {
                header('Location: /cita');
              }
            };
          } else {
            $alertas = Usuario::setAlerta('error', 'Usuario no encontrado');
          }
        }
      }

      $alertas = Usuario::getAlertas();

      $router->render('auth/login', [
        'alertas' => $alertas
      ]);
    }

    // Funcion de logout
    public static function logout(){
      $_SESSION = [];
      header('Location: /');
    }

    // Funcion de olvidar password
    public static function olvidar(Router $router){
      $alertas = [];

      if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $auth = new Usuario($_POST);
        $alertas = $auth->validarEmail();

        if(empty($alertas)){
          $usuario = Usuario::where('email', $auth->email);

          if($usuario && $usuario->confirmado === "1"){
            // Generamos un nuevo token para las instrucciones de recuperacion de password
            $usuario->crearToken();
            $usuario->guardar();

            // Creamos un nuevo email
            $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
            $email->enviarInstrucciones();

            Usuario::setAlerta('exito', 'Revisa tu email para recuperar tu password');
          } else {
            Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
          }
        }
      }

      $alertas = Usuario::getAlertas();
      
      $router->render('auth/olvidar',[
        'alertas' => $alertas
      ]);
    }

    // Funcion de recuperar password
    public static function recuperar(Router $router){
      $alertas = [];

      // Creamos una variable para ocultar el formulario en caso de token invalido
      $error = false;

      $token = $_GET['token'];

      $usuario = Usuario::where('token', $token);

      if(empty($usuario)){
        Usuario::setAlerta('error', 'Token no válido');
        $error = true;
      }

      if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $password = new Usuario($_POST);
        $alertas = $password->validarPassword();

        if(empty($alertas)){
          $usuario->password = null;
          $usuario->password = $password->password;

          // Hasheamos el nuevo password
          $usuario->hashPassword();

          $usuario->token = null;

          $resultado = $usuario->guardar();

          if($resultado){
            header("Location: /");
          }
        }
      }

      $alertas = Usuario::getAlertas();

      $router->render('auth/recuperar-password', [
        'alertas' => $alertas,
        'error' => $error
      ]);
    }

    // Funcion para crear cuenta
    public static function crearCuenta(Router $router){
      $usuario = new Usuario;
      $alertas = [];

      if($_SERVER['REQUEST_METHOD'] === 'POST'){
        // Sincronizamos el objeto en memoria con los datos de POST
        $usuario->sincronizar($_POST);

        $alertas = $usuario->validarCuenta();

        if(empty($alertas)){
          $resultado = $usuario->existeUsuario();
          if($resultado->num_rows){
            $alertas = Usuario::getAlertas();
          } else {
            $usuario->hashPassword();
            $usuario->crearToken();

            $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
            $email->enviarConfirmacion();

            $resultado = $usuario->guardar();

            if($resultado){
              header('Location: /mensaje');
            }
          }
        }
      }

      $router->render('auth/crear-cuenta', [
        'usuario' => $usuario,
        'alertas' => $alertas
      ]);

    }

    // Funcion para mensaje de envio de instrucciones
    public static function mensaje(Router $router){
      $router->render('auth/mensaje');
    }

    // Funcion para confirmar cuenta
    public static function confirmarCuenta(Router $router){
      $alertas = [];

      $token = s($_GET['token']);
      $usuario = Usuario::where('token', $token);

      if(empty($usuario)){
        Usuario::setAlerta('error', 'Token no válido');
      } else {
        // Cambiamos el valor de confirmado a 1
        $usuario->confirmado = "1";

        // Eliminamos el token
        $usuario->token = null;

        // Actualizamos los datos de la DB
        $usuario->guardar();

        Usuario::setAlerta('exito', 'Cuenta verificada correctamente');
      }

      // Obtenemos las alertas
      $alertas = Usuario::getAlertas();

      $router->render('auth/confirmar-cuenta', [
        'alertas' => $alertas
      ]);
    }
  }
?>