@extends('layouts/head-wow')


@section('Cuerpo')
<section id="hero"></section>


<?php
	echo $extras[0];
?>
<div class="row ">
	<div class="col-xl-6 mx-auto">
		<main id="tournament" class="torneo_desing">
			<?php
				echo $fixture;
			?>
		</main>
	</div>
	<div class="col-xl-4">
		<?php
			if(count($extras)==2){
				echo $extras[1];
			}
			//echo $extras[1];
		?>
	</div>
</div>


@endsection