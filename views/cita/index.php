<h1 class="nombre-pagina">Crear Nueva Cita</h1>

<?php include_once __DIR__ . '../../templates/barra.php'; ?>

<p class="descripcion-pagina">Completa la información</p>

<div id="app">
  <!-- Botones de navegacion -->
  <nav class="tabs">
    <button type="button" data-paso="1">Servicios</button>
    <button type="button" data-paso="2">Información Cita</button>
    <button type="button" data-paso="3">Resumen</button>
  </nav>

  <div id="paso-1" class="seccion">
    <h2>Servicios</h2>
    <p class="text-center">Elige tus servicios a continuacion</p>
    <div id="servicios" class="listado-servicios"></div>
  </div>

  <div id="paso-2" class="seccion">
    <h2>Tus Datos y Cita</h2>
    <p class="text-center">Coloca tus datos y fecha de tu cita</p>
    <form class="formulario">
      <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" value="<?php echo $nombre; ?>" disabled>
      </div>

      <div class="campo">
        <label for="fecha">Fecha</label>
        <input type="date" id="fecha" min= "<?php echo date('Y-m-d'); ?>">
      </div>

      <div class="campo">
        <label for="hora">Hora</label>
        <input type="time" id="hora">
        <input type="hidden" id= 'id' value="<?php echo $id; ?>">
      </div>
    </form>
  </div>

  <div id="paso-3" class="seccion contenido-resumen">
    <h2>Resumen</h2>
    <p class="text-center">Verifica que la información sea corecta</p>
  </div>

  <div class="paginacion">
    <button id="anterior" class="boton">&laquo; Anterior</button>
    <button id="siguiente" class="boton">Siguiente &raquo;</button>
  </div>

  <?php
    $script = "
      <script src='build/js/app.js'></script>
      <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    "; 
  ?>
</div>