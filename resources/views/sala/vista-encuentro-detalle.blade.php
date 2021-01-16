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
		<?php
			echo $pagina;
		?>
@endsection
<script type="text/javascript">

	const $elementoParaConvertir = document.body; // <-- Aquí puedes elegir cualquier elemento del DOM
		html2pdf()
		    .set({
		        margin: 0,
		        filename: 'Fixture_GamingUmpire.pdf',
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
</script>