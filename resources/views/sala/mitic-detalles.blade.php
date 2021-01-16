@extends('layouts/head-wow')


@section('Cuerpo')
<section id="hero"></section>
		<?php
			if(is_string($participantes[13])){
				$var=33;
			}
			else{
				$var=13;
			}
		?>
	<form method="POST" action="{{route('RGuardarDetalles',$participantes[$var])}}" enctype="multipart/form-data">
        @csrf
		<?php
			echo $pagina;
		?>
    </form>
@endsection