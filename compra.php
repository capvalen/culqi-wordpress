<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ADEMPERU - Pago de curso</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body>
	<style>
	#franja{
		background-color: #011631;
		color: #fff;
		font-size: 13px;
	}
	#franja a{color:white;}
	#franaja-negra{ background-color: #15171A; 	color: #fff; }
	#franaja-azul{ background-color: #011126; 	color: #fff; }
	.table .thead-dark th {
    color: #fff;
    background-color: #062650;
    border-color: #062650;
	}
	/* #btnEmpezarPago{
		background-color: #15171a;
	}
	#btnEmpezarPago:hover{
		background-color: #122d44;
	} */
	</style>
	<section id="franja" class="container-fluid py-3">
		<div class="container d-flex justify-content-between">
			<div>
				<strong><span>975 585 816 </span> <span>ayuda@ademperu.com</span></strong>
			</div>
			<div class="d-none">
				<strong><a class="px-2" href="https://ademperu.com/formulario-cuenta" >Regístrese</a> <a href="https://ademperu.com/wp-login.php">Inicio de sesión</a></strong>
			</div>
		</div>
	</section>
	<section id="franaja-negra" class="container-fluid py-3">
		<div class="container">
			<a href="https://ademperu.com"><img src="img/2020-05-08114342.png" alt=""></a>
		</div>
	</section>
	<section id="franaja-azul" class="container-fluid py-3">
		<div class="container">
			<img class="img-fluid" src="img/chkcompra.png" alt="">
		</div>
	</section>
	<section class="container mt-5">
	<?php

	$server="localhost";

	/* Net	*/
	$username="oxlrrisl_adem";
	$password="Mantis1322!";
	$db = "oxlrrisl_adem";

	$cadena= mysqli_connect($server,$username,$password)or die("No se ha podido establecer la conexion");
	$sdb= mysqli_select_db($cadena,$db)or die("La base de datos no existe");
	$cadena->set_charset("utf8");
	mysqli_set_charset($cadena,"utf8");

	$esclavo= new mysqli($server, $username, $password, $db);
	$esclavo->set_charset("utf8");
	$docente= new mysqli($server, $username, $password, $db);
	$docente->set_charset("utf8");
	$alumno= new mysqli($server, $username, $password, $db);
	$alumno->set_charset("utf8");
	$director= new mysqli($server, $username, $password, $db);
	$director->set_charset("utf8");

	$idCurso = $_GET['destino'];

	?>
	<h2>Proceso de aquisición de curso</h2>
	<?php 

	$sql="SELECT * FROM `wp_posts` where id = {$idCurso}";
	$resultado=$cadena->query($sql);
	$rowCursos = $resultado->num_rows;

	if($rowCursos ==0){
		?> <h3>No existe, regrese a la principal y seleccione un curso.</h3>
		<a href="https://ademperu.com/courses/">Volver</a>
		<?php
	}else{
		$row=$resultado->fetch_assoc();
		if( $row['post_type']=='lp_course' ){
			$sqlPrecio="SELECT meta_value FROM `wp_postmeta` where post_id = {$idCurso} and meta_key = '_lp_price'; ";
			$resultadoPrecio=$cadena->query($sqlPrecio);
			if( $resultadoPrecio->num_rows==0 ){
				$precioCurso = 0;
				$gratis = true;
			}else{
				$rowPrecio=$resultadoPrecio->fetch_assoc();
				$precioCurso = $rowPrecio['meta_value'];
				//vemos si tiene descuento
				$sqlDescuento="SELECT meta_value FROM `wp_postmeta` where post_id = {$idCurso} and meta_key =  '_lp_sale_price';";
				$resultadoDescuento=$alumno->query($sqlDescuento);
				if($resultadoDescuento->num_rows>0){
					$rowDescuento=$resultadoDescuento->fetch_assoc();
					$precioEspecial = $rowDescuento['meta_value'];
				}else{
					$precioEspecial = 0;
				}
				
				$gratis = false;
			}
			$tituloCurso= $row['post_title'];
			$linkCurso= "https://ademperu.com/cursos/".$row['post_name'];


			
			//Creando el post del curso
			$sqlPedido="INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
			(NULL, '1', now(), now(), '', concat( 'Pedido en ', CURDATE() ) , '', 'lp-pending', 'closed', 'closed', '', replace(concat( 'pedido en ', CURDATE() ), ' ', '-' ), '', '', now(), now(), '', '0', 'https://ademperu.com/cursos/compra.php?destino={$idCurso}', '0', 'lp_order', '', '0');";
			//echo $sqlPedido;
			$resultadoPedido=$docente->query($sqlPedido);
			$idPostInterno = $docente ->insert_id;


			//Creando el enlace curso, cliente
			$sqlEnlace="INSERT INTO `wp_postmeta`(`meta_id`, `post_id`, `meta_key`, `meta_value`) VALUES
			(null, {$idPostInterno}, '_edit_lock', concat(unix_timestamp(),':386') ),
			(null, {$idPostInterno}, '_order_currency', 'PEN' ),
			(null, {$idPostInterno}, '_prices_include_tax', 'no' ),
			(null, {$idPostInterno}, '_order_subtotal', {$precioCurso} ),
			(null, {$idPostInterno}, '_order_total', {$precioCurso} ),
			(null, {$idPostInterno}, '_order_key', 'ORDER{$idPostInterno}' ),
			(null, {$idPostInterno}, '_payment_method', '' ),
			(null, {$idPostInterno}, '_payment_method_title', '' ),
			(null, {$idPostInterno}, '_order_version', '3.0.0' ),
			(null, {$idPostInterno}, '_edit_last', '1' ),
			(null, {$idPostInterno}, '_user_id', '{$_GET['cliente']}' ),
			(null, {$idPostInterno}, '_user_ip_address', '' ),
			(null, {$idPostInterno}, '_user_agent', '' ),
			(null, {$idPostInterno}, '_created_via', '' ),
			(null, {$idPostInterno}, '	slide_template', 'default' )";
			$resultadoEnlace=$alumno->query($sqlEnlace);
			$idEnlace = $alumno ->insert_id;



			//Creando el pedido de pago del curso con el alumno
			$sqlPedido="INSERT INTO `wp_learnpress_order_items`(`order_item_id`, `order_item_name`, `order_id`) VALUES (null,'{$tituloCurso}', {$idPostInterno} ); ";
			$resultadoPedido=$esclavo->query($sqlPedido);
			$idPedido = $esclavo ->insert_id;

			$sqlDetalles="INSERT INTO `wp_learnpress_order_itemmeta`(`meta_id`, `learnpress_order_item_id`, `meta_key`, `meta_value`) VALUES
			(null, {$idPedido}, '_subtotal', {$precioCurso} ),
			(null, {$idPedido}, '_quantity', 1 ),
			(null, {$idPedido}, '_course_id', {$idCurso} ),
			(null, {$idPedido}, '_total', {$precioCurso} ); ";
			$resultadoDetalles=$cadena->query($sqlDetalles);
			

		

			?>
			<p>Este es el último paso para la compra de tu curso. Usted tiene 2 opciones de pago, los mismos que se hallan en la parte inferior:</p>
			<p> 1. Modo DEPÓSITO BANCARIO consiste en hacer un deposito en las cuentas que se le indicaremos</p>
			<p> 2. Modo PAGO CON TARJETA DÉBITO/CRÉDITO, consiste en pagar directamente con cualquier tipo de tarjeta.</p>
			
			<p>Detalles de la compra:</p>
			<div class="table-responsive">
				<table class="table table-hover">
					<thead class="thead-dark">
						<tr>
							<th scope="col">Pedido</th>
							<th scope="col">Curso</th>
							<th scope="col">Centro educativo</th>
							<th scope="col">Precio</th>
							<th scope="col">Promoción</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td id="divPostInt">#<?= $idPostInterno; ?></td>
							<td><?= $tituloCurso; ?></td>
							<td>Adem Perú</td>
							<td data-precio="<?= $precioCurso; ?>">S/ <?php if(floatval($precioEspecial)>0){ echo "<span><strike>" . number_format($precioCurso, 2) . "<strike></span>"; }else{ number_format($precioCurso, 2); } ?></td>
							<td data-precio="<?= $precioEspecial; ?>">S/ <?= number_format($precioEspecial, 2); ?></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="2"><label for=""><strong>Debe seleccionar el modo de pago</strong></label>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="exampleRadios" id="radioBanco" value="banco" onclick="$('#btnPagoCuentas').removeClass('d-none');$('#btnEmpezarPago').addClass('d-none');">
								<label class="form-check-label" for="radioBanco">Depósito Bancario</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="exampleRadios" id="radioTarjeta" value="tarjeta" onclick="$('#btnEmpezarPago').removeClass('d-none');$('#btnPagoCuentas').addClass('d-none');" checked>
								<label class="form-check-label" for="radioTarjeta">Pago con tajeta débito/crédito</label>
							</div>
							</td>
							<td></td>
							
							<td></td>
						</tr>
					</tbody>
				</table>
				
			</div>
			<!-- <h3>Pedido: #<?= $idPostInterno; ?> </h3>
			<h3>Curso: <?= $tituloCurso; ?> </h3>
			<h5>Centro educativo: Adem Perú</h5>
			<h5>Total: S/ <?= $precioCurso; ?></h5> -->
			<div class="d-flex justify-content-end">
			
				<button class="d-none btn btn-primary btn-lg rounded-0" onclick="$('#modalPagoBien').modal('show');">Probar ventana</button>
				<button class="btn btn-success btn-lg rounded-0" id="btnEmpezarPago">Completar pago</button>
				<button class="btn btn-success btn-lg rounded-0 d-none" id="btnPagoCuentas">Completar pago</button>
			</div>
			
			<div class="row d-flex justify-content-end mt-3">
				<p class="text-muted text-right pr-3">Este procedimiento es completamente seguro, cuenta con la asistencia avanzada del antivirus Imunify 360, con Certificado SSL y con el sistema de codificación y seguridad de TEAM CULQUI.</p> 
			</div>
			<div class="row d-flex justify-content-end">
				<div>
					<img src="img/1882900_original.jpg" alt="" width="50px">
					<img src="img/Imunify360-1.png" alt="" width="200px">
					<img src="img/ssl-certificate.jpg" alt="" width="200px">
				</div>

			</div>

			<?php
		}else{
			?> <h3>No existe, regrese a la principal y seleccione un curso.</h3>
				<a href="https://ademperu.com/courses/">Volver</a>
			<?php
		}

	}
	?>
	</section>


<!-- Modal -->
<div class="modal fade" id="modalPagoBien" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<img src="https://cdn.dribbble.com/users/791530/screenshots/6827794/clip-illustration-style-icons8.png" class="img-fluid">
				<h3 class="text-center">Pago procesado</h3>
				<p class="text-center">Ahora puedes continuar estudiando</p>
				<a class="btn btn-secondary btn-block" href="<?= $linkCurso; ?>">Ir al curso</a>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalPagoMal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<img src="https://ademperu.com/wp-content/uploads/2020/04/nopasapago.png" class="img-fluid">
				<h3 class="text-center">Pago no aceptado</h3>
				<p class="text-center">Hay un error con la plataforma y la tarjeta de débito/crédito. Por favor revisa tu estado de cuenta, y si encuentras algo que no va bien, comunícate con soporte para que activemos tu cuenta manualmente. Gracias y disculpe los inconvenientes</p>
				<a class="btn btn-secondary btn-block" href="https://ademperu.com/">Salir</a>
			</div>
		</div>
	</div>
</div>
<div id="modalCuentas" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true" data-backdrop="static" >
	<div class="modal-dialog" role="document">
		<div class="modal-content">		
			<div class="modal-body">
				<button class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<img src="img/ilustracion-vectorial-pago-impuestos-linea_95561-82.jpg" alt="" class="img-fluid">
				<h4 class="modal-title mb-4 display-4" id="my-modal-title">Depósito bancario</h4>
				<p><strong>Instrucciones para el pago:</strong> </p>
				<p>Este modo de pago, consiste en hacer el depósito en las cuentas bancarias indicadas; y una vez realizado, enviar en el comprobante de depósito al whatsapp del celular <strong>996 735382</strong> con el código <strong id="strCodigo"></strong></p>
				<p>Posteriormente en un plazo de 1 hora procederemos a habilitar el curso, el mismo que será confirmado con el envío a su correo electrónico y al número de celular registrado. Gracias por su confianza Colega.</p>
				<ul>
					<li><strong>BCP:</strong> <span>355-98897650-0-51</span></li>
					<li><strong>BBVA:</strong> <span>0011-0266-47-0200208207</span></li>
					<li><strong>Interbank:</strong> <span>8983148368980</span></li>
					<li><strong>Banco de la Nación:</strong> <span>04-388-511821</span></li>
				</ul>
				<p></p>
				<p>Todas las cuentas son en Soles y están a nombre de los apellidos: <strong>Gonzales Rojas</strong></p>
			</div>
		</div>
	</div>
</div>


<!-- Incluye Culqi Checkout en tu sitio web-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<script src="https://checkout.culqi.com/js/v3"></script>

<script>
<?php if( $precioCurso > 0){
	if($precioEspecial>0){ $precioCurso =  $precioEspecial; }
	?>
		// Configura tu llave pública
		Culqi.publicKey = 'pk_test_ee1ae9ee0bb87081';
		// Configura tu Culqi Checkout
		Culqi.settings({
				title: 'ADEMPERU',
				currency: 'PEN',
				description: 'Curso: <?= $tituloCurso; ?>',
				amount: <?= $precioCurso*100; ?>
		});
		// Usa la funcion Culqi.open() en el evento que desees
		$('#btnEmpezarPago').on('click', function(e) {
				// Abre el formulario con las opciones de Culqi.settings
				Culqi.open();
				e.preventDefault();
		});

		function culqi() {
			if (Culqi.token) { // ¡Objeto Token creado exitosamente!
				var token = Culqi.token.id;
				var email = Culqi.token.email;
				//alert('Se ha creado un token:' + token);
				//En esta linea de codigo debemos enviar el "Culqi.token.id" hacia tu servidor con Ajax
				let data = {producto: 'Curso: <?= $tituloCurso; ?>', precio: <?= $precioCurso*100; ?>, especial: <?= $precioEspecial*100;?>, token: token, correo: email, post: <?= $idPostInterno; ?>, curso: <?= $idCurso; ?>, cliente: <?= $_GET['cliente'];?> };
				let url = 'https://ademperu.com/cursos/proceso.php';
				$.post(url, data, function(resp){
					console.log( resp );
					if(resp == 'Gracias'){
						$('#modalPagoBien').modal('show');
					}else{
						$('#modalPagoMal').modal('show');
					}
				});

			} else { // ¡Hubo algún problema!
					// Mostramos JSON de objeto error en consola
					console.log(Culqi.error);
					alert(Culqi.error.user_message);
			}
		};
<?php }else{ ?>
	$('#btnEmpezarPago').on('click', function(e) {
		var token = '';
		var email = '';
		//alert('Se ha creado un token:' + token);
		//En esta linea de codigo debemos enviar el "Culqi.token.id" hacia tu servidor con Ajax
		let data = {producto: 'Curso: <?= $tituloCurso; ?>', precio: 0, token: token, correo: email, especial:0, post: <?= $idPostInterno; ?>, curso: <?= $idCurso; ?>, cliente: <?= $_GET['cliente'];?> };
		let url = 'https://ademperu.com/cursos/proceso.php';
		$.post(url, data, function(resp){
			console.log( resp );
			if(resp == 'Gracias'){
				$('#modalPagoBien').modal('show');
			}else{
				$('#modalPagoMal').modal('show');
			}
		});
	});
<?php } ?>
$('#btnPagoCuentas').click(function(){
	$('#strCodigo').text( $('#divPostInt').text() );
	$('#modalCuentas').modal('show');
});
</script>

</body>
</html>