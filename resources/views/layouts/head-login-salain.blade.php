<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gaming Umpires</title>
     <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link href="../../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link href="../../lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../../lib/animate/animate.min.css" rel="stylesheet">
    <link href="../../css/style-login.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
    <header id="header">
    <div class="container">

      <div id="logo" class="pull-left">
        <h1><a href="/">Gaming Umpires</a></h1>
      </div>

      <nav id="nav-menu-container" >
        <ul class="nav-menu">
          <li class="menu-active"><a href="/menu-usuario">Inicio</a></li>
          <li><a href="seleccion-juego">Crear Sala</a></li>
          <li><a href="salas-creadas">Salas Creadas</a></li>
            @if (Route::has('login'))
                    @auth
                      <li><a href="{{ url('/user/profile') }}" class="text-sm text-gray-700 underline">Perfil</a></li>
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