@extends('layouts/head-wow')


@section('Cuerpo')
<section id="hero"></section>
<form method="POST" action="{{ route('RAñadirTorneo') }}" enctype="multipart/form-data">
	@csrf
	<div class="row card-body">
		<div class="col-xl-2">
			<div class="card">
			  <div class="card-header cards_design_creacion">
			    Nombre del Torneo:
			  </div>
			  <div class="card-body cards_design_creacion2">
			    <input type="text" name="nombreTorneo" value="{{ old('nombreTorneo') }}">
			    {{ $errors->first('nombreTorneo') }}<br>
			  </div>
			</div>
		</div>
		<div class="col-xl-4">
			<div class="card">
			  <div class="card-header cards_design_creacion">
			    Seleccione logo del torneo:
			  </div>
			  <div class="card-body cards_design_creacion2">
			  	<input type="file" name="logo" id="logo" accept="image/*" value="{{ old('logo') }}">
			  </div>
			</div>
		</div>
		<div class="col-xl-2"></div>
		<div class="col-xl-2"></div>
		<div class="col-xl-2"></div>
		<div class="col-xl-2"></div>
	</div>
	<div id="card-package" class="card-body">
	  	<div id="card-box1" class="row padding_cards">
	  			<div id="card1" class="col-xl-2">
				    <div class="card cards_design_creacion">
				     	<div class="card-body">
					        <h5 class="card-title">Nombre del Equipo 1:</h5>
					        <input class="redisenio-imput-card" type="text" name="nombreEquipo1" value="{{ old('nombreEquipo1') }}">
					        {{ $errors->first('nombreEquipo1') }}<br>
					        <p class="card-text text-margin-redisenio">Integrantes:</p>
					        <input type="text" class="padding_input" name="name1_1" value="{{ old('name1_1') }}">
					        <input type="text" class="padding_input" name="name1_2" value="{{ old('name1_2') }}">
					        <input type="text" class="padding_input" name="name1_3" value="{{ old('name1_3') }}">
					        <input type="text" class="padding_input" name="name1_4" value="{{ old('name1_4') }}">
					        <input type="text" class="padding_input" name="name1_5" value="{{ old('name1_5') }}">
				     	</div>
				    </div>
				</div>
				<div id="card2" class="col-xl-2">
				    <div class="card cards_design_creacion">
				     	<div class="card-body">
					        <h5 class="card-title">Nombre del Equipo 2:</h5>
					        <input class="redisenio-imput-card" type="text" name="nombreEquipo2" value="{{ old('nombreEquipo2') }}">
					        {{ $errors->first('nombreEquipo2') }}<br>
					        <p class="card-text text-margin-redisenio">Integrantes:</p>
					        <input type="text" class="padding_input" name="name2_1" value="{{ old('name2_1') }}">
					        <input type="text" class="padding_input" name="name2_2" value="{{ old('name2_2') }}">
					        <input type="text" class="padding_input" name="name2_3" value="{{ old('name2_3') }}">
					        <input type="text" class="padding_input" name="name2_4" value="{{ old('name2_4') }}">
					        <input type="text" class="padding_input" name="name2_5" value="{{ old('name2_5') }}">
				     	</div>
				    </div>
				</div>
				<div id="card1" class="col-xl-2">
				    <div class="card cards_design_creacion">
				     	<div class="card-body">
					        <h5 class="card-title">Nombre del Equipo 3:</h5>
					        <input class="redisenio-imput-card" type="text" name="nombreEquipo3" value="{{ old('nombreEquipo3') }}">
					        {{ $errors->first('nombreEquipo3') }}<br>
					        <p class="card-text text-margin-redisenio">Integrantes:</p>
					        <input type="text" class="padding_input" name="name3_1" value="{{ old('name4_1') }}">
					        <input type="text" class="padding_input" name="name3_2" value="{{ old('name4_2') }}">
					        <input type="text" class="padding_input" name="name3_3" value="{{ old('name4_3') }}">
					        <input type="text" class="padding_input" name="name3_4" value="{{ old('name4_4') }}">
					        <input type="text" class="padding_input" name="name3_5" value="{{ old('name4_5') }}">
				     	</div>
				    </div>
				</div>
				<div id="card1" class="col-xl-2">
				    <div class="card cards_design_creacion ">
				     	<div class="card-body">
					        <h5 class="card-title">Nombre del Equipo 4:</h5>
					        <input class="redisenio-imput-card" type="text" name="nombreEquipo4" value="{{ old('nombreEquipo4') }}">
					        {{ $errors->first('nombreEquipo4') }}<br>
					        <p class="card-text text-margin-redisenio">Integrantes:</p>
					        <input type="text" class="padding_input" name="name4_1" value="{{ old('name4_1') }}">
					        <input type="text" class="padding_input" name="name4_2" value="{{ old('name4_2') }}">
					        <input type="text" class="padding_input" name="name4_3" value="{{ old('name4_3') }}">
					        <input type="text" class="padding_input" name="name4_4" value="{{ old('name4_4') }}">
					        <input type="text" class="padding_input" name="name4_5" value="{{ old('name4_5') }}">
				     	</div>
				    </div>
				</div>
			<div id="cardPlus" class="col-xl-2">
				<div class="card-border-redisenio">
			     	<div class="card-body card-plus-tamanio">
			     		<h5 class="card-title">Agregar Equipos:</h5>
			     			<input type="button" class="btn btn-success boton_cracion_design" onclick="crearCard()" value="+"><br><br><br>
			     		<h5 class="card-title">Eliminar Equipos:</h5>
			     			<input type="button" class="btn btn-danger boton_cracion_design" onclick="eliminarCard()" value="x">
			   		</div>
			   	</div>
			</div>
		</div>
	</div>
	<div>
<!-- Footer -->
<footer class="page-footer font-small cyan darken-3 letra_blanca">
  <div class="container">
  <div class="footer-copyright text-center py-3">© 2020 Copyright:
    <button onclick="return Confirmar()" class="btn btn-danger" type="submit">Crear torneo</button>
  </div>

</footer>
</form>

@endsection