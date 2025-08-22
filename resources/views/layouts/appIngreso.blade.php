<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', session('cliente', 'Cliente'))</title>

  {{-- Tu build / estilos --}}
  @vite(['resources/css/app.scss', 'resources/js/app.js'])

  {{-- Bootstrap (si lo usas) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- intl-tel-input CSS --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@22/build/css/intlTelInput.min.css">
</head>
<body>
  <div id="app">
    @yield('content')
  </div>

  {{-- intl-tel-input JS (DEBE ir antes de tu script de init) --}}
  <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@22/build/js/intlTelInput.min.js"></script>
</body>
</html>


