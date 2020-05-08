<?php
/* Template Name: Registro personalizado */
//var_dump($_POST);
global $wpdb;

$salto="<br>";

if($_POST){
	$usuario = $wpdb->prepare($_POST['dni']); // "calinfa2";
	$correo = $wpdb->prepare($_POST['correo']);
	$error='';
	
	if(empty($usuario) ){$error .= "Usuario no puede estar vacío"; }
	else if(username_exists($usuario) ){$error  .= "Usuario ya se encuentra registrado"; }
	else if (email_exists($correo)) { $error  .= "El correo ya está registrado"; }
	else{

	$mensaje = '<strong>Nombre: </strong>'.$_POST['nombres'].$salto.
		'<strong>Apellidos: </strong>'.$_POST['apellidos'].$salto.
		'<strong>DNI: </strong>'.$_POST['dni'].$salto.
		'<strong>Correo electrónico: </strong>'.$_POST['correo'].$salto.
		'<strong>Celular: </strong>'.$_POST['celular'].$salto.
		'<strong>Edad: </strong>'.$_POST['edad'].$salto.
		'<strong>Departamento: </strong>'.$_POST['departamento'].$salto.
		'<strong>Provincia: </strong>'.$_POST['provincia'].$salto.
		'<strong>Distrito: </strong>'.$_POST['distrito'].$salto.
		'<strong>Grado: </strong>'.$_POST['grado'].$salto.
		'<strong>Labora en: </strong>'.$_POST['labora'].$salto.
		'<strong>Cargo: </strong>'.$_POST['cargo'].$salto.
		'<strong>Institución: </strong>'.$_POST['institucion'].$salto.
		'<strong>Cargo que desea: </strong>'.$_POST['labora_futuro'].$salto.
		'<strong>Habilidades en áreas: </strong>'.$_POST['areas'].$salto.
		'<strong>Se enteró: </strong>'.$_POST['redes'].$salto;
		//echo $mensaje;
		$headers = array('Content-Type: text/html; charset=UTF-8','From: ADEMPERU <hola@ademperu.com>');
		
		$idUsuario = wp_create_user($usuario, '', $correo);

		/*Guardando nombre completo del usuario en la DB */
		$server="localhost";
		$username="";
		$password="";
		$db = "";
		$cadena= mysqli_connect($server,$username,$password)or die("No se ha podido establecer la conexion");
		$sdb= mysqli_select_db($cadena,$db)or die("La base de datos no existe");
		$cadena->set_charset("utf8");
		mysqli_set_charset($cadena,"utf8");

		$sqlCompleto="UPDATE `wp_users` SET `noombreCompleto` ='{$_POST['nombres']} {$_POST['apellidos']}' where `ID` = '{$idUsuario}'";
			//echo $sqlPedido;
		$resultadoCompleto=$cadena->query($sqlCompleto);

		/*Fin de Guardando nombre completo del usuario en la DB */
		wp_mail( 'hola@ademperu.com', 'Nuevo registro por la Web', $mensaje, $headers );
		wp_new_user_notification($idUsuario, null, 'user');
	}
}
if( strlen($error)>0){
	echo $error;
}



?>
<style>
	#divFormularioPrimero input, #divFormularioPrimero textarea{
		width:100%;
		margin-bottom: 15px;
	}
	.cajaBoton{
		width: 50%;
		cursor: pointer;
		background: black; color:white;
		padding: 10px 15px;
    	font-size: 20px;
	}
	.hidden{display:none;}
	#divFormularioPrimero .falta{
		border: 1px solid #f99;
	}
	#overlay {
		position: fixed; /* Sit on top of the page content */
		display: none; /* Hidden by default */
		width: 100%; /* Full width (cover the whole page) */
		height: 100%; /* Full height (cover the whole page) */
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: rgba(255, 255, 255, 0.62);
		z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
		cursor: pointer; /* Add a pointer on hover */
	}
	#overlay #contenido{
		position: fixed;
		top: 50%;
		left: calc(50% - 217px);
		color: #12266d;
	}
</style>
<link rel="stylesheet" href="https://ademperu.com/cursos/css/animate.css">
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

<?php if(strlen($error) == 0  && $_POST): ?>
	<h3>Usuario guardado con éxito</h3>
	<p>Hemos enviado un mail a su correo, revíselo para que pueda cambiar su contraseña. Gracias.</p>
<?php else: ?>
<form method="post" id="formPrincipal">
<div id="divFormularioPrimero">
	<h1>Registro para ADEMPERU</h1>
	<p class="sub-heading">Para registrarse, rellene el cuestionario</p>
		
	<div id="divPrimeraParte">	
		<h3>Datos personales</h3>
		<!-- <input type="text" placeholder="Usuario" v-bind:class="{'falta': faltaUsuario}" v-model="usuario" name="usuario"> -->
		<input type="text" placeholder="Nombres completos" v-bind:class="{'falta': faltaNombre}" v-model="nombres" name="nombres">
		<input type="text" placeholder="Apellidos completos"v-bind:class="{'falta': faltaApellidos}"  v-model="apellidos" name="apellidos">
		<input type="text" placeholder="D.N.I." v-bind:class="{'falta': faltaDni}" v-model="dni" name="dni">
		<input type="email" placeholder="Correo electrónico" v-bind:class="{'falta': faltaCorreo}" v-model="correo" name="correo">
		<input type="text" placeholder="Número de celular" v-bind:class="{'falta': faltaCelular}" v-model="celular" name="celular">
		<input type="number" placeholder="Edad" v-bind:class="{'falta': faltaEdad}" v-model="edad" name="edad">
		<div class="form-group">
			<label for="">Departamento</label>
			<select placeholder="Departamento" v-bind:class="{'falta': faltaDepartamento}" v-model="departamento" name="departamento" style="width:100%">
			<option value="Amazonas">Amazonas</option>
			<option value="Ancash">Ancash</option>
			<option value="Apurimac">Apurimac</option>
			<option value="Arequipa">Arequipa</option>
			<option value="Ayacucho">Ayacucho</option>
			<option value="Cajamarca">Cajamarca</option>
			<option value="Callao">Callao</option>
			<option value="Cusco">Cusco</option>
			<option value="Huancavelica">Huancavelica</option>
			<option value="Huanuco">Huanuco</option>
			<option value="Ica">Ica</option>
			<option value="Junin">Junin</option>
			<option value="La Libertad">La Libertad</option>
			<option value="Lambayeque">Lambayeque</option>
			<option value="Lima">Lima</option>
			<option value="Loreto">Loreto</option>
			<option value="Madre De Dios">Madre De Dios</option>
			<option value="Moquegua">Moquegua</option>
			<option value="Pasco">Pasco</option>
			<option value="Piura">Piura</option>
			<option value="Puno">Puno</option>
			<option value="San Martin">San Martin</option>
			<option value="Tacna">Tacna</option>
			<option value="Tumbes">Tumbes</option>
			<option value="Ucayali">Ucayali</option>n>
			</select>
		</div>
		<input type="text" placeholder="Provincia" v-bind:class="{'falta': faltaProvincia}" v-model="provincia" name="provincia">
		<input type="text" placeholder="Distrito" v-bind:class="{'falta': faltaDistrito}" v-model="distrito" name="distrito">
		<div class="cajaBoton" data-prox="2" @click="validar(2)">
			<span><i class="fa fa-chevron-right" aria-hidden="true"></i> <span>Continuar</span> </span>
		</div>
	</div>
		
	<div class="hidden" id="divSegundaParte">
		<h3>Datos del centro laboral</h3>
		<input type="text" placeholder="¿Cuál es su grado de estudios?" v-bind:class="{'falta': faltaGrado}"  v-model="grado" name="grado">
		<input type="text" placeholder="¿Dónde labora actualmente?" v-bind:class="{'falta': faltaLabora}"  v-model="labora" name="labora">
		<input type="text" placeholder="¿Qué cargo ostenta?" v-bind:class="{'falta': faltaCargo}"  v-model="cargo" name="cargo">
		<label>Según sus expectativas laborales</label>
		<input type="text" placeholder="¿En qué institución desea laborar?" v-bind:class="{'falta': faltaInstitucion}" v-model="institucion" name="institucion">
		<input type="text" placeholder="¿En qué cargo desea laborar?" v-bind:class="{'falta': faltaLabora_futuro}" v-model="labora_futuro" name="labora_futuro">
		<div class="cajaBoton" data-prox="3" @click="validar(3)">
			<span><i class="fa fa-chevron-right" aria-hidden="true"></i> <span>Continuar</span> </span>
		</div>
	</div>
	
	<div class="hidden" id="divTerceraParte">
		<h3>Datos de desarrollo de habilidades</h3>
		<label for="">¿En qué temas y/o áreas del derecho desea incrementar sus habilidades?</label>
		<textarea cols="30" rows="5" placeholder="Temas" v-bind:class="{'falta': faltaAreas}" v-model="areas" name="areas"></textarea>
		<div class="form-group">
			<label for="my-select">¿Cómo se enteró del evento?</label>
			<select id="my-select" class="form-control" v-bind:class="{'falta': faltaRedes}" v-model="redes" name="redes">
				<option value="facebok">Facebook</option>
				<option value="pagina-web">Páginas web</option>
				<option value="whatsapp">WhatsApp</option>
				<option value="e-mail">Correo electrónico</option>
				<option value="otros">Otros</option>
			</select>
		</div>
		<div class="cajaBoton" data-prox="-1"  @click="validar(4)">
			<span><i class="fa fa-save" aria-hidden="true"></i> <span>Registrarse</span> </span>
		</div>
	</div>
	<div class="hidden" id="divAgradecimiento">
		<h3>Agradecemos que te hayas registrado</h3>
		<p>A continuación recibirás un correo electrónico para que puedas ingresar tu contraseña y llevar los cursos.</p>
	</div>
</div>
</form>
<?php endif; ?>


<div id="overlay"><span id="contenido"><img src="https://ademperu.com/wp-content/uploads/2020/04/favic.png" alt=""> Guardando sus datos...</span></div>


<script>
var app = new Vue({
  el: '#divFormularioPrimero',
	data: {
		usuario: '', nombres: '', apellidos: '', dni: '', correo: '', celular: '', edad: '', departamento: '', provincia: '', distrito: '', grado: '', labora: '', cargo: '', areas: '', redes: '', institucion: '', labora_futuro: '',
		
		faltaUsuario:false, faltaNombre: false, faltaApellidos:false, faltaDni:false, faltaCorreo:false, faltaCelular:false, faltaEdad: false, faltaDepartamento: false, faltaProvincia: false, faltaDistrito: false, faltaGrado: false, faltaLabora: false, faltaCargo: false, faltaInstitucion: false, faltaLabora_futuro: false, faltaAreas: false, faltaRedes: false },
	methods:{
		validar(hacia){ console.log('Empezando valir')
			if(hacia==2){
				var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

				/* if(this.usuario==''){ this.faltaUsuario=true; } */
				if(this.nombres==''){ this.faltaNombre=true; }
				else if(this.apellidos==''){ this.faltaApellidos=true; }
				else if(this.dni==''){ this.faltaDni=true; }
				else if(this.correo=='' || !this.correo.match(mailformat)){ this.faltaCorreo=true; }
				else if(this.celular==''){ this.faltaCelular=true; }
				else if(this.edad==''){ this.faltaEdad=true; }
				else if(this.departamento==''){ this.faltaDepartamento=true; }
				else if(this.provincia==''){ this.faltaProvincia=true; }
				else if(this.distrito==''){ this.faltaDistrito=true; }
				else{
					jQuery('#divPrimeraParte').addClass('hidden'); jQuery('#divSegundaParte').addClass('animated bounceIn').removeClass('hidden')
				}
				
			}
			if(hacia==3){
				if(this.grado==''){ this.faltaGrado=true; }
				else if(this.labora==''){ this.faltaLabora=true; }
				else if(this.cargo==''){ this.faltaCargo=true; }
				else if(this.institucion==''){ this.faltaInstitucion=true; }
				else if(this.labora_futuro==''){ this.faltaLabora_futuro=true; }
				else{
					jQuery('#divSegundaParte').addClass('hidden'); jQuery('#divTerceraParte').addClass('animated bounceIn').removeClass('hidden')
				}
			}
			if(hacia==4){
				if(this.areas==''){ this.faltaAreas=true; }
				else if(this.redes==''){ this.faltaRedes=true; }
				else{
					jQuery('#overlay').css('display', 'block');
					jQuery('#formPrincipal').submit();
				}
			}
		}
	}
})
</script>