<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href="../../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Allerta+Stencil">
	<link rel="stylesheet" type="text/css" href="../../css/fixure-dota2.css">
	<script src="../../lib/jquery/html2pdf.bundle.min.js"></script>
</head>
<body>

<section class="detalles-partida" id="cuerpo">
	<?php

		    $data = file_get_contents("heroStats.json");
			$heros = json_decode($data, true);
			//extraccion de los items desde json
			$datas = file_get_contents("itemsStats.json");
			$items = json_decode($datas, true);

	         $id=$_REQUEST["id"];
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
             echo '<center><img src="'.$salaDatos[0]->logo.'" class="imagenes imagen-redonda"></center>';
             echo '<h1 class="titulo">'.$salaDatos[0]->modo_Juego.'</h1>';
             echo '<input type="hidden" id="numeroE" value="'.$salaDatos[0]->numero_Equipos.'">';

			//fixture de los equipos
					switch ($salaDatos[0]->numero_Equipos) {
						case 4:
						$fixture='
						<center><table>
						    <tr class="1">
						    	<td><p class="fequipos">'.($encuentrosDatos[0]->equipo_1).'</p></td>
						    	<td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="2">
						    	<td></td><td><p class="fequipos">'.($encuentrosDatos[2]->equipo_1).'</p></td>
						    	<td></td><td></td><td></td>
						    </tr>
						    <tr class="3">
						    	<td><p class="fequipos">'.($encuentrosDatos[0]->equipo_2).'</p></td>
						    	<td></td>
						    	<td></td>
						    	<td></td><td></td>
						    </tr>
						    <tr class="4">
						    	<td></td><td></td><td></td>
						    	<td></td></td>
						    	<td><p class="fequipos">'.($salaDatos[0]->equipo_Ganador).'</p></td>
						    </tr>
						    <tr class="5">
						    	<td><p class="fequipos">'.($encuentrosDatos[1]->equipo_1).'</p></td>
						    	<td></td><td></td>
						    	<td></td><td></td>
						    </tr>
						    <tr class="6">
						    	<td></td><td><p class="fequipos">'.($encuentrosDatos[2]->equipo_2).'</p></td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="7">
						    	<td><p class="fequipos">'.($encuentrosDatos[1]->equipo_2).'</p>></td>
						    	<td></td><td></td><td></td><td></td>
						    </tr>
						</table></center><div class="col-12 espaciado"></div>';

							 echo $fixture;
							break;
						case 8:
							 $fixture='
						<center><table>
						    <tr class="1">
						    	<td><p class="fequipos">'.($encuentrosDatos[0]->equipo_1).'</p></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="2">
						    	<td></td><td></td>
						    	<td><p class="fequipos">'.($encuentrosDatos[4]->equipo_1).'</p></td>
						    	<td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="3">
						    	<td><p class="fequipos">'.($encuentrosDatos[0]->equipo_2).'</p></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="4">
								<td></td><td></td><td></td><td><p class="fequipos">'.($encuentrosDatos[6]->equipo_1).'</p></td>
								<td></td><td></td><td></td>
						    </tr>
						    <tr class="5">
								<td><p class="fequipos">'.($encuentrosDatos[1]->equipo_1).'</p></td>
								<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="6">
								<td></td><td></td>
								<td><p class="fequipos">'.($encuentrosDatos[4]->equipo_2).'</p></td>
								<td></td><td></td>
								<td></td><td></td>
						    </tr>
						    <tr class="7">
								<td><p class="fequipos">'.($encuentrosDatos[1]->equipo_2).'</p></td>
								<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="8">
						    	<td></td><td></td><td></td><td></td><td></td>
						    	<td></td>
						    	<td><p class="fequipos">'.($salaDatos[0]->equipo_Ganador).'</p></td>
						    </tr>
						    <tr class="9">
								<td><p class="fequipos">'.($encuentrosDatos[2]->equipo_1).'</p></td>
								<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="10">
								<td></td><td></td>
								<td><p class="fequipos">'.($encuentrosDatos[5]->equipo_1).'</p></td>
								<td></td><td></td>
								<td></td><td></td>
						    </tr>
						    <tr class="11">
								<td><p class="fequipos">'.($encuentrosDatos[2]->equipo_2).'</p></td>
								<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="12">
								<td></td><td></td><td></td><td><p class="fequipos">'.($encuentrosDatos[6]->equipo_2).'</p></td>
								<td></td><td></td><td></td>
						    </tr>
						    <tr class="13">
								<td><p class="fequipos">'.($encuentrosDatos[3]->equipo_1).'</p></td>
								<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="14">
								<td></td><td></td>
								<td><p class="fequipos">'.($encuentrosDatos[5]->equipo_2).'</p></td>
								<td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="15">
								<td><p class="fequipos">'.($encuentrosDatos[3]->equipo_2).'</p></td>
								<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						</table></center>';
							 echo $fixture;
							break;
						case 16:
							  $fixture='
						<center><table>
						    <tr class="1">
						    	<td><p class="fequipos">'.($encuentrosDatos[0]->equipo_1).'</p></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="2">
						    	<td></td><td></td>
						    	<td><p class="fequipos">'.($encuentrosDatos[8]->equipo_1).'</p></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="3">
						    	<td><p class="fequipos">'.($encuentrosDatos[0]->equipo_2).'</p></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="4">
						    	<td></td><td></td><td></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="5">
						    	<td><p class="fequipos">'.($encuentrosDatos[1]->equipo_1).'</p></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="6">
						    	<td></td><td></td>
						    	<td><p class="fequipos">'.($encuentrosDatos[8]->equipo_2).'</p></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="7">
						    	<td><p class="fequipos">'.($encuentrosDatos[1]->equipo_2).'</p></td>
						    	<td></td><td></td><td></td>
						    	<td><p class="fequipos">'.($encuentrosDatos[12]->equipo_1).'</p></td>
						    	<td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="8">
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="9">
						    	<td><p class="fequipos">'.($encuentrosDatos[2]->equipo_1).'</p></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="10">
						    	<td></td><td></td>
						    	<td><p class="fequipos">'.($encuentrosDatos[9]->equipo_1).'</p></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="11">
						    	<td><p class="fequipos">'.($encuentrosDatos[2]->equipo_2).'</p></td>
						    	<td></td><td></td><td></td><td></td>
						    	<td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="12">
						    	<td></td><td></td><td></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="13">
						    	<td><p class="fequipos">'.($encuentrosDatos[3]->equipo_1).'</p></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="14">
						    	<td></td><td></td>
						    	<td><p class="fequipos">'.($encuentrosDatos[9]->equipo_2).'</p></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="15">
						    	<td><p class="fequipos">'.($encuentrosDatos[3]->equipo_2).'</p></td>
						    	<td></td><td></td><td></td>
						    	<td><p class="fequipos">'.($encuentrosDatos[12]->equipo_2).'</p></td><td></td>
						    	<td><p class="fequipos">'.($encuentrosDatos[14]->equipo_1).'</p></td><td></td><td></td>
						    </tr>
						    <tr class="16">
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    	<td></td>
						    	<td><p class="fequipos">'.($salaDatos[0]->equipo_Ganador).'</p></td>
						    </tr>
						    <tr class="17">
						    	<td><p class="fequipos">'.($encuentrosDatos[4]->equipo_1).'</p></td>
						    	<td></td><td></td><td></td>
						    	<td><p class="fequipos">'.($encuentrosDatos[13]->equipo_1).'</p></td><td></td>
						    	<td><p class="fequipos">'.($encuentrosDatos[14]->equipo_2).'</p></td><td></td><td></td>
						    </tr>
						    <tr class="18">
						    	<td></td><td></td>
						    	<td><p class="fequipos">'.($encuentrosDatos[10]->equipo_1).'</p></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="19">
						    	<td><p class="fequipos">'.($encuentrosDatos[4]->equipo_2).'</p></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="20">
						    	<td></td><td></td><td></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="21">
						    	<td><p class="fequipos">'.($encuentrosDatos[5]->equipo_1).'</p></td>
						    	<td></td><td></td><td></td><td></td>
						    	<td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="22">
						    	<td></td><td></td>
						    	<td><p class="fequipos">'.($encuentrosDatos[10]->equipo_2).'</p></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="23">
						    	<td><p class="fequipos">'.($encuentrosDatos[5]->equipo_2).'</p></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="24">
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="25">
						    	<td><p class="fequipos">'.($encuentrosDatos[6]->equipo_1).'</p></td>
						    	<td></td><td></td><td></td>
						    	<td><p class="fequipos">'.($encuentrosDatos[13]->equipo_2).'</p></td>
						    	<td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="26">
						    	<td></td><td></td>
						    	<td><p class="fequipos">'.($encuentrosDatos[11]->equipo_1).'</p></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="27">
						    	<td><p class="fequipos">'.($encuentrosDatos[6]->equipo_2).'</p></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="28">
						    	<td></td><td></td><td></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="29">
						    	<td><p class="fequipos">'.($encuentrosDatos[7]->equipo_1).'</p></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="30">
						    	<td></td><td></td>
						    	<td><p class="fequipos">'.($encuentrosDatos[11]->equipo_2).'</p></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						    <tr class="31">
						    	<td><<p class="fequipos">'.($encuentrosDatos[7]->equipo_2).'</p></td>
						    	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						    </tr>
						</table></center>';
							 echo $fixture;
							break;
					}
	?>
	<?php
	    $eliminacion=1;
            if($salaDatos[0]->tipo_Eliminacion=='BO3'){
             	$eliminacion=3;
             }elseif ($salaDatos[0]->tipo_Eliminacion=='BO5') {
             	$eliminacion=5;
             }
		//toda la informacion de los equipos
		for ($i=0; $i <$salaDatos[0]->numero_Equipos -1 ; $i++) {
			  echo "<h1 class='encuentro'>Encuentro: ".$encuentrosDatos[$i]->equipo_1." VS ".$encuentrosDatos[$i]->equipo_2."</h1>";
			  echo "<h1 class='ganadore'>Ganador del Encuentro: ".$encuentrosDatos[$i]->equipo_Ganador."</h1>";
			  $detallesTable = DB::table('detalles_partida_dota2')
                        ->where("codigo_Encuentro","=",$encuentrosDatos[$i]->id)
                        ->get();
			  for ($j=0; $j < $eliminacion ; $j++) {
			  	 echo "<h1 class='partida'>Partida N°".$detallesTable[$j]->numero_partida."</h1>";
			  	 echo "<h1 class='ganadore'>Ganador de la partida: ".$detallesTable[$j]->equipo_Ganador."</h1>";
			  	 echo  "<div class='row letra'><div class='col-1'></div>";
			  	 		$infoTable = DB::table('info_jugador_dota2')
                        				->where("codigo_DetalleP","=",$detallesTable[$j]->id)
                        				->get();
			  	 		for ($k=0; $k < 10 ; $k++) {
			  	 			$jugadorTable = DB::table('jugadores_dota_2')
                        				->where("id","=",$infoTable[$k]->codigo_Jugador)
                        				->get();
			  	 			echo "<div class='col-1 '>".$jugadorTable[0]->nickname." nivel-".$infoTable[$k]->nivel."
			  	 			<img class='imagen-hero' src=".$heros[$infoTable[$k]->personaje]["local"].">
			  	 			".$infoTable[$k]->asesinatos."/".$infoTable[$k]->muertes."/".$infoTable[$k]->asistencias."
			  	 			<img class='imagen-item' src=".$items[$infoTable[$k]->slotJunglas]["local"].">
			  	 			<img class='imagen-item' src=".$items[$infoTable[$k]->slot2]["local"].">
			  	 			<img class='imagen-item' src=".$items[$infoTable[$k]->slot3]["local"].">
			  	 			<img class='imagen-item' src=".$items[$infoTable[$k]->slot4]["local"].">
			  	 			<img class='imagen-item' src=".$items[$infoTable[$k]->slot5]["local"].">
			  	 			<img class='imagen-item' src=".$items[$infoTable[$k]->slot6]["local"].">
			  	 			<img class='imagen-item' src=".$items[$infoTable[$k]->slot1]["local"]."></div>";
			  	 		}

			  	  echo "<div class='col-1'></div></div>";
			  }
		}





	?>

</section>
</body>
</html>

<script type="text/javascript">

	const $elementoParaConvertir = document.body; // <-- Aquí puedes elegir cualquier elemento del DOM
		html2pdf()
		    .set({
		        margin: 0,
		        filename: 'Fixture_GamingUmpire_mitica.pdf',
		        image: {
		            type: 'jpeg',
		            quality: 0.98
		        },
		        html2canvas: {
		            scale: 1, // A mayor escala, mejores gráficos, pero más peso
		            letterRendering: true,
		        },
		        jsPDF: {
		            unit: "in",
		            format: "a2",
		            orientation: 'portrait' // landscape o portrait
		        }
		    })
		    .from($elementoParaConvertir)
		    .save()
		    .catch(err => console.log(err))
		   setTimeout ("Back()", 1000);

function Back(){
     javascript:history.back();
}
</script>