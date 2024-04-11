// Creamos esta variable para que muestre siempre la primer seccion
let paso = 1;

// Creamos limitadores para poder realizar la paginacion correctamente
const pasoIni = 1;
const pasoFin = 3;

const cita = {
  id: '',
  nombre: '',
  fecha: '',
  hora: '',
  servicios: []
};

// Funcion principal para llamar a la funcion que unifica todas las demas
document.addEventListener('DOMContentLoaded', function(){
  iniciarApp();
});

function iniciarApp(){
  tabs(); // Cambia la seccion cuando se presionen los tabs
  mostrarSeccion(); // Al llamar a la funcion cuando este el DOM cargara el paso que tengamos definido en la variable de arriba 'let = paso'
  botonPaginador(); // Agrega o quita los botones del paginador
  seccionAnt(); // Permite ir a las secciones anteriores
  seccionSig(); // Permite ir a las secciones siguientes
  consultaAPI(); // Consulta la API en el backend de PHP
  idCliente(); // Añade el id del cliente al objeto de cita
  nombeCliente(); // Añade el nombre del cliente al objeto de cita
  seleccionarFecha(); // Añade la fecha de la cita en el objeto
  seleccionarHora(); // Añade la hora en la cita
  mostrarResumen(); // Mostrar resumen de la cita
};

function mostrarSeccion(){
  const seccionAnt = document.querySelector('.mostrar');

  if(seccionAnt){
    seccionAnt.classList.remove('mostrar');
  }

  // Creamos el template string que tendra la etiqueta id del elemento seleccionado unido con el valor obtenido en tabs()
  const pasoSelector = `#paso-${paso}`;
  // Al crear este selector podemos seleccionar el boton que necesitamos sin necesidad de crear una variable para cada boton ya que el valor numerico ira cambiando gracias a la variable paso en tabs()
  const seccion = document.querySelector(pasoSelector);
  seccion.classList.add('mostrar');

  // Ocultamos el tab que tenga la clase actual
  const tabAnt = document.querySelector('.actual');

  if(tabAnt){
    tabAnt.classList.remove('actual');
  }

  // Resaltar el tab actual
  const tabSelector = `[data-paso="${paso}"]`; 
  const tab = document.querySelector(tabSelector);
  tab.classList.add('actual');
}

function tabs(){
  // Seleccionamos los botones de tabs 
  const botones = document.querySelectorAll('.tabs button');

  botones.forEach( boton => {
    boton.addEventListener('click', function(e){
      paso = parseInt(e.target.dataset.paso);
      mostrarSeccion();
      botonPaginador();
    });
  });
};

function botonPaginador(){
  const paginaAnterior = document.querySelector('#anterior');
  const paginaSiguiente = document.querySelector('#siguiente');

  if(paso === 1){
    paginaAnterior.classList.add('ocultar');
    paginaSiguiente.classList.remove('ocultar');
  } else if(paso === 3) {
    paginaAnterior.classList.remove('ocultar');
    paginaSiguiente.classList.add('ocultar');
    mostrarResumen();
  } else {
    paginaAnterior.classList.remove('ocultar');
    paginaSiguiente.classList.remove('ocultar');
  }

  mostrarSeccion();
}

function seccionAnt(){
  const paginaAnterior = document.querySelector('#anterior');

  paginaAnterior.addEventListener('click', function(){
    if(paso <= pasoIni) return;
    paso--;

    botonPaginador();
  });
}

function seccionSig(){
  const paginaSiguiente = document.querySelector('#siguiente');

  paginaSiguiente.addEventListener('click', function(){
    if(paso >= pasoFin) return;
    paso++;

    botonPaginador();
  });
}

// FUNCION CON ASYNC AWAIT
async function consultaAPI(){
  try {
    const url = `${location.origin}/api/servicios`;
    const resultado = await fetch(url);
    const servicios = await resultado.json();
    mostrarServicios(servicios);
  } catch(error){
    console.log(error);
  }
}

// Funcion para mostrar los servicios de consultaAPI()
function mostrarServicios(servicios){
  servicios.forEach(function(servicio){
    const {id, nombre, precio} = servicio;

    // NOMBRE
    const nombreServicio = document.createElement('P');
    nombreServicio.classList.add('nombre-servicio');
    nombreServicio.textContent = nombre;

    // PRECIO
    const precioServicio = document.createElement('P');
    precioServicio.classList.add('precio-servicio');
    precioServicio.textContent = `$ ${precio}`;
    
    // DIV COMO CONTENEDOR
    const divServicio = document.createElement('DIV');
    divServicio.classList.add('servicio');

    divServicio.dataset.idServicio = id; 
    divServicio.onclick = function() {
      seleccionarServicio(servicio);
    };

    divServicio.appendChild(nombreServicio);
    divServicio.appendChild(precioServicio);

    // Agregamos el div con los otros elementos al listado de servicios
    document.querySelector('#servicios').appendChild(divServicio);
  })
}

// Funcion de seleccion de servicios
function seleccionarServicio(servicio){
  const {id} = servicio;
  // Extraemos el array de servicios
  const {servicios} = cita;

  // Creamos la variable con el div que se selecciona
  const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

  if(servicios.some(agregado => agregado.id === id)){
    // ELIMINAR SERVICIO
    cita.servicios = servicios.filter(agregado => agregado.id !== id);
    divServicio.classList.remove('seleccionado');
  } else {
    // AGREGAR SERVICIO
    cita.servicios = [...servicios, servicio];
    divServicio.classList.add('seleccionado');
  }
}

function idCliente(){
  const id = document.querySelector('#id').value;
  cita.id = id;
}

function nombreCliente(){
  const nombre = document.querySelector('#nombre').value;
  cita.nombre = nombre;
}

function seleccionarFecha(){
  const inputFecha = document.querySelector('#fecha');

  inputFecha.addEventListener('input', function(e){
    const dia = new Date(e.target.value).getUTCDay();

    if([0,6].includes(dia)){
      e.target.value = '';
      mostrarAlerta('Sabados y Domingos cerrado', 'error', '.formulario');
    } else {
      cita.fecha = e.target.value;
    }
  });
};

function seleccionarHora(){
  const inputHora = document.querySelector('#hora');

  inputHora.addEventListener('input', function(e){
    const horario = e.target.value;
    const hora = parseInt(horario.split(':')[0]);

    if(hora < 9 || hora > 18) {
      e.target.value = '';
      mostrarAlerta('Los horarios disponibles son de 09 a 18', 'error', '.formulario');
    } else {
      cita.hora = e.target.value;
    };
  });
};

function mostrarAlerta(mensaje, tipo, elemento, dessaparecer = true){
  const alertaPrevia = document.querySelector('.alerta');

  if(alertaPrevia){
    alertaPrevia.remove();
  };

  // Creamos el mensaje de alerta
  const alerta = document.createElement('DIV');
  alerta.textContent = mensaje;
  alerta.classList.add('alerta');

  // Clase para agregar separacion del msj de error
  alerta.classList.add('separado');
  alerta.classList.add(tipo);

  // Mostramos el mensaje en el formulario
  const referencia = document.querySelector(elemento);
  referencia.appendChild(alerta);

  if(dessaparecer){
    setTimeout(() => {
      alerta.remove();
    }, 2000);
  };
};

function mostrarResumen() {
  const resumen = document.querySelector('.contenido-resumen');

  // Limpiar el contenido de resumen
  while(resumen.firstChild){
    resumen.removeChild(resumen.firstChild);
  }

  const valResumen = Object.values(cita);

  if(valResumen.includes('') || cita.servicios.length === 0){
    mostrarAlerta('Faltan datos o servicios', 'error', '.contenido-resumen', false);
    return;
  };

  const {nombre, fecha, hora, servicios} = cita;

  // Heading servicios
  const headingS = document.createElement('H3');
  headingS.textContent = 'Resumen de Servicios';
  resumen.appendChild(headingS);

  // Servicios
  servicios.forEach(servicio => {
    const {id, nombre, precio} = servicio;

    const contenedorServicio = document.createElement('DIV');
    contenedorServicio.classList.add('contenedor-servicio');

    const nombreServicio = document.createElement('P');
    nombreServicio.textContent = nombre;

    const precioServicio = document.createElement('P');
    precioServicio.innerHTML = `<span>Precio:</span> $ ${precio}`;

    contenedorServicio.appendChild(nombreServicio);
    contenedorServicio.appendChild(precioServicio);
    
    resumen.appendChild(contenedorServicio);
  });

  // Heading cita
  const headingC = document.createElement('H3');
  headingC.textContent = 'Resumen de Cita';
  resumen.appendChild(headingC);

  // Cliente
  const nombreCliente = document.createElement('P');
  nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

  // Formatear la fecha en español
  const fechaObj = new Date(fecha);
  const dia = fechaObj.getDate() + 2; 
  const mes = fechaObj.getMonth(); 
  const año = fechaObj.getFullYear();
  const fechaUTC = new Date(Date.UTC(año, mes, dia));

  const opciones = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
  const fechaNueva = fechaUTC.toLocaleDateString('es-AR', opciones);

  const fechaCliente = document.createElement('P');
  fechaCliente.innerHTML = `<span>Fecha:</span> ${fechaNueva}`;

  const horaCliente = document.createElement('P');
  horaCliente.innerHTML = `<span>Hora:</span> ${hora} hs`;

  // Boton para registrar cita
  const botonReserva = document.createElement('BUTTON');
  botonReserva.classList.add('boton');
  botonReserva.textContent = 'Reservar cita';
  botonReserva.onclick = reservarCita; // Recordar que con onclick no se debe colocar los parentesis en la funcion, en este caso reservarCita

  resumen.appendChild(nombreCliente);
  resumen.appendChild(fechaCliente);
  resumen.appendChild(horaCliente);
  resumen.appendChild(botonReserva);
};

// Reserva de cita
async function reservarCita(){
  const datos = new FormData();
  const {id, fecha, hora, servicios} = cita;
  const idServicio = servicios.map(servicio => servicio.id);

  datos.append('usuarioid', id);
  datos.append('fecha', fecha);
  datos.append('hora', hora);
  datos.append('servicios', idServicio);

  try {
    const url = `${location.origin}/api/citas`;
    const respuesta = await fetch(url, {
      method : 'POST',
      body : datos
    });
  
    const result = await respuesta.json();

    if(result.resultado){
      Swal.fire({
        icon: "success",
        title: "Cita Creada!",
        text: "Tu cita se a creado correctamente"
      }).then(() => {
        setTimeout(() => {
          window.location.reload();
        }, 2000);
      });
    };
    
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error!",
      text: "Hubo un error al guardar la cita"
    });
  }
}