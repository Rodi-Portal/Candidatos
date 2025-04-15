<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">

  <title>Registro</title>
  <link rel="icon" href="{{ asset('favicon.jpg') }}" type="image/x-icon">

  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
  @vite(['resources/css/app.scss', 'resources/js/app.js'])
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
  span i.fas {
    color: white;
    background-color: #007bff;
    /* Azul */
    padding: 8px;
    border-radius: 50%;
    /* Círculo */
  }
  </style>
  <script>
  const idUsuario = @json($id_usuario);
  const idPortal = @json($id_portal);

  // Verificar en consola que los valores son correctos
  </script>
</head>

<body>
  <!-- Header -->
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light " id="menu">
      <a class="navbar-brand text-light" href="#">
        <img src="{{ url('logo/'.$archivo) }}" style="width: 70px; height: 50px; margin-right: 10px; margin-left: 30px;"
          alt="Portal Icon">
        <strong> {{ $cliente }}</strong>
      </a>
    </nav>
  </header>

  <div class="alert alert-info">
    <h5 class="text-center">Registra tu información para poder apoyarte a encontrar una vacante adecuada para ti de
      nuestra bolsa de trabajo</h5>
  </div>

  <div class="loader" style="display: none;"></div>

  <!-- Formulario -->
  <form action="{{ route('registro.store') }}" method="POST">
    @csrf

    <div class="contenedor mt-5">
      <div class="card">
        <h5 class="card-header text-center seccion">Datos personales</h5>
        <div class="card-body">
          <!-- Datos personales -->
          <div class="row">
            <div class="col-12 col-md-4 mb-3">
              <label class="text-center-label">Nombre(s) *</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-user"></i></span>
                </div>
                <input type="text" class="form-control" id="nombre" name="nombre"
                  onkeyup="this.value = this.value.toUpperCase();">
              </div>
            </div>
            <div class="col-12 col-md-4 mb-3">
              <label class="text-center-label">Primer apellido *</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-user"></i></span>
                </div>
                <input type="text" class="form-control" id="paterno" name="paterno"
                  onkeyup="this.value = this.value.toUpperCase();">
              </div>
            </div>
            <div class="col-12 col-md-4 mb-3">
              <label class="text-center-label">Segundo apellido</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-user"></i></span>
                </div>
                <input type="text" class="form-control" id="materno" name="materno"
                  onkeyup="this.value = this.value.toUpperCase();">
              </div>
            </div>
          </div>

          <!-- Domicilio -->
          <div class="row">
            <div class="col-12 mb-3">
              <label class="text-center-label">Domicilio *</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-home"></i></span>
                </div>
                <input type="text" class="form-control" id="domicilio" name="domicilio"
                  onkeyup="this.value = this.value.toUpperCase();">
              </div>
            </div>
          </div>

          <!-- Fecha de nacimiento, Teléfono, Nacionalidad -->
          <div class="row">
            <div class="col-12 col-md-4 mb-3">
              <label class="text-center-label">Fecha de nacimiento *</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                </div>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
              </div>
            </div>
            <div class="col-12 col-md-4 mb-3">
              <label class="text-center-label">Teléfono *</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                </div>
                <input type="number" class="form-control" id="telefono" name="telefono" maxlength="16">
              </div>
            </div>
            <div class="col-12 col-md-4 mb-3">
              <label class="text-center-label">Nacionalidad *</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-flag"></i></span>
                </div>
                <input type="text" class="form-control" id="nacionalidad" name="nacionalidad">
              </div>
            </div>
          </div>

          <!-- Estado Civil, Dependientes -->
          <div class="row">
            <div class="col-12 col-md-4 mb-3">
              <label class="text-center-label">Estado civil *</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-user"></i></span>
                </div>
                <select name="civil" id="civil" class="form-control">
                  <option value="">Selecciona</option>
                  @foreach ($civiles as $civ)
                  <option value="{{ $civ->nombre }}">{{ $civ->nombre }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-12 col-md-8 mb-3">
              <label class="text-center-label">Personas que dependan de ti *</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-users"></i></span>
                </div>
                <input type="text" class="form-control" id="dependientes" name="dependientes" value="Ninguna">
              </div>
            </div>
          </div>

          <!-- Grado máximo de estudios -->
          <div class="row">
            <div class="col-12 col-md-4 mb-3">
              <label class="text-center-label">Grado máximo de estudios *</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-user-graduate"></i></span>
                </div>
                <select name="grado_estudios" id="grado_estudios" class="form-control">
                  <option value="">Selecciona</option>
                  @foreach ($grados as $grado)
                  <option value="{{ $grado->nombre }}">{{ $grado->nombre }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>



    <!-- Sección: Salud y Vida Social -->
    <div class="contenedor mt-5">
      <div class="card">
        <h5 class="card-header text-center seccion">Salud y Vida Social</h5>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <label class="text-center-label">¿Cómo es tu estado de salud actual? *</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-heartbeat"></i></span>
                </div>
                <input type="text" class="form-control" id="salud" name="salud" value="Saludable">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <label class="text-center-label">¿Padeces alguna enfermedad crónica? *</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-virus"></i></span>
                </div>
                <input type="text" class="form-control" id="enfermedad" name="enfermedad" value="Ninguna">
              </div>
            </div>
            <div class="col-md-4">
              <label class="text-center-label">¿Practicas algún deporte? *</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-running"></i></span>
                </div>
                <input type="text" class="form-control" id="deporte" name="deporte" value="No">
              </div>
            </div>
            <div class="col-md-4">
              <label class="text-center-label">¿Cuáles son tus metas en la vida? *</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-flag-checkered"></i></span>
                </div>
                <input type="text" class="form-control" id="metas" name="metas">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Conocimientos y habilidades -->
    <div class="contenedor mt-5 mb-5">
      <div class="card">
        <h5 class="card-header text-center seccion">Conocimientos y Habilidades</h5>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <label class="text-center-label">Idiomas que dominas *</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-language"></i></span>
                </div>
                <input type="text" class="form-control" id="idiomas" name="idiomas">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <label class="text-center-label">Máquinas de oficina o taller que manejes *</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-cogs"></i></span>
                </div>
                <input type="text" class="form-control" id="maquinas" name="maquinas">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <label class="text-center-label">Software que conoces *</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-laptop"></i></span>
                </div>
                <input type="text" class="form-control" id="software" name="software">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="contenedor mt-5 my-5">
      <div class="card">
        <h5 class="card-header text-center seccion">Últimos empleos</h5>
        <h5 class="text-center mt-3 my-3">Por favor registre al menos un empleo, comenzando por el actual o el más
          reciente.
          Puede agregar un segundo empleo si lo desea, pero no es obligatorio. </h5>
        <div class="card-body">
          <?php
          for ($i = 1; $i <= 2; $i++) {?>
          <h5 class="text-center mt-3 my-3">Empleo #<?php echo $i ?></h5>
          <div class="row">
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="text-center-label">Nombre de la empresa</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-building"></i></span>
                </div>
                <input type="text" class="form-control" id="empresa<?php echo $i ?>" name="empresa<?php echo $i ?>">
              </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="text-center-label">Periodo laborado</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                </div>
                <input type="text" class="form-control" id="periodo<?php echo $i ?>" name="periodo<?php echo $i ?>">
              </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="text-center-label">Puesto desempeñado</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                </div>
                <input type="text" class="form-control" id="puesto<?php echo $i ?>" name="puesto<?php echo $i ?>">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="text-center-label">Último sueldo</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                </div>
                <input type="number" class="form-control" id="sueldo<?php echo $i ?>" name="sueldo<?php echo $i ?>">
              </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="text-center-label">Motivo de salida</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-door-open"></i></span>
                </div>
                <input type="text" class="form-control" id="causa_separacion<?php echo $i ?>"
                  name="causa_separacion<?php echo $i ?>">
              </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="text-center-label">Teléfono para solicitar referencia</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                </div>
                <input type="number" class="form-control" id="telefono_empleo<?php echo $i ?>"
                  name="telefono_empleo<?php echo $i ?>">
              </div>
            </div>
          </div>
          <?php }?>
        </div>
      </div>
    </div>

    <div class="contenedor mt-5 my-5">
      <div class="card">
        <h5 class="card-header text-center seccion">Intereses</h5>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="text-center-label">¿Cómo te enteraste de la bolsa de trabajo de <strong> {{ $cliente }}</strong> ? *</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-smile-beam"></i></span>
                </div>
                <input type="text" name="medio_contacto" id="medio_contacto" class="form-control">

              </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4"><br>
              <label class="text-center-label">¿Qué área es de tu interés?*</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-question-circle"></i></span>
                </div>
                <input type="text" class="form-control" id="area_interes" name="area_interes">
              </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4"><br>
              <label class="text-center-label">¿Qué sueldo deseas percibir?*</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-hand-holding-usd"></i></span>
                </div>
                <input type="number" class="form-control" id="sueldo_deseado" name="sueldo_deseado">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-4 col-lg-3"><br>
              <label class="text-center-label">¿Tienes otros ingresos? *</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-hand-holding-usd"></i></span>
                </div>
                <select class="form-control" id="otros_ingresos" name="otros_ingresos">
                  <option value="No" selected>No</option>
                  <option value="Sí">Sí</option>
                </select>
              </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-3">
              <label class="text-center-label">¿Tienes disponibilidad para viajar? *</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-plane"></i></span>
                </div>
                <select class="form-control" id="viajar" name="viajar">
                  <option value="Sí">Sí</option>
                  <option value="No" selected>No</option>
                </select>
              </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-6">
              <label class="text-center-label">¿Qué fecha o en qué momento podrías presentarte a trabajar? *</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                </div>
                <input type="text" class="form-control" id="trabajar" name="trabajar">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="contenedor mt-5 my-5">
      <div class="row">
        <div class="col-12">
          <label class="text-center-label">Deja tus comentarios </label>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-comment"></i></span>
            </div>
            <textarea name="comentario" id="comentario" class="form-control" rows="4"></textarea>
            <br>
          </div>
        </div>
      </div>
      <div class="alert alert-info">
        <label class="text-center-label" class="container_checkbox">
        <input type="checkbox" id="aviso" value="aviso">
          Confirmo que he leído y acepto el <a href="#" target="_blank">aviso de privacidad</a>, y declaro que la
          información proporcionada es verdadera.
          
        </label>
      </div>
    </div>

    <div class="contenedor mt-5 my-5">
      <button type="button" class="btn btn-primary btn-lg btn-block" onclick="enviar()">Enviar Información</button>
    </div>

  </form>



  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.0/dist/sweetalert2.all.min.js">
  </script>
  <script src="{{ asset('js/formulario.js') }}"></script>




</body>

</html>