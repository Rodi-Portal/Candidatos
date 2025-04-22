function enviar() {
  const id_portal   = idPortal;   // Debe venir de tu contexto
  console.log("🚀 ~ enviar ~ id_portal:", id_portal)
  const id_usuario  = idUsuario;
  console.log("🚀 ~ enviar ~ id_usuario:", id_usuario)

  

  const errores = [];
  let primerErrorEnfocado = false;

  // 1) id_portal e id_usuario: required|numeric
  if (!id_portal || isNaN(Number(id_portal))) {
    errores.push('Portal inválido.');
  }
  if (!id_usuario || isNaN(Number(id_usuario))) {
    errores.push('Usuario inválido.');
  }

  // 2) aviso: accepted
  const aviso = document.getElementById('aviso').checked;
  if (!aviso) {
    errores.push('Debes aceptar el aviso de privacidad.');
    if (!primerErrorEnfocado) {
      document.getElementById('aviso').focus();
      primerErrorEnfocado = true;
    }
  }

  // Regex que repiten: letras y espacios, y teléfono
  const soloLetras = /^[A-ZÁÉÍÓÚÑ ]+$/i;             // para nombre/apellidos
  const telefonoRegex = /^[0-9+\-\s]+$/;            // para teléfonos

  // 3) nombre, paterno, materno: regex:/^[A-ZÁÉÍÓÚÑ ]+$/i
  const nombre  = document.getElementById('nombre').value.trim();
  const paterno = document.getElementById('paterno').value.trim();
  const materno = document.getElementById('materno').value.trim();
  if (!nombre || !soloLetras.test(nombre)) {
    errores.push('Nombre inválido.');
    if (!primerErrorEnfocado) {
      document.getElementById('nombre').focus();
      primerErrorEnfocado = true;
    }
  }
  if (!paterno || !soloLetras.test(paterno)) {
    errores.push('Primer apellido inválido.');
    if (!primerErrorEnfocado) {
      document.getElementById('paterno').focus();
      primerErrorEnfocado = true;
    }
  }
  if (materno && !soloLetras.test(materno)) {
    errores.push('Segundo apellido inválido.');
    if (!primerErrorEnfocado) {
      document.getElementById('materno').focus();
      primerErrorEnfocado = true;
    }
  }

  // 4) domicilio: required|string
  const domicilio = document.getElementById('domicilio').value.trim();
  if (!domicilio) {
    errores.push('Domicilio requerido.');
    if (!primerErrorEnfocado) {
      document.getElementById('domicilio').focus();
      primerErrorEnfocado = true;
    }
  }

  // 5) fecha_nacimiento: required|date|before:today
  const fechaNacimiento = document.getElementById('fecha_nacimiento').value;
  if (!fechaNacimiento || new Date(fechaNacimiento) >= new Date()) {
    errores.push('Fecha de nacimiento inválida.');
    if (!primerErrorEnfocado) {
      document.getElementById('fecha_nacimiento').focus();
      primerErrorEnfocado = true;
    }
  }

  // 6) telefono: required|regex:/^[0-9+\-\s]+$/
  const telefono = document.getElementById('telefono').value.trim();
  if (!telefono || !telefonoRegex.test(telefono)) {
    errores.push('Teléfono inválido.');
    if (!primerErrorEnfocado) {
      document.getElementById('telefono').focus();
      primerErrorEnfocado = true;
    }
  }

  // 7) nacionalidad, civil, dependientes, grado_estudios: required|string
  const nacionalidad  = document.getElementById('nacionalidad').value.trim();
  const civil         = document.getElementById('civil').value;
  const dependientes  = document.getElementById('dependientes').value.trim();
  const gradoEstudios = document.getElementById('grado_estudios').value;
  if (!nacionalidad) {
    errores.push('Nacionalidad requerida.');
    if (!primerErrorEnfocado) {
      document.getElementById('nacionalidad').focus();
      primerErrorEnfocado = true;
    }
  }
  if (!civil) {
    errores.push('Selecciona estado civil.');
    if (!primerErrorEnfocado) {
      document.getElementById('civil').focus();
      primerErrorEnfocado = true;
    }
  }
  if (!dependientes) {
    errores.push('Dependientes inválido.');
    if (!primerErrorEnfocado) {
      document.getElementById('dependientes').focus();
      primerErrorEnfocado = true;
    }
  }
  if (!gradoEstudios) {
    errores.push('Selecciona grado de estudios.');
    if (!primerErrorEnfocado) {
      document.getElementById('grado_estudios').focus();
      primerErrorEnfocado = true;
    }
  }

  // 8) salud, enfermedad, deporte, metas, idiomas, maquinas, software: required|string
  const salud      = document.getElementById('salud').value.trim();
  const enfermedad = document.getElementById('enfermedad').value.trim();
  const deporte    = document.getElementById('deporte').value.trim();
  const metas      = document.getElementById('metas').value.trim();
  const idiomas    = document.getElementById('idiomas').value.trim();
  const maquinas   = document.getElementById('maquinas').value.trim();
  const software   = document.getElementById('software').value.trim();
  if (!salud) {
    errores.push('Estado de salud requerido.');
    if (!primerErrorEnfocado) {
      document.getElementById('salud').focus();
      primerErrorEnfocado = true;
    }
  }
  if (!enfermedad) {
    errores.push('Especifica enfermedad crónica (o "NINGUNA").');
    if (!primerErrorEnfocado) {
      document.getElementById('enfermedad').focus();
      primerErrorEnfocado = true;
    }
  }
  if (!deporte) {
    errores.push('Especifica algún deporte.');
    if (!primerErrorEnfocado) {
      document.getElementById('deporte').focus();
      primerErrorEnfocado = true;
    }
  }
  if (!metas) {
    errores.push('Debes escribir tus metas.');
    if (!primerErrorEnfocado) {
      document.getElementById('metas').focus();
      primerErrorEnfocado = true;
    }
  }
  if (!idiomas) {
    errores.push('Indica los idiomas que dominas.');
    if (!primerErrorEnfocado) {
      document.getElementById('idiomas').focus();
      primerErrorEnfocado = true;
    }
  }
  if (!maquinas) {
    errores.push('Especifica qué máquinas sabes manejar.');
    if (!primerErrorEnfocado) {
      document.getElementById('maquinas').focus();
      primerErrorEnfocado = true;
    }
  }
  if (!software) {
    errores.push('Indica qué software conoces.');
    if (!primerErrorEnfocado) {
      document.getElementById('software').focus();
      primerErrorEnfocado = true;
    }
  }

  // 9) medio_contacto, area_interes: required|string
  const medio_contacto = document.getElementById('medio_contacto').value.trim();
  const area_interes   = document.getElementById('area_interes').value.trim();
  if (!medio_contacto) {
    errores.push('Medio de contacto requerido.');
    if (!primerErrorEnfocado) {
      document.getElementById('medio_contacto').focus();
      primerErrorEnfocado = true;
    }
  }
  if (!area_interes) {
    errores.push('Área de interés requerida.');
    if (!primerErrorEnfocado) {
      document.getElementById('area_interes').focus();
      primerErrorEnfocado = true;
    }
  }

  // 10) sueldo_deseado: required|numeric|min:0
  const sueldoDeseado = parseFloat(document.getElementById('sueldo_deseado').value.trim());
  if (isNaN(sueldoDeseado) || sueldoDeseado < 0) {
    errores.push('Sueldo deseado inválido.');
    if (!primerErrorEnfocado) {
      document.getElementById('sueldo_deseado').focus();
      primerErrorEnfocado = true;
    }
  }

  // 11) otros_ingresos, viajar, trabajar: required|string
  const otros_ingresos = document.getElementById('otros_ingresos').value.trim();
  const viajar         = document.getElementById('viajar').value.trim();
  const trabajar       = document.getElementById('trabajar').value.trim();
  if (!otros_ingresos) {
    errores.push('Otros ingresos requeridos.');
    if (!primerErrorEnfocado) {
      document.getElementById('otros_ingresos').focus();
      primerErrorEnfocado = true;
    }
  }
  if (!viajar) {
    errores.push('Debes indicar si puedes viajar.');
    if (!primerErrorEnfocado) {
      document.getElementById('viajar').focus();
      primerErrorEnfocado = true;
    }
  }
  if (!trabajar) {
    errores.push('Debes indicar si puedes trabajar fines de semana.');
    if (!primerErrorEnfocado) {
      document.getElementById('trabajar').focus();
      primerErrorEnfocado = true;
    }
  }

  // 12) empleos: array + validaciones de cada objeto
  const empleos = [];
  for (let i = 1; i <= 2; i++) {
    const empresa   = document.getElementById(`empresa${i}`).value.trim();
    const periodo   = document.getElementById(`periodo${i}`).value.trim();
    const puesto    = document.getElementById(`puesto${i}`).value.trim();
    const sueldo    = document.getElementById(`sueldo${i}`).value.trim();
    const causa     = document.getElementById(`causa_separacion${i}`).value.trim();
    const telEmpleo = document.getElementById(`telefono_empleo${i}`).value.trim();

    const alguno = empresa || periodo || puesto || sueldo || causa || telEmpleo;

    if (i === 1 && !alguno) {
      errores.push('Todos los campos del Empleo #1 son obligatorios.');
      if (!primerErrorEnfocado) {
        document.getElementById(`empresa${i}`).focus();
        primerErrorEnfocado = true;
      }
    }

    if (alguno) {
      // telefono
      if (!telefonoRegex.test(telEmpleo)) {
        errores.push(`Teléfono del empleo #${i} inválido.`);
        if (!primerErrorEnfocado) {
          document.getElementById(`telefono_empleo${i}`).focus();
          primerErrorEnfocado = true;
        }
      }
      // sueldo: numeric|min:0
      const su = parseFloat(sueldo);
      if (isNaN(su) || su < 0) {
        errores.push(`Sueldo del empleo #${i} inválido.`);
        if (!primerErrorEnfocado) {
          document.getElementById(`sueldo${i}`).focus();
          primerErrorEnfocado = true;
        }
      }

      empleos.push({
        empresa,
        periodo,
        puesto,
        sueldo: su,
        causa_separacion: causa,
        telefono: telEmpleo
      });
    }
  }

  // Mostrar errores si los hay
  if (errores.length) {
    Swal.fire({
      title: '¡Corrige los siguientes errores!',
      icon: 'error',
      html: errores.join('<br>'),
      confirmButtonText: 'Aceptar'
    });
    return;
  }

  // Si pasa todo, construyes y envías el payload:
  const data = {
    id_portal,
    id_usuario,
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
    sueldo_deseado: sueldoDeseado,
    otros_ingresos,
    viajar,
    trabajar,
    aviso,
    empleos
  };

  fetch('/api/registro', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(data)
  })
  .then(r => r.ok ? r.json() : Promise.reject(r))
  .then(json => {
    Swal.fire('¡Éxito!', json.message, 'success');
  })
  .catch(() => {
    Swal.fire('Error','Hubo un problema al registrar.','error');
  });
}


