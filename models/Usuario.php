<?php
  namespace Model;

  class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    // Constructor
    public function __construct($args = []){
      $this->id = $args['id'] ?? null;
      $this->nombre = $args['nombre'] ?? '';
      $this->apellido = $args['apellido'] ?? '';
      $this->email = $args['email'] ?? '';
      $this->password = $args['password'] ?? '';
      $this->telefono = $args['telefono'] ?? '';
      $this->admin = $args['admin'] ?? '0';
      $this->confirmado = $args['confirmado'] ?? '0';
      $this->token = $args['token'] ?? '';
    }

    // Validar campos de creacion de cuenta
    public function validarCuenta(){
      if($this->nombre){
        if(is_numeric($this->nombre)){
          self::$alertas['error'][] = 'El formato de nombre no es válido';
        }
      } else {
        self::$alertas['error'][] = 'El nombre es obligatorio';
      }

      if($this->apellido){
        if(is_numeric($this->apellido)){
          self::$alertas['error'][] = 'El formato de apellido no es válido';
        }
      } else {
        self::$alertas['error'][] = 'El apellido es obligatorio';
      }

      if($this->telefono){
        if(!is_numeric($this->telefono)){
          self::$alertas['error'][] = 'No es un teléfono válido';
        } else if(strlen($this->telefono) !== 10){
          self::$alertas['error'][] = 'El teléfono debe tener 10 caracteres';
        }
      } else {
        self::$alertas['error'][] = 'El teléfono es obligatorio';
      }

      if($this->email){
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
          self::$alertas['error'][] = 'Email no válido';
        }
      } else {
        self::$alertas['error'][] = 'El email es obligatorio';
      }

      if($this->password){
        if(strlen($this->password) < 6){
          self::$alertas['error'][] = 'El password debe tener un mínimo de 6 caracteres';
        }
      } else {
        self::$alertas['error'][] = 'El password es obligatorio';
      }

      return self::$alertas;
    }

    // Validar campos de login
    public function validarLogin(){
      if($this->email){
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
          self::$alertas['error'][] = 'Email no válido';
        }
      } else {
        self::$alertas['error'][] = 'El email es obligatorio';
      }

      if($this->password){
        if(strlen($this->password) < 6){
          self::$alertas['error'][] = 'El password debe tener un mínimo de 6 caracteres';
        }
      } else {
        self::$alertas['error'][] = 'El password es obligatorio';
      }

      return self::$alertas;
    }

    // Validar email
    public function validarEmail(){
      if(!$this->email) {
        self::$alertas['error'][] = 'El email es obligatorio';
      }

      if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
        self::$alertas['error'][] = 'Email no válido';
      }

      return self::$alertas;
    }

    // Validar password
    public function validarPassword(){
      if($this->password){
        if(strlen($this->password) < 6){
          self::$alertas['error'][] = 'El password debe tener un minimo de 6 caracteres';
        }
      } else {
        self::$alertas['error'][] = 'El password es obligatorio';
      }

      return self::$alertas;
    }

    // Funcion para revisar si un usuario existe
    public function existeUsuario(){
      $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1 ";
      $resultado = self::$db->query($query);
      if($resultado->num_rows){
        self::$alertas['error'][] = 'El usuario ya se encuentra registrado';
      }

      return $resultado;
    }

    // Funcion para hashear password
    public function hashPassword(){
      $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Funcion par crear token
    public function crearToken(){
      $this->token = uniqid();
    }

    // Funcion para verificar password
    public function vHashPassword($password){
      $resultado = password_verify($password, $this->password);
      
      // Creamos una condicion para verificar que el password este correcto y que exista el campo confirmado (que no este en NULL)
      if(!$resultado || !$this->confirmado){
        self::$alertas['error'][] = 'El password es incorrecto o la cuenta no esta confirmada';
      } else {
        return true;
      }
    }
  }
?>