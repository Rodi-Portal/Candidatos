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
        <h1 class="title mb-2 justify-content-center">Formulario nuevo ingreso</h1>
        <p class="subtitle mb-0">
          Este formulario está basado en la recolección de datos personales faltantes, acuerdos de confidencialidad y datos para el pago de nómina.
        </p>


        <form id="form-nuevo-ingreso" action="{{ route('nuevo_ingreso.store') }}" {{-- <-- AJUSTA ESTA RUTA --}}
          method="POST" enctype="multipart/form-data">
          @csrf

          <div class="card-body">

            {{-- Nombre completo --}}
            <div class="form-group">
              <label class="font-weight-bold" for="nombre_completo"><strong>Indica tu nombre completo</strong>
                <span class="text-danger">*</span>
              </label>
              <br>
              <small class="text-muted">Nombres y Apellidos</small>
              <input type="text" id="nombre_completo" name="nombre_completo"
                class="form-control @error('nombre_completo') is-invalid @enderror"
                placeholder="Ingresa tu nombre completo" value="{{ old('nombre_completo') }}" required>
              @error('nombre_completo')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror

            </div>

            {{-- Cédula de identidad (archivo) --}}
            <div class="form-group">
              <label class="font-weight-bold d-block"><strong>Foto de la cédula de identidad </strong><span
                  class="text-danger">*</span></label>
              <small class="text-muted d-block mb-2">Adjunta una foto legible y sin marcas de flash.</small>

              <div class="custom-dropzone" data-for="cedula_identidad">
                <div class="dz-instructions">
                  <span class="d-block">Suelta los archivos aquí para subirlos</span>
                  <span class="text-muted">o haz clic para seleccionar</span>
                </div>
                <input type="file" id="cedula_identidad" name="cedula_identidad"
                  class="d-none file-input @error('cedula_identidad') is-invalid @enderror"
                  accept="image/*,application/pdf" required>
              </div>
              <div class="text-danger small d-none" id="cedula_identidad_error">Este archivo es obligatorio.</div>
              @error('cedula_identidad')
              <div class="text-danger small">{{ $message }}</div>
              @enderror
            </div>

            {{-- Foto asociado (tipo carnet) --}}
            <div class="form-group">
              <label class="font-weight-bold d-block"><strong>Foto Asociado </strong><span
                  class="text-danger">*</span></label>
              <small class="text-muted d-block mb-2">Foto tipo carnet, actual, preferiblemente con fondo blanco.</small>

              <div class="custom-dropzone" data-for="foto_asociado">
                <div class="dz-instructions">
                  <span class="d-block">Suelta los archivos aquí para subirlos</span>
                  <span class="text-muted">o haz clic para seleccionar</span>
                </div>
                <input type="file" id="foto_asociado" name="foto_asociado"
                  class="d-none file-input @error('foto_asociado') is-invalid @enderror" accept="image/*" required>
              </div>
              <div class="text-danger small d-none" id="foto_asociado_error">Este archivo es obligatorio.</div>
              @error('foto_asociado') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Acuerdo de confidencialidad (Word) --}}
            <div class="form-group">
              <label class="font-weight-bold d-block"><strong>Acuerdo de Confidencialidad </strong><span
                  class="text-danger">*</span></label>
              <small class="text-muted d-block mb-2">Adjunta el documento de Word recibido por e-mail, previamente
                firmado.</small>

              <div class="custom-dropzone" data-for="acuerdo_confidencialidad">
                <div class="dz-instructions">
                  <span class="d-block">Suelta los archivos aquí para subirlos</span>
                  <span class="text-muted">o haz clic para seleccionar</span>
                </div>
                <input type="file" id="acuerdo_confidencialidad" name="acuerdo_confidencialidad"
                  class="d-none file-input @error('acuerdo_confidencialidad') is-invalid @enderror"
                  accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf"
                  required>
              </div>
              <div class="text-danger small d-none" id="acuerdo_confidencialidad_error">Este archivo es obligatorio.
              </div>
              @error('acuerdo_confidencialidad') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Convenio de confidencialidad (Word) --}}
            <div class="form-group">
              <label class="font-weight-bold d-block"><strong>Convenio de Confidencialidad </strong><span
                  class="text-danger">*</span></label>
              <small class="text-muted d-block mb-2">Adjunta el documento de Word recibido por e-mail, previamente
                firmado.</small>

              <div class="custom-dropzone" data-for="convenio_confidencialidad">
                <div class="dz-instructions">
                  <span class="d-block">Suelta los archivos aquí para subirlos</span>
                  <span class="text-muted">o haz clic para seleccionar</span>
                </div>
                <input type="file" id="convenio_confidencialidad" name="convenio_confidencialidad"
                  class="d-none file-input @error('convenio_confidencialidad') is-invalid @enderror"
                  accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf"
                  required>
              </div>
              <div class="text-danger small d-none" id="convenio_confidencialidad_error">Este archivo es obligatorio.
              </div>
              @error('convenio_confidencialidad') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <hr>

            {{-- ======== MÉTODO DE PAGO (todos los campos visibles) ======== --}}
            <div class="form-group">
              <label class="font-weight-bold"><strong>Método de pago</strong> <span class="text-danger">*</span></label>
              <small class="text-muted d-block mb-2">
                Selecciona el método de pago en el que deseas recibir tus ingresos.
              </small>
              <select id="metodo_pago" name="metodo_pago" class="form-control" required>
                <option value="" disabled {{ old('metodo_pago') ? '' : 'selected' }}>Selecciona una opción…</option>
                <option value="bank" {{ old('metodo_pago')==='bank' ? 'selected' : '' }}>Transferencia bancaria</option>
                <option value="binance" {{ old('metodo_pago')==='binance' ? 'selected' : '' }}>Binance</option>
                <option value="zelle" {{ old('metodo_pago')==='zelle' ? 'selected' : '' }}>Zelle</option>
                <option value="zinli" {{ old('metodo_pago')==='zinli' ? 'selected' : '' }}>Zinli</option>
              </select>
            </div>

            <div class="form-group">
              <label for="bank_details">
                <strong>Datos bancarios para Cuenta nacional o Cuenta Americana</strong>
              </label><br>
              <small class="form-text text-muted">
                Ingresa los datos bancarios como: titular de la cuenta / Cédula del titular / Banco a depositar / Nro.
                de cuenta / Tipo de cuenta.
                En caso de ser una cuenta americana: Banco / Routing / Número de cédula / Dirección / Tipo de cuenta /
                Ciudad / Estado / Correo electrónico / Código postal / Titular de la cuenta.
              </small>
              <textarea id="bank_details" name="bank_details" class="form-control" rows="3"
                placeholder="Indique todos los datos requeridos según sea el caso: Cuenta Nacional o Cuenta Americana"
                required></textarea>
            </div>

            {{-- BINANCE (SIEMPRE VISIBLE) --}}
            <div class="border rounded p-3 mb-3 bg-light">
              <h6 class="mb-1">Correo registrado en BINANCE</h6>
              <small class="text-muted d-block mb-2">Ingrese el correo registrado en su cuenta de Binance.</small>
              <label class="form-label"><strong>Indique el correo</strong></label>
              <input type="email" name="binance_email" id="binance_email" class="form-control"
                placeholder="correo@dominio.com" value="{{ old('binance_email') }}">
            </div>

            {{-- ZELLE (SIEMPRE VISIBLE) --}}
            <div class="border rounded p-3 mb-3 bg-light">
              <h6 class="mb-1">Correo Zelle registrado</h6>
              <small class="text-muted d-block mb-2">Indique el correo de la cuenta Zelle donde desea recibir el pago.
                Si usa Zelle, complete también los datos del titular.</small>

              <label class="form-label"><strong>Indique el correo</strong></label>
              <input type="email" name="zelle_email" id="zelle_email" class="form-control"
                placeholder="correo@dominio.com" value="{{ old('zelle_email') }}">

              <div class="row g-3 mt-2">
                <div class="col-md-7">
                  <label class="form-label"><strong>Nombre y apellido del titular</strong></label>
                  <input type="text" name="zelle_titular" id="zelle_titular" class="form-control"
                    value="{{ old('zelle_titular') }}">
                </div>
                <div class="col-md-5">
                  <label class="form-label"><strong>Teléfono del titular</strong></label>
                  <input type="tel" name="zelle_phone" id="zelle_phone" class="form-control telefono" data-country="us"
                    value="{{ old('zelle_phone') }}">
                </div>
              </div>
            </div>

            <hr>

            {{-- Contactos de emergencia --}}
            <h6 class="font-weight-bold mb-3">Contactos de emergencia</h6>
            {{-- Principal --}}
            <div class="border rounded p-3 mb-3">
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label><strong>N° de contacto principal</strong><span class="text-danger">*</span></label>
                  <input type="tel" name="contacto_principal" class="form-control telefono" data-country="ve"
                    {{-- código de país, por ejemplo VE = Venezuela --}} placeholder="Escribe tu teléfono"
                    value="{{ old('contacto_principal') }}" required>
                </div>
                <div class="form-group col-md-12">
                  <label><strong>¿A quién pertenece el número principal?</strong></label>
                  <input type="text" name="contacto_principal_propietario" class="form-control"
                    placeholder="Nombre Apellido / Parentesco" value="{{ old('contacto_principal_propietario') }}"
                    required>
                </div>
              </div>
            </div>

            {{-- Secundario --}}
            <div class="border rounded p-3 mb-3">
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label><strong>N° de contacto secundario</strong><span class="text-danger">*</span></label>
                  <input type="tel" name="contacto_secundario" class="form-control telefono" data-country="ve"
                    placeholder="Escribe tu teléfono" value="{{ old('contacto_secundario') }}" required>
                </div>
                <div class="form-group col-md-12">
                  <label><strong>¿A quién pertenece el número secundario?</strong></label>
                  <input type="text" name="contacto_secundario_propietario" class="form-control"
                    placeholder="Nombre Apellido / Parentesco" value="{{ old('contacto_secundario_propietario') }}"
                    required>
                </div>
              </div>
            </div>

            {{-- Adicional --}}
            <div class="border rounded p-3 mb-4">
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label><strong>N° de contacto adicional</strong><span class="text-danger">*</span></label>
                  <input type="tel" name="contacto_adicional" class="form-control telefono" data-country="ve"
                    placeholder="Escribe tu teléfono" value="{{ old('contacto_adicional') }}" required>
                </div>
                <div class="form-group col-md-12">
                  <label><strong>¿A quién pertenece el número adicional?</strong></label>
                  <input type="text" name="contacto_adicional_propietario" class="form-control"
                    placeholder="Nombre Apellido / Parentesco" value="{{ old('contacto_adicional_propietario') }}"
                    required>
                </div>
              </div>
            </div>


          </div>

          <div class="card-footer d-flex justify-content-end">
            <button type="submit" class="btn btn-primary w-100">
              Enviar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


{{-- SI NO lo metiste en el layout, descomenta esta línea para cargar el plugin aquí mismo: --}}
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@22/build/js/intlTelInput.min.js"></script>

<script>
(function() {
  'use strict';

  function onReady(fn) {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }

  // utilidades cortas
  const $ = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

  // ========= configuración específica de TU plantilla =========
  // IDs de inputs file que son obligatorios en tu vista
  const REQUIRED_FILE_IDS = [
    'cedula_identidad',
    'foto_asociado',
    'acuerdo_confidencialidad',
    'convenio_confidencialidad'
  ];

  // ========= helpers de UI/errores =========
  function ensureAfter(refNode, node) {
    if (!refNode || !refNode.parentNode) return;
    if (refNode.nextSibling) refNode.parentNode.insertBefore(node, refNode.nextSibling);
    else refNode.parentNode.appendChild(node);
  }

  function ensureFileScaffolding(input) {
    // Asegura filename
    let fileName = document.getElementById(input.id + '_filename');
    if (!fileName) {
      fileName = document.createElement('div');
      fileName.id = input.id + '_filename';
      fileName.className = 'small mt-1';
      fileName.textContent = 'Sin archivos seleccionados';
      // lo ponemos después de la dropzone si existe; si no, después del input
      const zone = document.querySelector(`.custom-dropzone[data-for="${input.id}"]`);
      ensureAfter(zone || input, fileName);
    }
    // Asegura error
    let err = document.getElementById(input.id + '_error');
    if (!err) {
      err = document.createElement('div');
      err.id = input.id + '_error';
      err.className = 'text-danger small d-none';
      err.textContent = 'Este archivo es obligatorio.';
      ensureAfter(fileName, err);
    }
  }

  function zoneOf(input) {
    return document.querySelector(`.custom-dropzone[data-for="${input.id}"]`);
  }

  function fileLabelOf(input) {
    return document.getElementById(input.id + '_filename');
  }

  function fileErrorOf(input) {
    return document.getElementById(input.id + '_error');
  }

  function showTextError(el, msg) {
    // intenta usar .invalid-feedback debajo; si no existe, fabrica uno simple
    let fb = el.parentElement && el.parentElement.querySelector('.invalid-feedback');
    if (!fb && el.parentElement) {
      fb = document.createElement('div');
      fb.className = 'invalid-feedback';
      el.parentElement.appendChild(fb);
    }
    if (fb) {
      fb.textContent = msg || 'Este campo es obligatorio.';
      fb.style.display = 'block';
    }
    el.classList.add('is-invalid');
  }

  function clearTextError(el) {
    const fb = el.parentElement && el.parentElement.querySelector('.invalid-feedback');
    if (fb) fb.style.display = 'none';
    el.classList.remove('is-invalid');
  }

  // --- REEMPLAZA ESTO EN TU SCRIPT ---

  function showFileError(input, msg) {
    const zone = zoneOf(input);
    const lbl = fileLabelOf(input);
    const err = fileErrorOf(input);

    if (zone) {
      zone.classList.add('is-invalid');
      zone.style.boxShadow = '0 0 0 2px rgba(220,53,69,.35)';
      zone.setAttribute('aria-invalid', 'true');
      if (!zone.hasAttribute('tabindex')) zone.setAttribute('tabindex', '-1');
    }
    if (lbl) {
      lbl.classList.remove('text-success', 'filename-success');
      lbl.classList.add('text-danger');
    }
    if (err) {
      err.textContent = msg || 'Este archivo es obligatorio.';
      err.classList.remove('d-none');
      err.style.display = 'block';
    } else {
      showTextError(input, msg);
    }
  }


  function clearFileError(input) {
    const zone = zoneOf(input);
    const lbl = fileLabelOf(input);
    const err = fileErrorOf(input);

    if (zone) {
      zone.classList.remove('is-invalid');
      zone.style.boxShadow = '';
      zone.removeAttribute('aria-invalid');
    }
    if (lbl) {
      lbl.classList.remove('text-danger');
      if (!input.files || !input.files.length) {
        lbl.textContent = 'Sin archivos seleccionados';
        lbl.classList.remove('text-success', 'filename-success');
      }
    }
    if (err) {
      err.classList.add('d-none');
      err.style.display = 'none';
    }
    clearTextError(input);
  }



  function focusAndScroll(el) {
    if (!el) return;
    try {
      if (!el.hasAttribute('tabindex')) el.setAttribute('tabindex', '-1');
    } catch (e) {}
    try {
      el.focus({
        preventScroll: true
      });
    } catch (e) {}
    try {
      el.scrollIntoView({
        behavior: 'smooth',
        block: 'center'
      });
    } catch (e) {}
  }

  // ========= inicialización =========
  onReady(function() {
    const form = document.getElementById('form-nuevo-ingreso');
    if (!form) {
      console.error('[VAL] No encontré #form-nuevo-ingreso');
      return;
    }

    // desactiva validación nativa para que no tape la nuestra
    form.setAttribute('novalidate', 'novalidate');

    // asegura filename/error y listeners de cada input file requerido
    REQUIRED_FILE_IDS.forEach(id => {
      const input = document.getElementById(id);
      if (!input) {
        console.warn('[VAL] Input file requerido no encontrado:', id);
        return;
      }
      ensureFileScaffolding(input);

      // arma la dropzone si existe
      const zone = zoneOf(input);
      const fileLbl = fileLabelOf(input);

      if (zone) {
        if (!zone.hasAttribute('role')) zone.setAttribute('role', 'button');
        if (!zone.hasAttribute('tabindex')) zone.setAttribute('tabindex', '0');

        const openFile = () => input.click();

        zone.addEventListener('click', openFile);
        zone.addEventListener('keydown', (e) => {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            openFile();
          }
        });
        zone.addEventListener('dragover', (e) => {
          e.preventDefault();
          zone.classList.add('dragover');
        });
        zone.addEventListener('dragleave', (e) => {
          e.preventDefault();
          zone.classList.remove('dragover');
        });
        zone.addEventListener('drop', (e) => {
          e.preventDefault();
          zone.classList.remove('dragover');
          if (e.dataTransfer.files && e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files;
            if (fileLbl) fileLbl.textContent = Array.from(input.files).map(f => f.name).join(', ');
            fileLbl.classList.add('text-success'); // o: 'filename-success'
            fileLbl.classList.remove('text-danger');
            clearFileError(input);
          }
        });
      }

      input.addEventListener('change', function() {
        if (!fileLbl) return;
        if (input.files.length) {
          fileLbl.textContent = Array.from(input.files).map(f => f.name).join(', ');
          // pinta en VERDE
          fileLbl.classList.add('text-success'); // o: 'filename-success'
          fileLbl.classList.remove('text-danger');
          clearFileError(input, fileLbl);
        } else {
          fileLbl.textContent = 'Sin archivos seleccionados';
          // quita colores si no hay archivo
          fileLbl.classList.remove('text-success', 'text-danger', 'filename-success');
        }
      });

    });

    // blur/change para TODOS los required (no files)
    $$('[required]', form).forEach(field => {
      const handler = () => {
        if (field.type === 'file') {
          if (!field.files || !field.files.length) showFileError(field);
          else clearFileError(field);
          return;
        }
        const val = (field.value || '').trim();
        if (!val) showTextError(field, 'Este campo es obligatorio.');
        else clearTextError(field);
      };
      field.addEventListener('blur', handler);
      field.addEventListener('change', handler);
    });

    // teléfonos (funciona con o sin intl-tel-input)
    const itiMap = new Map();
    if (window.intlTelInput) {
      $$('input.telefono', form).forEach(input => {
        if (itiMap.has(input)) return;
        const country = (input.dataset.country || 've').toLowerCase();
        const iti = window.intlTelInput(input, {
          initialCountry: country,
          allowDropdown: false,
          separateDialCode: true,
          nationalMode: true,
          autoPlaceholder: 'polite',
          formatOnDisplay: true,
          placeholderNumberType: 'FIXED_LINE_OR_MOBILE',
          utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@22/build/js/utils.js'
        });
        iti.promise?.then(() => {
          if (input.value && input.value.trim() && window.intlTelInputUtils) {
            iti.setNumber(input.value);
            input.value = iti.getNumber(window.intlTelInputUtils.numberFormat.NATIONAL);
          }
        });
        input.addEventListener('blur', function() {
          const val = (input.value || '').trim();
          if (!val && input.hasAttribute('required')) {
            showTextError(input, 'Este campo es obligatorio.');
          } else if (val && !iti.isValidNumber()) {
            showTextError(input, 'Número de teléfono inválido.');
          } else {
            clearTextError(input);
          }
        });
        input.addEventListener('input', function() {
          input.value = input.value.replace(/[^\d\s\-().]/g, '');
        });
        itiMap.set(input, iti);
      });
    } else {
      $$('input.telefono', form).forEach(input => {
        input.addEventListener('blur', function() {
          const digits = (input.value || '').replace(/\D/g, '');
          if (input.hasAttribute('required') && digits.length < 7) {
            showTextError(input, 'Número de teléfono inválido.');
          } else {
            clearTextError(input);
          }
        });
      });
    }

    // ========= validación final al enviar =========
    form.addEventListener('submit', function(e) {
      let firstInvalid = null;

      // 1) required (incluye archivos)
      $$('[required]', form).forEach(field => {
        if (field.disabled) return;

        if (field.type === 'file') {
          if (!field.files || !field.files.length) {
            showFileError(field);
            if (!firstInvalid) firstInvalid = field;
          } else {
            clearFileError(field);
          }
        } else {
          const val = (field.value || '').trim();
          if (!val) {
            showTextError(field, 'Este campo es obligatorio.');
            if (!firstInvalid) firstInvalid = field;
          } else {
            clearTextError(field);
          }
        }
      });

      // 2) teléfonos
      if (window.intlTelInput && itiMap.size) {
        itiMap.forEach((iti, input) => {
          const val = (input.value || '').trim();
          if (val) {
            const full = iti.getNumber();
            if (full) input.value = full;
            if (!iti.isValidNumber()) {
              showTextError(input, 'Número de teléfono inválido.');
              if (!firstInvalid) firstInvalid = input;
            }
          } else if (input.hasAttribute('required')) {
            showTextError(input, 'Este campo es obligatorio.');
            if (!firstInvalid) firstInvalid = input;
          }
        });
      } else {
        $$('input.telefono[required]', form).forEach(input => {
          const digits = (input.value || '').replace(/\D/g, '');
          if (digits.length < 7) {
            showTextError(input, 'Número de teléfono inválido.');
            if (!firstInvalid) firstInvalid = input;
          }
        });
      }

      // 3) si hay errores -> bloquear y enfocar
      if (firstInvalid) {
        e.preventDefault();
        if (firstInvalid.type === 'file') {
          const zone = zoneOf(firstInvalid);
          focusAndScroll(zone || firstInvalid);
        } else {
          focusAndScroll(firstInvalid);
        }
      }
    });

    // logs de depuración (para confirmar que corre)
    console.info('[VAL] init ok', {
      requiredTotal: $$('[required]', form).length,
      requiredFiles: REQUIRED_FILE_IDS.filter(id => document.getElementById(id)).length
    });
  });
})();
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
@endsection