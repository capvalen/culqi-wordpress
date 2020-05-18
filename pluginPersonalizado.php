<?php
function usuarios_plugin() {
   add_option( 'nombre_plugin', 'Usuarios registrados');
   register_setting( 'myplugin_options_group', 'nombre_plugin', 'myplugin_callback' );
}
add_action( 'admin_init', 'usuarios_plugin' );

function registro_de_opciones() {
  add_options_page('Consolidado -', 'Consolidado de Usuarios', 'manage_options', 'consolidadoUsuarios', 'ejecutar_plg');
}
add_action('admin_menu', 'registro_de_opciones');


function ejecutar_plg()
{
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );

	
	if ( ! current_user_can( 'manage_options' ) )  {
    return;
  }else{} //fin de comprobar si es admin
	
		$cadena= mysqli_connect( DB_HOST , DB_USER, DB_PASSWORD)or die("No se ha podido establecer la conexion");
		$sdb= mysqli_select_db($cadena, DB_NAME )or die("La base de datos no existe");
		$cadena->set_charset("utf8");
		$sqlCompleto="SELECT * FROM wp_users WHERE ID<>1 order by id desc; ";
		$resultadoCompleto=$cadena->query($sqlCompleto); $i=0;
	
	define( 'WP_DEBUG', true );
		
	

?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<style>
.fade{display:none!important}
</style>
  <div class="wrap">
	  <h2>Resumen de todos los usuarios registrados</h2>
	  <p>Todos los usuarios registrados hasta la fecha:</p>
	  <table class="table table-hover">
	  <thead>
		<tr>
		  <th scope="col">#</th>
		  <th scope="col">Nombres completos</th>
		  <th scope="col">DNI</th>
		  <th scope="col">Correo</th>
			<th scope="col">Celular</th>
			<th scope="col">Edad</th>
			<th scope="col">Departamento</th>
			<th scope="col">Provincia</th>
			<th scope="col">Distrito</th>
			<th scope="col">Grado</th>
			<th scope="col">Labora en</th>
			<th scope="col">Cargo</th>
			<th scope="col">Institución</th>
			<th scope="col">Desea laborar</th>
			<th scope="col">Áreas</th>
			<th scope="col">Redes sociales</th>
		</tr>
	  </thead>
	  <tbody>
		<?php while( $rowCompleto=$resultadoCompleto->fetch_assoc() ){ ?>
		  	<tr>
			  <th scope="row"><?= $i+1; ?></th>
			  <td><?= ucwords( strtolower( $rowCompleto['noombreCompleto'])); ?></td>
				<td><?= $rowCompleto['usDni']; ?></td>
				<td><?= $rowCompleto['usCorreo']; ?></td>
				<td><?= $rowCompleto['usCelular']; ?></td>
				<td><?= $rowCompleto['usEdad']; ?></td>
				<td><?= $rowCompleto['usDepartamento']; ?></td>
				<td><?= $rowCompleto['usProvincia']; ?></td>
				<td><?= $rowCompleto['usDistrito']; ?></td>
				<td><?= $rowCompleto['usGrado']; ?></td>
				<td><?= $rowCompleto['usLabora']; ?></td>
				<td><?= $rowCompleto['usCargo']; ?></td>
				<td><?= $rowCompleto['usInstitucion']; ?></td>
				<td><?= $rowCompleto['usDesea']; ?></td>
				<td><?= $rowCompleto['usAreas']; ?></td>
				<td><?= $rowCompleto['usEntero']; ?></td>
			  
			</tr>
		<?php $i++; } ?>
		  </tbody>
	  </table>
  </div>
<?php
	
} 