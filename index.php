<?php 


if( isset($_GET['cliente']) && isset($_GET['curso']) ):
	require('infocat.php');

	


	
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

	$idCurso= $_GET['curso'];
	$idCliente = $_GET['cliente'];

	$sqlEnlace= "SELECT ref_id, start_time, end_time FROM `wp_learnpress_user_items` where user_id = '{$idCliente}' and item_id ='{$idCurso}' and ref_type = 'lp_order'; ";
	$resultadoEnlace=$cadena->query($sqlEnlace);
	$rowEnlace=$resultadoEnlace->fetch_assoc();
	$numEnlaces = $resultadoEnlace->num_rows;
	//echo "enlaces: ". $numEnlaces;
	
	if( $numEnlaces==0 ){
		imprimirError();
		exit();
	}
	$idPost = $rowEnlace['ref_id'];

	$sqlTitulo="SELECT order_item_name FROM `wp_learnpress_order_items` where order_id = {$idPost}; ";
	$resultadoTitulo=$cadena->query($sqlTitulo);
	$rowTitulo=$resultadoTitulo->fetch_assoc();
	$tituloCurso = $rowTitulo['order_item_name'];
		

	$sqlDuracion="SELECT meta_value FROM `wp_postmeta` where post_id = {$idCurso} and meta_key = 'thim_course_duration'; ";
	$resultadoDuracion=$cadena->query($sqlDuracion);
	$rowDuracion=$resultadoDuracion->fetch_assoc();

	$fechaComienza = new DateTime($rowEnlace['start_time']);
	$fechaFin = new DateTime($rowEnlace['end_time']);
	if( $fechaFin == '0000-00-00 00:00:00' ){
		imprimirError();
		exit();
	}
	


require('fpdf/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();

//Img fondo
$pdf->Image('imgs/modelo_fondo.png',0,0, 210, 300);


$pdf->SetFont('Arial','B',18);
$pdf->SetXY(55, 70);
$nombre = utf8_decode('Carlos Alex Pariona Valencia');
$w = $pdf->GetStringWidth( $nombre )+6;
$pdf->SetX((210-$w)/2);
$pdf->Cell($w,9,$nombre );


$pdf->SetFont('Arial','B',24);
$pdf->SetXY(35, 110);
$titulo = utf8_decode( $tituloCurso );
$w = $pdf->GetStringWidth( $titulo )+6;
$pdf->SetX((210-$w)/2);
$pdf->Cell($w,9,$titulo );


$pdf->SetFont('Arial', '', 12);
$pdf->SetX(15);


$pdf->Cell(15,70, utf8_decode( 'Desarrollado el día ' . $fechaComienza->format('d') ." de " . $meses[$fechaComienza->format('n')-1] . ' del ' .$fechaComienza->format('Y') . ', desarrollado en la modalidad  Online,'));
$pdf->SetX(15);
$pdf->Cell(15,81, utf8_decode('con una duración de ' . $rowDuracion['meta_value'] . ' académicas, organizado por la Academia de Desarrollo Profesional '));
$pdf->SetX(15);
$pdf->Cell(15,91, utf8_decode('y Emprendimiento en colaboración con la Asociación ASOROS'));

$pdf->SetFont('Arial', '', 14);
$pdf->SetXY(120, 200);
$pdf->Cell(55,10, utf8_decode('Huancayo, ' . $fechaFin->format('d') ." de " . $meses[$fechaFin->format('n')-1] . ' del ' .$fechaFin->format('Y') ));



$pdf->Image("https://ademperu.com/certificados/generador.php?cliente={$_GET['cliente']}&curso={$_GET['curso']}", 150, 245, 30, 30, 'png');

$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(80, 250);
$pdf->Cell(50,10, utf8_decode('QR de validación'));

$pdf->SetXY(80, 255);
$pdf->Cell(50,10, utf8_decode('Acceda a: https://ademperu.com/certificado'));
$pdf->Ln();
$pdf->SetX(80);
$pdf->Cell(50,0, utf8_decode('Código: ' . $_GET['cliente']."-". $_GET['curso']));

$pdf->Output();
exit;
else:
	imprimirError();
endif;


function imprimirError(){ 
	header("Location: https://ademperu.com/no-data/");
	exit();
} ?>