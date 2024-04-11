<?php
  namespace Classes;

  use PHPMailer\PHPMailer\PHPMailer;

  class Email {
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token){
      $this->email = $email;
      $this->nombre = $nombre;
      $this->token = $token;
    }

    // Funcion para enviar confirmacion de email
    public function enviarConfirmacion(){

      // Objeto del cuerpo del email
      $mail = new PHPMailer();
      $mail->isSMTP();
      $mail->Host = $_ENV['EMAIL_HOST'];
      $mail->SMTPAuth = true;
      $mail->Port = $_ENV['EMAIL_PORT'];
      $mail->Username = $_ENV['EMAIL_USER'];
      $mail->Password = $_ENV['EMAIL_PASS'];
      
      // Datos de envio y recepcion
      $mail->setFrom('cuentas@appsalon.com');
      $mail->addAddress($this->email, $this->nombre);
      $mail->Subject = 'Confirma tu cuenta';

      // Set HTML
      $mail->isHTML(true);
      $mail->CharSet = 'UTF-8';

      // Cuerpo del mail
      $contenido = "<html>";
      $contenido .= "<p><strong>Hola " . $this->nombre . "!</strong> bienvenido a AppSalon! Para finalizar el proceso de creacion de tu cuenta debes darle click al siguiente enlace:</p>";
      $contenido .= "<a href='" . $_ENV['APP_URL'] . "/confirmar-cuenta?token=" . $this->token . " '>Confirmar cuenta</a>";
      $contenido .= "<p>Si tu no solicitaste esta cuenta puedes ignorar este mensaje</p>";
      $contenido .= "</html>";

      // Seleccionamos $contenido para el Body
      $mail->Body = $contenido;

      // Enviar email
      $mail->send();
    }

    // Funcion para enviar instrucciones
    public function enviarInstrucciones(){
      $mail = new PHPMailer();
      $mail->isSMTP();
      $mail->Host = $_ENV['EMAIL_HOST'];
      $mail->SMTPAuth = true;
      $mail->Port = $_ENV['EMAIL_PORT'];
      $mail->Username = $_ENV['EMAIL_USER'];
      $mail->Password = $_ENV['EMAIL_PASS'];

      $mail->setFrom('cuentas@appsalon.com');
      $mail->addAddress('cuentas@appsalon.com', 'Appsalon.com');
      $mail->Subject = 'Reestablece tu password';

      $mail->isHTML(true);
      $mail->CharSet = 'UTF-8';
    
      $contenido = "<html>";
      $contenido .= "<p><strong>Hola " . $this->nombre . "!</strong> Has solicitado reestablecer tu password, sigue el siguiente enlace para hacerlo:</p>";
      $contenido .= "<a href='" . $_ENV['APP_URL'] . "/recuperar?token=" . $this->token . " '>Reestablecer Password</a>";
      $contenido .= "<p>Si tu no solicitaste esta cambio puedes ignorar este mensaje</p>";
      $contenido .= "</html>";
      
      $mail->Body = $contenido;
      $mail->send();
    }
  }
?>