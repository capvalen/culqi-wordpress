<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Cargamos Requests y Culqi PHP
include_once dirname(__FILE__).'/Requests/library/Requests.php';
Requests::register_autoloader();
include_once dirname(__FILE__).'/culqi-php/lib/culqi.php';


$server="localhost";

/* Net	*/
$username="";
$password="";
$db = "";

$cadena= mysqli_connect($server,$username,$password)or die("No se ha podido establecer la conexion");
$sdb= mysqli_select_db($cadena,$db)or die("La base de datos no existe");
$cadena->set_charset("utf8");
mysqli_set_charset($cadena,"utf8");

$esclavo= new mysqli($server, $username, $password, $db);
$esclavo->set_charset("utf8");


try {
	
	$SECRET_KEY = "sk_test_e1138a738c5946f2";
	$culqi = new Culqi\Culqi(array('api_key' => $SECRET_KEY));


	// Creamos Cargo a una tarjeta
	$charge = $culqi->Charges->create(
		array(
			"amount" => $_POST['precio'],
			"capture" => true,
			"currency_code" => "PEN",
			"description" => $_POST['producto'],
			"email" => $_POST['correo'],
			"installments" => 0,
			"antifraud_details" => array(
				"address" => "Av. Lima 123",
				"address_city" => "LIMA",
				"country_code" => "PE",
				"first_name" => "Will",
				"last_name" => "Muro",
				"phone_number" => "9889678986",
		),
			"source_id" => $_POST['token']
		)
	);

	//Respuesta
	//print_r($charge);
	if( $charge->outcome->type=='venta_exitosa' ){
		$sql="UPDATE `wp_posts` SET `post_status` = 'lp-completed', post_modified=now(), post_modified_gmt=now() WHERE `ID` = {$_POST['post']} ;";
		$resultado=$cadena->query($sql);

		$sqlItemUser="INSERT INTO `wp_learnpress_user_items`(`user_item_id`, `user_id`, `item_id`, `start_time`, `start_time_gmt`, `end_time`, `end_time_gmt`, `item_type`, `status`, `ref_id`, `ref_type`, `parent_id`) VALUES 
		(null, {$_POST['cliente']}, {$_POST['curso']}, now(), now(), '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'lp_course', 'enrolled', {$_POST['post']}, 'lp_order', 0)";
		$resultadoItemUser=$esclavo->query($sqlItemUser);
		$idItemUser = $esclavo->insert_id;


		$sql="INSERT INTO `wp_learnpress_user_itemmeta`(`meta_id`, `learnpress_user_item_id`, `meta_key`, `meta_value`) VALUES 
		(null, {$idItemUser}, '_last_status', ''),
		(null, {$idItemUser}, '_current_status', 'enrolled'),
		(null, {$idItemUser}, 'course_results_evaluate_lesson', 'a:6:{s:6:'result';i:0;s:5:'grade';s:11:'in-progress';s:6:'status';s:8:'enrolled';s:11:'count_items';s:1:'2';s:15:'completed_items';i:0;s:13:'skipped_items';i:2;}'),
		(null, {$idItemUser}, 'grade', 'in-progress'),
		";
		$resultado=$cadena->query($sql);
	
		
		
		print_r("Gracias");
	}else{
		print_r("Error de tarjeta");
	}

} catch (\Throwable $th) {
	print_r("Error en conexión");
}
