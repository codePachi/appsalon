<h1 class="nombre-pagina">Recuperar cuenta</h1>
<p class="descripcion-pagina">Completa los siguientes datos para reestablecer tu cuenta</p>

<?php include_once __DIR__ . "/../templates/alertas.php"; ?>

<form action="/olvidar" method="POST" class="formulario">
  <div class="campo">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" placeholder="Escribe tu email">
  </div>

  <input type="submit" value="Enviar Instrucciones" class="boton">
</form>

<div class="acciones">
  <a href="/crear-cuenta">¿No tienes una cuenta? Regístrate aquí</a>
  <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
</div>