<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
		<h1>hola mundo</h1>
</body>
</html>

<?php


use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();
$dompdf->loadHtml('hello world');

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();





?>