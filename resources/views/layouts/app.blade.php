<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
<title>@yield('title', session('cliente', 'Cliente'))</title>
<link rel="icon" href="{{ asset('favicon.jpg') }}" type="image/x-icon">
  @vite(['resources/css/app.scss', 'resources/js/app.js'])
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@22/build/css/intlTelInput.min.css">
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4" id="menu">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="{{ url('logo/'.( session('logo', 'portal_icon.png') )) }}" style="width: 54px; height: 40px; margin-right: 14px;" alt="Portal Icon">
        <strong style="font-size: 1.2em;">{{ session('cliente', 'Cliente') }}</strong>
      </a>
    </div>
  </nav>

  <!-- Contenido -->
  <div id="app">
    @yield('content')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@22/build/js/intlTelInput.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@22/build/js/utils.js"></script>
</body>

</html>
