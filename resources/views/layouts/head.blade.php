<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gaming Umpires</title>
     <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <header id="header">
    <div class="container">

      <div id="logo" class="pull-left">
        <h1><a href="/">Gaming Umpires</a></h1>
      </div>

      <nav id="nav-menu-container" >
        <ul class="nav-menu">

          <li class="menu-active"><a href="/">Inicio</a></li>
          <li><a href="#about">Acerca de</a></li>
          <li><a href="#services">Servicios</a></li>
          <li><a href="#team">Equipo</a></li>
          <li><a href={{ route('Partidasrecientes') }}>Recientes</a></li>
            @if (Route::has('login'))
                    @auth
                      <li><a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 underline">Panel</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Inicio de sesion</a></li>
                        @if (Route::has('register'))
                            <li><a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Registro</a></li>
                        @endif
                    @endif
            @endif
        </ul>
      </nav>
    </div>
  </header>
  @yield('Cuerpo')
</body>
</html>