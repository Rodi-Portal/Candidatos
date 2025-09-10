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
        <i class="bi bi-info-circle-fill me-2"></i> <!-- requiere Bootstrap Icons -->
        <p class="subtitle mb-0">
          <strong>Antes de empezar</strong><br>
          Diligencie este formulario para suministrar los datos de su empresa y el perfil del asistente virtual que busca.
        </p>

        <form method="POST" action="{{ route('solicitudes.store') }}" enctype="multipart/form-data">
          @csrf
          {{-- conserva el token para redirecciones en caso de error --}}
          <div class="card-body">

            <input type="hidden" name="_from_token" value="{{ request('token') }}">

            {{-- DATOS DEL CLIENTE --}}
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">NOMBRE DEL CLIENTE *</label>
                <input type="text" name="nombre_cliente"
                  class="form-control @error('nombre_cliente') is-invalid @enderror" value="{{ old('nombre_cliente') }}"
                  placeholder="Nombre y apellido" required>
                @error('nombre_cliente')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">E-MAIL *</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                  value="{{ old('email') }}" placeholder="correo@empresa.com" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">INDIQUE SU TELÉFONO *</label>
                <input type="tel" id="telefono" name="telefono" class="form-control" required>

                @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">SELECCIONE MÉTODO DE COMUNICACIÓN PRINCIPAL *</label>
                <select name="metodo_comunicacion"
                  class="form-control @error('metodo_comunicacion') is-invalid @enderror" required>
                  <option value="">Seleccione opción…</option>
                  @php($metodos = ['E-MAIL','LLAMADA DE VOZ','WHATSAPP'])
                  @foreach($metodos as $m)
                  <option value="{{ $m }}" {{ old('metodo_comunicacion')===$m?'selected':'' }}>{{ $m }}</option>
                  @endforeach
                </select>
                @error('metodo_comunicacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>

            {{-- EMPRESA --}}
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">RAZÓN SOCIAL *</label>
                <input type="text" name="razon_social" class="form-control @error('razon_social') is-invalid @enderror"
                  value="{{ old('razon_social') }}" placeholder="Nombre de su empresa" required>
                @error('razon_social')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">NÚMERO DE IDENTIFICACIÓN TRIBUTARIA</label>
                <input type="text" name="nit" class="form-control @error('nit') is-invalid @enderror"
                  value="{{ old('nit') }}" placeholder="RUT / EIN / RFC">
                @error('nit')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">SELECCIONE EL PAÍS DE SU EMPRESA *</label>
                <select name="pais_empresa" id="pais_empresa"
                  class="form-control @error('pais_empresa') is-invalid @enderror" required>
                  <option value="">Seleccione opición…</option>
                  @php($paises = ['CANADÁ',
                  'CHILE',
                  'COLOMBIA',
                  'COSTA RICA',
                  'EEUU',
                  'ESPAÑA',
                  'GUATEMALA',
                  'MEXICO',
                  'PERÚ',
                  'PUERTO RICO',
                  'REPUBLICA DOMINICANA',
                  'VENEZUELA',
                  'OTRO'])
                  @foreach($paises as $p)
                  <option value="{{ $p }}" {{ old('pais_empresa')===$p?'selected':'' }}>{{ $p }}</option>
                  @endforeach
                </select>
                @error('pais_empresa')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6 mb-3" id="wrap_pais_otro" style="display:none;">
                <label class="form-label">INDIQUE PARA QUE OTRO PAÍS DESEA EL SERVICIO</label>
                <input type="text" name="pais_otro" id="pais_otro"
                  class="form-control @error('pais_otro') is-invalid @enderror" value="{{ old('pais_otro') }}"
                  placeholder="País objetivo del servicio">
                @error('pais_otro')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">SITIO WEB DE SU EMPRESA</label>
                <input type="url" name="sitio_web" class="form-control @error('sitio_web') is-invalid @enderror"
                  value="{{ old('sitio_web') }}" placeholder="https://…">
                @error('sitio_web')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12 mb-3">
                <label class="form-label">ACTIVIDAD DE LA EMPRESA *</label>
                <textarea name="actividad" class="form-control @error('actividad') is-invalid @enderror" rows="2"
                  placeholder="Describe brevemente a qué se dedica." required>{{ old('actividad') }}</textarea>
                @error('actividad')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-md-12 mb-3">
                <label class="form-label">FECHA SOLICITUD *</label>
                <input type="date" name="fecha_solicitud"
                  class="form-control @error('fecha_solicitud') is-invalid @enderror"
                  value="{{ old('fecha_solicitud') }}" required>
                @error('fecha_solicitud')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>

            {{-- SERVICIO / PLAN / VOIP --}}
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">SELECCIONE EL PLAN QUE DESEA CONTRATAR *</label>
                <select name="plan" class="form-control @error('plan') is-invalid @enderror" required>
                  <option value="">Seleccione…</option>
                  @foreach(['ASISTENTE BÁSICO',
                  'ASISTENTE BÁSICO BILINGUE',
                  'AGENTE COMERCIAL/ADMINISTRATIVO',
                  'AGENTE COMERCIAL/ADMINISTRATIVO BILINGUE',
                  'GRADO TÉCNICO',
                  'GRADO TÉCNICO BILINGUE',
                  'GRADO PROFESIONAL',
                  'GRADO PROFESIONAL BILINGUE'] as $plan)
                  <option value="{{ $plan }}" {{ old('plan')===$plan?'selected':'' }}>{{ $plan }}</option>
                  @endforeach
                </select>
                @error('plan')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">¿REQUIERE SERVICIO DE TELEFONÍA VoIP ADICIONAL?*</label>
                <select name="requiere_voip" id="requiere_voip"
                  class="form-control @error('requiere_voip') is-invalid @enderror" required>
                  <option value="">Seleccione…</option>
                  <option value="si" {{ old('requiere_voip')==='si'?'selected':'' }}>Sí</option>
                  <option value="no" {{ old('requiere_voip')==='no'?'selected':'' }}>No</option>
                </select>
                @error('requiere_voip')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-md-6 mb-3" id="wrap_voip_propiedad" style="display:none;">
                <label class="form-label">EL SERVICIO DE TELEFONÍA VoIP QUE USARÁ SU TELEOPERADOR, ¿ES PROPIO O SERÁ
                  SUMINISTRADO POR ASISTENTE VIRTUAL OK.COM?</label>
                <select name="voip_propiedad" class="form-control @error('voip_propiedad') is-invalid @enderror">
                  <option value="">Seleccione…</option>
                  <option value="propio" {{ old('voip_propiedad')==='propio'?'selected':'' }}>Telefonía propia</option>
                  <option value="asistente_virtual_ok"
                    {{ old('voip_propiedad')==='asistente_virtual_ok'?'selected':'' }}>
                    A través de Asistente Virtual Ok.com</option>
                </select>
                @error('voip_propiedad')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-md-6 mb-3" id="wrap_voip_ubicacion" style="display:none;">
                <label class="form-label">INDIQUE EL PAÍS/CIUDAD DE LA TELEFONÍA VoIP QUE REQUIERE</label>
                <input type="text" name="voip_pais_ciudad"
                  class="form-control @error('voip_pais_ciudad') is-invalid @enderror"
                  value="{{ old('voip_pais_ciudad') }}" placeholder="País / Ciudad">
                @error('voip_pais_ciudad')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>

            {{-- REFERIDOS / BNI --}}
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">¿USTED ES MIEMBRO BNI? *</label>
                <select name="miembro_bni" class="form-control @error('miembro_bni') is-invalid @enderror" required>
                  <option value="">Seleccione…</option>
                  <option value="si" {{ old('miembro_bni')==='si'?'selected':'' }}>Sí</option>
                  <option value="no" {{ old('miembro_bni')==='no'?'selected':'' }}>No</option>
                </select>
                @error('miembro_bni')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">¿VIENE USTED REFERIDO? *</label>
                <input type="text" name="referido" class="form-control @error('referido') is-invalid @enderror"
                  value="{{ old('referido') }}" placeholder="Sí/No y nombre de la persona o empresa quien lo refirió">
                @error('referido')<div class="invalid-feedback" required>{{ $message }}</div>@enderror
              </div>
            </div>

            {{-- PERFIL DEL ASISTENTE --}}
            <div class="row">
              <div class="col-md-12 mb-3">
                <label class="form-label">HORARIO DE TRABAJO *</label>
                <input type="text" name="horario" class="form-control @error('horario') is-invalid @enderror"
                  value="{{ old('horario') }}"
                  placeholder="Indique el horario de trabajo que desea  le acompañe su Asistente Virtual">
                @error('horario')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-md-12 mb-3">
                <label class="form-label">SELECCIONE EL SEXO DE PREFERENCIA PARA SU ASISTENTE VIRTUAL *</label>
                <select name="sexo_preferencia" class="form-control @error('sexo_preferencia') is-invalid @enderror"
                  required>
                  <option value="">Seleccionar opción…</option>
                  @foreach(['FEMENINO','MASCULINO','UNISEX'] as $sx)
                  <option value="{{ $sx }}" {{ old('sexo_preferencia')===$sx?'selected':'' }}>{{ $sx }}</option>
                  @endforeach
                </select>
                @error('sexo_preferencia')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-md-12 mb-3">
                <label class="form-label">INDIQUE EL RANGO DE EDAD DE SU ASISTENTE VIRTUAL *</label>
                <input type="text" name="rango_edad" class="form-control @error('rango_edad') is-invalid @enderror"
                  value="{{ old('rango_edad') }}" placeholder="Desde - Hasta." required>
                @error('rango_edad')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12 mb-3">
                <label class="form-label">FUNCIONES A DESEMPEÑAR *</label>
                <textarea name="funciones" class="form-control @error('funciones') is-invalid @enderror" rows="3"
                  placeholder="Describa  las  funciones principales  que serán  realizadas  por  su Asistente Virtual"
                  required>{{ old('funciones') }}</textarea>
                @error('funciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12 mb-3">
                <label class="form-label">REQUISITOS/HABILIDADES *</label>
                <textarea name="requisitos" class="form-control @error('requisitos') is-invalid @enderror" rows="3"
                  placeholder="Describa  los conocimientos  y habilidades con los que debe contar  su Asistente Virtual"
                  required>{{ old('requisitos') }}</textarea>
                @error('requisitos')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12 mb-3">
                <label class="form-label">RECURSOS TÉCNICOS/PROGRAMAS A UTILIZAR *</label>
                <textarea name="recursos" class="form-control @error('recursos') is-invalid @enderror" rows="3"
                  placeholder="Indique los recursos y/o programas que debe manejar su Asistente Virtual"
                  required>{{ old('recursos') }}</textarea>
                @error('recursos')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">¿SU EMPRESA HACE USO DE ALGÚN CRM?*</label>
                <select name="usa_crm" id="usa_crm" class="form-control @error('usa_crm') is-invalid @enderror"
                  required>
                  <option value="">Seleccionar opcion…</option>
                  <option value="si" {{ old('usa_crm')==='si'?'selected':'' }}>Sí</option>
                  <option value="no" {{ old('usa_crm')==='no'?'selected':'' }}>No</option>
                </select>
                @error('usa_crm')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6 mb-3" id="wrap_crm_nombre" style="display:none;">
                <label class="form-label">¿CÓMO SE LLAMA EL CRM QUE UTILIZAN EN SU EMPRESA?</label>
                <input type="text" name="crm_nombre" class="form-control @error('crm_nombre') is-invalid @enderror"
                  value="{{ old('crm_nombre') }}" placeholder="Indique el nombre del CRM">
                @error('crm_nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">ELIJA LA FECHA TENTATIVA DE INICIO DE SU SERVICIO *</label>
                <input type="date" name="fecha_inicio" class="form-control @error('fecha_inicio') is-invalid @enderror"
                  value="{{ old('fecha_inicio') }}"
                  placeholder="indique la  fecha en la que desea  iniciar  su servicio" required>
                @error('fecha_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>

            {{-- OBSERVACIONES + ARCHIVOS --}}
            <div class="col-md-12 mb-3">
              <label class="form-label">Observaciones adicionales</label>
              <textarea name="observaciones" class="form-control @error('observaciones') is-invalid @enderror" rows="3"
                placeholder="Algo más que debamos considerar.">{{ old('observaciones') }}</textarea>
              @error('observaciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <label class="form-label">¿Desea adjuntar algún archivo? (Opcional)</label>
              <input type="file" name="archivo" class="form-control @error('archivo') is-invalid @enderror" />
              @error('archivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
              <small class="text-muted">Puede arrastrar y soltar el archivo sobre el campo.</small>
            </div>
            {{-- TÉRMINOS Y CONDICIONES --}}
            <div class="col-12 mb-3">
              <div class="form-check">
                <input class="form-check-input @error('acepta_terminos') is-invalid @enderror" type="checkbox"
                  id="acepta_terminos" name="acepta_terminos" value="1" {{ old('acepta_terminos') ? 'checked' : '' }}
                  required>

                <label class="form-check-label" for="acepta_terminos">
                  He leído y acepto los
                  @if(!empty($terminosUrl))
                  <a href="{{ $terminosUrl }}" target="_blank" rel="noopener">Términos y Condiciones</a>.
                  @else
                  <span class="text-danger">Términos y Condiciones (archivo no disponible)</span>.
                  @endif
                </label>

                @error('acepta_terminos')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              {{-- Enviar al backend qué archivo se aceptó --}}
              <input type="hidden" name="terminos_file" value="{{ session('terminos_file') }}">
              <input type="hidden" name="terminos_hash" value="{{ session('terminos_hash') }}">
            </div>
          </div>

          <div class="card-footer d-flex justify-content-end">
            <button class="btn btn-primary mt-2" type="submit">Enviar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection




<script>
document.addEventListener('DOMContentLoaded', () => {
  // ======= FORM =======
  const form = document.querySelector('form[action*="solicitudes.store"]') || document.querySelector('form');
  if (!form) return;

  // ======= TELÉFONO (intl-tel-input) =======
  const tel = document.getElementById('telefono');
  let iti = null;

  if (tel && window.intlTelInput) {
    iti = window.intlTelInput(tel, {
      initialCountry: 'us',
      separateDialCode: false, // LADA dentro del input
      nationalMode: false,
      autoPlaceholder: 'aggressive',
      formatOnDisplay: true,
      utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@22/build/js/utils.js',
      preferredCountries: ['mx', 'us', 'es', 'co', 'ar', 'cl', 'pe']
    });

    iti.promise.then(() => {
      if (!tel.value.trim()) {
        tel.value = '+' + (iti.getSelectedCountryData().dialCode || '') + ' ';
      }
      if (typeof validateField === 'function') validateField(tel);
    });

    tel.addEventListener('countrychange', () => {
      const dial = '+' + (iti.getSelectedCountryData().dialCode || '');
      const val = tel.value.trim();
      if (!val || !val.startsWith('+')) {
        tel.value = dial + ' ';
      } else {
        tel.value = dial + ' ' + val.replace(/^\+\d+\s?/, '');
      }
      validateField(tel);
    });
  }

  // ======= REQUERIDOS + ASTERISCOS ROJOS =======
  const requiredFields = form.querySelectorAll('input[required], select[required], textarea[required]');

  // Pinta asterisco(s) en rojo o los agrega si faltan
  requiredFields.forEach(el => {
    const wrap = el.closest('.mb-3') || el.closest('.form-check') || el.parentElement;
    const label = wrap ? wrap.querySelector('label.form-label, label.form-check-label') : null;
    if (label) {
      if (label.innerHTML.includes('*')) {
        label.innerHTML = label.innerHTML.replace(/\*/g, '<span class="text-danger">*</span>');
      } else {
        label.insertAdjacentHTML('beforeend', ' <span class="text-danger">*</span>');
      }
    }
  });

  // Crea/obtiene feedback debajo del campo
  function ensureFeedback(el) {
    let fb;
    if (el.type === 'checkbox') {
      fb = el.closest('.form-check')?.querySelector('.invalid-feedback.client');
      if (!fb) {
        fb = document.createElement('div');
        fb.className = 'invalid-feedback client d-block';
        fb.style.display = 'none';
        fb.textContent = 'Debe aceptar para continuar.';
        el.closest('.form-check').appendChild(fb);
      }
      return fb;
    }

    // ---- para inputs/select/textarea (incluye teléfono) ----
    const itiWrap = el.closest('.iti'); // wrapper de intl-tel-input si existe
    const container = itiWrap || el.parentElement; // insertaremos después de este contenedor
    const next = container.nextElementSibling;

    if (next && next.classList?.contains('invalid-feedback') && next.classList?.contains('client')) {
      fb = next; // ya existe
    } else {
      fb = document.createElement('div');
      fb.className = 'invalid-feedback client';
      fb.textContent = 'Este campo es obligatorio.';
      container.insertAdjacentElement('afterend', fb); // <<< clave: fuera del .iti
    }
    return fb;
  }

  // Detecta si el teléfono solo tiene la LADA (ej. "+52 ")
  function phoneHasOnlyDial() {
    const val = (tel?.value || '').trim();
    const digits = val.replace(/[^\d]/g, '');
    const dial = iti?.getSelectedCountryData()?.dialCode || '';
    return digits !== '' && digits === dial;
  }

  // Validación por campo
  function validateField(el) {
    let valid = true;
    const val = (el.value || '').trim();

    // Caso especial: TELÉFONO
    if (tel && el === tel) {
      const onlyDial = phoneHasOnlyDial();
      if (iti) {
        valid = !onlyDial && iti.isValidNumber();
      } else {
        // Fallback si no cargó la librería
        valid = !onlyDial && /\d{6,}/.test(val);
      }

      el.classList.toggle('is-invalid', !valid);
      el.classList.toggle('is-valid', valid);

      const fb = ensureFeedback(el);
      if (!valid) {
        fb.textContent = onlyDial ?
          'Completa el número (faltan dígitos).' :
          'Ingresa un número telefónico válido.';
        fb.style.display = 'block';
      } else {
        fb.style.display = 'none';
      }
      return valid;
    }

    // Resto de tipos
    if (el.type === 'checkbox') {
      valid = el.checked;
    } else if (el.tagName === 'SELECT') {
      valid = val !== '';
    } else if (el.type === 'email') {
      valid = val !== '' && el.checkValidity();
    } else if (el.type === 'date') {
      valid = el.value !== '';
    } else {
      valid = val !== '';
    }

    el.classList.toggle('is-invalid', !valid);
    el.classList.toggle('is-valid', valid);

    if (el.type === 'checkbox') {
      const fb = ensureFeedback(el);
      fb.style.display = valid ? 'none' : 'block';
    }
    return valid;
  }

  // Inicializa feedback y listeners
  requiredFields.forEach(el => {
    ensureFeedback(el);
    el.addEventListener('blur', () => validateField(el));
    el.addEventListener('change', () => validateField(el));
    el.addEventListener('input', () => {
      if (el.classList.contains('is-invalid')) validateField(el);
    });
  });

  // Submit: valida todo; si ok y tel es válido, normaliza a E.164
  form.addEventListener('submit', (e) => {
    let ok = true;
    requiredFields.forEach(el => {
      if (!validateField(el)) ok = false;
    });
    if (!ok) {
      e.preventDefault();
      (form.querySelector('.is-invalid') || requiredFields[0])?.focus();
      return;
    }
    if (tel && iti && iti.isValidNumber()) {
      tel.value = iti.getNumber(); // +E.164
    }
  });
  // --- Mostrar/ocultar "¿Cómo se llama el CRM?" según usa_crm ---
  const selCrm = document.getElementById('usa_crm');
  const wrapCrm = document.getElementById('wrap_crm_nombre'); // <div ... style="display:none;">
  const inpCrm = wrapCrm ? wrapCrm.querySelector('input[name="crm_nombre"]') : null;

  function toggleCrm() {
    const show = selCrm && selCrm.value === 'si';
    if (wrapCrm) wrapCrm.style.display = show ? 'block' : 'none';
    if (inpCrm) {
      inpCrm.required = show; // sólo requerido si responde "sí"
      if (!show) inpCrm.value = ''; // limpia si se oculta
    }
  }

  if (selCrm) {
    selCrm.addEventListener('change', toggleCrm);
    toggleCrm(); // estado inicial (incluye old('usa_crm'))
  }

  // (opcional) mismo patrón para "OTRO" país:
  const selPais = document.getElementById('pais_empresa');
  const wrapOtro = document.getElementById('wrap_pais_otro');
  const inpOtro = document.getElementById('pais_otro');

  function togglePais() {
    const show = selPais && selPais.value === 'OTRO';
    if (wrapOtro) wrapOtro.style.display = show ? 'block' : 'none';
    if (inpOtro) {
      inpOtro.required = show;
      if (!show) inpOtro.value = '';
    }
  }

  if (selPais) {
    selPais.addEventListener('change', togglePais);
    togglePais();
  }

});
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
  height: 110px;                 /* alto elegante */
  display: block;
  box-shadow: 0 2px 8px rgba(0,0,0,.08);
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
  left: 0;                       /* ALINEADO AL IZQUIERDO DEL CONTAINER */
  transform: translateY(-50%);
  background: #ffffff03;              /* “tarjetita” blanca */
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
  max-height: 120px;              /* controla el alto del logo */
  width: auto;                   /* mantiene proporción */
  object-fit: contain;
}

/* El card “muerde” ligeramente la franja azul (look SaaS) */
.card-modern {
  margin-top: -36px;             /* sube el card */
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
  .hero-bar { height: 88px; }
  .hero-logo {
    left: 50%;
    transform: translate(-50%, -50%);  /* centrado en móvil */
    max-width: 180px;
    max-height: 64px;
    padding: 6px 12px;
  }
  .card-modern { margin-top: 10px; }   /* sin “mordida” en pantallas pequeñas */
}


/* Responsivo */
@media (max-width: 992px){
  .hero-bar{ height: 88px; }
  .hero-logo{ left: 14px; height: 56px; }
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
