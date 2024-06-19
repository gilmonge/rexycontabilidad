<?php
	/**************************************************
		Sistema de contabilidad
		Desarrollador: Rexy Studios
		Año de creación: 2020
		Última modificación del archivo: 21-04-2020
	**************************************************/
	include_once("database.php");
	class Quick_function{

		private $Mes_ingles  = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
    	private $Mes_espanol = array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
    
		private $Dias_ingles  = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
		private $Dias_espanol = array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
		
		public function SQLDatos_SA($sql){ /* SQL datos sin argumentos */
			$dbc = new ConexionDB();
			$dbo=$dbc->conexion();
            $stmt = $dbo->prepare($sql);
            $stmt->execute();
            return $stmt;
        }
		
		public function SQLDatos_CA($sql, $argumentos){ /* SQL datos con argumentos */
			$dbc = new ConexionDB();
			$dbo=$dbc->conexion();
            $stmt = $dbo->prepare($sql);
            $stmt->execute($argumentos);
            return $stmt;
        }
		
		public function TraerParametro($parametro){ /* Trae parametro */
			$dbc = new ConexionDB();
			$dbo=$dbc->conexion();
            $stmt = $dbo->prepare("SELECT valor FROM ".TABLA_PARAMETROS." WHERE identificador=:parametro");
            $stmt->execute(array(':parametro'=>$parametro));
			$par = $stmt->fetch();
            return $par['valor'];
        }
		
		public function Topnumber($campo, $tabla){ /* trae numero top */
			$dbc = new ConexionDB();
			$dbo=$dbc->conexion();
            $stmt = $dbo->prepare("SELECT max(".$campo.") AS maximo FROM ".$tabla.";");
            $stmt->execute();
			$max = $stmt->fetch();
            return $max['maximo'];
        }
		
		public function codigo(){ /* Genera numero aleatorio para la contraseña */
			return rand((int) 1000000000000, (int) 9999999999999);
        }
		
		public function generarStringRandom($length) { /* Genera string aleatorio */
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
		}
		
		public function es_logueado(){ /* analiza si esta logueado */
			if(isset($_COOKIE['usuario'])){ $usuario=$_COOKIE['usuario']; }
			else{ $usuario=''; }
			
			if(isset($_COOKIE['codigo'])){ $codigo=$_COOKIE['codigo']; } 
			/* else if(isset($_SESSION['codigo'])){ $codigo=$_SESSION['codigo']; }  */
			else{ $codigo=''; }
			$ip= $this->get_ip_address();
		
			$par= $this->SQLDatos_CA("SELECT * FROM ".TABLA_USUARIOS_CONECTADOS." WHERE usuario=:usuario", array(':usuario'=>$usuario));
			$par = $par->fetch();
			
			
			if($par!=''){
				$hora_acceso = date($par['hora_acceso']);
				$ipBD = $par['ip'];
				$codigoBD = $par['codigo'];
				
				if($codigoBD==$codigo){ /*($ipBD==$ip) && */
					
					if($par['abierto']==1){ return true; }
					else{
						$tiempo_permitido=$this->TraerParametro('login_time');
						
						$hora_actual = date('Y-m-d h:i:s');
						$minutos = ceil((strtotime($hora_actual) - strtotime($hora_acceso)) / 60);
						if ($tiempo_permitido>$minutos) { return true; }
						else{ 
							$this->SQLDatos_CA("DELETE FROM ".TABLA_USUARIOS_CONECTADOS." WHERE usuario=:usuario", array(':usuario'=>$usuario));
							unset($_SESSION['usuario']);	unset($_COOKIE['usuario']);
							unset($_SESSION['codigo']);		unset($_COOKIE['codigo']);
						return false; }
					}
					
				}
				else {
					unset($_SESSION['usuario']);	unset($_COOKIE['usuario']);
					unset($_SESSION['codigo']);		unset($_COOKIE['codigo']);
					return false; 
				}
			}
			else { $_SESSION['usuario']=''; $_SESSION['codigo']=''; return false; }
        }
		
		public function tiene_permiso(){ /* analiza si tiene acceso al archivo */
			$archivo=$_SERVER['PHP_SELF'];
			$archivo=explode('/', $archivo);
			$archivo=array_pop($archivo);
			
			if(isset($_COOKIE['usuario'])){ $usuario=$_COOKIE['usuario']; }
			else{ $usuario=''; }
			
			$par= $this->SQLDatos_CA("SELECT * FROM ".TABLA_ADMINISTRADORES." WHERE usuario=:usuario", array(':usuario'=>$usuario));
            $par = $par->fetch();
			$_SESSION['idrol']=$par['rol'];
			$novalida=0;
			if($archivo=='index.php'){ $novalida=1; }
			else if($archivo=='documentos-info.php'){ $novalida=1; }
			
			if($novalida==0){
				
				$per= $this->SQLDatos_CA("
					SELECT ".TABLA_MENU_PERMISOS.".visualizar, ".TABLA_MENU_PERMISOS.".agregar, ".TABLA_MENU_PERMISOS.".editar, ".TABLA_MENU_PERMISOS.".borrar
					FROM ".TABLA_MENU_PERMISOS." 
					INNER JOIN ".TABLA_MENU_ADMIN." on ".TABLA_MENU_ADMIN.".id=".TABLA_MENU_PERMISOS.".id_menu_admin
					where (".TABLA_MENU_ADMIN.".script=:script OR ".TABLA_MENU_ADMIN.".subscripts like '%$archivo%') and  ".TABLA_MENU_PERMISOS.".id_rol=:id_rol", array(':script'=>$archivo, ':id_rol'=>$par['rol']));
				$per = $per->fetch();
					
					$_SESSION['visualizar']=$per['visualizar'];
					$_SESSION['agregar']=$per['agregar'];
					$_SESSION['editar']=$per['editar'];
					$_SESSION['borrar']=$per['borrar'];
			}
			else{ $_SESSION['visualizar']=1; $_SESSION['agregar']=1; $_SESSION['editar']=1; $_SESSION['borrar']=1; }
        }
		
        public function datos_administrador(){ /* analiza si esta logueado */
            if(isset($_COOKIE['usuario'])){
                $par= $this->SQLDatos_SA("SELECT * FROM ".TABLA_ADMINISTRADORES." WHERE usuario='".$_COOKIE['usuario']."' ");
                $admin=$par->fetch();
                return $admin;
            }
            else{
                $admin = [];
                return $admin;
            }
        }
		
		public function get_ip_address() { /* obtiene la ip del cliente */
			if (!empty($_SERVER['HTTP_CLIENT_IP'])) { $ip = $_SERVER['HTTP_CLIENT_IP']; }
			else {
				if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; }
				else { $ip = $_SERVER['REMOTE_ADDR']; }
			}
			return $ip;
		}
		
		public function encryptlabel($string) { /* genera encriptado */
			$key=LLAVELBL;
			$result = '';
			for($i=0; $i<strlen($string); $i++) {
				$char = substr($string, $i, 1);
				$keychar = substr($key, ($i % strlen($key))-1, 1);
				$char = chr(ord($char)+ord($keychar));
				$result.=$char;
			}
			return base64_encode($result);
		}

		public function decryptlabel($string) { /* genera desencriptado */
			$key=LLAVELBL;
			$result = '';
			$string = base64_decode($string);
			for($i=0; $i<strlen($string); $i++) {
				$char = substr($string, $i, 1);
				$keychar = substr($key, ($i % strlen($key))-1, 1);
				$char = chr(ord($char)-ord($keychar));
				$result.=$char;
			}
			return $result;
		}
		
		public function emailformat($htmlcustom) { /* regresa la primera parte del correo */
			$html = '
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					<meta name="viewport" content="width=device-width, initial-scale=1.0">
					<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
					<meta name="format-detection" content="telephone=no" />
					<title>Respmail is a response HTML email designed to work on all major email platforms and smartphones</title>
					<style type="text/css">
						html { background-color:#E1E1E1; margin:0; padding:0; }
						body, #bodyTable, #bodyCell, #bodyCell{height:100% !important; margin:0; padding:0; width:100% !important;font-family:Helvetica, Arial, "Lucida Grande", sans-serif;}
						table{border-collapse:collapse;}
						table[id=bodyTable] {width:100%!important;margin:auto;max-width:500px!important;color:#7A7A7A;font-weight:normal;}
						img, a img{border:0; outline:none; text-decoration:none;height:auto; line-height:100%;}
						a {text-decoration:none !important;border-bottom: 1px solid;}
						h1, h2, h3, h4, h5, h6{color:#5F5F5F; font-weight:normal; font-family:Helvetica; font-size:20px; line-height:125%; text-align:Left; letter-spacing:normal;margin-top:0;margin-right:0;margin-bottom:10px;margin-left:0;padding-top:0;padding-bottom:0;padding-left:0;padding-right:0;}
						
						.ReadMsgBody{width:100%;} .ExternalClass{width:100%;}
						.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{line-height:100%;}
						table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;}
						#outlook a{padding:0;}
						img{-ms-interpolation-mode: bicubic;display:block;outline:none; text-decoration:none;}
						body, table, td, p, a, li, blockquote{-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; font-weight:normal!important;}
						.ExternalClass td[class="ecxflexibleContainerBox"] h3 {padding-top: 10px !important;}
						
						h1{display:block;font-size:26px;font-style:normal;font-weight:normal;line-height:100%;}
						h2{display:block;font-size:20px;font-style:normal;font-weight:normal;line-height:120%;}
						h3{display:block;font-size:17px;font-style:normal;font-weight:normal;line-height:110%;}
						h4{display:block;font-size:18px;font-style:italic;font-weight:normal;line-height:100%;}
						.flexibleImage{height:auto;}
						.linkRemoveBorder{border-bottom:0 !important;}
						table[class=flexibleContainerCellDivider] {padding-bottom:0 !important;padding-top:0 !important;}

						body, #bodyTable{background-color:#E1E1E1;}
						#emailHeader{background-color:#E1E1E1;}
						#emailBody{background-color:#FFFFFF;}
						#emailFooter{background-color:#E1E1E1;}
						.nestedContainer{background-color:#F8F8F8; border:1px solid #CCCCCC;}
						.emailButton{background-color:#205478; border-collapse:separate;}
						.buttonContent{color:#FFFFFF; font-family:Helvetica; font-size:18px; font-weight:bold; line-height:100%; padding:15px; text-align:center;}
						.buttonContent a{color:#FFFFFF; display:block; text-decoration:none!important; border:0!important;}
						.emailCalendar{background-color:#FFFFFF; border:1px solid #CCCCCC;}
						.emailCalendarMonth{background-color:#205478; color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-size:16px; font-weight:bold; padding-top:10px; padding-bottom:10px; text-align:center;}
						.emailCalendarDay{color:#205478; font-family:Helvetica, Arial, sans-serif; font-size:60px; font-weight:bold; line-height:100%; padding-top:20px; padding-bottom:20px; text-align:center;}
						.imageContentText {margin-top: 10px;line-height:0;}
						.imageContentText a {line-height:0;}
						#invisibleIntroduction {display:none !important;}
						
						span[class=ios-color-hack] a {color:#275100!important;text-decoration:none!important;}
						span[class=ios-color-hack2] a {color:#205478!important;text-decoration:none!important;}
						span[class=ios-color-hack3] a {color:#8B8B8B!important;text-decoration:none!important;}
						.a[href^="tel"], a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:none!important;cursor:default!important;}
						.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:auto!important;cursor:default!important;}


						@media only screen and (max-width: 480px){
							body{width:100% !important; min-width:100% !important;}

							table[id="emailHeader"],
							table[id="emailBody"],
							table[id="emailFooter"],
							table[class="flexibleContainer"],
							td[class="flexibleContainerCell"] {width:100% !important;}
							td[class="flexibleContainerBox"], td[class="flexibleContainerBox"] table {display: block;width: 100%;text-align: left;}
							
							td[class="imageContent"] img {height:auto !important; width:100% !important; max-width:100% !important; }
							img[class="flexibleImage"]{height:auto !important; width:100% !important;max-width:100% !important;}
							img[class="flexibleImageSmall"]{height:auto !important; width:auto !important;}

							table[class="flexibleContainerBoxNext"]{padding-top: 10px !important;}

							table[class="emailButton"]{width:100% !important;}
							td[class="buttonContent"]{padding:0 !important;}
							td[class="buttonContent"] a{padding:15px !important;}

						}
						@media only screen and (-webkit-device-pixel-ratio:.75){}
						@media only screen and (-webkit-device-pixel-ratio:1){}
						@media only screen and (-webkit-device-pixel-ratio:1.5){}
						@media only screen and (min-device-width : 320px) and (max-device-width:568px) {}
					</style>
				</head>
				<body bgcolor="#E1E1E1" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
					<center style="background-color:#E1E1E1;">
						<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="table-layout: fixed;max-width:100% !important;width: 100% !important;min-width: 100% !important;">
							<tr>
								<td align="center" valign="top" id="bodyCell">

									<table bgcolor="#FFFFFF"  border="0" cellpadding="0" cellspacing="0" width="500" id="emailBody">
										<tr>
											<td align="center" valign="top">
												<table border="0" cellpadding="0" cellspacing="0" width="100%" style="color:#FFFFFF;" bgcolor="#0762B4">
													<tr>
														<td align="center" valign="top">
															<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
																<tr>
																	<td align="center" valign="top" width="500" class="flexibleContainerCell">
																		<table border="0" cellpadding="30" cellspacing="0" width="100%">
																			<tr>
																				<td align="center" valign="top" class="textContent">
																					<h1 style="color:#FFFFFF;line-height:100%;font-family:Helvetica,Arial,sans-serif;font-size:35px;font-weight:normal;margin-bottom:5px;text-align:center;">CienciPost</h1>
																					<div style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#FFFFFF;line-height:135%;">'.$this->TraerParametro("email_slogan").'</div>
																				</td>
																			</tr>
																		</table>

																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
										
										<tr>
											<td align="center" valign="top">
												<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff">
													<tr>
														<td align="center" valign="top">
															<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
																<tr>
																	<td align="center" valign="top" width="500" class="flexibleContainerCell">
																		<table border="0" cellpadding="30" cellspacing="0" width="100%">
																			<tr>
																				<td align="center" valign="top">
																					<table border="0" cellpadding="0" cellspacing="0" width="100%">
																						<tr>
																							<td valign="top" class="textContent">
																								';
																								$html.=$htmlcustom;
																								$html.='
																							</td>
																						</tr>
																					</table>

																				</td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>

										<tr>
											<td align="center" valign="top">
												<table border="0" cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td align="center" valign="top">
															<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
																<tr>
																	<td align="center" valign="top" width="500" class="flexibleContainerCell">
																		<table border="0" cellpadding="0" cellspacing="0" width="100%">
																			<tr>
																				<td valign="top" class="imageContent">
																					<img src="'.$this->TraerParametro("email_imagen").'" width="500" class="flexibleImage" style="max-width:500px;width:100%;display:block;" alt="Text" title="Text" />
																				</td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
										
										<tr>
											<td align="center" valign="top">
												<table border="0" cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td align="center" valign="top">
															<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
																<tr>
																	<td valign="top" width="500" class="flexibleContainerCell">
																		<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
																			<tr>
																				<td align="left" valign="top" class="flexibleContainerBox" style="background-color:#3E4551;">
																					<table border="0" cellpadding="30" cellspacing="0" width="100%" style="max-width:100%;">
																						<tr>
																							<td align="left" class="textContent">
																								<div style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#FFFFFF;line-height:135%;">'.$this->TraerParametro("email_footer_I").'</div>
																							</td>
																						</tr>
																					</table>
																				</td>
																				<td align="right" valign="top" class="flexibleContainerBox" style="background-color:#180D82;">
																					<table class="flexibleContainerBoxNext" border="0" cellpadding="30" cellspacing="0" width="100%" style="max-width:100%;">
																						<tr>
																							<td align="left" class="textContent">
																								<div style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#FFFFFF;line-height:135%;">'.$this->TraerParametro("email_footer_D").'</div>
																							</td>
																						</tr>
																					</table>
																				</td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
										
									</table>
									
									<table bgcolor="#E1E1E1" border="0" cellpadding="0" cellspacing="0" width="500" id="emailFooter">
										<tr>
											<td align="center" valign="top">
												<table border="0" cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td align="center" valign="top">
															<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
																<tr>
																	<td align="center" valign="top" width="500" class="flexibleContainerCell">
																		<table border="0" cellpadding="30" cellspacing="0" width="100%">
																			<tr>
																				<td valign="top" bgcolor="#E1E1E1">

																					<div style="font-family:Helvetica,Arial,sans-serif;font-size:13px;color:#828282;text-align:center;line-height:120%;">
																						<div>Copyright &#169; 2018 <a href="http://ciencipost.com/creator/" target="_blank" style="text-decoration:none;color:#828282;"><span style="color:#828282;">CienciPost</span></a>. All&nbsp;rights&nbsp;reserved.</div>
																					</div>

																				</td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</center>
				</body>
			</html>
				';
			return $html;
		}

		public function Money__Format($monto, $codificacion = "es-CR"){
			$a = new \NumberFormatter($codificacion, \NumberFormatter::CURRENCY);
			return $a->format($monto); /* Ejemplo ₡568,49 */
		}
	
		public function buscar_cta_contable($id_tipo_cuenta, $listado_cta_contables = [], $codigo ='', $id_padre = 0){
			$listado_cuentas_encontradas = [];
			foreach ($listado_cta_contables as $cta_cont_key => $cta_cont_value) {
				if(
					$cta_cont_value["id_tipo_cuenta"] == $id_tipo_cuenta &&
					$cta_cont_value["id_padre"] == $id_padre
				){
					$sublistado = $this->buscar_cta_contable($cta_cont_value["id_tipo_cuenta"], $listado_cta_contables, $codigo.$cta_cont_value["codigo_cuenta"].'-', $cta_cont_value["id"]);
					$cta_cont_value['disabled'] = (count($sublistado)>0)? 1 : 0 ;
					$listado_cuentas_encontradas[] = array(
						"id"                => $cta_cont_value["id"],
						"id_tipo_cuenta"	=> $cta_cont_value["id_tipo_cuenta"],
						"id_padre"          => $cta_cont_value["id_padre"],
						"codigo_cuenta"     => $cta_cont_value["codigo_cuenta"],
						"nombre"            => $cta_cont_value["nombre"],
						"saldoinicial"      => $cta_cont_value["saldoinicial"],
						"saldoactual"       => $cta_cont_value["saldoactual"],
						"naturaleza"        => $cta_cont_value["naturaleza"],
						"id_moneda"         => $cta_cont_value["id_moneda"],
						"comentario"        => $cta_cont_value["comentario"],
						"activo"            => $cta_cont_value["activo"],
						"borrado"  			=> $cta_cont_value["borrado"],
						"usuario"           => $cta_cont_value["usuario"],
						"fecha_creacion"    => $cta_cont_value["fecha_creacion"],
						"codigo"        	=> $codigo.$cta_cont_value["codigo_cuenta"],
						"posee_hijo"        => (count($sublistado)>0)? 1 : 0 ,
					);
					if(count($sublistado)>0){ 
						foreach ($sublistado as $value) {
							$listado_cuentas_encontradas[] = $value; 
						}
					}
				}
			}
			return $listado_cuentas_encontradas;
		}

		public function traer_ctas_contables($padre = 0, $codigo = "", $subcuentas = true, $activas = false){
			/* Trae las cuentas mayores */
				$CTA_MAYORES = json_decode($this->TraerParametro('CTA_MAYORES'), TRUE);
			/* Trae las cuentas mayores */
			
			/* Trae el listado de las cuentas contables */
				$TABLA_CUENTAS_CONTABLES = TABLA_CUENTAS_CONTABLES;
				$solo_activas = ($activas === true)? ' WHERE TBL_CC.activo = 1 AND TBL_CC.borrado = 0 ' : '' ;
				$select = " SELECT * FROM $TABLA_CUENTAS_CONTABLES AS TBL_CC  $solo_activas ORDER BY TBL_CC.id_tipo_cuenta, id_padre";
				$par= $this->SQLDatos_SA($select);
	
				$listado_cta_contables=[];
				while ($row = $par->fetch()) {
					$listado_cta_contables[] = $row;
				}
			/* Trae el listado de las cuentas contables */
	
			$listado_cuentas_encontradas = [];
			
			$nivel_contable = 1;
			foreach ($CTA_MAYORES as $principal) { /* Trae seccion principal */
				if($principal["padre"] == $padre){
					$sublistado_cta_contables = [];
					$posee_hijo = 0;
	
					$sublistado = $this->traer_ctas_contables($principal["id"], $principal["codigo"].'-', $subcuentas, $activas);
					$posee_hijo = (count($sublistado)>0)? 1 : 0;
	
					if(count($sublistado) == 0 && $subcuentas){
						$sublistado_cta_contables = $this->buscar_cta_contable($principal["id"], $listado_cta_contables, $codigo.$principal["codigo"].'-', 0);
						$posee_hijo = (count($sublistado_cta_contables)>0)? 1 : 0;
					}
					
					$listado_cuentas_encontradas[] = array(
						"id"                    => $principal["id"],
						"id_tipo_cuenta"        => $principal["id"],
						"id_padre"              => $principal["padre"],
						"codigo_cuenta"         => $principal["codigo"],
						"nombre"                => $principal["nombre"],
						"codigo"                => $codigo.$principal["codigo"],
						"posee_hijo"            => $posee_hijo,
						"mayor"            		=> 1,
					);
					
					if(count($sublistado)>0){ 
						foreach ($sublistado as $value) {
							$listado_cuentas_encontradas[] = $value; 
						}
					}
					
					if(count($sublistado_cta_contables)>0){ 
						foreach ($sublistado_cta_contables as $value_cta) {
							$listado_cuentas_encontradas[] = $value_cta; 
						}
					}
				}
				$nivel_contable = 1;
			}
			return $listado_cuentas_encontradas;
		}

		public function traer_ctas_contables_activas(){
			return $this->traer_ctas_contables( 0, "", true, true);
		}

		public function fecha($fecha){
			$fecha = date('d-m-Y',strtotime($fecha));
																		
			$fecha = strftime("%d %b del %Y", strtotime(date('m/d/Y', strtotime($fecha))));
			$fecha = str_replace($this->Mes_ingles, $this->Mes_espanol, $fecha);
			$fecha = str_replace($this->Dias_ingles, $this->Dias_espanol, $fecha);
			return $fecha;
		}

		public function fecha_hora($fecha){
			$fecha = date('d-m-Y H:i:s',strtotime($fecha));
			
			$fecha = strftime("%b/%d/%y, %l:%M %p", strtotime(date('m/d/Y g:i:s a', strtotime($fecha))));
			$fecha = str_replace($this->Mes_ingles, $this->Mes_espanol, $fecha);
			$fecha = str_replace($this->Dias_ingles, $this->Dias_espanol, $fecha);
			return $fecha;
		}
	}
?>