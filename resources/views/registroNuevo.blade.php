{{-- resources/views/ingresos/nuevoIngreso.blade.php --}}
@extends('layouts.appIngreso')

@section('content')
{{-- Barra superior (hero) --}}
<div class="hero-bar">
  <div class="container position-relative">
    <div class="hero-logo">
      <img src="{{ url('logo/'.( session('logo', 'portal_icon.png') )) }}" alt="Logo Cliente">
    </div>
  </div>
</div>

<div class="container modern-form">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      {{-- Cambiamos las clases del card --}}
      <div class="card card-modern shadow-lg">

        <br><br>
        <h1 class="title mb-2">Formulario</h1>
        <form method="POST" action="{{ route('registro.storeNuevo') }}">
          @csrf
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label">Nombre del Asociado o postulante</label>
              <input type="text" name="nombre" class="form-control" placeholder="Escriba su nombre y apellido" required>
            </div>
            <div class="mb-3">
              <label class="form-label">N° de cédula</label>
              <input type="text" name="cedula" class="form-control" placeholder="Escriba su número de cédula" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Fecha de nacimiento*</label>
              <input type="date" name="fecha_nacimiento" class="form-control" required>
            </div>
            <div class="mb-3">

              <label class="form-label">Teléfono de contacto</label>
              <input id="telefono" type="tel" name="telefono" class="form-control"
                placeholder="Indique su número de teléfono" required>


              <small id="lada-info" class="form-text text-muted"></small>
            </div>
            <div class="mb-3">
              <label class="form-label">Correo electrónico</label>
              <input type="email" name="correo" class="form-control" placeholder="Indica tu correo electronico"
                required>
            </div>
            <div class="mb-3">
              <label class="form-label">Dirección actual</label>
              <input type="text" name="direccion" class="form-control" placeholder="Indique su dirección" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Estado en que resides actualmente*</label>
              <input type="text" name="estado" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">¿Conoces tu proveedor de internet? ¿Cómo se llama?</label>
              <input type="text" name="proveedor_internet" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">¿Tu proveedor de internet te brinda el servicio a través de fibra
                óptica?*</label>
              <select name="fibra_optica" class="form-control" required>
                <option value="">Selecciona</option>
                <option value="si">Sí</option>
                <option value="no">No</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">¿Tu servicio de fibra es por antena o por cable de fibra?*</label>
              <select name="tipo_fibra" class="form-control" required>
                <option value="">Selecciona</option>
                <option value="antena">Antena</option>
                <option value="cable">Cable de fibra</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">¿Cuál es el plan contratado con ellos o de cuantos megas es el plan?*</label>
              <input type="text" name="plan_internet" class="form-control"
                placeholder="Indique la velocidad de su servicio de internet" required>
            </div>
            <div class="mb-3">
              <label class="form-label">¿Cuentas con cámara en el pc?</label>
              <select name="camara_pc" class="form-control">
                <option value="si">Sí</option>
                <option value="no" selected>No</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">¿Cuentas con micrófono en el pc?</label>
              <select name="microfono_pc" class="form-control">
                <option value="si">Sí</option>
                <option value="no" selected>No</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">¿Cuentas con Auriculares del pc, no son los mismos de teléfonos móviles?</label>
              <select name="auriculares_pc" class="form-control">
                <option value="si">Sí</option>
                <option value="no" selected>No</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">¿Tienes racionamiento eléctrico en tu zona?</label>
              <select name="racionamiento" class="form-control">
                <option value="si">Sí</option>
                <option value="no" selected>No</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">En caso de ser sí, describe los tiempos y días de racionamiento
                eléctrico.</label>
              <input type="text" name="detalle_racionamiento" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">¿Cuántas veces ha fallado el servicio eléctrico en las dos últimas
                semanas?</label>
              <input type="number" name="fallas_electricas" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">¿Los recursos validados son propios, es decir, el pc y el servicio de
                internet?</label>
              <select name="recursos_propios" class="form-control">
                <option value="si">Sí</option>
                <option value="no" selected>No</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">¿Cuentas con mini-ups, para respaldar el servicio de Internet?</label>
              <select name="mini_ups" class="form-control">
                <option value="si">Sí</option>
                <option value="no" selected>No</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">¿Cuentas con ups, para el PC de escritorio o laptop?</label>
              <select name="ups" class="form-control">
                <option value="si">Sí</option>
                <option value="no" selected>No</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">¿Cuentas con planta eléctrica en tu hogar?</label>
              <select name="planta_electrica" class="form-control">
                <option value="si">Sí</option>
                <option value="no" selected>No</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">¿Cuentas con un lugar alterno donde puedas continuar tus actividades, en caso de
                una
                falla eléctrica?</label>
              <select name="lugar_alterno" class="form-control">
                <option value="si">Sí</option>
                <option value="no" selected>No</option>
              </select>
            </div>
            <div class="mb-3">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="aviso" name="aviso" required>
                <label class="form-check-label" for="aviso">
                  Confirmo que he leído y acepto el
                  <a href="{{ url('aviso/'.$aviso) }}" target="_blank">Aviso de privacidad</a>, y declaro que la
                  información
                  proporcionada es verdadera.
                </label>
                <div class="invalid-feedback" style="display:none;">Este campo es obligatorio.</div>
              </div>
            </div>
          </div>
          <div class="card-footer d-flex justify-content-end">

            <input type="hidden" name="id_usuario" value="{{ $id_usuario ?? '' }}">
            <input type="hidden" name="id_portal" value="{{ $id_portal ?? '' }}">
            <button class="btn btn-primary mt-3" type="submit">Enviar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const input = document.querySelector('#telefono');


  /* ② Si quieres que arranque MOSTRANDO la lada aun cuando el usuario no
        haya tecleado nada, fuerza el dial-code vacío una sola vez:       */

  /* ①  guarda la instancia */
  const iti = window.intlTelInput(input, {
    initialCountry: 've',
    nationalMode: false,
    utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@22/build/js/utils.js'
  });
  iti.promise.then(() => {
    if (!input.value.trim()) {
      const dial = '+' + iti.getSelectedCountryData().dialCode;
      input.value = dial + ' '; // queda:  "+52 "
    }
  });

  /* ②  úsala dentro del submit */
  input.form.addEventListener('submit', function(e) {

    if (!input.value.trim()) return;


    if (iti.isValidNumber()) {
      input.value = iti.getNumber();
    } else {
      e.preventDefault(); // detiene el envío
      Swal.fire({
        icon: 'error',
        title: 'Teléfono inválido',
        text: 'Por favor, ingresa un número de teléfono válido.'
      });
    }
  });
});

document.addEventListener("DOMContentLoaded", function() {
  // Recorre todos los inputs y selects requeridos
  document.querySelectorAll('form [required]').forEach(function(input) {
    // Si ya existe feedback, no agregar de nuevo
    let feedback = input.parentNode.querySelector('.invalid-feedback');
    if (!feedback) {
      feedback = document.createElement('div');
      feedback.className = 'invalid-feedback';
      feedback.innerText = 'Este campo es obligatorio.';
      feedback.style.display = 'none';
      // Si es checkbox o radio, colócalo después del label
      if (input.type === 'checkbox' || input.type === 'radio') {
        input.parentNode.appendChild(feedback); // después de label
      } else {
        input.parentNode.appendChild(feedback); // después del input
      }
    }

    input.addEventListener('blur', function() {
      let showError = false;
      if (input.type === 'checkbox' || input.type === 'radio') {
        showError = !input.checked;
      } else {
        showError = (input.value.trim() === '');
      }
      input.classList.toggle('is-invalid', showError);
      feedback.style.display = showError ? 'block' : 'none';
    });
    input.addEventListener('input', function() {
      if (input.type === 'checkbox' || input.type === 'radio') {
        if (input.checked) {
          input.classList.remove('is-invalid');
          feedback.style.display = 'none';
        }
      } else {
        if (input.value.trim() !== '') {
          input.classList.remove('is-invalid');
          feedback.style.display = 'none';
        }
      }
    });
  });

});
</script>
@endsection



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.0/dist/sweetalert2.all.min.js">
</script>

<style>
:root {
  --hero: #089bd6;
  /* color de la barra superior */
  --card-grad-top: #ffffff;
  --card-grad-bot: #f4f8fb;
  /* fondo suave del card */
  --ring: #0091d5;
  /* color de foco */
  --radius-xl: 20px;
}

/* Franja superior (hero) */
.hero-bar {
  background: linear-gradient(90deg, #0d6efd, #0a58ca);
  height: 110px;
  /* alto elegante */
  display: block;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
}

/* Contenedor para posicionar el logo relativo al grid Bootstrap */
.hero-bar .container {
  position: relative;
  height: 100%;
}

/* Logo “flotante” alineado al borde izquierdo del card */
.hero-logo {
  position: absolute;
  top: 50%;
  left: 0;
  /* ALINEADO AL IZQUIERDO DEL CONTAINER */
  transform: translateY(-50%);
  background: #ffffff03;
  /* “tarjetita” blanca */
  border-radius: 12px;
  padding: 8px 14px;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 100;
  /* límites para que NO se deforme y nunca tape */
  max-width: 250px;
  max-height: 150px;
  overflow: hidden;
}

.hero-logo img {
  display: block;
  max-height: 120px;
  /* controla el alto del logo */
  width: auto;
  /* mantiene proporción */
  object-fit: contain;
}

/* El card “muerde” ligeramente la franja azul (look SaaS) */
.card-modern {
  margin-top: -36px;
  /* sube el card */
  border-radius: 20px;
  border: 0;
}

/* Refinos del título */
.card-modern .title {
  color: #0a7cc7;
  font-weight: 800;
  letter-spacing: .3px;
}

/* RESPONSIVE: en móvil centramos el logo y quitamos el solape */
@media (max-width: 576px) {
  .hero-bar {
    height: 88px;
  }

  .hero-logo {
    left: 50%;
    transform: translate(-50%, -50%);
    /* centrado en móvil */
    max-width: 180px;
    max-height: 64px;
    padding: 6px 12px;
  }

  .card-modern {
    margin-top: 10px;
  }

  /* sin “mordida” en pantallas pequeñas */
}


/* Responsivo */
@media (max-width: 992px) {
  .hero-bar {
    height: 88px;
  }

  .hero-logo {
    left: 14px;
    height: 56px;
  }
}







/* Ajusta el ancho total del contenedor del input */
.iti {
  width: 100%;
}

/* Hace que el input ocupe el espacio restante sin romper el diseño */
.iti input[type=tel],
.iti input.telefono {
  width: 100% !important;
  box-sizing: border-box;
}



.modern-form {
  /* levanta el card para que “pise” la barra */

  margin-bottom: 48px;
}

.card-modern {
  border: 0;
  border-radius: var(--radius-xl);
  overflow: hidden;
  background: linear-gradient(180deg, var(--card-grad-top), var(--card-grad-bot));
}

.card-modern-header {
  position: relative;
  padding: 32px 32px 8px 32px;
}

.brand-badge {
  background-color: white;
  border-radius: 12px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.brand-badge img {
  width: 70%;
  height: 70%;
  object-fit: contain;
}

.title {
  font-size: clamp(24px, 4vw, 40px);
  font-weight: 700;
  color: #0C9DD3;
  line-height: 1.15;
  margin-left: 25px;
  text-align: center;
  /* deja espacio para el logo */
}

.subtitle {
  color: #4b5563;

  margin-left: 25px;
  text-align: center;
}

/* Inputs con look moderno (scoped al card para no afectar todo el sitio) */
.card-modern .form-control {
  border: 1.5px solid #dbe5ee;
  border-radius: 12px;
  padding: 12px 14px;
  transition: all .18s ease;
  background: #fff;
}

/* Antes lo tenías con overflow:hidden; cámbialo a visible */
.card-modern {
  border: 0;
  border-radius: 20px;
  background: linear-gradient(180deg, #ffffff, #f4f8fb);
  overflow: visible;
  /* ← clave */
}

/* Header sigue siendo relativo para posicionar el logo */
.card-modern-header {
  position: relative;
  padding: 32px 32px 8px 32px;
}

.brand-badge {
  position: absolute;
  top: -28px;
  /* sobresale del borde superior */
  left: 32px;
  width: 70px;
  height: 70px;
  padding: 8px;
  border-radius: 16px;
  background: #ffffff05;
  box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
  display: grid;
  place-items: center;
  z-index: 5;
  /* por si hay sombras o capas */
}

.brand-badge img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}


.card-modern .form-control:focus {
  border-color: var(--ring);
  box-shadow: 0 0 0 .25rem rgba(0, 145, 213, .15);
}

.card-modern .btn.btn-primary {
  border-radius: 12px;
  padding-left: 20px;
  padding-right: 20px;
}

/* Drag & drop: usa tus estilos y añade un toque */
.custom-dropzone {
  position: relative;
  border: 2px dashed #cbd5e0;
  border-radius: 12px;
  padding: 1.25rem;
  text-align: center;
  cursor: pointer;
  background: #f8fafc;
  transition: all .15s ease;
}

.custom-dropzone:hover {
  background: #f2f7fb;
}

.custom-dropzone.dragover {
  background: #eef6ff;
  border-color: #5b9aff;
}

@media (max-width: 576px) {
  .brand-badge {
    left: 16px;
  }

  .title,
  .subtitle {
    margin-left: 25px;
  }



  .card-body {
    padding: 16px !important;
  }
}

.filename-success {
  color: green;
  font-weight: 600;
}

.custom-dropzone.is-invalid {
  border: 2px dashed #dc3545 !important;
}

.custom-dropzone.is-invalid {
  border: 2px dashed #dc3545 !important;
}

.custom-dropzone.is-invalid .dz-instructions {
  color: #dc3545;
}
</style>