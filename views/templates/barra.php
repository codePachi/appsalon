<div class="barra">
  <p>Hola <?php echo $nombre ?? ''; ?>!</p>
  <a href="/logout" class="boton">Cerrar Sesión</a>
</div>

<?php if(isset($_SESSION['admin'])){ ?>
  <div class="barra-servicios">
    <a href="/admin" class="boton">Citas</a>
    <a href="/servicios" class="boton">Servicios</a>
    <a href="/servicios/crear" class="boton">Nuevo Servicio</a>
  </div>
<?php }; ?>