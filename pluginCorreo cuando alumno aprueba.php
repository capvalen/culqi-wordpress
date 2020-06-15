<?php
//add_action('learn-press/quiz/after-complete-button', 'enviarCorreoCertificado'  );

function enviarCorreoCertificado($idQuiz){
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
	$esclavo= mysqli_connect( DB_HOST , DB_USER, DB_PASSWORD)or die("No se ha podido establecer la conexion");
	$sdb= mysqli_select_db($esclavo, DB_NAME )or die("La base de datos no existe");
	$esclavo->set_charset("utf8");
	
	$sql="SELECT user_item_id FROM `wp_learnpress_user_items` where user_id = ". get_current_user_id() ." and item_id = ". $idQuiz .";";
	echo $sql;
	$resultadoCompleto=$esclavo->query($sql);
	if( $resultadoCompleto->num_rows > 0 ){
		$rowCompleto=$resultadoCompleto->fetch_assoc();
		$idItem =  $rowCompleto['user_item_id'];
		
		$sqlItem="SELECT meta_value FROM `wp_learnpress_user_itemmeta` where learnpress_user_item_id = ". $idItem ." and meta_key ='grade'; ";
		$resultadoItem = $esclavo->query($sqlItem);
		$rowItem = $resultadoItem->fetch_assoc();

		wp_mail('infocat2.0@gmail.com', 'Aprobaron un curso', 'Hola, alguien aprobó un curso con el código: ' . get_current_user_id() . '-' . get_the_ID() . ' resultado del examen: ' . $rowItem['meta_value'] . " respuesta 6");	
	}
	
}

function holaPeru($idAlumno, $curso ){
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
	$esclavo= mysqli_connect( DB_HOST , DB_USER, DB_PASSWORD)or die("No se ha podido establecer la conexion");
	$sdb= mysqli_select_db($esclavo, DB_NAME )or die("La base de datos no existe");
	$esclavo->set_charset("utf8");
	
	$sql="SELECT * FROM `certificadoAprobado` where idUsuario = ". $idAlumno ." and idCurso = ". $curso .";";
	//echo $sql;
	$resultadoCompleto=$esclavo->query($sql);
	if( $resultadoCompleto->num_rows == 0 ){
		
		wp_mail('certificados@ademperu.com', 'Aprobaron un curso', 'Hola, el alumno aprobó el curso con el código: ' . $idAlumno . '-' . $curso . ' para su certificado.'  );	
		$sqlInsertar = "INSERT INTO `certificadoAprobado`(`idCertificado`, `idUsuario`, `idCurso`) VALUES ( null, ". $idAlumno. "," . $curso. " )";
		$resultado = $esclavo->query($sqlInsertar);
	}
}