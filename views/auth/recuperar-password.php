<h1 class="nombre-pagina">Recuperar Password</h1>
<p class="descripcion-pagina">Coloca tu nuevo password a continuacion</p>

<?php include_once __DIR__ . "/../templates/alertas.php"; ?>

<!-- Cambiamos el estado a true para que nos de como verdadera esta condicion que al usar return antes del formuarlio no se mostrara -->
<?php if($error) return;  ?>

<form method="POST" class="formulario">
  <div class="campo">
    <label for="password">Password</label>
    <input type="password" name="password" id="password" placeholder="Ingresa tu nueva password">
  </div> 

  <input type="submit" value="Guardar" class="boton">
</form>

<div class="acciones">
  <a href="/crear-cuenta">¿No tienes una cuenta? Regístrate aquí</a>
  <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
</div>