@extends('layouts/head-wow')


@section('Cuerpo')
<section id="hero"></section>

<div class="container alto-content">
	<div class="row">
		<?php
			echo $tarjetas;
		?>
	</div>
</div>
@endsection