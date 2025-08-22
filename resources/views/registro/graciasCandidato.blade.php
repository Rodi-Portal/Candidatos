@extends('layouts.app')

@section('content')
<div class="container my-5 text-center">
  <h2 style="color: #0C9DD3;" class="mb-3">¡Formulario enviado correctamente!</h2>
  <p>
    Gracias por compartir sus datos de contacto y los métodos por los cuales recibirá su pago.<br>
    Le pedimos estar pendiente de los medios proporcionados, ya que por ellos se le dará seguimiento a su proceso.<br>
    ¡Le deseamos un excelente día!
  </p>
</div>

{{-- Evitar volver atrás --}}
<script>
  (function () {
    window.history.pushState(null, "", window.location.href);
    window.onpopstate = function () {
      window.history.pushState(null, "", window.location.href);
    };
  })();
</script>
@endsection
