
function enviar() {

  const id_portal = idPortal;
  const id_usuario = idUsuario;
  
  const errores = [];  // Asegúrate de declarar la variable errores
  
  // Verificar si se ha aceptado el aviso de privacidad
  const avisoAceptado = document.getElementById('aviso').checked;
  if (!avisoAceptado) {
    Swal.fire({
      title: 'Error',
      text: 'Debes aceptar el aviso de privacidad para poder enviar la información.',
      icon: 'error',
      confirmButtonText: 'Aceptar'
    });
    return;  // Detiene la ejecución si no se ha aceptado el aviso
  }

  const soloLetras = /^[A-ZÁÉÍÓÚÑ ]+$/i;
  const telefonoRegex = /^[0-9+\-\s]+$/;
  // Asegúrate de declarar la variable errores
  let primerErrorEnfocado = false;  // Variable para verificar si ya se ha enfocado un error
  

  // Parte 1 - Datos personales
  const aviso = document.getElementById('aviso').checked;

  const nombre = document.getElementById('nombre').value.trim();
  const paterno = document.getElementById('paterno').value.trim();
  const materno = document.getElementById('materno').value.trim();
  const domicilio = document.getElementById('domicilio').value.trim();
  const fechaNacimiento = document.getElementById('fecha_nacimiento').value;
  const telefono = document.getElementById('telefono').value.trim();
  const nacionalidad = document.getElementById('nacionalidad').value.trim();
  const civil = document.getElementById('civil').value;
  const dependientes = document.getElementById('dependientes').value.trim();
  const gradoEstudios = document.getElementById('grado_estudios').value;

  // Parte 2 - Salud y habilidades
  const salud = document.getElementById('salud').value.trim();
  const enfermedad = document.getElementById('enfermedad').value.trim();
  const deporte = document.getElementById('deporte').value.trim();
  const metas = document.getElementById('metas').value.trim();
  const idiomas = document.getElementById('idiomas').value.trim();
  const maquinas = document.getElementById('maquinas').value.trim();
  const software = document.getElementById('software').value.trim();
  
  // Validación de intereses
  const medio_contacto = document.getElementById("medio_contacto").value.trim();
  const area_interes = document.getElementById("area_interes").value.trim();
  const sueldo_deseado = document.getElementById("sueldo_deseado").value.trim();
  const otros_ingresos = document.getElementById("otros_ingresos").value.trim();
  const viajar = document.getElementById("viajar").value.trim();
  const trabajar = document.getElementById("trabajar").value.trim();
  const comentario = document.getElementById("comentario").value.trim(); // opcional
 
  
  // Validación de empleos
  const empleos = [];

  for (let i = 1; i <= 2; i++) {
    const empresa = document.getElementById(`empresa${i}`).value.trim();
    const periodo = document.getElementById(`periodo${i}`).value.trim();
    const puesto = document.getElementById(`puesto${i}`).value.trim();
    const sueldo = document.getElementById(`sueldo${i}`).value.trim();
    const causa = document.getElementById(`causa_separacion${i}`).value.trim();
    const telefonoRef = document.getElementById(`telefono_empleo${i}`).value.trim();

    const algunCampoLleno = empresa || periodo || puesto || sueldo || causa || telefonoRef;

    if (i === 1) {
      // Empleo 1 es obligatorio
      if (!empresa || !periodo || !puesto || !sueldo || !causa || !telefonoRef) {
        errores.push("Todos los campos del Empleo #1 son obligatorios.");
        if (!primerErrorEnfocado) {
          document.getElementById(`empresa${i}`).focus();  // Enfoque en el primer campo incorrecto
          primerErrorEnfocado = true; // Asegurarse de que solo se enfoque el primer error
        }
      }
    }

    if (i === 1 || algunCampoLleno) {
      // Validación general si se llena cualquier campo (o si es el primero)
      if (!telefonoRegex.test(telefonoRef)) {
        errores.push(`Teléfono del empleo #${i} inválido.`);
        if (!primerErrorEnfocado) {
          document.getElementById(`telefono_empleo${i}`).focus();  // Enfoque en el campo incorrecto
          primerErrorEnfocado = true;
        }
      }

      empleos.push({
        empresa,
        periodo,
        puesto,
        sueldo,
        causa_separacion: causa,
        telefono: telefonoRef
      });
    }
  }

  // Validaciones personales
  if (!nombre || !soloLetras.test(nombre)) {
    errores.push('Nombre inválido.');
    if (!primerErrorEnfocado) {
      document.getElementById('nombre').focus();  // Enfoque en el campo con error
      primerErrorEnfocado = true;
    }
  }
  if (!paterno || !soloLetras.test(paterno)) {
    errores.push('Primer apellido inválido.');
    if (!primerErrorEnfocado) {
      document.getElementById('paterno').focus();  // Enfoque en el campo con error
      primerErrorEnfocado = true;
    }
  }
  if (materno && !soloLetras.test(materno)) {
    errores.push('Segundo apellido inválido.');
    if (!primerErrorEnfocado) {
      document.getElementById('materno').focus();  // Enfoque en el campo con error
      primerErrorEnfocado = true;
    }
  }
  if (!aviso) {
    errores.push('Aceptar Aviso de privacidad por favor ');
    if (!primerErrorEnfocado) {
      document.getElementById('aviso').focus();  // Enfoque en el campo con error
      primerErrorEnfocado = true;
    }
  }
  if (!domicilio) {
    errores.push('Domicilio requerido.');
    if (!primerErrorEnfocado) {
      document.getElementById('domicilio').focus();  // Enfoque en el campo con error
      primerErrorEnfocado = true;
    }
  }
  if (!fechaNacimiento || new Date(fechaNacimiento) >= new Date()) {
    errores.push('Fecha de nacimiento inválida.');
    if (!primerErrorEnfocado) {
      document.getElementById('fecha_nacimiento').focus();  // Enfoque en el campo con error
      primerErrorEnfocado = true;
    }
  }
  if (!telefono || !telefonoRegex.test(telefono)) {
    errores.push('Teléfono inválido.');
    if (!primerErrorEnfocado) {
      document.getElementById('telefono').focus();  // Enfoque en el campo con error
      primerErrorEnfocado = true;
    }
  }
  if (!nacionalidad) {
    errores.push('Nacionalidad requerida.');
    if (!primerErrorEnfocado) {
      document.getElementById('nacionalidad').focus();  // Enfoque en el campo con error
      primerErrorEnfocado = true;
    }
  }
  if (!civil) {
    errores.push('Selecciona estado civil.');
    if (!primerErrorEnfocado) {
      document.getElementById('civil').focus();  // Enfoque en el campo con error
      primerErrorEnfocado = true;
    }
  }
  if (!dependientes) {
    errores.push('Dependientes inválido.');
    if (!primerErrorEnfocado) {
      document.getElementById('dependientes').focus();  // Enfoque en el campo con error
      primerErrorEnfocado = true;
    }
  }
  if (!gradoEstudios) {
    errores.push('Selecciona grado de estudios.');
    if (!primerErrorEnfocado) {
      document.getElementById('grado_estudios').focus();  // Enfoque en el campo con error
      primerErrorEnfocado = true;
    }
  }

  // Validaciones salud y habilidades
  if (!salud) {
    errores.push('Estado de salud requerido.');
    if (!primerErrorEnfocado) {
      document.getElementById('salud').focus();  // Enfoque en el campo con error
      primerErrorEnfocado = true;
    }
  }
  if (!enfermedad) {
    errores.push('Especifica enfermedad crónica (usa "NINGUNA" si no aplica).');
    if (!primerErrorEnfocado) {
      document.getElementById('enfermedad').focus();  // Enfoque en el campo con error
      primerErrorEnfocado = true;
    }
  }
  if (!deporte) {
    errores.push('Especifica algún deporte.');
    if (!primerErrorEnfocado) {
      document.getElementById('deporte').focus();  // Enfoque en el campo con error
      primerErrorEnfocado = true;
    }
  }
  if (!metas) {
    errores.push('Debes escribir tus metas en la vida.');
    if (!primerErrorEnfocado) {
      document.getElementById('metas').focus();  // Enfoque en el campo con error
      primerErrorEnfocado = true;
    }
  }
  if (!idiomas) {
    errores.push('Indica los idiomas que dominas.');
    if (!primerErrorEnfocado) {
      document.getElementById('idiomas').focus();  // Enfoque en el campo con error
      primerErrorEnfocado = true;
    }
  }
  if (!maquinas) {
    errores.push('Especifica qué máquinas sabes manejar.');
    if (!primerErrorEnfocado) {
      document.getElementById('maquinas').focus();  // Enfoque en el campo con error
      primerErrorEnfocado = true;
    }
  }
  if (!software) {
    errores.push('Indica qué software conoces.');
    if (!primerErrorEnfocado) {
      document.getElementById('software').focus();  // Enfoque en el campo con error
      primerErrorEnfocado = true;
    }
  }

  if (errores.length > 0) {
    Swal.fire({
      title: '¡Corrige los siguientes errores!',
      icon: 'error',
      html: errores.join('<br>'),
      confirmButtonText: 'Aceptar'
    });
    return;
  }

  // Construimos el payload final
  const data = {
    aviso,
    id_usuario,
    id_portal,
    nombre,
    paterno,
    materno,
    domicilio,
    fecha_nacimiento: fechaNacimiento,
    telefono,
    nacionalidad,
    civil,
    dependientes,
    grado_estudios: gradoEstudios,
    salud,
    enfermedad,
    deporte,
    metas,
    idiomas,
    maquinas,
    software,
    medio_contacto,
    area_interes,
    sueldo_deseado,
    otros_ingresos,
    viajar,
    trabajar,
    comentario,
    empleos,
  };

  console.log(JSON.stringify(data, null, 2));
  // Enviamos a la API (ajusta la URL si es necesario)
  fetch('/api/registro', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
    body: JSON.stringify(data)
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Error al registrar');
    }
    return response.json();
  })
  .then(result => {
    console.log('✔️ Registro exitoso:', result.message);
    
    // Usamos SweetAlert para mostrar el mensaje de éxito
    Swal.fire({
      title: '¡Éxito!',
      text: result.message,
      icon: 'success',
      confirmButtonText: 'Aceptar'
    });
  })
  .catch(error => {
    console.error('❌ Error en el registro:', error);
  
    // SweetAlert para errores
    Swal.fire({
      title: 'Error',
      text: 'Hubo un problema al registrar, intente nuevamente.',
      icon: 'error',
      confirmButtonText: 'Aceptar'
    });
  });
  
}


