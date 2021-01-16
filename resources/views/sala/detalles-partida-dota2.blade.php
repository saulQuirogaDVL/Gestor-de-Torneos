@extends('layouts/head-login-salain')


@section('Cuerpo')
<!DOCTYPE html>
<html>
<head>
	<title></title>
    <link href="../../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Allerta+Stencil">
	<link rel="stylesheet" type="text/css" href="../../css/style-detalles-d2.css">
	<script src="../../lib/jquery/html2pdf.bundle.min.js"></script>
</head>
<body>
<section id="hero"></section>
<section class="detalles-partida">
	<?php
	         echo '<input type="hidden" id="id" value="'.$id.'">';
			 $salaDatos = DB::table('sala_dota_2')
                                            ->where("id","=",$id)
                                            ->get();
            //lista de los equipos y encuentros
             $encuentrosDatos = DB::table('encuentros_dota2')
                                            ->where("codigo_Sala","=",$id)
                                            ->get();   
             //convirtiendo el tipo de eliminacion a numero
             $eliminacion=1;
             if($salaDatos[0]->tipo_Eliminacion=='BO3'){
             	$eliminacion=3;
             }elseif ($salaDatos[0]->tipo_Eliminacion=='BO5') {
             	$eliminacion=5;
             }

             //opciones para ver los detalles de una partida
             $options='';
             for ($i=1; $i <=$eliminacion; $i++) { 
             	$options.='<option  value="'.$i.'" >partida:'.$i.'</option>';
             }
             echo '<h1 class="subtitulo"> Codigo de la Sala: '.$salaDatos[0]->id.'</h1>';
             echo '<h1 class="titulo">'.$salaDatos[0]->nombre_Torneo.'</h1>';
             echo '<input type="hidden" id="numeroE" value="'.$salaDatos[0]->numero_Equipos.'">';
             
			//fixture de los equipos
					switch ($salaDatos[0]->numero_Equipos) {
						case 4:
						$fixture='
						<center><table>
						    <tr class="1">
						    	<td><input type="text" name="cuartosFinal1" readonly="readonly" class="tablas4"
							 	 id="cuartosFinal1"	value="'.($encuentrosDatos[0]->equipo_1).'"></td>
						    	<td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="2">
						    	<td></td><td><button id="cuartos1vs2" class="detalles4">Ver Detalles</button>
								<select id="matchc1vs2" class="select4">'.$options.'</select></td>
						    	<td></td><td></td><td></td>
						    </tr>
						    <tr class="3">
						    	<td><input type="text" name="cuartosFinal2" readonly="readonly" class="tablas4"
						     	 id="cuartosFinal2" value="'.($encuentrosDatos[0]->equipo_2).'"></td>
						    	<td></td>
						    	<td><input type="text" name="semiFinalista1" readonly="readonly" class="tablas4"
						    	value="'.($encuentrosDatos[2]->equipo_1).'" id="semiFinalista1" ></td>
						    	<td></td><td></td>
						    </tr>
						    <tr class="4">
						    	<td></td><td></td><td></td>
						    	<td><button id="semifinal"  class="detalles4">Ver Detalles</button>
						    	<select id="matchsf" class="select4">'.$options.'</select></td></td>
						    	<td><input type="text" name="Ganador"  readonly="readonly" class="tablas4"
						    	value="'.($salaDatos[0]->equipo_Ganador).'" ></td>
						    </tr>
						    <tr class="5">
						    	<td><input type="text" name="cuartosFinal3"  id="cuartosFinal3" class="tablas4"
						     	 			value="'.$encuentrosDatos[1]->equipo_1.'"></td>
						    	<td></td><td><input type="text" name="semiFinalista2" readonly="readonly" class="tablas4"
						    	 value="'.($encuentrosDatos[2]->equipo_2).'"  id="semiFinalista2"></td>
						    	<td></td><td></td>
						    </tr>
						    <tr class="6">
						    	<td></td><td><button id="cuartos3vs4"  class="detalles4">Ver Detalles</button>
						    	<select id="matchc3vs4" class="select4">'.$options.'</select></td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="7">
						    	<td><input type="text" name="cuartosFinal4"  id="cuartosFinal4" class="tablas4"
						     	 			value="'.($encuentrosDatos[1]->equipo_2).'"></td>
						    	<td></td><td></td><td></td><td></td>
						    </tr>
						</table></center><div class="col-12 espaciado"></div>';

							 echo $fixture;
							break;
						case 8:
							 $fixture='
						<center><table>
						    <tr class="1">
						    	<td><input type="text" name="octavosFinal1" id="octavosFinal1" readonly="readonly"
							 	 	class="tablas"	value="'.($encuentrosDatos[0]->equipo_1).'"</td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="2">
						    	<td></td><td><button id="octavose1ye2" class="detalles">Ver Detalles</button>
						    	<select id="matcho1vs2" class="select">'.$options.'</select></td>
						    	<td><input type="text" name="cuartosFinal1" id="cuartosFinal1" readonly="readonly" class="tablas"
						    	value="'.($encuentrosDatos[4]->equipo_1).'"></td>
						    	<td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="3">
						    	<td><input type="text" name="octavosFinal2" id="octavosFinal2"  readonly="readonly"
							 	 		class="tablas"	value="'.($encuentrosDatos[0]->equipo_2).'"</td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="4">
								<td></td><td></td><td></td><td><button id="cuartos1vs2" class="detalles">Ver Detalles</button>
								<select id="matchc1vs2" class="select">'.$options.'</select></td>
								<td></td><td></td><td></td>
						    </tr>
						    <tr class="5">
								<td><input type="text" name="octavosFinal3" id="octavosFinal3"  readonly="readonly"
							 	 		class="tablas"	value="'.($encuentrosDatos[1]->equipo_1).'"</td>
								<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="6">
								<td></td><td><button id="octavose3ye4" class="detalles">Ver Detalles</button>
								<select id="matcho3vs4" class="select">'.$options.'</select></td>
								<td><input type="text" name="cuartosFinal2" id="cuartosFinal2" readonly="readonly" class="tablas"
								value="'.($encuentrosDatos[4]->equipo_2).'"></td>
								<td></td><td><input type="text" name="semiFinalista1" id="semiFinalista1" readonly="readonly" class="tablas"
								value="'.($encuentrosDatos[6]->equipo_1).'"></td>
								<td></td><td></td>
						    </tr>
						    <tr class="7">
								<td><input type="text" name="octavosFinal4" id="octavosFinal4"  readonly="readonly"
							 	 			class="tablas" value="'.($encuentrosDatos[1]->equipo_2).'"</td>
								<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="8">
						    	<td></td><td></td><td></td><td></td><td></td>
						    	<td><button id="semifinal" class="detalles">Ver Detalles</button>
						    	<select id="matchsf" class="select">'.$options.'</select></td>
						    	<td><input type="text" name="Ganador" readonly="readonly" placeholder="Ganador" class="tablas"
						    	value="'.($salaDatos[0]->equipo_Ganador).'"></td>
						    </tr>
						    <tr class="9">
								<td><input type="text" name="octavosFinal5" id="octavosFinal5"  readonly="readonly"
							 	 		class="tablas"	value="'.($encuentrosDatos[2]->equipo_1).'"</td>
								<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="10">
								<td></td><td><button id="octavose5ye6" class="detalles">Ver Detalles</button>
								<select id="matcho5vs6" class="select">'.$options.'</select></td>
								<td><input type="text" name="cuartosFinal3" id="cuartosFinal3" readonly="readonly" class="tablas"
								value="'.($encuentrosDatos[5]->equipo_1).'"></td>
								<td></td><td><input type="text" name="semiFinalista2" id="semiFinalista2" readonly="readonly" class="tablas"
								value="'.($encuentrosDatos[6]->equipo_2).'" ></td>
								<td></td><td></td>
						    </tr>
						    <tr class="11">
								<td><input type="text" name="octavosFinal6" id="octavosFinal6"  readonly="readonly"
							 	 	class="tablas"	value="'.($encuentrosDatos[2]->equipo_2).'"</td>
								<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="12">
								<td></td><td></td><td></td><td><button id="cuartos3vs4" class="detalles">Ver Detalles</button>
								<select id="matchc3vs4" class="select">'.$options.'</select></td>
								<td></td><td></td><td></td>
						    </tr>
						    <tr class="13">
								<td><input type="text" name="octavosFinal7" id="octavosFinal7"  readonly="readonly"
							 	 	class="tablas"	value="'.($encuentrosDatos[3]->equipo_1).'"</td>
								<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="14">
								<td></td><td><button id="octavose7ye8" class="detalles">Ver Detalles</button>
								<select id="matcho7vs8" class="select">'.$options.'</select></td>
								<td><input type="text" name="cuartosFinal4" id="cuartosFinal4" readonly="readonly" class="tablas"
								value="'.($encuentrosDatos[5]->equipo_2).'" ></td>
								<td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="15">
								<td><input type="text" name="octavosFinal8" id="octavosFinal8"  readonly="readonly"
							 	 	class="tablas"	value="'.($encuentrosDatos[3]->equipo_2).'"</td>
								<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						</table></center>';
							 echo $fixture;
							break;
						case 16:
							  $fixture='
						<table>
						    <tr class="1">
						    	<td><input type="text" name="clasificacion1" readonly="readonly" class="tablas"
							 	 	id="clasificacion1" value="'.($encuentrosDatos[0]->equipo_1).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="2">
						    	<td></td><td><button id="clasie1ye2" class="detalles">Ver Detalles</button>
						    	<select id="matchcl1vs2" class="select">'.$options.'</select></td>
						    	<td><input type="text" name="octavosFinal1" id="octavosFinal1" readonly="readonly" 
						    	class="tablas" value="'.($encuentrosDatos[8]->equipo_1).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="3">
						    	<td><input type="text" name="clasificacion2" readonly="readonly" class="tablas"
							 	 	id="clasificacion2"	value="'.($encuentrosDatos[0]->equipo_2).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="4">
						    	<td></td><td></td><td></td>
						    	<td><button id="octavose1ye2" class="detalles">Ver Detalles</button>
						    	<select id="matcho1vs2" class="select">'.$options.'</select></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="5">
						    	<td><input type="text" name="clasificacion3" id="clasificacion3" readonly="readonly"
							 	 class="tablas"	value="'.($encuentrosDatos[1]->equipo_1).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="6">
						    	<td></td><td><button id="clasie3ye4" class="detalles">Ver Detalles</button>
						    	<select id="matchcl3vs4" class="select">'.$options.'</select></td>
						    	<td><input type="text" name="octavosFinal2" id="octavosFinal2" readonly="readonly"
						    	class="tablas" 
						    	value="'.($encuentrosDatos[8]->equipo_2).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="7">
						    	<td><input type="text" name="clasificacion4" id="clasificacion4" readonly="readonly"
							 	 class="tablas"	value="'.($encuentrosDatos[1]->equipo_2).'"></td>
						    	<td></td><td></td><td></td>
						    	<td><input type="text" name="cuartosFinal1" 
						    	id="cuartosFinal1" readonly="readonly" class="tablas" 
						    	value="'.($encuentrosDatos[12]->equipo_1).'"></td>
						    	<td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="8">
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="9">
						    	<td><input type="text" name="clasificacion5" id="clasificacion5" readonly="readonly"
							 	 class="tablas"	value="'.($encuentrosDatos[2]->equipo_1).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="10">
						    	<td></td><td><button id="clasie5ye6" class="detalles">Ver Detalles</button>
						    	<select id="matchcl5vs6" class="select">'.$options.'</select></td>
						    	<td><input type="text" id="octavosFinal3" name="octavosFinal3" readonly="readonly"
						    	class="tablas"
						    	value="'.($encuentrosDatos[9]->equipo_1).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="11">
						    	<td><input type="text" name="clasificacion6" id="clasificacion6" readonly="readonly"
							 	 class="tablas"	value="'.($encuentrosDatos[2]->equipo_2).'"></td>
						    	<td></td><td></td><td></td><td></td>
						    	<td><button id="cuartos1vs2" class="detalles">Ver Detalles</button>
						    	<select id="matchc1vs2" class="select">'.$options.'</select></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="12">
						    	<td></td><td></td><td></td>
						    	<td><button id="octavose3ye4" class="detalles">Ver Detalles</button>
						    	<select id="matcho3vs4" class="select">'.$options.'</select></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="13">
						    	<td><input type="text" name="clasificacion7" id="clasificacion7" readonly="readonly"
							 	 class="tablas"	value="'.($encuentrosDatos[3]->equipo_1).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="14">
						    	<td></td><td><button id="clasie7ye8" class="detalles">Ver Detalles</button>
						    	<select id="matchcl7vs8" class="select">'.$options.'</select></td>
						    	<td><input type="text" name="octavosFinal4" id="octavosFinal4" readonly="readonly" class="tablas" value="'.($encuentrosDatos[9]->equipo_2).'"</td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="15">
						    	<td><input type="text" name="clasificacion8" id="clasificacion8" readonly="readonly"
							 	 class="tablas"	value="'.($encuentrosDatos[3]->equipo_2).'"</td>
						    	<td></td><td></td><td></td>
						    	<td><input type="text" name="cuartosFinal2" id="cuartosFinal2"  readonly="readonly" 
						    	class="tablas"
						    	value="'.($encuentrosDatos[12]->equipo_2).'"></td><td></td>
						    	<td><input type="text" name="semiFinalista1" id="semiFinalista1" readonly="readonly" class="tablas"
						    	value="'.($encuentrosDatos[14]->equipo_1).'" ></td><td></td><td></td>
						    </tr>
						    <tr class="16">
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    	<td><button id="semifinal" class="detalles">Ver Detalles</button>
						    	<select id="matchsf" class="select">'.$options.'</select></td>
						    	<td><input type="text" name="Ganador" readonly="readonly" class="tablas"
						    	value="'.($salaDatos[0]->equipo_Ganador).'" ></td>
						    </tr>
						    <tr class="17">
						    	<td><input type="text" name="clasificacion9" id="clasificacion9" readonly="readonly"
							 	 class="tablas"	value="'.($encuentrosDatos[4]->equipo_1).'"></td>
						    	<td></td><td></td><td></td>
						    	<td><input type="text" name="cuartosFinal3" id="cuartosFinal3" readonly="readonly"  class="tablas"
						    	value="'.($encuentrosDatos[13]->equipo_1).'"></td><td></td>
						    	<td><input type="text" name="semiFinalista2" id="semiFinalista2" readonly="readonly"  class="tablas"
						    	value="'.($encuentrosDatos[14]->equipo_2).'"></td><td></td><td></td>
						    </tr>
						    <tr class="18">
						    	<td></td><td><button id="clasie9ye10" class="detalles">Ver Detalles</button>
						    	<select id="matchcl9vs10" class="select">'.$options.'</select></td>
						    	<td><input type="text" name="octavosFinal5" id="octavosFinal5" readonly="readonly" class="tablas" 
						    	value="'.($encuentrosDatos[10]->equipo_1).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="19">
						    	<td><input type="text" name="clasificacion10" id="clasificacion10" readonly="readonly"
							 	 	class="tablas" value="'.($encuentrosDatos[4]->equipo_2).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="20">
						    	<td></td><td></td><td></td>
						    	<td><button id="octavose5ye6" class="detalles">Ver Detalles</button>
						    	<select id="matcho5vs6" class="select">'.$options.'</select></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="21">
						    	<td><input type="text" name="clasificacion11" id="clasificacion11" readonly="readonly"
							 	 	class="tablas"	value="'.($encuentrosDatos[5]->equipo_1).'"></td>
						    	<td></td><td></td><td></td><td></td>
						    	<td><button id="cuartos3vs4" class="detalles">Ver Detalles</button>
						    	<select id="matchc3vs4" class="select">'.$options.'</select></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="22">
						    	<td></td><td><button id="clasie11ye12" class="detalles">Ver Detalles</button>
						    	<select id="matchcl11vs12" class="select">'.$options.'</select></td>
						    	<td><input type="text" name="octavosFinal6" id="octavosFinal6" readonly="readonly" class="tablas"
						    	value="'.($encuentrosDatos[10]->equipo_2).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="23">
						    	<td><input type="text" name="clasificacion12" id="clasificacion12" readonly="readonly"
							 	 	class="tablas" value="'.($encuentrosDatos[5]->equipo_2).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="24">
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="25">
						    	<td><input type="text" name="clasificacion13" id="clasificacion13" readonly="readonly"
							 	 	class="tablas"	value="'.($encuentrosDatos[6]->equipo_1).'"></td>
						    	<td></td><td></td><td></td>
						    	<td><input type="text" name="cuartosFinal4" id="cuartosFinal4" readonly="readonly" class="tablas" 
						    	value="'.($encuentrosDatos[13]->equipo_2).'"></td>
						    	<td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="26">
						    	<td></td><td><button id="clasie13ye14" class="detalles">Ver Detalles</button>
						    	<select id="matchcl13vs14" class="select">'.$options.'</select></td>
						    	<td><input type="text" name="octavosFinal7" id="octavosFinal7" readonly="readonly" class="tablas"
						    	value="'.($encuentrosDatos[11]->equipo_1).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="27">
						    	<td><input type="text" name="clasificacion14" id="clasificacion14" readonly="readonly"
							 	 		class="tablas"	value="'.($encuentrosDatos[6]->equipo_2).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="28">
						    	<td></td><td></td><td></td>
						    	<td><button id="octavose7ye8" class="detalles">Ver Detalles</button>
						    	<select id="matcho7vs8" class="select">'.$options.'</select></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="29">
						    	<td><input type="text" name="clasificacion15" id="clasificacion15" readonly="readonly"
							 	 	class="tablas"	value="'.($encuentrosDatos[7]->equipo_1).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="30">
						    	<td></td><td><button id="clasie15ye16" class="detalles"	>Ver Detalles</button>
						    	<select id="matchcl15vs16" class="select">'.$options.'</select></td>
						    	<td><input type="text" name="octavosFinal8" id="octavosFinal8" readonly="readonly" class="tablas"
						    	value="'.($encuentrosDatos[11]->equipo_2).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="31">
						    	<td><input type="text" name="clasificacion16" id="clasificacion16" readonly="readonly"
							 	 		class="tablas"	value="'.($encuentrosDatos[7]->equipo_2).'"></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						</table>';
							 echo $fixture;
							break;
					}
	?>
	<form action="/sala/Generate-details-dota2">
		 <input type="hidden" name="id" value="{{ $id }}">
		 <?php
		 	$boolT="disabled";
		 	$title="el torneo debe culminar para poder generar un PDF con los detalles";
		 	if($salaDatos[0]->equipo_Ganador!="por verificar"&& $salaDatos[0]->equipo_Ganador!="por concluir"){
		 		$boolT="";
		 		$title="torneo concluido presione para generar un PDF con los detalles";
		 	}
		 ?>
		 <center><button class='butonr' {{ $boolT }} title="{{ $title }}" >Generar PDF de los detalles</button></center>
	</form>
</body>

<!--script para los botones-->
<script type="text/javascript">
	
	if(document.getElementById("numeroE").value==16){
	//clasificacion e1ye2
	document.getElementById("clasie1ye2").onclick=function(){

		var id=document.getElementById("id").value;
		var equipos1=document.getElementById("clasificacion1").value;
		var equipos2=document.getElementById("clasificacion2").value;
		var numeroEnc=document.getElementById("matchcl1vs2").value;

		window.location.href = "http://gamingumpires.test/sala/fixture/"+equipos1+"&&&"+equipos2+"&&&"+id+"&&&"+numeroEnc;
	}

	//clasificacion e3ye4
	document.getElementById("clasie3ye4").onclick=function(){

		var id=document.getElementById("id").value;
		var equipos1=document.getElementById("clasificacion3").value;
		var equipos2=document.getElementById("clasificacion4").value;
		var numeroEnc=document.getElementById("matchcl3vs4").value;

		window.location.href = "http://gamingumpires.test/sala/fixture/"+equipos1+"&&&"+equipos2+"&&&"+id+"&&&"+numeroEnc;
	}
	//clasificacion e5ye6
	document.getElementById("clasie5ye6").onclick=function(){

		var id=document.getElementById("id").value;
		var equipos1=document.getElementById("clasificacion5").value;
		var equipos2=document.getElementById("clasificacion6").value;
		var numeroEnc=document.getElementById("matchcl5vs6").value;

		window.location.href = "http://gamingumpires.test/sala/fixture/"+equipos1+"&&&"+equipos2+"&&&"+id+"&&&"+numeroEnc;
	}
	//clasificacion e7ye8
	document.getElementById("clasie7ye8").onclick=function(){

		var id=document.getElementById("id").value;
		var equipos1=document.getElementById("clasificacion7").value;
		var equipos2=document.getElementById("clasificacion8").value;
		var numeroEnc=document.getElementById("matchcl7vs8").value;

		window.location.href = "http://gamingumpires.test/sala/fixture/"+equipos1+"&&&"+equipos2+"&&&"+id+"&&&"+numeroEnc;
	}
	//clasificacion e9ye10
	document.getElementById("clasie9ye10").onclick=function(){

		var id=document.getElementById("id").value;
		var equipos1=document.getElementById("clasificacion9").value;
		var equipos2=document.getElementById("clasificacion10").value;
		var numeroEnc=document.getElementById("matchcl9vs10").value;

		window.location.href = "http://gamingumpires.test/sala/fixture/"+equipos1+"&&&"+equipos2+"&&&"+id+"&&&"+numeroEnc;
	}
	//clasificacion e11ye12
	document.getElementById("clasie11ye12").onclick=function(){

		var id=document.getElementById("id").value;
		var equipos1=document.getElementById("clasificacion11").value;
		var equipos2=document.getElementById("clasificacion12").value;
		var numeroEnc=document.getElementById("matchcl11vs12").value;

		window.location.href = "http://gamingumpires.test/sala/fixture/"+equipos1+"&&&"+equipos2+"&&&"+id+"&&&"+numeroEnc;
	}
	//clasificacion e13ye14
	document.getElementById("clasie13ye14").onclick=function(){

		var id=document.getElementById("id").value;
		var equipos1=document.getElementById("clasificacion13").value;
		var equipos2=document.getElementById("clasificacion14").value;
		var numeroEnc=document.getElementById("matchcl13vs14").value;
		window.location.href = "http://gamingumpires.test/sala/fixture/"+equipos1+"&&&"+equipos2+"&&&"+id+"&&&"+numeroEnc;
	}
	//clasificacion e15ye16
	document.getElementById("clasie15ye16").onclick=function(){

		var id=document.getElementById("id").value;
		var equipos1=document.getElementById("clasificacion15").value;
		var equipos2=document.getElementById("clasificacion16").value;
		var numeroEnc=document.getElementById("matchcl15vs16").value;

		window.location.href = "http://gamingumpires.test/sala/fixture/"+equipos1+"&&&"+equipos2+"&&&"+id+"&&&"+numeroEnc;
	}
}

if(document.getElementById("numeroE").value	>=8){
	//octavos e1ye2
	document.getElementById("octavose1ye2").onclick=function(){
		var id=document.getElementById("id").value;
		var equipos1=document.getElementById("octavosFinal1").value;
		var equipos2=document.getElementById("octavosFinal2").value;
		var numeroEnc=document.getElementById("matcho1vs2").value;
		window.location.href = "http://gamingumpires.test/sala/fixture/"+equipos1+"&&&"+equipos2+"&&&"+id+"&&&"+numeroEnc;
	}

	//octavos e3ye4
	document.getElementById("octavose3ye4").onclick=function(){

		var id=document.getElementById("id").value;
		var equipos1=document.getElementById("octavosFinal3").value;
		var equipos2=document.getElementById("octavosFinal4").value;
		var numeroEnc=document.getElementById("matcho3vs4").value;

		window.location.href = "http://gamingumpires.test/sala/fixture/"+equipos1+"&&&"+equipos2+"&&&"+id+"&&&"+numeroEnc;
	}

	//octavos e5ye6
	document.getElementById("octavose5ye6").onclick=function(){

		var id=document.getElementById("id").value;
		var equipos1=document.getElementById("octavosFinal5").value;
		var equipos2=document.getElementById("octavosFinal6").value;
		var numeroEnc=document.getElementById("matcho5vs6").value;

		window.location.href = "http://gamingumpires.test/sala/fixture/"+equipos1+"&&&"+equipos2+"&&&"+id+"&&&"+numeroEnc;
	}

	//octavos e7ye8
	document.getElementById("octavose7ye8").onclick=function(){

		var id=document.getElementById("id").value;
		var equipos1=document.getElementById("octavosFinal7").value;
		var equipos2=document.getElementById("octavosFinal8").value;
		var numeroEnc=document.getElementById("matcho7vs8").value;

		window.location.href = "http://gamingumpires.test/sala/fixture/"+equipos1+"&&&"+equipos2+"&&&"+id+"&&&"+numeroEnc;
	}
}
	//cuartos de final 1
	document.getElementById("cuartos1vs2").onclick=function(){
		var id=document.getElementById("id").value;
		var equipos1=document.getElementById("cuartosFinal1").value;
		var equipos2=document.getElementById("cuartosFinal2").value;
		var numeroEnc=document.getElementById("matchc1vs2").value;
		window.location.href = "http://gamingumpires.test/sala/fixture/"+equipos1+"&&&"+equipos2+"&&&"+id+"&&&"+numeroEnc;
	}

	//cuartos de final 2
	document.getElementById("cuartos3vs4").onclick=function(){
		var id=document.getElementById("id").value;
		var equipos1=document.getElementById("cuartosFinal3").value;
		var equipos2=document.getElementById("cuartosFinal4").value;
		var numeroEnc=document.getElementById("matchc3vs4").value;
		window.location.href = "http://gamingumpires.test/sala/fixture/"+equipos1+"&&&"+equipos2+"&&&"+id+"&&&"+numeroEnc;
	}

	//final 
	document.getElementById("semifinal").onclick=function(){
		var id=document.getElementById("id").value;
		var equipos1=document.getElementById("semiFinalista1").value;
		var equipos2=document.getElementById("semiFinalista2").value;
		var numeroEnc=document.getElementById("matchsf").value;
		window.location.href = "http://gamingumpires.test/sala/fixture/"+equipos1+"&&&"+equipos2+"&&&"+id+"&&&"+numeroEnc;
	}
</script>
</html>
@endsection