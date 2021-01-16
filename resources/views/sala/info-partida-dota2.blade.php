@extends('layouts/head-login-salain')


@section('Cuerpo')
<!DOCTYPE html>
<html>
<head>
	<title></title>
    <link href="../../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Allerta+Stencil">
	<link href="../../css/style-info-dota2.css" rel="stylesheet" >
	<?php  
			use App\Models\encuentros_dota2;
			use Illuminate\Support\Facades\DB;
			use App\Models\detalles_partida_dota2;
			use App\Models\info_jugador_dota2;
			use App\Models\sala;
			use App\Models\picks_dota2;
			use App\Models\bans_dota2;
			use App\Models\equipos_dota_2;
			use App\Models\jugadores_dota_2;
			//extraccion de los heroes desde json

			$data = file_get_contents("heroStats.json");
			$heros = json_decode($data, true);
			//extraccion de los items desde json
			$datas = file_get_contents("itemsStats.json");
			$items = json_decode($datas, true);

			//variables sesion que usaremos para guardar los registros
			session_start();
			$_SESSION["encuentrosTable"] = $encuentrosTable;
			$_SESSION["detallesTable"]=$detallesTable;
			$_SESSION["infoTable"]=$infoTable;
			$_SESSION["url"]=$_SERVER['REQUEST_URI'];


	?>
</head>
<body><form action="/sala/fixture/guardar/info">
	<section id="hero"></section>
	<section class="info-partida"><center>

		<h1 class="titulo">Detalles de la partida N°{{ $detallesTable[0]->numero_partida }}</h1>
		<?php
			$salaTable = DB::table('sala_dota_2')
                        ->where("id","=",$encuentrosTable[0]->codigo_Sala)   
                        ->get();
             echo "<h2 class='subtitulo'>MODO DE JUEGO: ".$salaTable[0]->modo_Juego."</h2>";
             echo "<h3 class='letra'>Ganador:</h3> <select id='teamWinner' name='teamWinner' class='select'>
	             	   <option value='1'>".$encuentrosTable[0]->equipo_1."</option>
	             	   <option value='2'>".$encuentrosTable[0]->equipo_2."</option>
             	   </select></td>";
            //nombres de los heroes en un listbox
             $listaHeros="";
             foreach ($heros as $hero) {
             	$listaHeros.=" <option value=".$hero["id"].">".$hero["localized_name"]."</option>";
             }
             //nombres de los items en un listbox
             $listaItems="";
             foreach ($items as $item) {
             	$listaItems.=" <option value=".$item["id"].">".$item["name"]."</option>";
             }

		?>
		<div class="container-fluid creacion-sala">
			<div class="row">
				<div class="col-6">
					<h2 class="letra">{{ $encuentrosTable[0]->equipo_1 }}</h2>
					
				</div>
				<div class="col-6 ">
					<h2 class="letra">{{ $encuentrosTable[0]->equipo_2}}</h2>
					
				</div>
				<div class="col-2  col-md-2 col-lg-2 col-xl-2 pt-2 d-none  d-md-block"></div>
				<div class="col-3  col-md-1 col-lg-1 col-xl-1"><p class="letrakills">kills Totales:</p></div>
				<div class="col-3  col-md-1 col-lg-1 col-xl-1"><input type="number" min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
					name="killsequipo1" class="cajasT" required
					value="{{ $detallesTable[0]->eliminaciones_e1 }}"></div>
				<div class="col-2  col-md-2 col-lg-2 col-xl-2 pt-2 d-none  d-md-block"></div>
				<div class="col-2  col-md-2 col-lg-2 col-xl-2 pt-2 d-none  d-md-block"></div>
				<div class="col-3  col-md-1 col-lg-1 col-xl-1"><p class="letrakills">kills Totales:</p></div>
				<div class="col-3  col-md-1 col-lg-1 col-xl-1"><input type="number" min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="killsequipo2" class="cajasT" required
					value="{{ $detallesTable[0]->eliminaciones_e2 }}"></div>
				<div class="col-2  col-md-2 col-lg-2 col-xl-2 pt-2 d-none  d-md-block"></div>
			</div>
		</div>
		<div class="player1teams">
			<div class="row">
				<div class="col-12 col-md-6 col-lg-6 col-xl-6 pt-6  team1">
					<?php 
						$jugadorTable = DB::table('jugadores_dota_2')
                        				->where("id","=",$infoTable[0]->codigo_Jugador)   
                        				->get();
                       echo "<input type='text' class='team1lp' readonly value=".$jugadorTable[0]->nickname."><br>";
					?>
					<img class="team1img"  width="150" height="150"  
					id="imgjugador1"   src={{ $heros[$infoTable[0]->personaje]["img"] }} >
					<select id="heroj1t1" name="heroj1t1" onchange="verIndex('heroj1t1','imgjugador1')" class="team1slct">
					<option value="{{$infoTable[0]->personaje}}">
						<?php echo $heros[$infoTable[0]->personaje]["localized_name"]; ?></option>
					<?php echo $listaHeros; ?></select>
					<a class="team1ltr"><a class="team1ltr">nivel:</a></a><input type="number" min="0" max="30" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"   name="nivelj1t1" value="{{ $infoTable[0]->nivel }}"class="team1ct"><br>
						<a class="team1ltr"><a class="team1ltr">k:</a></a><input type="number"  min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="asesinatosj1t1" value="{{ $infoTable[0]->asesinatos}}" class="team1ct">
						<a class="team1ltr"><a class="team1ltr">D:</a></a><input type="number" min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="muertesj1t1" value="{{ $infoTable[0]->muertes }}" class="team1ct">
						<a class="team1ltr"><a class="team1ltr">A:</a></a><input type="number"  min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"name="asistenciasj1t1" value="{{ $infoTable[0]->asistencias}}" class="team1ct">
					items:
					<select id="itemj1t1" class="team1slct2" onchange="verItems('1','itemj1t1')"><?php echo $listaItems; ?></select>
					<table>
						<tr>
							<td><input type="radio" id="slot1j1" name="rbj1">
								<input type="hidden" name="img1txtj1" id="img1txtj1" value={{ $infoTable[0]->slot1 }}>
								<img class="team1img"  width="100" height="100" id="imgitem1j1"
								src="{{ $items[$infoTable[0]->slot1]["img"] }}">
							</td>
							<td><input type="radio" id="slot2j1" name="rbj1">
								<input type="hidden" name="img2txtj1" id="img2txtj1" value="{{ $infoTable[0]->slot2 }}">
								<img class="team1img"  width="100" height="100" id="imgitem2j1"
								src="{{ $items[$infoTable[0]->slot2]["img"] }}">
							</td>
							<td><input type="radio" id="slot3j1" name="rbj1">
								<input type="hidden" name="img3txtj1" id="img3txtj1" value="{{ $infoTable[0]->slot3 }}">
								<img class="team1img"  width="100" height="100" id="imgitem3j1"
								src="{{ $items[$infoTable[0]->slot3]["img"] }}">
							</td>	
							<td></td>
							<td><input type="radio" id="slot0j1" name="rbj1">
								<input type="hidden" name="img0txtj1" id="img0txtj1" value="{{ $infoTable[0]->slotJunglas }}">
								<img class="team1img"  width="100" height="100" id="imgitem0j1"
								src="{{ $items[$infoTable[0]->slotJunglas]["img"] }}">
							</td>
						</tr>
						<tr>
							<td>
								<input type="radio" id="slot4j1" name="rbj1">
								<input type="hidden" name="img4txtj1" id="img4txtj1" value="{{ $infoTable[0]->slot4 }}">
								<img class="team1img"  width="100" height="100" id="imgitem4j1"
								src="{{ $items[$infoTable[0]->slot4]["img"] }}">
							</td>
							<td>
								<input type="radio" id="slot5j1" name="rbj1">
								<input type="hidden" name="img5txtj1" id="img5txtj1" value="{{ $infoTable[0]->slot5 }}">
								<img class="team1img"  width="100" height="100" id="imgitem5j1"
								src="{{ $items[$infoTable[0]->slot5]["img"] }}">
							</td>
							<td><input type="radio" id="slot6j1" name="rbj1">
								<input type="hidden" name="img6txtj1" id="img6txtj1" value="{{ $infoTable[0]->slot6 }}">
								<img class="team1img"  width="100" height="100" id="imgitem6j1"
								src="{{ $items[$infoTable[0]->slot6]["img"] }}">
							</td>
						</tr>
					</table>
				</div>
				<div class="col-12 col-md-6 col-lg-6 col-xl-6 pt-6 team2">
					<?php 
						$jugadorTable = DB::table('jugadores_dota_2')
                        				->where("id","=",$infoTable[5]->codigo_Jugador)   
                        				->get();
                       echo "<input type='text' class='team2lp' readonly value=".$jugadorTable[0]->nickname."><br>";
					?>
					<img class="team2img"  width="150" height="150"  
					id="imgjugador6"   src={{ $heros[$infoTable[5]->personaje]["img"] }} >
					<select id="heroj1t2" name="heroj1t2" onchange="verIndex('heroj1t2','imgjugador6')" class="team2slct">
					<option value="{{$infoTable[5]->personaje}}">
						<?php echo $heros[$infoTable[5]->personaje]["localized_name"]; ?></option>
					<?php echo $listaHeros; ?></select>
						<a class="team2ltr">nivel:</a><input type="number"  min="0" max="30" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="nivelj1t2" value="{{ $infoTable[5]->nivel }}" class="team1ct"><br>
						<a class="team2ltr">k:</a><input type="number"  min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="asesinatosj1t2" value="{{ $infoTable[5]->asesinatos}}" class="team1ct">
						<a class="team2ltr">D:</a><input type="number"  min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="muertesj1t2" value="{{ $infoTable[5]->muertes }}" class="team1ct">
						<a class="team2ltr">A:</a><input type="number" min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  name="asistenciasj1t2" value="{{ $infoTable[5]->asistencias}}" class="team1ct">
					items:
					<select id="itemj1t2" class="team2slct2" onchange="verItems('6','itemj1t2')"><?php echo $listaItems; ?></select>
					<table>
						<tr>
							<td><input type="radio" id="slot1j6" name="rbj6">
								<input type="hidden" name="img1txtj6" id="img1txtj6" value={{ $infoTable[5]->slot1 }}>
								<img class="team2img"  width="100" height="100" id="imgitem1j6"
								src="{{ $items[$infoTable[5]->slot1]["img"] }}">
							</td>
							<td><input type="radio" id="slot2j6" name="rbj6">
								<input type="hidden" name="img2txtj6" id="img2txtj6" value="{{ $infoTable[5]->slot2 }}">
								<img class="team2img"  width="100" height="100" id="imgitem2j6"
								src="{{ $items[$infoTable[5]->slot2]["img"] }}">
							</td>
							<td><input type="radio" id="slot3j6" name="rbj6">
								<input type="hidden" name="img3txtj6" id="img3txtj6" value="{{ $infoTable[5]->slot3 }}">
								<img class="team2img"  width="100" height="100" id="imgitem3j6"
								src="{{ $items[$infoTable[5]->slot3]["img"] }}">
							</td>	
							<td></td>
							<td><input type="radio" id="slot0j6" name="rbj6">
								<input type="hidden" name="img0txtj6" id="img0txtj6" value="{{ $infoTable[5]->slotJunglas }}">
								<img class="team2img"  width="100" height="100" id="imgitem0j6"
								src="{{ $items[$infoTable[5]->slotJunglas]["img"] }}">
							</td>
						</tr>
						<tr>
							<td>
								<input type="radio" id="slot4j6" name="rbj6">
								<input type="hidden" name="img4txtj6" id="img4txtj6" value="{{ $infoTable[5]->slot4 }}">
								<img class="team2img"  width="100" height="100" id="imgitem4j6"
								src="{{ $items[$infoTable[5]->slot4]["img"] }}">
							</td>
							<td>
								<input type="radio" id="slot5j6" name="rbj6">
								<input type="hidden" name="img5txtj6" id="img5txtj6" value="{{ $infoTable[5]->slot5 }}">
								<img class="team2img"  width="100" height="100" id="imgitem5j6"
								src="{{ $items[$infoTable[5]->slot5]["img"] }}">
							</td>
							<td><input type="radio" id="slot6j6" name="rbj6">
								<input type="hidden" name="img6txtj6" id="img6txtj6" value="{{ $infoTable[5]->slot6 }}">
								<img class="team2img"  width="100" height="100" id="imgitem6j6"
								src="{{ $items[$infoTable[5]->slot6]["img"] }}">
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="player2teams">
			<div class="row">
				<div class="col-12 col-md-6 col-lg-6 col-xl-6 pt-6  team1">
					<?php 
						$jugadorTable = DB::table('jugadores_dota_2')
                        				->where("id","=",$infoTable[1]->codigo_Jugador)   
                        				->get();
                       echo "<input type='text' class='team1lp' readonly value=".$jugadorTable[0]->nickname."><br>";
					?>
					<img class="team1img"  width="150" height="150"  
					id="imgjugador2"   src={{ $heros[$infoTable[1]->personaje]["img"] }} >
					<select id="heroj2t1" name="heroj2t1" onchange="verIndex('heroj2t1','imgjugador2')" class="team1slct">
					<option value="{{$infoTable[1]->personaje}}">
						<?php echo $heros[$infoTable[1]->personaje]["localized_name"]; ?></option>
					<?php echo $listaHeros; ?></select>
						<a class="team1ltr">nivel:</a><input type="number"  min="0" max="30" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="nivelj2t1" value="{{ $infoTable[1]->nivel }}" class="team1ct"><br>
						<a class="team1ltr">k:</a><input type="number" min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  name="asesinatosj2t1" value="{{ $infoTable[1]->asesinatos}}" class="team1ct">
						<a class="team1ltr">D:</a><input type="number" min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  name="muertesj2t1" value="{{ $infoTable[1]->muertes }}" class="team1ct">
						<a class="team1ltr">A:</a><input type="number" min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  name="asistenciasj2t1" value="{{ $infoTable[1]->asistencias}}" class="team1ct">
					items:
					<select id="itemj2t1" class="team1slct2" onchange="verItems('2','itemj2t1')"><?php echo $listaItems; ?></select>
					<table>
						<tr>
							<td><input type="radio" id="slot1j2" name="rbj2">
								<input type="hidden" name="img1txtj2" id="img1txtj2" value={{ $infoTable[1]->slot1 }}>
								<img class="team1img"  width="100" height="100" id="imgitem1j2"
								src="{{ $items[$infoTable[1]->slot1]["img"] }}">
							</td>
							<td><input type="radio" id="slot2j2" name="rbj2">
								<input type="hidden" name="img2txtj2" id="img2txtj2" value="{{ $infoTable[1]->slot2 }}">
								<img class="team1img"  width="100" height="100" id="imgitem2j2"
								src="{{ $items[$infoTable[1]->slot2]["img"] }}">
							</td>
							<td><input type="radio" id="slot3j2" name="rbj2">
								<input type="hidden" name="img3txtj2" id="img3txtj2" value="{{ $infoTable[1]->slot3 }}">
								<img class="team1img"  width="100" height="100" id="imgitem3j2"
								src="{{ $items[$infoTable[1]->slot3]["img"] }}">
							</td>	
							<td></td>
							<td><input type="radio" id="slot0j2" name="rbj2">
								<input type="hidden" name="img0txtj2" id="img0txtj2" value="{{ $infoTable[1]->slotJunglas }}">
								<img class="team1img"  width="100" height="100" id="imgitem0j2"
								src="{{ $items[$infoTable[1]->slotJunglas]["img"] }}">
							</td>
						</tr>
						<tr>
							<td>
								<input type="radio" id="slot4j2" name="rbj2">
								<input type="hidden" name="img4txtj2" id="img4txtj2" value="{{ $infoTable[1]->slot4 }}">
								<img class="team1img"  width="100" height="100" id="imgitem4j2"
								src="{{ $items[$infoTable[1]->slot4]["img"] }}">
							</td>
							<td>
								<input type="radio" id="slot5j2" name="rbj2">
								<input type="hidden" name="img5txtj2" id="img5txtj2" value="{{ $infoTable[1]->slot5 }}">
								<img class="team1img"  width="100" height="100" id="imgitem5j2"
								src="{{ $items[$infoTable[1]->slot5]["img"] }}">
							</td>
							<td><input type="radio" id="slot6j2" name="rbj2">
								<input type="hidden" name="img6txtj2" id="img6txtj2" value="{{ $infoTable[1]->slot6 }}">
								<img class="team1img"  width="100" height="100" id="imgitem6j2"
								src="{{ $items[$infoTable[1]->slot6]["img"] }}">
							</td>
						</tr>
					</table>
				</div>
				<div class="col-12 col-md-6 col-lg-6 col-xl-6 pt-6 team2">
					<?php 
						$jugadorTable = DB::table('jugadores_dota_2')
                        				->where("id","=",$infoTable[6]->codigo_Jugador)   
                        				->get();
                       echo "<input type='text' class='team2lp' readonly value=".$jugadorTable[0]->nickname."><br>";
					?>
					<img class="team2img"  width="150" height="150"  
					id="imgjugador7"   src={{ $heros[$infoTable[6]->personaje]["img"] }} >
					<select id="heroj2t2" name="heroj2t2" onchange="verIndex('heroj2t2','imgjugador7')" class="team2slct">
					<option value="{{$infoTable[6]->personaje}}">
						<?php echo $heros[$infoTable[6]->personaje]["localized_name"]; ?></option>
					<?php echo $listaHeros; ?></select>
						<a class="team2ltr">nivel:</a><input type="number" min="0" max="30" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  name="nivelj2t2" value="{{ $infoTable[6]->nivel }}" class="team1ct"><br>
						<a class="team2ltr">k:</a><input type="number"  min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="asesinatosj2t2" value="{{ $infoTable[6]->asesinatos}}" class="team1ct">
						<a class="team2ltr">D:</a><input type="number"  min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="muertesj2t2" value="{{ $infoTable[6]->muertes }}" class="team1ct">
						<a class="team2ltr">A:</a><input type="number"  min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="asistenciasj2t2" value="{{ $infoTable[6]->asistencias}}" class="team1ct">
					items:
					<select id="itemj2t2" class="team2slct2" onchange="verItems('7','itemj2t2')"><?php echo $listaItems; ?></select>
					<table>
						<tr>
							<td><input type="radio" id="slot1j7" name="rbj7">
								<input type="hidden" name="img1txtj7" id="img1txtj7" value={{ $infoTable[6]->slot1 }}>
								<img class="team2img"  width="100" height="100" id="imgitem1j7"
								src="{{ $items[$infoTable[6]->slot1]["img"] }}">
							</td>
							<td><input type="radio" id="slot2j7" name="rbj7">
								<input type="hidden" name="img2txtj7" id="img2txtj7" value="{{ $infoTable[6]->slot2 }}">
								<img class="team2img"  width="100" height="100" id="imgitem2j7"
								src="{{ $items[$infoTable[6]->slot2]["img"] }}">
							</td>
							<td><input type="radio" id="slot3j7" name="rbj7">
								<input type="hidden" name="img3txtj7" id="img3txtj7" value="{{ $infoTable[6]->slot3 }}">
								<img class="team2img"  width="100" height="100" id="imgitem3j7"
								src="{{ $items[$infoTable[6]->slot3]["img"] }}">
							</td>	
							<td></td>
							<td><input type="radio" id="slot0j7" name="rbj7">
								<input type="hidden" name="img0txtj7" id="img0txtj7" value="{{ $infoTable[6]->slotJunglas }}">
								<img class="team2img"  width="100" height="100" id="imgitem0j7"
								src="{{ $items[$infoTable[6]->slotJunglas]["img"] }}">
							</td>
						</tr>
						<tr>
							<td>
								<input type="radio" id="slot4j7" name="rbj7">
								<input type="hidden" name="img4txtj7" id="img4txtj7" value="{{ $infoTable[6]->slot4 }}">
								<img class="team2img"  width="100" height="100" id="imgitem4j7"
								src="{{ $items[$infoTable[6]->slot4]["img"] }}">
							</td>
							<td>
								<input type="radio" id="slot5j7" name="rbj7">
								<input type="hidden" name="img5txtj7" id="img5txtj7" value="{{ $infoTable[6]->slot5 }}">
								<img class="team2img"  width="100" height="100" id="imgitem5j7"
								src="{{ $items[$infoTable[6]->slot5]["img"] }}">
							</td>
							<td><input type="radio" id="slot6j7" name="rbj7">
								<input type="hidden" name="img6txtj7" id="img6txtj7" value="{{ $infoTable[6]->slot6 }}">
								<img class="team2img"  width="100" height="100" id="imgitem6j7"
								src="{{ $items[$infoTable[6]->slot6]["img"] }}">
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="player3teams">
			<div class="row">
				<div class="col-12 col-md-6 col-lg-6 col-xl-6 pt-6  team1">
					<?php 
						$jugadorTable = DB::table('jugadores_dota_2')
                        				->where("id","=",$infoTable[2]->codigo_Jugador)   
                        				->get();
                       echo "<input type='text' class='team1lp' readonly value=".$jugadorTable[0]->nickname."><br>";
					?>
					<img class="team1img"  width="150" height="150"  
					id="imgjugador3"   src={{ $heros[$infoTable[2]->personaje]["img"] }} >
					<select id="heroj3t1" name="heroj3t1" onchange="verIndex('heroj3t1','imgjugador3')" class="team1slct">
					<option value="{{$infoTable[2]->personaje}}">
						<?php echo $heros[$infoTable[2]->personaje]["localized_name"]; ?></option>
					<?php echo $listaHeros; ?></select>
						<a class="team1ltr">nivel:</a><input type="number"  min="0" max="30" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="nivelj3t1" value="{{ $infoTable[2]->nivel }}" class="team1ct"><br>
						<a class="team1ltr">k:</a><input type="number"  min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="asesinatosj3t1" value="{{ $infoTable[2]->asesinatos}}" class="team1ct">
						<a class="team1ltr">D:</a><input type="number" min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  name="muertesj3t1" value="{{ $infoTable[2]->muertes }}" class="team1ct">
						<a class="team1ltr">A:</a><input type="number"  min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="asistenciasj3t1" value="{{ $infoTable[2]->asistencias}}" class="team1ct">
					items:
					<select id="itemj3t1" class="team1slct2" onchange="verItems('3','itemj3t1')"><?php echo $listaItems; ?></select>
					<table>
						<tr>
							<td><input type="radio" id="slot1j3" name="rbj3">
								<input type="hidden" name="img1txtj3" id="img1txtj3" value={{ $infoTable[2]->slot1 }}>
								<img class="team1img"  width="100" height="100" id="imgitem1j3"
								src="{{ $items[$infoTable[2]->slot1]["img"] }}">
							</td>
							<td><input type="radio" id="slot2j3" name="rbj3">
								<input type="hidden" name="img2txtj3" id="img2txtj3" value="{{ $infoTable[2]->slot2 }}">
								<img class="team1img"  width="100" height="100" id="imgitem2j3"
								src="{{ $items[$infoTable[2]->slot2]["img"] }}">
							</td>
							<td><input type="radio" id="slot3j3" name="rbj3">
								<input type="hidden" name="img3txtj3" id="img3txtj3" value="{{ $infoTable[2]->slot3 }}">
								<img class="team1img"  width="100" height="100" id="imgitem3j3"
								src="{{ $items[$infoTable[2]->slot3]["img"] }}">
							</td>	
							<td></td>
							<td><input type="radio" id="slot0j3" name="rbj3">
								<input type="hidden" name="img0txtj3" id="img0txtj3" value="{{ $infoTable[2]->slotJunglas }}">
								<img class="team1img"  width="100" height="100" id="imgitem0j3"
								src="{{ $items[$infoTable[2]->slotJunglas]["img"] }}">
							</td>
						</tr>
						<tr>
							<td>
								<input type="radio" id="slot4j3" name="rbj3">
								<input type="hidden" name="img4txtj3" id="img4txtj3" value="{{ $infoTable[2]->slot4 }}">
								<img class="team1img"  width="100" height="100" id="imgitem4j3"
								src="{{ $items[$infoTable[2]->slot4]["img"] }}">
							</td>
							<td>
								<input type="radio" id="slot5j3" name="rbj3">
								<input type="hidden" name="img5txtj3" id="img5txtj3" value="{{ $infoTable[2]->slot5 }}">
								<img class="team1img"  width="100" height="100" id="imgitem5j3"
								src="{{ $items[$infoTable[2]->slot5]["img"] }}">
							</td>
							<td><input type="radio" id="slot6j3" name="rbj3">
								<input type="hidden" name="img6txtj3" id="img6txtj3" value="{{ $infoTable[2]->slot6 }}">
								<img class="team1img"  width="100" height="100" id="imgitem6j3"
								src="{{ $items[$infoTable[2]->slot6]["img"] }}">
							</td>
						</tr>
					</table>
				</div>
				<div class="col-12 col-md-6 col-lg-6 col-xl-6 pt-6 team2">
					<?php 
						$jugadorTable = DB::table('jugadores_dota_2')
                        				->where("id","=",$infoTable[7]->codigo_Jugador)   
                        				->get();
                       echo "<input type='text' class='team2lp' readonly value=".$jugadorTable[0]->nickname."><br>";
					?>
					<img class="team2img"  width="150" height="150"  
					id="imgjugador8"   src={{ $heros[$infoTable[7]->personaje]["img"] }} >
					<select id="heroj3t2" name="heroj3t2" onchange="verIndex('heroj3t2','imgjugador8')" class="team2slct">
					<option value="{{$infoTable[7]->personaje}}">
						<?php echo $heros[$infoTable[7]->personaje]["localized_name"]; ?></option>
					<?php echo $listaHeros; ?></select>
						<a class="team2ltr">nivel:</a><input type="number" min="0" max="30" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  name="nivelj3t2" value="{{ $infoTable[7]->nivel }}" class="team1ct"><br>
						<a class="team2ltr">k:</a><input type="number" min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  name="asesinatosj3t2" value="{{ $infoTable[7]->asesinatos}}" class="team1ct">
						<a class="team2ltr">D:</a><input type="number" min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  name="muertesj3t2" value="{{ $infoTable[7]->muertes }}" class="team1ct">
						<a class="team2ltr">A:</a><input type="number"  min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="asistenciasj3t2" value="{{ $infoTable[7]->asistencias}}" class="team1ct">
					items:
					<select id="itemj3t2" class="team2slct2" onchange="verItems('8','itemj3t2')"><?php echo $listaItems; ?></select>
					<table>
						<tr>
							<td><input type="radio" id="slot1j8" name="rbj8">
								<input type="hidden" name="img1txtj8" id="img1txtj8" value={{ $infoTable[7]->slot1 }}>
								<img class="team2img"  width="100" height="100" id="imgitem1j8"
								src="{{ $items[$infoTable[7]->slot1]["img"] }}">
							</td>
							<td><input type="radio" id="slot2j8" name="rbj8">
								<input type="hidden" name="img2txtj8" id="img2txtj8" value="{{ $infoTable[7]->slot2 }}">
								<img class="team2img"  width="100" height="100" id="imgitem2j8"
								src="{{ $items[$infoTable[7]->slot2]["img"] }}">
							</td>
							<td><input type="radio" id="slot3j8" name="rbj8">
								<input type="hidden" name="img3txtj8" id="img3txtj8" value="{{ $infoTable[7]->slot3 }}">
								<img class="team2img"  width="100" height="100" id="imgitem3j8"
								src="{{ $items[$infoTable[7]->slot3]["img"] }}">
							</td>	
							<td></td>
							<td><input type="radio" id="slot0j8" name="rbj8">
								<input type="hidden" name="img0txtj8" id="img0txtj8" value="{{ $infoTable[7]->slotJunglas }}">
								<img class="team2img"  width="100" height="100" id="imgitem0j8"
								src="{{ $items[$infoTable[7]->slotJunglas]["img"] }}">
							</td>
						</tr>
						<tr>
							<td>
								<input type="radio" id="slot4j8" name="rbj8">
								<input type="hidden" name="img4txtj8" id="img4txtj8" value="{{ $infoTable[7]->slot4 }}">
								<img class="team2img"  width="100" height="100" id="imgitem4j8"
								src="{{ $items[$infoTable[7]->slot4]["img"] }}">
							</td>
							<td>
								<input type="radio" id="slot5j8" name="rbj8">
								<input type="hidden" name="img5txtj8" id="img5txtj8" value="{{ $infoTable[7]->slot5 }}">
								<img class="team2img"  width="100" height="100" id="imgitem5j8"
								src="{{ $items[$infoTable[7]->slot5]["img"] }}">
							</td>
							<td><input type="radio" id="slot6j8" name="rbj8">
								<input type="hidden" name="img6txtj8" id="img6txtj8" value="{{ $infoTable[7]->slot6 }}">
								<img class="team2img"  width="100" height="100" id="imgitem6j8"
								src="{{ $items[$infoTable[7]->slot6]["img"] }}">
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="player4teams">
			<div class="row">
				<div class="col-12 col-md-6 col-lg-6 col-xl-6 pt-6  team1">
					<?php 
						$jugadorTable = DB::table('jugadores_dota_2')
                        				->where("id","=",$infoTable[3]->codigo_Jugador)   
                        				->get();
                       echo "<input type='text' class='team1lp' readonly value=".$jugadorTable[0]->nickname."><br>";
					?>
					<img class="team1img"  width="150" height="150"  
					id="imgjugador4"   src={{ $heros[$infoTable[3]->personaje]["img"] }} >
					<select id="heroj4t1" name="heroj4t1" onchange="verIndex('heroj4t1','imgjugador4')" class="team1slct">
					<option value="{{$infoTable[3]->personaje}}">
						<?php echo $heros[$infoTable[3]->personaje]["localized_name"]; ?></option>
					<?php echo $listaHeros; ?></select>
						<a class="team1ltr">nivel:</a><input type="number" min="0" max="30" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  name="nivelj4t1" value="{{ $infoTable[3]->nivel }}" class="team1ct"><br>
						<a class="team1ltr">k:</a><input type="number" min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  name="asesinatosj4t1" value="{{ $infoTable[3]->asesinatos}}" class="team1ct">
						<a class="team1ltr">D:</a><input type="number"  min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="muertesj4t1" value="{{ $infoTable[3]->muertes }}" class="team1ct">
						<a class="team1ltr">A:</a><input type="number"  min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="asistenciasj4t1" value="{{ $infoTable[3]->asistencias}}" class="team1ct">
					items:
					<select id="itemj4t1" class="team1slct2" onchange="verItems('4','itemj4t1')"><?php echo $listaItems; ?></select>
					<table>
						<tr>
							<td><input type="radio" id="slot1j4" name="rbj4">
								<input type="hidden" name="img1txtj4" id="img1txtj4" value={{ $infoTable[3]->slot1 }}>
								<img class="team1img"  width="100" height="100" id="imgitem1j4"
								src="{{ $items[$infoTable[3]->slot1]["img"] }}">
							</td>
							<td><input type="radio" id="slot2j4" name="rbj4">
								<input type="hidden" name="img2txtj4" id="img2txtj4" value="{{ $infoTable[3]->slot2 }}">
								<img class="team1img"  width="100" height="100" id="imgitem2j4"
								src="{{ $items[$infoTable[3]->slot2]["img"] }}">
							</td>
							<td><input type="radio" id="slot3j4" name="rbj4">
								<input type="hidden" name="img3txtj4" id="img3txtj4" value="{{ $infoTable[3]->slot3 }}">
								<img class="team1img"  width="100" height="100" id="imgitem3j4"
								src="{{ $items[$infoTable[3]->slot3]["img"] }}">
							</td>	
							<td></td>
							<td><input type="radio" id="slot0j4" name="rbj4">
								<input type="hidden" name="img0txtj4" id="img0txtj4" value="{{ $infoTable[3]->slotJunglas }}">
								<img class="team1img"  width="100" height="100" id="imgitem0j3"
								src="{{ $items[$infoTable[3]->slotJunglas]["img"] }}">
							</td>
						</tr>
						<tr>
							<td>
								<input type="radio" id="slot4j4" name="rbj4">
								<input type="hidden" name="img4txtj4" id="img4txtj4" value="{{ $infoTable[3]->slot4 }}">
								<img class="team1img"  width="100" height="100" id="imgitem4j4"
								src="{{ $items[$infoTable[3]->slot4]["img"] }}">
							</td>
							<td>
								<input type="radio" id="slot5j4" name="rbj4">
								<input type="hidden" name="img5txtj4" id="img5txtj4" value="{{ $infoTable[3]->slot5 }}">
								<img class="team1img"  width="100" height="100" id="imgitem5j4"
								src="{{ $items[$infoTable[3]->slot5]["img"] }}">
							</td>
							<td><input type="radio" id="slot6j4" name="rbj4">
								<input type="hidden" name="img6txtj4" id="img6txtj4" value="{{ $infoTable[3]->slot6 }}">
								<img class="team1img"  width="100" height="100" id="imgitem6j4"
								src="{{ $items[$infoTable[3]->slot6]["img"] }}">
							</td>
						</tr>
					</table>
				</div>
				<div class="col-12 col-md-6 col-lg-6 col-xl-6 pt-6 team2">
					<?php 
						$jugadorTable = DB::table('jugadores_dota_2')
                        				->where("id","=",$infoTable[8]->codigo_Jugador)   
                        				->get();
                       echo "<input type='text' class='team2lp' readonly value=".$jugadorTable[0]->nickname."><br>";
					?>
					<img class="team2img"  width="150" height="150"  
					id="imgjugador9"   src={{ $heros[$infoTable[8]->personaje]["img"] }} >
					<select id="heroj4t2" name="heroj4t2" onchange="verIndex('heroj4t2','imgjugador9')" class="team2slct">
					<option value="{{$infoTable[8]->personaje}}">
						<?php echo $heros[$infoTable[8]->personaje]["localized_name"]; ?></option>
					<?php echo $listaHeros; ?></select>
						<a class="team2ltr">nivel:</a><input type="number"  min="0" max="30" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="nivelj4t2" value="{{ $infoTable[8]->nivel }}" class="team1ct"><br>
						<a class="team2ltr">k:</a><input type="number"  min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="asesinatosj4t2" value="{{ $infoTable[8]->asesinatos}}" class="team1ct">
						<a class="team2ltr">D:</a><input type="number"  min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="muertesj4t2" value="{{ $infoTable[8]->muertes }}" class="team1ct">
						<a class="team2ltr">A:</a><input type="number"  min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="asistenciasj4t2" value="{{ $infoTable[8]->asistencias}}" class="team1ct">
					items:
					<select id="itemj4t2" class="team2slct2" onchange="verItems('9','itemj4t2')"><?php echo $listaItems; ?></select>
					<table>
						<tr>
							<td><input type="radio" id="slot1j9" name="rbj9">
								<input type="hidden" name="img1txtj9" id="img1txtj9" value={{ $infoTable[8]->slot1 }}>
								<img class="team2img"  width="100" height="100" id="imgitem1j9"
								src="{{ $items[$infoTable[8]->slot1]["img"] }}">
							</td>
							<td><input type="radio" id="slot2j9" name="rbj9">
								<input type="hidden" name="img2txtj9" id="img2txtj9" value="{{ $infoTable[8]->slot2 }}">
								<img class="team2img"  width="100" height="100" id="imgitem2j9"
								src="{{ $items[$infoTable[8]->slot2]["img"] }}">
							</td>
							<td><input type="radio" id="slot3j9" name="rbj9">
								<input type="hidden" name="img3txtj9" id="img3txtj9" value="{{ $infoTable[8]->slot3 }}">
								<img class="team2img"  width="100" height="100" id="imgitem3j9"
								src="{{ $items[$infoTable[8]->slot3]["img"] }}">
							</td>	
							<td></td>
							<td><input type="radio" id="slot0j9" name="rbj9">
								<input type="hidden" name="img0txtj9" id="img0txtj9" value="{{ $infoTable[8]->slotJunglas }}">
								<img class="team2img"  width="100" height="100" id="imgitem0j9"
								src="{{ $items[$infoTable[8]->slotJunglas]["img"] }}">
							</td>
						</tr>
						<tr>
							<td>
								<input type="radio" id="slot4j9" name="rbj9">
								<input type="hidden" name="img4txtj9" id="img4txtj9" value="{{ $infoTable[8]->slot4 }}">
								<img class="team2img"  width="100" height="100" id="imgitem4j9"
								src="{{ $items[$infoTable[8]->slot4]["img"] }}">
							</td>
							<td>
								<input type="radio" id="slot5j9" name="rbj9">
								<input type="hidden" name="img5txtj9" id="img5txtj9" value="{{ $infoTable[8]->slot5 }}">
								<img class="team2img"  width="100" height="100" id="imgitem5j9"
								src="{{ $items[$infoTable[8]->slot5]["img"] }}">
							</td>
							<td><input type="radio" id="slot6j9" name="rbj9">
								<input type="hidden" name="img6txtj9" id="img6txtj9" value="{{ $infoTable[8]->slot6 }}">
								<img class="team2img"  width="100" height="100" id="imgitem6j9"
								src="{{ $items[$infoTable[8]->slot6]["img"] }}">
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="player5teams">
			<div class="row">
				<div class="col-12 col-md-6 col-lg-6 col-xl-6 pt-6  team1">
					<?php 
						$jugadorTable = DB::table('jugadores_dota_2')
                        				->where("id","=",$infoTable[4]->codigo_Jugador)   
                        				->get();
                       echo "<input type='text' class='team1lp' readonly value=".$jugadorTable[0]->nickname."><br>";
					?>
					<img class="team1img"  width="150" height="150"  
					id="imgjugador5"   src={{ $heros[$infoTable[4]->personaje]["img"] }} >
					<select id="heroj5t1" name="heroj5t1" onchange="verIndex('heroj5t1','imgjugador5')" class="team1slct">
					<option value="{{$infoTable[4]->personaje}}">
						<?php echo $heros[$infoTable[4]->personaje]["localized_name"]; ?></option>
					<?php echo $listaHeros; ?></select>
						<a class="team1ltr">nivel:</a><input type="number"  min="0" max="30" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="nivelj5t1" value="{{ $infoTable[4]->nivel }}" class="team1ct"><br>
						<a class="team1ltr">k:</a><input type="number"  min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="asesinatosj5t1" value="{{ $infoTable[4]->asesinatos}}" class="team1ct">
						<a class="team1ltr">D:</a><input type="number" min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  name="muertesj5t1" value="{{ $infoTable[4]->muertes }}" class="team1ct">
						<a class="team1ltr">A:</a><input type="number" min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  name="asistenciasj5t1" value="{{ $infoTable[4]->asistencias}}" class="team1ct">
					items:
					<select id="itemj5t1" class="team1slct2" onchange="verItems('5','itemj5t1')"><?php echo $listaItems; ?></select>
					<table>
						<tr>
							<td><input type="radio" id="slot1j5" name="rbj5">
								<input type="hidden" name="img1txtj5" id="img1txtj5" value={{ $infoTable[4]->slot1 }}>
								<img class="team1img"  width="100" height="100" id="imgitem1j5"
								src="{{ $items[$infoTable[4]->slot1]["img"] }}">
							</td>
							<td><input type="radio" id="slot2j5" name="rbj5">
								<input type="hidden" name="img2txtj5" id="img2txtj5" value="{{ $infoTable[4]->slot2 }}">
								<img class="team1img"  width="100" height="100" id="imgitem2j5"
								src="{{ $items[$infoTable[4]->slot2]["img"] }}">
							</td>
							<td><input type="radio" id="slot3j5" name="rbj5">
								<input type="hidden" name="img3txtj5" id="img3txtj5" value="{{ $infoTable[4]->slot3 }}">
								<img class="team1img"  width="100" height="100" id="imgitem3j5"
								src="{{ $items[$infoTable[4]->slot3]["img"] }}">
							</td>	
							<td></td>
							<td><input type="radio" id="slot0j5" name="rbj5">
								<input type="hidden" name="img0txtj5" id="img0txtj5" value="{{ $infoTable[4]->slotJunglas }}">
								<img class="team1img"  width="100" height="100" id="imgitem0j5"
								src="{{ $items[$infoTable[4]->slotJunglas]["img"] }}">
							</td>
						</tr>
						<tr>
							<td>
								<input type="radio" id="slot4j5" name="rbj5">
								<input type="hidden" name="img4txtj5" id="img4txtj5" value="{{ $infoTable[4]->slot4 }}">
								<img class="team1img"  width="100" height="100" id="imgitem4j5"
								src="{{ $items[$infoTable[4]->slot4]["img"] }}">
							</td>
							<td>
								<input type="radio" id="slot5j5" name="rbj5">
								<input type="hidden" name="img5txtj5" id="img5txtj5" value="{{ $infoTable[4]->slot5 }}">
								<img class="team1img"  width="100" height="100" id="imgitem5j5"
								src="{{ $items[$infoTable[4]->slot5]["img"] }}">
							</td>
							<td><input type="radio" id="slot6j5" name="rbj5">
								<input type="hidden" name="img6txtj5" id="img6txtj5" value="{{ $infoTable[4]->slot6 }}">
								<img class="team1img"  width="100" height="100" id="imgitem6j5"
								src="{{ $items[$infoTable[4]->slot6]["img"] }}">
							</td>
						</tr>
					</table>
				</div>
				<div class="col-12 col-md-6 col-lg-6 col-xl-6 pt-6 team2">
					<?php 
						$jugadorTable = DB::table('jugadores_dota_2')
                        				->where("id","=",$infoTable[9]->codigo_Jugador)   
                        				->get();
                       echo "<input type='text' class='team2lp' readonly value=".$jugadorTable[0]->nickname."><br>";
					?>
					<img class="team2img"  width="150" height="150"  
					id="imgjugador10"   src={{ $heros[$infoTable[9]->personaje]["img"] }} >
					<select id="heroj5t2" name="heroj5t2" onchange="verIndex('heroj5t2','imgjugador10')" class="team2slct">
					<option value="{{$infoTable[9]->personaje}}">
						<?php echo $heros[$infoTable[9]->personaje]["localized_name"]; ?></option>
					<?php echo $listaHeros; ?></select>
						<a class="team2ltr">nivel:</a><input type="number" min="0" max="30" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  name="nivelj5t2" value="{{ $infoTable[9]->nivel }}" class="team1ct"><br>
						<a class="team2ltr">k:</a><input type="number"  min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="asesinatosj5t2" value="{{ $infoTable[9]->asesinatos}}" class="team1ct">
						<a class="team2ltr">D:</a><input type="number" min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  name="muertesj5t2" value="{{ $infoTable[9]->muertes }}" class="team1ct">
						<a class="team2ltr">A:</a><input type="number" min="0" max="99" maxlength="2" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  name="asistenciasj5t2" value="{{ $infoTable[9]->asistencias}}" class="team1ct">
					items:
					<select id="itemj5t2" class="team2slct2"onchange="verItems('10','itemj5t2')"><?php echo $listaItems; ?></select>
					<table>
						<tr>
							<td><input type="radio" id="slot1j10" name="rbj10">
								<input type="hidden" name="img1txtj10" id="img1txtj10" value={{ $infoTable[9]->slot1 }}>
								<img class="team2img"  width="100" height="100" id="imgitem1j10"
								src="{{ $items[$infoTable[9]->slot1]["img"] }}">
							</td>
							<td><input type="radio" id="slot2j10" name="rbj10">
								<input type="hidden" name="img2txtj10" id="img2txtj10" value="{{ $infoTable[9]->slot2 }}">
								<img class="team2img"  width="100" height="100" id="imgitem2j10"
								src="{{ $items[$infoTable[9]->slot2]["img"] }}">
							</td>
							<td><input type="radio" id="slot3j10" name="rbj10">
								<input type="hidden" name="img3txtj10" id="img3txtj10" value="{{ $infoTable[9]->slot3 }}">
								<img class="team2img"  width="100" height="100" id="imgitem3j10"
								src="{{ $items[$infoTable[9]->slot3]["img"] }}">
							</td>	
							<td></td>
							<td><input type="radio" id="slot0j10" name="rbj10">
								<input type="hidden" name="img0txtj10" id="img0txtj10" value="{{ $infoTable[9]->slotJunglas }}">
								<img class="team2img"  width="100" height="100" id="imgitem0j10"
								src="{{ $items[$infoTable[9]->slotJunglas]["img"] }}">
							</td>
						</tr>
						<tr>
							<td>
								<input type="radio" id="slot4j10" name="rbj10">
								<input type="hidden" name="img4txtj10" id="img4txtj10" value="{{ $infoTable[9]->slot4 }}">
								<img class="team2img"  width="100" height="100" id="imgitem4j10"
								src="{{ $items[$infoTable[9]->slot4]["img"] }}">
							</td>
							<td>
								<input type="radio" id="slot5j10" name="rbj10">
								<input type="hidden" name="img5txtj10" id="img5txtj10" value="{{ $infoTable[9]->slot5 }}">
								<img class="team2img"  width="100" height="100" id="imgitem5j10"
								src="{{ $items[$infoTable[9]->slot5]["img"] }}">
							</td>
							<td><input type="radio" id="slot6j10" name="rbj10">
								<input type="hidden" name="img6txtj10" id="img6txtj10" value="{{ $infoTable[9]->slot6 }}">
								<img class="team2img"  width="100" height="100" id="imgitem6j10"
								src="{{ $items[$infoTable[9]->slot6]["img"] }}">
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<input type="submit" value="Guardar" class="btn">
	</center></section>

</form></body>
</html>
@endsection
<!--javas-->
<script type="text/javascript">

	
	function verIndex(select, imagen)
	{
    	var lista = document.getElementById(select);
    	var opcion = lista.selectedIndex-1;
    	fetch('../../heroStats.json')
    		.then(respuesta=>respuesta.json())
    		.then(heros=>{
    			document.getElementById(imagen).src=heros[opcion].img;
    		})
    	
	}

	function verItems(number,select)
	{
    	var lista = document.getElementById(select);
    	var opcion = lista.selectedIndex;
    	fetch('../../itemsStats.json')
    		.then(respuesta=>respuesta.json())
    		.then(items=>{
    			for(i=0;i<8;i++){
    				var nombre="slot"+i+"j"+number;
    				if(document.getElementById(nombre).checked){
    					var nombreIMG="imgitem"+i+"j"+number;
    					document.getElementById(nombreIMG).src=items[opcion].img;
    				    var txtimg="img"+i+"txtj"+number;
    					document.getElementById(txtimg).value=items[opcion].id;
    				}
    			}
    			
    		})
    	
	}
</script>


