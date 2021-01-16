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
<div class="container-fluid seleccion-juego">

	<div class="row justify-content-center">
		<div class="col-12  mt-5 pt-5 titulocs">
			<h1 class="w-100">Seleccione un juego para la creacion de la sala</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-md-2 col-lg-2 col-xl-3 pt-3 d-none  d-md-block imagenes"></div>
		<div class="col-12 col-md-4 col-lg-4 col-xl-3 pt-3  imagenes">
			<a href="{{ route('RCrearTorneo') }}"><img class="mx-auto d-block imagen-redondaw" width="250" height="250"  
				id="imagen_redonda"   src="../imagenes/wowlogo.jpg" ></a>
		</div>		
		<div class="col-none col-md-4 col-lg-4 col-xl-3 pt-3 imagenes">
			<a href="/creacion-sala-dota2"><img class="mx-auto d-block imagen-redonda " width="250" height="250" id="imagen_redonda"   src="../imagenes/dota2logo.jpg"></a>
		</div>
		<div class="col-12 col-md-2 col-lg-2 col-xl-3 pt-3 d-none  d-md-block imagenes"></div>
		<div class="col-12 espaciado"></div>
	</div>
</div>
</body>
</html>
@endsection



<!--estilos hasta llegar a la parte de  documentos-->
<style type="text/css">

</style>



