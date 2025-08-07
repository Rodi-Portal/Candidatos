@extends('layouts.app')

@section('content')
<div class="container my-4">
  <div class="card shadow p-4">
    <h2 class="mb-4 text-primary">FORMULARIO</h2>
    <form method="POST" action="{{ route('registro.storeNuevo') }}">
      @csrf

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
        <input id="telefono" type="tel" name="telefono" class="form-control" placeholder="Indique su número de teléfono"
          required>


        <small id="lada-info" class="form-text text-muted"></small>
      </div>
      <div class="mb-3">
        <label class="form-label">Correo electrónico</label>
        <input type="email" name="correo" class="form-control" placeholder="Indica tu correo electronico" required>
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
        <label class="form-label">¿Tu proveedor de internet te brinda el servicio a través de fibra óptica?*</label>
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
        <label class="form-label">En caso de ser sí, describe los tiempos y días de racionamiento eléctrico.</label>
        <input type="text" name="detalle_racionamiento" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">¿Cuántas veces ha fallado el servicio eléctrico en las dos últimas semanas?</label>
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
        <label class="form-label">¿Cuentas con un lugar alterno donde puedas continuar tus actividades, en caso de una
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
            <a href="{{ url('aviso/'.$aviso) }}" target="_blank">Aviso de privacidad</a>, y declaro que la información
            proporcionada es verdadera.
          </label>
          <div class="invalid-feedback" style="display:none;">Este campo es obligatorio.</div>
        </div>
      </div>
      <input type="hidden" name="id_usuario" value="{{ $id_usuario ?? '' }}">
      <input type="hidden" name="id_portal" value="{{ $id_portal ?? '' }}">
      <button class="btn btn-primary mt-3" type="submit">Enviar</button>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const input = document.querySelector('#telefono');
  

  /* ② Si quieres que arranque MOSTRANDO la lada aun cuando el usuario no
        haya tecleado nada, fuerza el dial-code vacío una sola vez:       */
 
  /* ①  guarda la instancia */
  const iti = window.intlTelInput(input, {
    initialCountry: 'mx',
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
  e.preventDefault();                  // detiene el envío
  Swal.fire({
    icon: 'error',
    title: 'Teléfono inválido',
    text: 'Por favor, ingresa un número de teléfono válido.'
  });    }
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
body {
  background: #f6f8fb;
}

.card {
  border-radius: 1.3rem;
  box-shadow: 0 8px 36px 0 rgba(44, 62, 80, 0.09);
  max-width: 680px;
  margin: 40px auto;
}

h2 {
  font-weight: 800;
  letter-spacing: -.5px;
}

.form-label {
  font-weight: 600;
  color: #207cbe;
  letter-spacing: -.2px;
}

.form-control {
  border-radius: 12px;
  border: 1.6px solid #d6e7f8;
  background: #fafdff;
  transition: border-color 0.15s, box-shadow 0.15s;
  font-size: 1.09rem;
  font-family: inherit;
}

.form-control:focus {
  border-color: #6be1fb;
  box-shadow: 0 1px 6px 0 rgba(32, 124, 190, 0.13);
}

.is-invalid {
  border-color: #ee6c6c;
  background: #fff5f5;
}

.invalid-feedback {
  display: block;
  color: #e74c3c;
  font-size: .97em;
  margin-top: 2px;
  margin-left: 2px;
  font-weight: 500;
}

.btn-primary {
  background: linear-gradient(90deg, #207cbe 20%, #6be1fb 80%);
  border: none;
  border-radius: 9px;
  font-weight: 700;
  font-size: 1.12em;
  letter-spacing: .5px;
  padding: 12px 0;
  box-shadow: 0 2px 14px 0 rgba(44, 62, 80, 0.05);
  transition: background .18s;
}

.btn-primary:hover,
.btn-primary:focus {
  background: linear-gradient(90deg, #6be1fb 0%, #207cbe 100%);
}

@media (max-width: 600px) {
  .card {
    padding: 12px 2vw 12px 2vw !important;
    border-radius: 10px;
  }
}

label.form-label:has(+ [required]):after {
  content: " *";
  color: #e74c3c;
  font-size: 1.05em;
}

.iti--allow-dropdown {
  width: 100%;
}

.iti__country-list {
  font-size: 1em;
}
</style>