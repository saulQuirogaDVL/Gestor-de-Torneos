@extends('layouts/head-login-sala')


@section('Cuerpo')
<!DOCTYPE html>
<html>
<head>
	<title></title>
	  <link href="../css/style-sala.css" rel="stylesheet">
	  <link href="../css/style-dota2.css" rel="stylesheet">
	  <link href="../css/style-dota2.css" rel="stylesheet" >
</head>
<body>
	<section id="hero"></section> 
	<section class="revicion-juego">
		<form method="post" action="{{ route('createSalaDota2') }}">
			@csrf
			<center>
				<?php
					use App\Models\equipos_dota_2;
					use App\Models\jugadores_dota_2;

					$equiposFixture = array($sala['numero_Equipos']);
					//for para randomizar los equipos
					for ($i=0; $i <$sala['numero_Equipos'] ; $i++) { 
						$controlador=0;
							while ($controlador==0) {
								$random=rand(1,$sala['numero_Equipos']);
								if(!in_array($random,$equiposFixture)){
									$equiposFixture[$i]=$random;
									$controlador=1;
								}
							}
					}


					//fixture de los equipos
					switch ($sala['numero_Equipos']) {
						case 4:
						$fixture='
						<table>
						    <tr class="1">
						    	<td><input type="text" name="cuartosFinal1" readonly class="tablasr"
							 	 		   value="'.($listaEquipos[$equiposFixture[0]]->nombre_Equipo).'"></td>
						    	<td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="2">
						    	<td></td><td><img src="../imagenes/versus.jpg" class="versusr"/></td>
						    	<td></td><td></td><td></td>
						    </tr>
						    <tr class="3">
						    	<td><input type="text" name="cuartosFinal2" readonly class="tablasr"						     	value="'.($listaEquipos[$equiposFixture[1]]->nombre_Equipo).'"></td>
						    	<td></td><td><input type="text" name="semiFinalista1" readonly="readonly" placeholder="Semi-final" 
						    	class="tablasr"></td>
						    	<td></td><td></td>
						    </tr>
						    <tr class="4">
						    	<td></td><td></td><td></td>
						    	<td><img src="../imagenes/versus.jpg" class="versusr"/></td>
						    	<td><input type="text" name="Ganador"  readonly="readonly" placeholder="Ganador" 
						    	class="tablasr" ></td>
						    </tr>
						    <tr class="5">
						    	<td><input type="text" name="cuartosFinal3" readonly class="tablasr"
						     	 			value="'.($listaEquipos[$equiposFixture[2]]->nombre_Equipo).'"></td>
						    	<td></td><td><input type="text" name="semiFinalista2" readonly="readonly" placeholder="Semi-final" 
						    	class="tablasr" ></td>
						    	<td></td><td></td>
						    </tr>
						    <tr class="6">
						    	<td></td><td><img src="../imagenes/versus.jpg" class="versusr"/></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="7">
						    	<td><input type="text" name="cuartosFinal4" readonly class="tablasr"
						     	 			value="'.($listaEquipos[$equiposFixture[3]]->nombre_Equipo).'"></td>
						    	<td></td><td></td><td></td><td></td>
						    </tr>
						<table>';

							 echo $fixture;
							break;
						case 8:
							 $fixture='
						<table>
						    <tr class="1">
						    	<td><input type="text" name="octavosFinal1" readonly="readonly" class="tablasr"
							 	 			value="'.($listaEquipos[$equiposFixture[0]]->nombre_Equipo).'"</td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="2">
						    	<td></td><td><img src="../imagenes/versus.jpg" class="versusr"/></td>
						    	<td><input type="text" name="cuartosFinal1" readonly="readonly" placeholder="Cuartos de Final 1" 
						    	class="tablasr"></td>
						    	<td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="3">
						    	<td><input type="text" name="octavosFinal2" readonly="readonly" class="tablasr"
							 	 			value="'.($listaEquipos[$equiposFixture[1]]->nombre_Equipo).'"</td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="4">
								<td></td><td></td><td></td><td><img src="../imagenes/versus.jpg" class="versusr"/></td>
								<td><input type="text" name="semiFinalista1" readonly="readonly" placeholder="Semi-final"
								class="tablasr"></td><td></td><td></td>
						    </tr>
						    <tr class="5">
								<td><input type="text" name="octavosFinal3" readonly="readonly" class="tablasr"
							 	 			value="'.($listaEquipos[$equiposFixture[2]]->nombre_Equipo).'"</td>
								<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="6">
								<td></td><td><img src="../imagenes/versus.jpg" class="versusr"/></td>
								<td><input type="text" name="cuartosFinal2" readonly="readonly" placeholder="Cuartos de Final 1"
								class="tablasr"></td>
								<td></td><td></td>
								<td></td><td></td>
						    </tr>
						    <tr class="7">
								<td><input type="text" name="octavosFinal4" readonly="readonly" class="tablasr"
							 	 			value="'.($listaEquipos[$equiposFixture[3]]->nombre_Equipo).'"</td>
								<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="8">
						    	<td></td><td></td><td></td><td></td><td></td>
						    	<td><img src="../imagenes/versus.jpg" class="versusr"/></td>
						    	<td><input type="text" name="Ganador" readonly="readonly" placeholder="Ganador" 
						    	class="tablasr"></td>
						    </tr>
						    <tr class="9">
								<td><input type="text" name="octavosFinal5" readonly="readonly" class="tablasr"
							 	 			value="'.($listaEquipos[$equiposFixture[4]]->nombre_Equipo).'"</td>
								<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="10">
								<td></td><td><img src="../imagenes/versus.jpg" class="versusr"/></td>
								<td><input type="text" name="cuartosFinal3" readonly="readonly" placeholder="Cuartos de Final 2" 
								class="tablasr"></td>
								<td></td><td></td>
								<td></td><td></td>
						    </tr>
						    <tr class="11">
								<td><input type="text" name="octavosFinal6" readonly="readonly" class="tablasr"
							 	 			value="'.($listaEquipos[$equiposFixture[5]]->nombre_Equipo).'"</td>
								<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="12">
								<td></td><td></td><td></td><td><img src="../imagenes/versus.jpg" class="versusr"/></td>
								<td><input type="text" name="semiFinalista2" readonly="readonly" placeholder="Semi-final"
								class="tablasr" ></td><td></td><td></td>
						    </tr>
						    <tr class="13">
								<td><input type="text" name="octavosFinal7" readonly="readonly" class="tablasr"
							 	 			value="'.($listaEquipos[$equiposFixture[6]]->nombre_Equipo).'"</td>
								<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="14">
								<td></td><td><img src="../imagenes/versus.jpg" class="versusr"/></td>
								<td><input type="text" name="cuartosFinal4" readonly="readonly" placeholder="Cuartos de Final 2" class="tablasr"></td>
								<td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="15">
								<td><input type="text" name="octavosFinal8" readonly="readonly" class="tablasr"
							 	 			value="'.($listaEquipos[$equiposFixture[7]]->nombre_Equipo).'"</td>
								<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						<table>';
							 echo $fixture;
							break;
						case 16:
							  $fixture='
						<table>
						    <tr class="1">
						    	<td><input type="text" name="clasificacion1" readonly="readonly" class="tablas2r"
							 	 			value="'.($listaEquipos[$equiposFixture[0]]->nombre_Equipo).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="2">
						    	<td></td><td><img src="../imagenes/versus.jpg" class="versus2r"/></td>
						    	<td><input type="text" name="octavosFinal1" readonly="readonly" placeholder="Octavos 1" 
						    	class="tablas2r"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="3">
						    	<td><input type="text" name="clasificacion2" readonly="readonly" class="tablas2r"
							 	 			value="'.($listaEquipos[$equiposFixture[1]]->nombre_Equipo).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="4">
						    	<td></td><td></td><td></td>
						    	<td><img src="../imagenes/versus.jpg" class="versus2r"/></td><td><input type="text" name="cuartosFinal1"  readonly="readonly" placeholder="Cuartos 1" class="tablas2r"></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="5">
						    	<td><input type="text" name="clasificacion3" readonly="readonly" class="tablas2r"
							 	 			value="'.($listaEquipos[$equiposFixture[2]]->nombre_Equipo).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="6">
						    	<td></td><td><img src="../imagenes/versus.jpg" class="versus2r"/></td>
						    	<td><input type="text" name="octavosFinal2" readonly="readonly" placeholder="Octavos 1"
						    	class="tablas2r"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="7">
						    	<td><input type="text" name="clasificacion4" readonly="readonly" class="tablas2r"
							 	 			value="'.($listaEquipos[$equiposFixture[3]]->nombre_Equipo).'"></td>
						    	<td></td><td></td><td></td>
						    	<td></td>
						    	<td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="8">
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td><img src="../imagenes/versus.jpg" class="versus2r"/><input type="text" name="semiFinalista1" readonly="readonly" placeholder="Semi-final"
						    	class="tablas2r" ></td><td></td><td></td>
						    </tr>
						    <tr class="9">
						    	<td><input type="text" name="clasificacion5" readonly="readonly" class="tablas2r"
							 	 			value="'.($listaEquipos[$equiposFixture[4]]->nombre_Equipo).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="10">
						    	<td></td><td><img src="../imagenes/versus.jpg" class="versus2r"/></td>
						    	<td><input type="text" name="octavosFinal3" readonly="readonly" placeholder="Octavos 2"
						    	class="tablas2r" ></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="11">
						    	<td><input type="text" name="clasificacion6" readonly="readonly" class="tablas2r"
							 	 			value="'.($listaEquipos[$equiposFixture[5]]->nombre_Equipo).'"></td>
						    	<td></td><td></td><td></td><td></td>
						    	<td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="12">
						    	<td></td><td></td><td></td>
						    	<td><img src="../imagenes/versus.jpg" class="versus2r"/></td><td><input type="text" name="cuartosFinal2"  readonly="readonly"placeholder="Cuartos 1"
						    	class="tablas2r" ></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="13">
						    	<td><input type="text" name="clasificacion7" readonly="readonly" class="tablas2r"
							 	 			value="'.($listaEquipos[$equiposFixture[6]]->nombre_Equipo).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="14">
						    	<td></td><td><img src="../imagenes/versus.jpg" class="versus2r"/></td>
						    	<td><input type="text" name="octavosFinal4"  readonly="readonly" placeholder="Octavos 2"
						    	class="tablas2r"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="15">
						    	<td><input type="text" name="clasificacion8" readonly="readonly" class="tablas2r"
							 	 			value="'.($listaEquipos[$equiposFixture[7]]->nombre_Equipo).'"></td>
						    	<td></td><td></td><td></td>
						    	<td></td><td></td>
						    	<td></td><td></td><td></td>
						    </tr>
						    <tr class="16">
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    	<td><img src="../imagenes/versus.jpg" class="versus2r"/></td>
						    	<td><input type="text" name="Ganador" readonly="readonly" placeholder="Ganador" 
						    	class="tablas2r"></td>
						    </tr>
						    <tr class="17">
						    	<td><input type="text" name="clasificacion9" readonly="readonly" class="tablas2r"
							 	 			value="'.($listaEquipos[$equiposFixture[8]]->nombre_Equipo).'"></td>
						    	<td></td><td></td><td></td>
						    	<td></td><td></td>
						    	<td></td><td></td><td></td>
						    </tr>
						    <tr class="18">
						    	<td></td><td><img src="../imagenes/versus.jpg" class="versus2r"/></td>
						    	<td><input type="text" name="octavosFinal5" readonly="readonly" placeholder="Octavos 3"
						    	class="tablas2r"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="19">
						    	<td><input type="text" name="clasificacion10" readonly="readonly" class="tablas2r"
							 	 			value="'.($listaEquipos[$equiposFixture[9]]->nombre_Equipo).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="20">
						    	<td></td><td></td><td></td>
						    	<td><img src="../imagenes/versus.jpg" class="versus2r"/></td><td><input type="text" name="cuartosFinal3"  readonly="readonly"  placeholder="Cuartos 2"
						    	class="tablas2r" ></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="21">
						    	<td><input type="text" name="clasificacion11" readonly="readonly" class="tablas2r"
							 	 			value="'.($listaEquipos[$equiposFixture[10]]->nombre_Equipo).'"></td>
						    	<td></td><td></td><td></td><td></td>
						    	<td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="22">
						    	<td></td><td><img src="../imagenes/versus.jpg" class="versus2r"/></td>
						    	<td><input type="text" name="octavosFinal6" readonly="readonly" placeholder="Octavos 3"
						    	class="tablas2r"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="23">
						    	<td><input type="text" name="clasificacion12" readonly="readonly" class="tablas2r"
							 	 			value="'.($listaEquipos[$equiposFixture[11]]->nombre_Equipo).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="24">
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td><img src="../imagenes/versus.jpg" class="versus2r"/><input type="text" name="semiFinalista2" readonly="readonly"  placeholder="Semi-final" 
						    	class="tablas2r"></td><td></td><td></td>
						    </tr>
						    <tr class="25">
						    	<td><input type="text" name="clasificacion13" readonly="readonly" class="tablas2r"
							 	 			value="'.($listaEquipos[$equiposFixture[12]]->nombre_Equipo).'"></td>
						    	<td></td><td></td><td></td>
						    	<td></td>
						    	<td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="26">
						    	<td></td><td><img src="../imagenes/versus.jpg" class="versus2r"/></td>
						    	<td><input type="text" name="octavosFinal7" readonly="readonly" placeholder="Octavos 4"
						    	class="tablas2r"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="27">
						    	<td><input type="text" name="clasificacion14" readonly="readonly" class="tablas2r"
							 	 			value="'.($listaEquipos[$equiposFixture[13]]->nombre_Equipo).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="28">
						    	<td></td><td></td><td></td>
						    	<td><img src="../imagenes/versus.jpg" class="versus2r"/></td><td><input type="text" name="cuartosFinal4" readonly="readonly" placeholder="Cuartos 2" 
						    	class="tablas2r"></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="29">
						    	<td><input type="text" name="clasificacion15" readonly="readonly" class="tablas2r"
							 	 			value="'.($listaEquipos[$equiposFixture[14]]->nombre_Equipo).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="30">
						    	<td></td><td><img src="../imagenes/versus.jpg" class="versus2r"/></td>
						    	<td><input type="text" name="octavosFinal8" readonly="readonly" placeholder="Octavos 4"
						    	class="tablas2r"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="31">
						    	<td><input type="text" name="clasificacion16" readonly="readonly" class="tablas2r"
							 	 			value="'.($listaEquipos[$equiposFixture[15]]->nombre_Equipo).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						<table><br><br><br><br>';
							 echo $fixture;
							break;
					}
				?>
		<div class="container-fluid creacion-sala">
		<div class="row">
			<div class="col-12 "><h1 class="titulor">Detalles de la sala</h1></div>
			<input type="hidden" name="codigoUsuario" id="codigoUsuario" value="1">
			<div class="col-12"><h3><input type="text" readonly="readonly" name="nombreTorneo" id="nombreTorneo"  
			 class="titulor"  value="{{ $sala['nombre_Torneo'] }}"></h3></div>
		</div>
		<div class="row">
			<div class="col-12">
			<img src="{{ $sala['logo'] }}" style="width: 200px; height: 200px;" name="logo" class="logosr"></div>
		</div>
		<div class="row">
			<div class="col-3 col-md-3 col-lg-3 col-xl-3 pt-3 d-none  d-md-block"></div>
			<div class="col-6 col-md-3 col-lg-3 col-xl-3"><p class="letrar">tipo de eliminacion:</p></div>
			<div class="col-6 col-md-3 col-lg-3 col-xl-3"><input type="text" readonly="readonly" name="nombreTorneo" id="nombreTorneo" value="{{ $sala['tipo_Eliminacion'] }}" class="letra2r"></div>
			<div class="col-3 col-md-3 col-lg-3 col-xl-3 pt-3 d-none  d-md-block"></div>	
			<div class="col-3 col-md-3 col-lg-3 col-xl-3 pt-3 d-none  d-md-block"></div>
			<div class="col-6 col-md-3 col-lg-3 col-xl-3"><p class="letrar">Modo de juego:</p></div>
			<div class="col-6 col-md-3 col-lg-3 col-xl-3"><input type="text" readonly="readonly" name="nombreTorneo" id="nombreTorneo" value="{{ $sala['modo_Juego'] }}" class="letra2r"></div>
			 <div class="col-3 col-md-3 col-lg-3 col-xl-3 pt-3 d-none  d-md-block"></div>
			 <div class="col-3 col-md-3 col-lg-3 col-xl-3 pt-3 d-none  d-md-block"></div>
			 <div class="col-6 col-md-3 col-lg-3 col-xl-3"><p class="letrar">Numero de equipos:</p> </div>
			 <div class="col-6 col-md-3 col-lg-3 col-xl-3"><input type="text" readonly="readonly" name="nombreTorneo" id="nombreTorneo" value="{{ $sala['numero_Equipos'] }}" class="letra2r"></div>
			 <div class="col-3 col-md-3 col-lg-3 col-xl-3 pt-3 d-none  d-md-block"></div>
			 <div class="col-3 col-md-4 col-lg-4 col-xl-4 pt-4 d-none  d-md-block"></div>
			 <div class="col-12 col-md-4 col-lg-4 col-xl-4 buton randomr"><a href="#" onclick="window.location.reload(true);">randomizar equipos</a></div>
			 <div class="col-3 col-md-4 col-lg-4 col-xl-4 pt-4 d-none  d-md-block"></div>
			 <div class="col-3 col-md-3 col-lg-3 col-xl-3 pt-3 d-none  d-md-block"></div>
			 <div class="col-6 col-md-3 col-lg-3 col-xl-3"><button class="butonr">crear sala</button></div>
			 <div class="col-6 col-md-3 col-lg-3 col-xl-3"><input type="buttonr" class="butonr" onclick="ConfirmCancel()" value="cancelar creacion" /></div>
			<div class="col-3 col-md-3 col-lg-3 col-xl-3 pt-3 d-none  d-md-block"></div>
		</div></div>

			<!--envio de los equipos-->
			<?php
				$arrayNombreEquipos = array();
				$arrayNombreJugadores = array();
				$contArray=0;
				for ($i=1; $i <= $sala['numero_Equipos'] ; $i++){
		              	$arrayNombreEquipos[$i]=$listaEquipos[$i]->nombre_Equipo." ";
		              	for ($j=1; $j <=5 ; $j++) { 
		              		$arrayNombreJugadores[$contArray]=$listaJugadores[$contArray]->nickname." ";
		              		$contArray++;
		              	}
        		 }

			?>
		  <input type="hidden" readonly name="arrayEquipos" value="<?php echo implode(",", $arrayNombreEquipos); ?>">
		  <input type="hidden" readonly name="arrayJugadores" value="<?php echo implode(",", $arrayNombreJugadores); ?>">
	</center>
  <form action="#">
</section>
<?php
			
			 //envio de informacion por debajo
			//envio de la sala
 			  echo '<input type="hidden" value="'.$sala['codigo_Usuario'].'" name="codigo_Usuario">';
			  echo '<input type="hidden" value="'.$sala['nombre_Torneo'].'" name="nombre_Torneo">';
			  echo '<input type="hidden" value="'.$sala['logo'].'" name="logo">';
			  echo '<input type="hidden" value="'.$sala['tipo_Eliminacion'].'" name="tipo_Eliminacion">';
			  echo '<input type="hidden" value="'.$sala['modo_Juego'].'" name="modo_Juego">';
			  echo '<input type="hidden" value="'.$sala['numero_Equipos'].'" name="numero_Equipos">';
			  echo '<input type="hidden" value="'.$sala['equipo_ganador'].'" name="equipo_ganador">';
		   

?>
</body>

</html>
<!--los scripts van aqui-->
<script type="text/javascript">
function ConfirmCancel() {
		//Ingresamos un mensaje a mostrar
		var mensaje = confirm("esta seguro que desea cancelar la creacion de la sala?");
		//Detectamos si el usuario acepto el mensaje
		if (mensaje) {
			alert("la creacion a sido cancelada!");
			window.location="/menu-usuario";
		}

	}		

</script>
@endsection