<?php




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
$docente= new mysqli($server, $username, $password, $db);
$docente->set_charset("utf8");
$alumno= new mysqli($server, $username, $password, $db);
$alumno->set_charset("utf8");
$director= new mysqli($server, $username, $password, $db);
$director->set_charset("utf8");

?>