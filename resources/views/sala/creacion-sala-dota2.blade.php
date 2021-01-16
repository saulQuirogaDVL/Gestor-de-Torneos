@extends('layouts/head-login-sala')


@section('Cuerpo')
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Allerta+Stencil">
	<link href="css/style-dota2.css" rel="stylesheet" >
</head>
<body>
<section id="hero"></section> 
<section id="creacion-sala"> 
	<center>
		<h1 class="titulo">Selecciona las reglas del juego</h1>
		<form method="post" action="{{ route('add-Model-Sala') }}" enctype="multipart/form-data">
				@csrf
				<div class="container-fluid creacion-sala">
					<div class="row">
						<div class="col-12 col-md-3 col-lg-3 col-xl-3 pt-3 d-none  d-md-block"></div>
						<div class="col-6  col-md-3 col-lg-3 col-xl-3"><p class="letra">Nombre del torneo: </p></div>
						<div class="col-6  col-md-3 col-lg-3 col-xl-3"><input type="text" name="nombreTorneo" id="nombreTorneo" value="{{ old('nombreTorneo') }}" class="inputxt" maxlength="50"></div>
						<div class="col-12"><p class="error">{{ $errors->first('nombreTorneo') }}</p></div>
						<div class="col-12 col-md-3 col-lg-3 col-xl-3 pt-3 d-none  d-md-block "></div>
					</div>	
					<div class="row">
						<div class="col-12 col-md-3 col-lg-3 col-xl-3 pt-3 d-none  d-md-block"></div>
						<div class="col-6  col-md-3 col-lg-3 col-xl-3"><p class="letra">logo:</p></div>
						<div class="col-6  col-md-3 col-lg-3 col-xl-3 " ><img src="../imagenes/LogoFinal2.png" style="width: 200px; height: 200px;" class="imagenes imagen-redonda"></div>
						<div class="col-12 col-md-3 col-lg-3 col-xl-3 pt-3 d-none  d-md-block "></div>
						<div class="col-12"><input type="file" name="logo" id="logo" accept="image/*" value="{{ old('logo') }}" 
						class="letra"></div>
						<div class="col-12"><p class="error">{{ $errors->first('logo')}}</p></div>
					</div>
					<div class="row">
						<div class="col-12 col-md-3 col-lg-3 col-xl-3 pt-3 d-none  d-md-block"></div>
						<div class="col-6  col-md-3 col-lg-3 col-xl-3"><p class="letra">tipo de eliminacion: </p></div>
						<div class="col-6  col-md-3 col-lg-3 col-xl-3"><select name="tipoEliminacion" id="tipoEliminacion" value="{{ old('tipoEliminacion') }}" class="selector">
  								<option value="BO3">BO3</option>
  								<option value="BO5">BO5</option>
  								<option value="Eliminacion Directa">Eliminacion Directa</option>
							</select></div>
						<div class="col-12"><p class="error">{{ $errors->first('tipoEliminacion')}}</p></div>
						<div class="col-12 col-md-3 col-lg-3 col-xl-3 pt-3 d-none  d-md-block "></div>
					</div>
					<div class="row">
						<div class="col-12 col-md-3 col-lg-3 col-xl-3 pt-3 d-none  d-md-block"></div>
						<div class="col-6  col-md-3 col-lg-3 col-xl-3"><p class="letra">Modo de juego: </p></div>
						<div class="col-6  col-md-3 col-lg-3 col-xl-3"><select name="modoJuego" id="modoJuego" value="{{ old('modoJuego') }}" class="selector">
  								<option value="All pick">All pick</option>
  								<option value="Captain Mode">Captain Mode</option>
  								<option value="Simple Selection">Simple Selection</option>
					  </select></div>
						<div class="col-12"><p class="error">{{ $errors->first('modoJuego') }}</p></div>
						<div class="col-12 col-md-3 col-lg-3 col-xl-3 pt-3 d-none  d-md-block "></div>
					</div>
					<div class="row">
						<div class="col-12 col-md-3 col-lg-3 col-xl-3 pt-3 d-none  d-md-block"></div>
						<div class="col-6  col-md-3 col-lg-3 col-xl-3"><p class="letra">Numero de equipos:</p></div>
						<div class="col-6  col-md-3 col-lg-3 col-xl-3"><select name="numeroEquipos" id="numeroEquipos" value="{{ old('numeroEquipos') }}" class="selector">
  								<option value="4">4</option>
  								<option value="8">8</option>
  								<option value="16">16</option>
					  </select></div>
						<div class="col-12"><p class="error">{{ $errors->first('numeroEquipos') }}</p></div>
						<div class="col-12 col-md-3 col-lg-3 col-xl-3 pt-3 d-none  d-md-block "></div>
					</div>

					<div class="row">
						<div class="col-12"><button class="buton">continuar</button></div>
					</div>
				</div>		
		</form>
	</center>
</section>
</body>
</html>
@endsection


