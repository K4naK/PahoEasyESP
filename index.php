<!doctype html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="initial-scale=1, maximum-scale=1">
        <meta name="theme-color" content="#000000">
		<title>Panel MQTT</title>
		<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
		<link rel="stylesheet" href="css/bootstrap-toggle.min.css" type="text/css">
		<link rel="stylesheet" href="css/core.css" type="text/css">
		<link rel="stylesheet" href="css/jquery.minicolors.css">
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/bootstrap-toggle.min.js"></script>
		<script src="js/mqttws31.js"></script>
		<script src="js/core.js"></script>
		<script src="js/screenfull.js"></script>
		<script src="js/minicolor.js"></script>
	</head>
	<body>
		<div class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<a class="navbar-brand" href="#IndexConexiones" data-toggle="modal" data-target="#IndexConexiones" aria-expanded="false" id="nombrePanel">#</a>
					<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div class="navbar-collapse collapse" id="navbar-main">
					<ul class="nav navbar-nav">
						<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">Habitaciones <span class="caret"></span></a>
							<ul class="dropdown-menu" aria-labelledby="Habitaciones" id="dropHabitacion">
							</ul>
						</li>
						<li class="active">
							<a href="#Todo" data-toggle="tab" class="menu">Todo</a>
						</li>
						<li>
							<a href="#Switch" data-toggle="tab" class="menu">Switch</a>
						</li>
						<li>
							<a href="#Sensor" data-toggle="tab" class="menu">Sensor</a>
						</li>
					</ul>

					
					<ul class="nav navbar-nav navbar-right">
						<li>
							<a href="#" id="recargarDatos"><span class="glyphicon glyphicon-refresh"></span></a>
						</li>
						<li>
							<a href="#" id="editarDispositivos"><span class="glyphicon glyphicon-edit"></span></a>
						</li>
						<li>
							<a href="#" id="screenSaver"><span class="glyphicon glyphicon-lamp"></span></a>
						</li>
						<li>
							<a href="#" id="fullScreen"><span class="glyphicon glyphicon-fullscreen"></span></a>
						</li>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">Administrar <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li class=""><a href="#Sistemas" data-toggle="modal" data-target="#Sistemas" aria-expanded="false">Sistemas</a></li>
								<li class="divider"></li>
								<li class=""><a href="#Habitaciones" data-toggle="modal" data-target="#Habitaciones" aria-expanded="false">Habitaciones</a></li>
								<li class="divider"></li>
								<li class=""><a href="#Dispositivos" data-toggle="modal" data-target="#Dispositivos" aria-expanded="false">Dispositivo</a></li>
								<li class="divider"></li>
								<li class=""><a href="#Monitor" data-toggle="modal" data-target="#Monitor" aria-expanded="false">Monitor</a></li>
								<li class="divider"></li>
								<li class=""><a href="#Log" data-toggle="modal" data-target="#Log" aria-expanded="false">Log MQTT</a></li>
							</ul>
						</li>
					</ul>
				</div>
		  </div>
    	</div>
		<div class="container" id="mainContainer">
			<div class="row" style="padding: 10px" id="mainRow">
				<div class="progress" style="width: 100%; position: fixed; top: 35px; left: 0px">
					<div class="progress-bar" id="IndexDivProgreso"></div>
				</div>
				<div class="alert alert-info" id="alerta">
					<p id="estado">Conectando con Servidor</p>
				</div>
				<div class="modal fade" id="IndexConexiones" tabindex="-1" role="dialog" aria-labelledby="Sistemas">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">Sistemas</h4>
							</div>
							<div class="modal-body" id="IndexListaConexiones">

							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="Sistemas" tabindex="-1" role="dialog" aria-labelledby="Sistemas">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title text-center">Administrador de Sistemas</h4>
							</div>
							<div class="modal-body">
								<ul class="nav nav-tabs" id="modalSistemas">
								</ul>
								<div class="tab-content">
									<div class="tab-pane fade active in" id="home">
										<div class="tooltip fade left" role="tooltip" id="tooltip36493" style="top: 7.5px; left: -98px; display: block;">
											<div class="tooltip-arrow" style="top: 50%;"></div>
											<div class="tooltip-inner"></div>
										</div>
										<form id="IndexFormSistema" method="post">
											<input type="number" name="id" value="0" id="IndexSistemaId" class="oculto">
											<br>
											<fieldset>
												<legend>Conexión</legend>
												<div class="row">
													<div class="col-md-3">
														<div class="form-group">
															<label for="IndexSistemaNombre">Nombre</label>
															<input type="text" name="nombre" class="form-control" placeholder="Identificador" id="IndexSistemaNombre" required>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="IndexSistemaUrl">Servidor <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ingrese IP o URL del Broker con soporte de WebSockets"></span></label>
															<input type="text" name="ip" class="form-control" placeholder="Broker" id="IndexSistemaUrl" required>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="IndexSistemaPuerto">Puerto</label>
															<input type="text" name="puerto" class="form-control" placeholder="Port" id="IndexSistemaPuerto" required>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="IndexSistemaRuta">Ruta</label>
															<input type="text" name="ruta" class="form-control" placeholder="Path" id="IndexSistemaRuta">
														</div>
													</div>
												</div>
											</fieldset>
											<fieldset>
												<legend>Autentificación</legend>
												<div class="row">
													<div class="col-md-4">
														<div class="form-group">
															<label for="IndexSistemaUser">Usuario</label>
															<input type="text" name="usuario" class="form-control" placeholder="Username" id="IndexSistemaUser">
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label for="IndexSistemaPass">Contraseña</label>
															<input type="password" name="clave" class="form-control" placeholder="Password" id="IndexSistemaPass">

														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label for="IndexSistemaSSL">Certificado SSL/TLS <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="top" title="" data-original-title="Requiere certificado de seguridad instalado"></span></label>
															<input type="checkbox" name="ssl" class="form-control" id="IndexSistemaSSL">
														</div>
													</div>
												</div>
											</fieldset>
											<fieldset>
												<legend>Cliente</legend>
												<div class="row">
													<div class="col-md-3">
														<div class="form-group">
															<label for="IndexSistemaVida">Tiempo de Vida</label>
															<input type="number" name="keepalive" class="form-control" min="30" max="65535" value="120" placeholder="KeepAlive" id="IndexSistemaVida" required>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="IndexSistemaEspera">Tiempo de Espera</label>
															<input type="number" name="timeout" class="form-control" min="0" max="15" value="3" placeholder="TimeOut" id="IndexSistemaEspera" required>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="IndexSistemaCliente">Identificador <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="top" title="" data-original-title="Deje en blanco para generar ID aleatorio"></span></label>
															<input type="text" name="cliente" class="form-control" placeholder="ClientID" id="IndexSistemaCliente">
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="IndexSistemaLimpia">Sesion Limpia</label>
															<input type="checkbox" name="limpia" checked class="form-control" id="IndexSistemaLimpia">
														</div>
													</div>
												</div>
											</fieldset>
											<fieldset>
												<legend>Testamento</legend>
												<div class="row">
													<div class="col-md-3">
														<div class="form-group">
															<label for="IndexSistemaTopico">Topico</label>
															<input type="text" name="lwt" class="form-control" placeholder="LastWill Topic" id="IndexSistemaTopico">
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="IndexSistemaQOS">QoS</label>
															<select id="IndexSistemaQOS" name="qos" class="form-control">
																<option selected>0</option>
																<option>1</option>
																<option>2</option>
															</select>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="IndexSistemaRetenido">Retenido</label>
															<input type="checkbox" name="retain" class="form-control" id="IndexSistemaRetenido">
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="IndexSistemaTestamento">Contenido</label>
															<input type="text" name="lwp" class="form-control" placeholder="LastWill Payload" id="IndexSistemaTestamento">
														</div>
													</div>
												</div>
											</fieldset>
											<fieldset>
												<legend>Protector de Pantalla</legend>
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label for="IndexSistemaClima">Informacion Climatica <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="top" title="" data-original-title="Requiere condicion climatica obtenida de wunderground.com"></span></label>
															<input type="url" name="url" class="form-control" placeholder="http://api.wunderground.com/api/TU_APIKEY/conditions/lang:SP/q/CL/TU_CIUDAD.json" id="IndexSistemaClima">
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="IndexSistemaCover">Tiempo de Espera</label>
															<input type="number" name="cover" min="10" value="30" class="form-control" id="IndexSistemaCover" required>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="IndexSistemaKeyCover">Clave de protector</label>
															<input type="password" name="keyCover" required class="form-control" id="IndexSistemaKeyCover" pattern="[0-9]{4,}">
														</div>
													</div>
												</div>
											</fieldset>
											<fieldset>
												<legend>Correo de Alerta</legend>
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label for="IndexSistemaMail1">Correo Principal</label>
															<input type="email" name="mail1" class="form-control" id="IndexSistemaMail1">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label for="IndexSistemaMail2">Correo Alternativo</label>
															<input type="email" name="mail2" class="form-control" id="IndexSistemaMail2">
														</div>
													</div>
												</div>
											</fieldset>
											<br>
											<div class="btn-group btn-group-justified" role="group" aria-label="...">
												<div class="btn-group" role="group" id="IndexFormEliminar">
													<button type="button" value="0" class="btn btn-danger funcionFomulario" data-loading-text="Cargando...">Eliminar <span class="glyphicon glyphicon-remove"></span></button>
												</div>
												<div class="btn-group" role="group" id="IndexFormPredeterminado">
													<button type="button" value="1" class="btn btn-info funcionFomulario" data-loading-text="Cargando...">Predeterminado <span class="glyphicon glyphicon-star"></span></button>
												</div>
												<div class="btn-group" role="group" id="IndexFormGuardar">
													<button type="submit" class="btn btn-success" data-loading-text="Cargando...">Guardar <span class="glyphicon glyphicon-ok"></span></button>
												</div>
											</div>
											<br>
											<div class="alert oculto" id="IndexDivAlertaForm">
												<p id="IndexPAlertaForm"></p>
											</div>
										</form>
								  	</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="Habitaciones" tabindex="-1" role="dialog" aria-labelledby="Administrar Habitaciones">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">Administrar Habitaciones</h4>
							</div>
							<div class="modal-body">
								<form class="IndexFormHabitacion">
									<div class="row">
										<div class="col-md-12">
											<div class="input-group">
												<input type="text" class="form-control" name="nombre" placeholder="Nombre" required>
												<input type="number" class="oculto" id="IndexFormHabitacionSistema" name="sistema" >
												<input type="text" class="oculto" value="0" name="id">
												<span class="input-group-btn">
													<button class="btn btn-success principal" type="submit" data-loading-text="Cargando...">Guardar</button>
												</span>
											</div>
										</div>
									</div>
								</form>
								<br>
								<div class="row" id="IndexListaHabitaciones"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="EdicionDispositivo" tabindex="-1" role="dialog" aria-labelledby="Editar Dispositivo">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">Editar Dispositivo</h4>
							</div>
							<div class="modal-body">
								<form id="IndexFormEditaDispositivo" method="post">
									<input type="number" name="id" id="IndexEditaDispositivoId" class="oculto" required>
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="IndexEditaDispositivoNombre">Nombre</label>
												<input type="text" name="nombre" class="form-control" placeholder="" id="IndexEditaDispositivoNombre" required>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="IndexEditaDispositivoTipo">Tipo</label>
												<select name="tipo" id="IndexEditaDispositivoTipo" class="form-control" required>
													<option value="1">Switch</option>
													<option value="2">Sensor</option>
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="IndexEditaDispositivoHabitacion">Habitacion</label>
												<select name="habitacion" id="IndexEditaDispositivoHabitacion" class="form-control selectHab" required>
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="IndexEditaDispositivoColor">Color</label>
												<input type="text" name="color" class="form-control colorpicker" placeholder="" data-control="hue" id="IndexEditaDispositivoColor" required>
												<input type="text" readonly class="inputDesplazado">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="IndexEditaDispositivoTopico">Topico <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="top" title="" data-original-title="Topico de valor de lectura"></span></label>
												<input type="text" name="topico" class="form-control" placeholder="" id="IndexEditaDispositivoTopico" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="IndexEditaDispositivoEstado">Estado <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="top" title="" data-original-title="Topico de estado de dispositivo"></span></label>
												<input type="text" name="estado" class="form-control" placeholder="" id="IndexEditaDispositivoEstado" required>
											</div>
										</div>
									</div>
									<div class="btn-group btn-group-justified" role="group" aria-label="...">
										<div class="btn-group" role="group">
											<button type="button" value="0" class="btn btn-danger" id="IndexEditaDispositivoEliminar">Eliminar <span class="glyphicon glyphicon-remove"></span></button>
										</div>
										<div class="btn-group" role="group">
											<button type="submit" class="btn btn-success" data-loading-text="Cargando...">Guardar <span class="glyphicon glyphicon-ok"></span></button>
										</div>
									</div>
								</form>
								<br>
								<div class="alert oculto" id="IndexDivAlertaFormEdita">
									<p id="IndexPAlertaFormEdita"></p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="Dispositivos" tabindex="-1" role="dialog" aria-labelledby="Administrar Dispositivo">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">Agregar Dispositivos</h4>
							</div>
							<div class="modal-body">
								<form id="IndexFormAgregaDispositivo" method="post">
									<input type="number" name="id" value="0" class="oculto" required>
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label>Nombre</label>
												<input type="text" name="nombre" class="form-control" placeholder="" required>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Tipo</label>
												<select name="tipo" class="form-control" required>
													<option value="1">Switch</option>
													<option value="2">Sensor</option>
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Habitacion</label>
												<select name="habitacion" class="form-control selectHab" required>
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Color</label>
												<input type="text" name="color" class="form-control colorpicker" placeholder="" data-control="hue" value="#000000" required>
												<input type="text" readonly class="inputDesplazado">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Topico <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="top" title="" data-original-title="Topico de valor de lectura"></span></label>
												<input type="text" name="topico" class="form-control" placeholder="" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Estado <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="top" title="" data-original-title="Topico de estado de dispositivo"></span></label>
												<input type="text" name="estado" class="form-control" placeholder="" required>
											</div>
										</div>
									</div>
									<div class="btn-group btn-group-justified" role="group" aria-label="...">
										<div class="btn-group" role="group">
											<button type="submit" class="btn btn-success" data-loading-text="Cargando...">Guardar <span class="glyphicon glyphicon-ok"></span></button>
										</div>
									</div>
								</form>
								<br>
								<div class="alert oculto" id="IndexDivAlertaFormAgregaDis">
									<p id="IndexPAlertaFormAgregaDis"></p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="Monitor" tabindex="-1" role="dialog" aria-labelledby="Monitor">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">Monitor</h4>
							</div>
							<div class="modal-body">
								<form id="IndexFormMonitor">
									<input type="number" value="0" name="id" class="oculto">
									<input type="number" id="IndexFormMonitorSistema" name="sistema" class="oculto">
									<div class="row">
										<div class="col-md-3">
											<label>Habitacion</label>
											<select name="habitacionIn" class="form-control selectHab" required>
											</select>
										</div>
										<div class="col-md-3">
											<label>Dispositivo</label>
											<select name="dispositivoIn" class="form-control" required>
											</select>
										</div>
										<div class="col-md-2">
											<label>Comparacion</label>
											<select class="form-control" name="compara">
												<option value="==">Igual a</option>
												<option value="!=">Distinto a</option>
												<option value="<">Menor a</option>
												<option value="<=">Menor o igual a</option>
												<option value=">">Mayor a</option>
												<option value=">=">Mayor o igual a</option>
											</select>
										</div>
										<div class="col-md-4">
											<label>Valor Entrada</label>
											<input type="text" class="form-control" name="valIn" required>
										</div>
										<div class="col-md-3">
											<label>Habitacion</label>
											<select name="habitacionOut" class="form-control selectHab">
											</select>
										</div>
										<div class="col-md-3">
											<label>Dispositivo</label>
											<select name="dispositivoOut" class="form-control">
											</select>
										</div>
										<div class="col-md-4">
											<label>Valor Salida</label>
											<input type="text" class="form-control" name="valOut">
										</div>
										<div class="col-md-2" align="center">
											<label>Enviar Alerta</label>
											<input type="checkbox" name="mail" class="form-control" style="margin: 0px; padding: 0px">
										</div>
										<div class="col-md-6">
											<button class="btn btn-default btn-block" id="IndexBtnMonitorReset">Resetear <span class="glyphicon glyphicon-refresh"></span></button>
										</div>
										<div class="col-md-6">
											<button class="btn btn-success btn-block" type="submit" data-loading-text="...">Guardar <span class="glyphicon glyphicon-ok"</button>
										</div>
									</div>
								</form>
								<br>
								<div class="row">
									<div class="col-md-12">
										<table class="table table-striped table-hover table-responsive" id="IndexTableMonitor">
											<tr>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="Log" tabindex="-1" role="dialog" aria-labelledby="Log MQTT">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">Log Mensajes MQTT <button id="limpiarLog" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-trash"></span></button></h4>
							</div>
							<div class="modal-body">
								<ul id="listaLog"></ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container" id="IndexDivPad">
			<div class="row vcenter">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-sm-offset-3 col-md-offset-3 col-lg-offset-3">
					<input type="password" class="form-control" id="IndexInputPad" style="height: 50px; font-size: 50px; text-align: center" readonly>
				</div>
				<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 col-sm-offset-3 col-md-offset-3 col-lg-offset-3">
					<button class="btn btn-default btn-block btn-lg pad numerico">1</button>
				</div>
				<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
					<button class="btn btn-default btn-block btn-lg pad numerico">2</button>
				</div>
				<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
					<button class="btn btn-default btn-block btn-lg pad numerico">3</button>
				</div>
				<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 col-sm-offset-3 col-md-offset-3 col-lg-offset-3">
					<button class="btn btn-default btn-block btn-lg pad numerico">4</button>
				</div>
				<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
					<button class="btn btn-default btn-block btn-lg pad numerico">5</button>
				</div>
				<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
					<button class="btn btn-default btn-block btn-lg pad numerico">6</button>
				</div>
				<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 col-sm-offset-3 col-md-offset-3 col-lg-offset-3">
					<button class="btn btn-default btn-block btn-lg pad numerico">7</button>
				</div>
				<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
					<button class="btn btn-default btn-block btn-lg pad numerico">8</button>
				</div>
				<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
					<button class="btn btn-default btn-block btn-lg pad numerico">9</button>
				</div>
				<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 col-sm-offset-3 col-md-offset-3 col-lg-offset-3">
					<button class="btn btn-default btn-block btn-lg pad borrar">Borrar</button>
				</div>
				<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
					<button class="btn btn-default btn-block btn-lg pad numerico">0</button>
				</div>
				<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
					<button class="btn btn-default btn-block btn-lg pad aceptar" value="true">Aceptar</button>
				</div>
			</div>
		</div>
		<div class="container" id="cover" align="center">
			<div class="row">
				<div class="col-lg-12 col-md-12 visible-lg visible-md" style="padding-top: 0px"></div>
				<div class="col-xs-12 col-sm-12 col-md-10 col-lg-8 col-lg-offset-2 col-md-offset-1">
						<span class="text-center visible-lg" style="font-size: 14em">
							<span class="hora"></span>
							<span class="minuto"></span>
							<span class="segundo"></span>
						</span>
						<span class="text-center visible-sm visible-xs visible-md" style="font-size: 7em">
							<span class="hora"></span>
							<span class="minuto"></span>
						</span>
				</div>
				<div class="visible-sm visible-xs col-sm-12 col-xs-12">
					<div id="clock_id"></div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-10 col-lg-6 col-lg-offset-3 col-md-offset-1">
					<span class="text-center fecha h1"></span>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-lg-offset-5 col-md-offset-5" style="margin-top: 30px">
					<img class="icon" width="50" height="auto">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-lg-offset-5 col-md-offset-5">
					<span class="condicion h1"></span>
				</div>
				<div class="col-lg-3 col-md-3 visible-lg visible-md col-lg-offset-3 col-md-offset-3" style="margin-top: 30px; font-size: 80px">
					<span class="temperatura"></span>
				</div>
				<div class="col-lg-3 col-md-3 visible-lg visible-md" style="margin-top: 30px; font-size: 80px">
					<span class="humedad"></span>
				</div>
				<div class="col-xs-6 col-sm-6 visible-sm visible-xs" style="margin-top: 30px">
					<span class="temperatura h1"></span>
				</div>
				<div class="col-xs-6 col-sm-6 visible-sm visible-xs" style="margin-top: 30px">
					<span class="humedad h1"></span>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-lg-offset-4 col-md-offset-4" style="margin-top: 30px">
					<span class="ubicacion h1"></span>
				</div>
			</div>
		</div>
	</body>
</html>
