<?php
	/**************************************************
		Sistema de contabilidad
		Desarrollador: Rexy Studios
		Año de creación: 2020
		Última modificación del archivo: 21-04-2020
	**************************************************/
	@session_start();
	include_once('variables_globales.php');
	include_once('quick_function.php');
	$Quick_function = new Quick_function;
	
	$uri=$Quick_function->TraerParametro("URI");
	$url_sistema=$uri."admin/";
	$url_login=$uri;
	$url_register=$uri."register-creator.php";
	$url_recuperarpass=$uri."forgot.php";
	
	
	$formaction='';
	if(isset($_POST['formaction'])){ $formaction=$_POST['formaction']; } else if(isset($_GET['formaction'])){ $formaction=$_GET['formaction']; }
		
	
	$valida_recaptcha = TRUE;
	$valida_login = TRUE;
	if(DEBUG !== "DEVELOP"){
		if(isset($_POST['g-recaptcha-response'])){
			$secret = KEY_SECRETO;
			$URL_recaptcha='https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response'];
			
			$verifyResponse = file_get_contents($URL_recaptcha);
			$responseData = json_decode($verifyResponse);
			
			if( !($responseData->success) ){ $valida_login = FALSE; }
		}
		else{
			$valida_recaptcha = FALSE;
		}
	}
	
	if($valida_recaptcha == TRUE){

        if($valida_login == TRUE){
			switch($formaction){
				case "login":
					login($url_login, $url_sistema, $Quick_function);
				break;
				case "register-creator":
					register($url_login, $url_register, $url_sistema, $Quick_function);
				break;
				case "forget":
					forget($url_recuperarpass, $url_sistema, $Quick_function);
				break;
				case "elimina":
					echo "se elimina";
				break;
				default:
					logout($url_login, $Quick_function);
				break;
			}
        }
		else{
			if($formaction=='forget'){ header('Location: '.$url_recuperarpass."?error=2"); }
			else{ header('Location: '.$url_login."?error=2"); }
		}
    }
	else{ 
		if($formaction=='forget'){ header('Location: '.$url_recuperarpass."?error=1"); }
		if($formaction=='login'){ header('Location: '.$url_login."?error=1"); }
		else { logout($url_login, $Quick_function); }
	}
	
	
	
	function login($url_login, $url_sistema, $Quick_function){
		$tiempo=0;
		$codigo=$Quick_function->codigo();
		
		$sql="SELECT * FROM ".TABLA_ADMINISTRADORES." WHERE usuario= :usuario  and estado='1'";
		$usuario=$Quick_function->SQLDatos_CA($sql, array(':usuario'=>$_POST['username']));
		$usuario = $usuario->fetch();
		
		if($usuario['usuario']!=''){
			$Quick_function->SQLDatos_CA("DELETE FROM ".TABLA_USUARIOS_CONECTADOS." WHERE usuario=:usuario", array(':usuario'=>$_POST['username']));
		
			$contrasenabd= $usuario['contrasena'];
			$contrasena= md5($usuario['codigo'].$_POST['password'].LLAVE);
			$contrasena= hash('ripemd160',$contrasena);
			
			if($contrasenabd==$contrasena){
				$Path=$Quick_function->TraerParametro('login_path');
				if(isset($_POST['remember'])){ $abierto=1; $tiempo=60*60*24*365; } else { $abierto=0; $tiempo=60*$Quick_function->TraerParametro('login_time'); }
				
				$ip= $Quick_function->get_ip_address();
				$sql="INSERT INTO ".TABLA_USUARIOS_CONECTADOS." (usuario, hora_acceso, ip, abierto, codigo) values (:usuario, now(), :ip, :abierto, :codigo)";
				$prueba=$Quick_function->SQLDatos_CA($sql, array(':usuario'=>$_POST['username'], ':ip'=>$ip, ':abierto'=>$abierto, ':codigo'=>$codigo));
				$_SESSION['usuario']=$_POST['username'];  setcookie("usuario",$_POST['username'],time()+$tiempo, $Path);
				
				$_SESSION['codigo']=$codigo; setcookie("codigo",$codigo,time()+$tiempo, $Path);
				
				header('Location: '.$url_sistema);
			}
			else{ header('Location: '.$url_login."?error=5"); }
			
		}
		else{
			header('Location: '.$url_login."?error=4");
		}
	}
	
	function register($url_login, $url_register, $url_sistema, $Quick_function){
		
		if(isset($_POST['correo'])){ $correo=filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL); }
		if(isset($_POST['usuario'])){ $usuario=filter_var($_POST['usuario'], FILTER_SANITIZE_STRING); $usuario=addslashes($usuario); }
		
		$sql="SELECT * FROM ".TABLA_ADMINISTRADORES." WHERE usuario= :usuario OR correo=:correo";
		$usuario=$Quick_function->SQLDatos_CA($sql, array(':usuario'=>$usuario, ':correo'=>$correo));
		$usuario = $usuario->fetch();
		
		$registrar=1;
		if($usuario['usuario']!=''){ $registrar=0; }
		else{
			$usuario=$Quick_function->SQLDatos_CA("SELECT * FROM ".TABLA_CREADORES." WHERE usuario=:usuario OR correo=:correo", array(':usuario'=>$usuario, ':correo'=>$correo));
			$usuario = $usuario->fetch();
			
			if($usuario['usuario']!=''){ $registrar=0; }
			else{
				$registrar=1;
			}
			
		}
		
		if($registrar==0){
			header('Location: '.$url_register."?error=1");
		}
		else{
			$codigo=$Quick_function->codigo();
			$id=$Quick_function->Topnumber('id', TABLA_CREADORES)+1;
			$usuario=''; $correo=''; $nombre=''; $apellido=''; $perfil=''; $paypal=''; $canalYT='';
			
			if(isset($_POST['correo'])){ $correo=filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL); }
			if(isset($_POST['usuario'])){ $usuario=filter_var($_POST['usuario'], FILTER_SANITIZE_STRING); $usuario=addslashes($usuario); }
			if(isset($_POST['paypal'])){ $paypal=filter_var($_POST['paypal'], FILTER_SANITIZE_EMAIL); }
			if(isset($_POST['nombre'])){ $nombre=filter_var($_POST['nombre'], FILTER_SANITIZE_STRING); $nombre=addslashes($nombre); }
			if(isset($_POST['apellido'])){ $apellido=filter_var($_POST['apellido'], FILTER_SANITIZE_STRING); $apellido=addslashes($apellido); }
			if(isset($_POST['perfil'])){ $perfil=filter_var($_POST['perfil'], FILTER_SANITIZE_STRING); $perfil=addslashes($perfil); }
			if(isset($_POST['contrasena'])){ $contrasena=filter_var($_POST['contrasena'], FILTER_SANITIZE_STRING); $contrasena=addslashes($contrasena); }
			$administrador=0;
			
			$contrasenabd= md5($codigo.$contrasena.LLAVE);
			$contrasenabd= hash('ripemd160',$contrasenabd);
			
			$img='';
			
			$sql="INSERT INTO ".TABLA_CREADORES." (id, perfil, usuario, correo, nombre, apellido, contrasena, codigo, fecha_creacion, img, administrador, correoPaypal, rol, estado) values (:id, :perfil, :usuario, :correo, :nombre, :apellido, :contrasena, :codigo, now(), :img, :administrador, :correoPaypal, '3', '1')";
			$prueba=$Quick_function->SQLDatos_CA($sql, array(':id'=>$id, ':perfil'=>$perfil, ':usuario'=>$usuario, ':correo'=>$correo, ':nombre'=>$nombre, ':apellido'=>$apellido, ':contrasena'=>$contrasenabd, ':codigo'=>$codigo, ':img'=>$img, ':administrador'=>$administrador, ':correoPaypal'=>$paypal));/*  */
			
			{
				mkdir("../../biblioteca-medios/".$id, 0755);
				mkdir("../../biblioteca-medios/".$id."/cms", 0755);
				mkdir("../../biblioteca-medios/".$id."/thumbs", 0755);
			}
			
			{ /* Envia el correo */
				
				$NombreSistema = $Quick_function->TraerParametro('Nombre_sistema');
			
				$htmlcustom="
					Buenas ".$nombre." ".$apellido." (".$perfil.") te has registrado correctamente, gracias por decidir formar parte de los creadores de ".$Quick_function->TraerParametro('Nombre_sistema').".<br/>
					<b>Correo</b>: ".$correo."<br/>
					<b>Nombre</b>: ".$nombre." ".$apellido."<br/><br/>
					<b>Usuario</b>: ".$usuario."<br/><br/>
					<b>Contraseña</b>: ".$contrasena."<br/><br/>
					<b>Esperamos que tu instacia con nosotros sea agradable</b>
				";
				$html = $Quick_function->emailformat($htmlcustom);
				
				
				/* listado de correos */
					$listaCorreos[]=$correo;
				/* listado de correos */
				
				$Subject = "Bienvenido a ".$NombreSistema." ";
			$enviado=enviarMail(HOST, EMAILUSERNAME, EMAILPASSWORD, $NombreSistema, $listaCorreos, $Subject, $html);
				
			/* Envia el correo */ }
			header('Location: '.$url_login."?error=6");
			
		}
		
	}
	
	function forget($url_recuperarpass, $url_sistema, $Quick_function){
		$codigo=$Quick_function->codigo();
		$sql="SELECT * FROM ".TABLA_ADMINISTRADORES." WHERE correo= :correo ";
		$usuario=$Quick_function->SQLDatos_CA($sql, array(':correo'=>$_POST['email']));
		$usuario = $usuario->fetch();
		
		if($usuario['usuario']!=''){
			$contrasena=$Quick_function->generarStringRandom($Quick_function->TraerParametro('password_length'));
			$contrasenabd= md5($codigo.$contrasena.LLAVE);
			$contrasenabd= hash('ripemd160',$contrasenabd);
    		{ /* Envia el correo */
    			
    			$NombreSistema = $Quick_function->TraerParametro('Nombre_sistema');
    		
    		    $htmlcustom="
    				Buenas ".$usuario['usuario']." tu contraseña ha sido regenerada.<br/>
    				Tu contraseña nueva es: $contrasena
    				";
    			$html = $Quick_function->emailformat($htmlcustom);
    			
    			
    			/* listado de correos */
    				$listaCorreos[]=$usuario['correo'];
    			/* listado de correos */
    			
    			$Subject = 'Recuperacion de contraseña';
    		$enviado=enviarMail(HOST, EMAILUSERNAME, EMAILPASSWORD, $NombreSistema, $listaCorreos, $Subject, $html);
    			
    		/* Envia el correo */ }
			
			
			$sql="UPDATE ".TABLA_ADMINISTRADORES." SET contrasena=:contrasena, codigo=:codigo WHERE correo= :correo";
			$prueba=$Quick_function->SQLDatos_CA($sql, array(':correo'=>$_POST['email'], ':contrasena'=>$contrasenabd, ':codigo'=>$codigo));
			
			header('Location: '.$url_recuperarpass."?error=1");
			
		}
		else{
			$usuario=$Quick_function->SQLDatos_CA("SELECT * FROM ".TABLA_CREADORES." WHERE correo=:correo", array(':correo'=>$_POST['email']));
			$usuario = $usuario->fetch();
			if($usuario['usuario']!=''){
				$contrasena=$Quick_function->generarStringRandom($Quick_function->TraerParametro('password_length'));
				$contrasenabd= md5($codigo.$contrasena.LLAVE);
		    	$contrasenabd= hash('ripemd160',$contrasenabd);
				
        		{ /* Envia el correo */
        			
        			$NombreSistema = $Quick_function->TraerParametro('Nombre_sistema');
        		
        		    $htmlcustom="
        				Buenas ".$usuario['usuario']." tu contraseña ha sido regenerada.<br/>
        				Tu contraseña nueva es: $contrasena
        				";
        			$html = $Quick_function->emailformat($htmlcustom);
        			
        			
        			/* listado de correos */
        				$listaCorreos[]=$usuario['correo'];
        			/* listado de correos */
        			
        			$Subject = 'Recuperacion de contraseña';
        		$enviado=enviarMail(HOST, EMAILUSERNAME, EMAILPASSWORD, $NombreSistema, $listaCorreos, $Subject, $html);
        			
        		/* Envia el correo */ }
			
			
			
			
				$sql="UPDATE ".TABLA_CREADORES." SET contrasena=:contrasena, codigo=:codigo WHERE correo= :correo";
				$prueba=$Quick_function->SQLDatos_CA($sql, array(':correo'=>$_POST['email'], ':contrasena'=>$contrasenabd, ':codigo'=>$codigo));
				
				header('Location: '.$url_recuperarpass."?error=2");
				
			}
			else{
				header('Location: '.$url_recuperarpass."?error=3");
			}
		}
	}
	
	function logout($url_login, $Quick_function){
		
		if(isset($_COOKIE['usuario'])){
			$Quick_function->SQLDatos_CA("DELETE FROM ".TABLA_USUARIOS_CONECTADOS." WHERE usuario=:usuario", array(':usuario'=>$_COOKIE['usuario']));
			unset($_SESSION['usuario']);	unset($_COOKIE['usuario']);
			unset($_SESSION['codigo']);		unset($_COOKIE['codigo']);
		}
		header('Location: '.$url_login);
	}
	
?>