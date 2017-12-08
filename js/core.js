$(document).ready(function(){
	var sistema;
	var meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
	var diasSemana = ["Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"];
	var inactividad = 0;
	var padToCover = null;
	var reconnectTimeout = 5000;
	var lastChange = {payload:"-1",topic:""};
	var subscribe = [];
	var conectado = false;
	var client = null;
	var protector = false;
	var monitorTopIn = [];
	var monitorValIn = [];
	var monitorCompare = [];
	var monitorTopOut = [];
	var monitorValOut = [];
	var monitorMail = [];
	var operacion = {
		'==': function(a, b) { return a == b },
		'!=': function(a, b) { return a != b },
		'<': function(a, b) { return a < b },
		'<=': function(a, b) { return a <= b },
		'>': function(a, b) { return a > b },
		'>=': function(a, b) { return a >= b }
	};
	
	iniciar();
	reloj();

	$(function(){
		$('.colorpicker').each( function() {
			$(this).minicolors({
				control: 'hue',
				inline: $(this).attr('data-inline') === 'true',
				letterCase: 'uppercase',
				opacity: false,
				change: function(hex, opacity) {
					if(!hex) return;
					if(opacity) hex += ', ' + opacity;
					$(this).select();
				},
				theme: 'bootstrap'
			});
		});
	});
	
	$(document).on("focus","input.colorpicker",function(){
		$('.inputDesplazado').focus();
	});
	
	$(document).bind("contextmenu", function (event) {
		event.preventDefault();
	});
	
	$(document).on('click touchstart', function () {
		inactividad = 0;
		if(protector){
			$("#cover").hide();
			$("#IndexDivPad").show();
			clearTimeout(padToCover);
			padToCover = setTimeout(function(){
				$("#cover").show();
				$("#IndexDivPad").hide();
			}, 10000);
		}
	});
	
	$(document).on("click","button.pad.aceptar",function(){
		if($("#IndexInputPad").val() == "1315"){
			screenSaverOff();
			clearTimeout(padToCover);
			$("#IndexInputPad").val("");
		}else{
			$("#IndexInputPad").val("");
		}
		
	});
	
	$(document).on("click","button.pad.numerico",function(){
		var num = $(this).html();
		$("#IndexInputPad").val($("#IndexInputPad").val()+num);
	});
	
	$(document).on("click","button.pad.borrar",function(){
		$("#IndexInputPad").val("");
	});
	
	$(document).on("click","button.conexiones",function(){
		if(conectado){
			unsubscribeDevice();
			client.disconnect();
			client = null;
			console.log("desconectado");
			subscribe = [];
		}
		iniciar($(this).val());
		
	});

	$(document).on("click","a.menu",function(){
		var tipo = $(this).html();
		var removeElements = function(text, selector) {
			var wrapped = $("<div>" + text + "</div>");
			wrapped.find(selector).remove();
			return wrapped.html().trim();
		}
		tipo = removeElements(tipo,"span");
		if(tipo!="Todo"){
			$(document).find(".Dispositivo").each(function(e){
				if(!$(this).hasClass(tipo)){
					$(this).hide("slow");
				}else{
					$(this).show("slow");
				}
			});
		}else{
			$(document).find(".Dispositivo").each(function(e){
				$(this).show("slow");
			});
		}
	});

	$(document).on("change","input[type=checkbox].toogle",function(){
		var topic = $(this).attr("id");
		var payload;
		if($(this).prop("checked")){
			payload="1";
		}else{
			payload="0";
		}
		if((lastChange.payload!=payload && lastChange.topic==topic) || lastChange.topic!=topic){
			sendMessage(payload,topic);
		}
	});
	
	$(document).on("click","#recargarDatos",function(){
		sendMessage("1","Dispositivos/Actualizar");
	})

	$(document).on("click","#fullScreen",function(){
		fullScreen();
	})
	
	$(document).on("click","#screenSaver",function(){
		protectorManual = true;
		screenSaver();
	});
	
	$(document).on("click","#limpiarLog",function(){
		$("#listaLog").html("");
	});

	$(document).on("click","#Recargar", function(){
		location.href = location.pathname
	});
	
	$(document).on("click",".selectorSistema", function(){
		var id = $(this).data("value");
		$.ajax({
			dataType: "json",
			type: "POST",	
			url: "core/config.php",
			data: {id:id},
			beforeSend : function (){
			},
			success: function(data){
				$("#IndexSistemaId").val(data[0].id);
				$("#IndexSistemaNombre").val(data[0].nombre);
				$("#IndexSistemaUrl").val(data[0].ip);
				$("#IndexSistemaPuerto").val(data[0].puerto);
				$("#IndexSistemaRuta").val(data[0].path);
				$("#IndexSistemaUser").val(data[0].usuario);
				$("#IndexSistemaPass").val(data[0].clave);
				$("#IndexSistemaSSL").attr("checked",data[0].ssl);
				$("#IndexSistemaVida").val(data[0].keepalive);
				$("#IndexSistemaEspera").val(data[0].timeout);
				$("#IndexSistemaCliente").val(data[0].cliente);
				$("#IndexSistemaLimpia").attr("checked",data[0].clean);
				$("#IndexSistemaTopico").val(data[0].lastwilltopic);
				$("#IndexSistemaQOS").val(data[0].qos);
				$("#IndexSistemaRetenido").attr("checked",data[0].retained);
				$("#IndexSistemaTestamento").val(data[0].lastwillpayload);
				$("#IndexSistemaClima").val(data[0].url);
				$("#IndexSistemaCover").val(data[0].espera);
				$("#IndexSistemaMail1").val(data[0].mail1);
				$("#IndexSistemaMail2").val(data[0].mail2);
				$("#IndexFormGuardar").show();
				$("#IndexFormEliminar").show();
				if(data[0].default){
					$("#IndexFormPredeterminado").hide();
				}else{
					$("#IndexFormPredeterminado").show();
				}
				hostname=data[0].ip.toString();
				port= parseInt(data[0].puerto);
				path = data[0].path;
				user=data[0].usuario;
				pass=data[0].clave;
				ssl = data[0].ssl;
				keepAlive = parseInt(data[0].keepalive);
				timeout = parseInt(data[0].timeout);
				clientId = data[0].cliente;
				cleanSession = data[0].clean;
				lastWillTopic = data[0].lastwilltopic;
				lastWillMessage = data[0].lastwillpayload;
				lastWillQos = data[0].qos;
				lastWillRetain = data[0].retained;
				cover=data[0].espera;
			}
		});
	});
	
	$(document).on("click","#IndexNuevoSistema",function(){
		$("#IndexFormSistema")[0].reset();
		$("#IndexSistemaSSL").attr("checked",false);
		$("#IndexSistemaLimpia").attr("checked",true);
		$("#IndexSistemaRetenido").attr("checked",false);
		$("#IndexFormPredeterminado").hide();
		$("#IndexFormEliminar").hide();
	});
	
	$(document).on("submit","#IndexFormSistema",function(e){
		e.preventDefault();
		$.ajax({
			url: "core/load/adUpSis.php",
			type: "POST",
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,
			beforeSend : function (){
				$("#IndexFormSistema").find("button[type=submit]").button("loading");
			},
			success: function(data)
			{
				switch(data){
					case "0":
					$("#IndexDivAlertaForm").removeClass("alert-info alert-success alert-danger alert-warning");
					$("#IndexDivAlertaForm").addClass("alert-danger");
					$("#IndexPAlertaForm").html("No se pudieron guardar los cambios");
					$("#IndexDivAlertaForm").show("fast");
					setTimeout(function(){$("#IndexDivAlertaForm").hide("fast");},3000);
					break;
					case "1":
					$("#IndexDivAlertaForm").removeClass("alert-info alert-success alert-danger alert-warning");
					$("#IndexDivAlertaForm").addClass("alert-success");
					$("#IndexPAlertaForm").html("Sistema guardado exitosamente");
					$("#IndexDivAlertaForm").show("fast");
					setTimeout(function(){$("#IndexDivAlertaForm").hide("fast");},3000);
					break;
					case "2":
					$("#IndexDivAlertaForm").removeClass("alert-info alert-success alert-danger alert-warning");
					$("#IndexDivAlertaForm").addClass("alert-success");
					$("#IndexPAlertaForm").html("Sistema editado exitosamente");
					$("#IndexDivAlertaForm").show("fast");
					setTimeout(function(){$("#IndexDivAlertaForm").hide("fast");},3000);
					break;
				}
				cargarSistemas();
			}
		}).always(function(){
			$("#IndexFormSistema").find("button[type=submit]").button("reset");
		});
	});
	
	$(document).on("click",".funcionFomulario",function(){
		var btn = $(this);
		var funcion = btn.val();
		var sistema = $("#IndexSistemaId").val();
		$.ajax({
			url: "core/load/delDefSis.php",
			type: "POST",
			data: {id:sistema, fun:funcion},
			beforeSend : function (){
				$(btn).button("loading");
			},
			success: function(data)
			{
				switch(data){
					case "0":
					$("#IndexDivAlertaForm").removeClass("alert-info alert-success alert-danger alert-warning");
					$("#IndexDivAlertaForm").addClass("alert-danger");
					$("#IndexPAlertaForm").html("No se pudo eliminar el sistema");
					$("#IndexDivAlertaForm").show("fast");
					setTimeout(function(){$("#IndexDivAlertaForm").hide("fast");},3000);
					break;
					case "1":
					$("#IndexDivAlertaForm").removeClass("alert-info alert-success alert-danger alert-warning");
					$("#IndexDivAlertaForm").addClass("alert-success");
					$("#IndexPAlertaForm").html("Sistema eliminado exitosamente");
					$("#IndexDivAlertaForm").show("fast");
					cargarSistemas();
					setTimeout(function(){$("#IndexDivAlertaForm").hide("fast");},3000);
					break;
					case "2":
					$("#IndexDivAlertaForm").removeClass("alert-info alert-success alert-danger alert-warning");
					$("#IndexDivAlertaForm").addClass("alert-warning");
					$("#IndexPAlertaForm").html("No se pudo establecer sistema predeterminado");
					$("#IndexDivAlertaForm").show("fast");
					setTimeout(function(){$("#IndexDivAlertaForm").hide("fast");},3000);
					break;
					case "3":
					$("#IndexDivAlertaForm").removeClass("alert-info alert-success alert-danger alert-warning");
					$("#IndexDivAlertaForm").addClass("alert-success");
					$("#IndexPAlertaForm").html("Sistema predeterminado establecido");
					$("#IndexDivAlertaForm").show("fast");
					$("#IndexFormPredeterminado").hide();
					setTimeout(function(){$("#IndexDivAlertaForm").hide("fast");},3000);
					break;
					default:
					console.log(data);
					break;
				}
			}
		}).always(function(){
			$(btn).button("reset");
		});
	});
	
	$(document).on("click","#editarDispositivos",function(){
		$(".editarDispositivo").toggle();
	});
	
	$(document).on("click",".editarDispositivo",function(){
		var id = $(this).val();
		$.ajax({
			dataType: "json",
			type: "POST",	
			url: "core/disid.php",
			data: {id:id},
			beforeSend : function (){
				
			},
			success: function(data){
				$("#IndexEditaDispositivoId").val(id);
				$("#IndexEditaDispositivoNombre").val(data[0].nombre);
				$("#IndexEditaDispositivoTipo").val(data[0].tipo);
				$("#IndexEditaDispositivoHabitacion").val(data[0].habitacionId);
				$("#IndexEditaDispositivoColor").minicolors("value",data[0].color);
				$("#IndexEditaDispositivoTopico").val(data[0].topico);
				$("#IndexEditaDispositivoEstado").val(data[0].estado);
			}
		}).done(function(){
			$('#EdicionDispositivo').modal('show');
		});
	});
	
	$(document).on("submit","#IndexFormEditaDispositivo",function(e){
		e.preventDefault();
		$.ajax({
			url: "core/load/adUpDis.php",
			type: "POST",
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,
			beforeSend : function (){
				$("#IndexFormEditaDispositivo").find("button[type=submit]").button("loading");
			},
			success: function(data)
			{
				switch(data){
					case "0":
					$("#IndexDivAlertaFormEdita").removeClass("alert-info alert-success alert-danger alert-warning");
					$("#IndexDivAlertaFormEdita").addClass("alert-danger");
					$("#IndexPAlertaFormEdita").html("No se pudieron guardar los cambios");
					$("#IndexDivAlertaFormEdita").show("fast");
					setTimeout(function(){$("#IndexDivAlertaFormEdita").hide("fast");},3000);
					break;
					case "2":
					$("#IndexDivAlertaFormEdita").removeClass("alert-info alert-success alert-danger alert-warning");
					$("#IndexDivAlertaFormEdita").addClass("alert-success");
					$("#IndexPAlertaFormEdita").html("Dispositivo guardado exitosamente");
					$("#IndexDivAlertaFormEdita").show("fast");
					setTimeout(function(){$("#IndexDivAlertaFormEdita").hide("fast");},3000);
					cargarHabitaciones(sistema);
					cargarDispositivos(sistema);
					subscribeDevice();
					break;
				}
			}
		}).always(function(){
			$("#IndexFormEditaDispositivo").find("button[type=submit]").button("reset");
		}).done(function(){
			sendMessage("1","Dispositivos/Actualizar");
		});
	});
	
	$(document).on("click","#IndexEditaDispositivoEliminar",function(){
		var id = $("#IndexEditaDispositivoId").val();
		$.ajax({
			url: "core/load/delDis.php",
			type: "POST",
			data: {id:id},
			beforeSend : function (){
				
			},
			success: function(data)
			{
				switch(data){
					case "0":
					$("#IndexDivAlertaFormEdita").removeClass("alert-info alert-success alert-danger alert-warning");
					$("#IndexDivAlertaFormEdita").addClass("alert-danger");
					$("#IndexPAlertaFormEdita").html("No se pudieron guardar los cambios");
					$("#IndexDivAlertaFormEdita").show("fast");
					setTimeout(function(){$("#IndexDivAlertaFormEdita").hide("fast");},3000);
					break;
					case "1":
					$('#EdicionDispositivo').modal('hide');
					cargarDispositivos(sistema);
					subscribeDevice();
					sendMessage("1","Dispositivos/Actualizar");	
					break;
				}
			}
		});
	})
	
	$(document).on("submit","#IndexFormAgregaDispositivo",function(e){
		e.preventDefault();
		$.ajax({
			url: "core/load/adUpDis.php",
			type: "POST",
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,
			beforeSend : function (){
				$("#IndexFormAgregaDispositivo").find("button[type=submit]").button("loading");
			},
			success: function(data)
			{
				switch(data){
					case "0":
					$("#IndexDivAlertaFormAgregaDis").removeClass("alert-info alert-success alert-danger alert-warning");
					$("#IndexDivAlertaFormAgregaDis").addClass("alert-danger");
					$("#IndexPAlertaFormAgregaDis").html("No se pudieron guardar los cambios");
					$("#IndexDivAlertaFormAgregaDis").show("fast");
					setTimeout(function(){$("#IndexDivAlertaFormAgregaDis").hide("fast");},3000);
					break;
					case "1":
					$("#IndexDivAlertaFormAgregaDis").removeClass("alert-info alert-success alert-danger alert-warning");
					$("#IndexDivAlertaFormAgregaDis").addClass("alert-success");
					$("#IndexPAlertaFormAgregaDis").html("Dispositivo agregado exitosamente");
					$("#IndexDivAlertaFormAgregaDis").show("fast");
					setTimeout(function(){$("#IndexDivAlertaFormAgregaDis").hide("fast");},3000);
					cargarHabitaciones(sistema);
					cargarDispositivos(sistema);
					subscribeDevice();
					$("#IndexFormAgregaDispositivo")[0].reset();
					break;
				}
			}
		}).done(function(){
			sendMessage("1","Dispositivos/Actualizar");
		}).always(function(){
			$("#IndexFormAgregaDispositivo").find("button[type=submit]").button("reset");
		});
	});
	
	$(document).on("click",".editarHabitacion",function(){
		$(this).hide();
		$(this).parent("span").find(".guardarHabitacion").show();
		$(this).closest("div").find("input[type=text]").removeAttr("readonly");
	});

	$(document).on("click",".eliminarHabitacion",function(e){
		e.preventDefault();
		var id = $(this).closest("div").find("input[name='id']").val();
		$.ajax({
			url: "core/load/delHab.php",
			type: "POST",
			data: {id:id},
			success: function(data)
			{
				if(data=="1"){
					cargarHabitaciones(sistema);
					cargarDispositivos(sistema);
					subscribeDevice();
					sendMessage("1","Dispositivos/Actualizar");			
				}

			}
		});
	});
	
	$(document).on("submit",".IndexFormHabitacion",function(e){
		e.preventDefault();
		$.ajax({
			url: "core/load/adUpHab.php",
			type: "POST",
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function(){
				$("#Habitaciones").find("button[type=submit].principal").button("loading");
			},
			success: function(data)
			{
				if(data!="0"){
					cargarHabitaciones(sistema);
					cargarDispositivos(sistema);
					subscribeDevice();
				}else{
					$(".editarHabitacion").show();
					$(".guardarHabitacion").hide();
					$(".IndexFormHabitacion").find("input[type=text].formEditable").each(function(){
						$(this).attr("readonly",true);
					});
				}
			}
		}).done(function(){
			sendMessage("1","Dispositivos/Actualizar");
		}).always(function(){
			$("#Habitaciones").find("button[type=submit].principal").button("reset");
		});
	});
	
	$(document).on("change","select[name=habitacionIn]",function(){
		var hab = $(this).val();
		$.ajax({
			dataType: "json",
			url: "core/disHab.php",
			type: "POST",
			data: {id:hab},
			beforeSend: function(){
				$("#IndexFormMonitor").find("select[name=dispositivoIn]").html("");
			},
			success: function(data)
			{
				$.each(data, function(i, dis) {
					$("#IndexFormMonitor").find("select[name=dispositivoIn]").append("<option value=\""+dis.id+"\">"+dis.nombre+"</option>");
				});
			}
		});
	});
	
	$(document).on("change","select[name=habitacionOut]",function(){
		var hab = $(this).val();
		$.ajax({
			dataType: "json",
			url: "core/disHab.php",
			type: "POST",
			data: {id:hab},
			beforeSend: function(){
				$("#IndexFormMonitor").find("select[name=dispositivoOut]").html("");
			},
			success: function(data)
			{
				$.each(data, function(i, dis) {
					$("#IndexFormMonitor").find("select[name=dispositivoOut]").append("<option value=\""+dis.id+"\">"+dis.nombre+"</option>");
				});
			}
		});
	});
	
	$(document).on("submit","#IndexFormMonitor",function(e){
		e.preventDefault();
		$.ajax({
			url: "core/load/adUpMon.php",
			type: "POST",
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,
			beforeSend : function (){
				$("#IndexFormMonitor").find("button[type=submit]").button("loading");
			},
			success: function(data){
				if(data>0){
					cargarMonitor(sistema);
					$("#IndexFormMonitor").find("input[type=text]").each(function(){$(this).val("");});
					$("#IndexFormMonitor").find("input[name=id]").val(0);
				}
			}
		}).always(function(){
			$("#IndexFormMonitor").find("button[type=submit]").button("reset");
		});
	});
	
	$(document).on("click","#IndexBtnMonitorReset",function(e){
		e.preventDefault();
		$("#IndexFormMonitor").find("input[type=text]").each(function(){$(this).val("");});
		$("#IndexFormMonitor").find("input[name=id]").val(0);
	});
	
	$(document).on("click",".eliminarMonitor",function(e){
		e.preventDefault();
		var id = $(this).val();
		$.ajax({
			url: "core/load/delMon.php",
			type: "POST",
			data: {id:id},
			success: function(data)
			{
				if(data=="1"){
					cargarMonitor(sistema);		
				}

			}
		});
	});
	
	$(document).on("click",".editarMonitor",function(e){
		e.preventDefault();
		var id = $(this).val();
		var habIn = $(this).closest("tr").find("td:first").html();
		var disIn = $(this).closest("tr").find("td:gt(0)").html();
		var compare = $(this).closest("tr").find("td:gt(1)").html();
		var valIn = $(this).closest("tr").find("td:gt(2)").html();
		var habOut = $(this).closest("tr").find("td:gt(3)").html();
		var disOut = $(this).closest("tr").find("td:gt(4)").html();
		var valOut = $(this).closest("tr").find("td:gt(5)").html();
		var mail = $(this).closest("tr").find("td:gt(6)").html()=="Si"?true:false;
		
		$("#IndexFormMonitor").find("input[name='id']").val(id);
		$("#IndexFormMonitor").find("input[name='sistema']").val(sistema);
		$("#IndexFormMonitor").find("select[name='compara'] option").each(function() {
			if($(this).text() == compare.toString()) {
				$(this).attr('selected',true);            
			}else{
				$(this).removeAttr('selected');
			}                        
		});
		$("#IndexFormMonitor").find("select[name='habitacionIn'] option").each(function() {
			if($(this).text() == habIn.toString()) {
				$(this).attr('selected',true);            
			}else{
				$(this).removeAttr('selected');
			}                        
		});
		$("#IndexFormMonitor").find("select[name=habitacionIn]").trigger("change");
		$(document).ajaxComplete(function( event, request, settings ) {
			$("#IndexFormMonitor").find("select[name='dispositivoIn'] option").each(function() {
				if($(this).text() == disIn.toString()) {
					$(this).attr('selected',true);            
				}else{
					$(this).removeAttr('selected');
				}                        
			});
		});
		$("#IndexFormMonitor").find("select[name='habitacionOut'] option").each(function() {
			if($(this).text() == habOut.toString()) {
				$(this).attr('selected',true);            
			}else{
				$(this).removeAttr('selected');
			}                        
		});
		$("#IndexFormMonitor").find("select[name=habitacionOut]").trigger("change");
		$(document).ajaxComplete(function( event, request, settings ) {
			$("#IndexFormMonitor").find("select[name='dispositivoOut'] option").each(function() {
				if($(this).text() == disOut.toString()) {
					$(this).attr('selected',true);            
				}else{
					$(this).removeAttr('selected');
				}                        
			});
		});
		$("#IndexFormMonitor").find("input[name='valIn']").val(valIn);
		$("#IndexFormMonitor").find("input[name='valOut']").val(valOut);
		$("#IndexFormMonitor").find("input[name='mail']").prop("checked",mail);
	});
	
	$('[data-toggle="tooltip"]').tooltip();
	
	function screenSaverOff(){
		inactividad = 0;
		$("#mainContainer").show();
		$("#IndexDivPad").fadeOut("slow");
		$("#cover").fadeOut("slow");
		protector = false;
	}
	
	function screenSaver(){
		inactividad = cover;
		$("#cover").fadeIn("slow",function(){
			$("#mainContainer").hide(); 
			protector = true;
		});
	}
	
	function timers(){
		setInterval(reloj, 1000);
		setInterval(function(){
			if(inactividad<=cover){
				$("#IndexDivProgreso").css("width",((100*inactividad)/cover)+"%");
			}else{
				screenSaver();
			}
			inactividad++;
		}, 1000);
		setInterval(actualizarTiempo, 20000);
	}
	
	function iniciar(id){
		cargarSistemas();
		$.ajax({
			dataType: "json",
			type: "POST",	
			url: "core/config.php",
			data: {id:id},
			beforeSend : function (){
				
			},
			success: function(data){
				if(data[0].id!=null){
					$("#nombrePanel").html(data[0].nombre);
					sistema=data[0].id;
					cargarHabitaciones(data[0].id);
					cargarDispositivos(data[0].id);
					cargarMonitor(data[0].id);
					hostname=data[0].ip.toString();
					port= parseInt(data[0].puerto);
					path = data[0].path;
					user=data[0].usuario;
					pass=data[0].clave;
					ssl = data[0].ssl;
					keepAlive = parseInt(data[0].keepalive);
					timeout = parseInt(data[0].timeout);
					clientId = data[0].cliente;
					cleanSession = data[0].clean;
					lastWillTopic = data[0].lastwilltopic;
					lastWillMessage = data[0].lastwillpayload;
					lastWillQos = data[0].qos;
					lastWillRetain = data[0].retained;
					cover=data[0].espera;
					url=data[0].url;


					$("#IndexSistemaId").val(data[0].id);
					$("#IndexSistemaNombre").val(data[0].nombre);
					$("#IndexSistemaUrl").val(data[0].ip);
					$("#IndexSistemaPuerto").val(data[0].puerto);
					$("#IndexSistemaRuta").val(data[0].path);
					$("#IndexSistemaUser").val(data[0].usuario);
					$("#IndexSistemaPass").val(data[0].clave);
					$("#IndexSistemaSSL").attr("checked",data[0].ssl);
					$("#IndexSistemaVida").val(data[0].keepalive);
					$("#IndexSistemaEspera").val(data[0].timeout);
					$("#IndexSistemaCliente").val(data[0].cliente);
					$("#IndexSistemaLimpia").attr("checked",data[0].clean);
					$("#IndexSistemaTopico").val(data[0].lastwilltopic);
					$("#IndexSistemaQOS").val(data[0].qos);
					$("#IndexSistemaRetenido").attr("checked",data[0].retained);
					$("#IndexSistemaTestamento").val(data[0].lastwillpayload);
					$("#IndexSistemaClima").val(data[0].url);
					$("#IndexSistemaCover").val(data[0].espera);
					$("#IndexSistemaMail1").val(data[0].mail1);
					$("#IndexSistemaMail2").val(data[0].mail2);
					$("#IndexFormHabitacionSistema").val(sistema);
					$("#IndexFormMonitorSistema").val(sistema);
					if(data[0].default){
						$("#IndexFormPredeterminado").hide();
					}else{
						$("#IndexFormPredeterminado").show();
					}
				}else{
					$("#IndexFormPredeterminado").hide();
					$("#IndexFormEliminar").hide();
				}
			}
		}).done(function(){
			if(sistema!==undefined){
				MQTTconnect();
				timers();
				if(url!==""){
					clima();
					setInterval(clima, 3600000);
				}
			}
		});
	}

	function cargarHabitaciones(id){
		$.ajax({
			dataType: "json",
			type: "POST",	
			url: "core/hab.php",
			data: {id:id},
			beforeSend : function (){
				$("#dropHabitacion").html("");
				$(".selectHab").html("");
				$("#IndexFormMonitor").find("select[name=habitacionOut]").append("<option value=\"\">Ninguna</option>");
				$("#IndexEditaDispositivoHabitacion").html("");
				$("#IndexListaHabitaciones").html("");
			},
			success: function(data){
				$.each(data, function(i, hab) {
					$("#dropHabitacion").append("<li><a href=\"#"+hab.nombre+"\" data-toggle=\"tab\" aria-expanded=\"false\" class=\"menu\">"+hab.nombre+" <span class=\"badge\">"+hab.cantidad+"</span></a></li>");
					$("#dropHabitacion").append("<li class=\"divider\"></li>");
					
					$(".selectHab").append("<option value=\""+hab.id+"\">"+hab.nombre+"</option>");
					
					$("#IndexListaHabitaciones").append("<form class=\"IndexFormHabitacion\"><div class=\"col-md-12\"><div class=\"input-group\"><input type=\"text\" name=\"nombre\" readonly required class=\"form-control formEditable\" value=\""+hab.nombre+"\"><input type=\"number\" style=\"display: none\" name=\"sistema\" value=\""+sistema+"\"><input type=\"number\" name=\"id\" style=\"display: none\" value=\""+hab.id+"\"><span class=\"input-group-btn\"><a class=\"btn btn-default editarHabitacion\"><span class=\"glyphicon glyphicon-edit\"></span></a><button class=\"btn btn-success guardarHabitacion\" type=\"submit\" style=\"display: none\"><span class=\"glyphicon glyphicon-ok\"></span></button><button class=\"btn btn-danger eliminarHabitacion\"><span class=\"glyphicon glyphicon-remove\"></span></button></span></div></div></form>");
				});
				$("#IndexFormMonitor").find("select[name=habitacionIn]").trigger("change");
				$("#IndexFormMonitor").find("select[name=habitacionOut]").trigger("change");
				$("#dropHabitacion li:last-child").remove();
			}
		});
	}

	function cargarDispositivos(id){
		$.ajax({
			dataType: "json",
			type: "POST",	
			url: "core/dis.php",
			data: {id:id},
			beforeSend : function (){
				$(".Dispositivo").each(function(){
					$(this).remove();
				});
			},
			success: function(data){
				subscribe = [];
				$.each(data, function(i, dis) {
					if(subscribe.indexOf(dis.estado)==-1){
						subscribe.push(dis.estado);
					}
					if(subscribe.indexOf(dis.topico)==-1){
						subscribe.push(dis.topico);
					}
					if(dis.tipo=="Switch"){
						$("#mainRow").append("<div class=\"col-xs-12 col-sm-6 col-md-4 col-lg-3 label Dispositivo "+dis.habitacion+" Switch\" style=\"border-radius: 10px; height: 120px; margin-bottom: 10px; background-color:"+dis.color+"\"><div class=\"Estado "+dis.estado+"\" style=\"width: 15px; height: 15px; background-color: #FF0004; border-radius: 15px; position: absolute; left: 10px\"></div><div style=\"position: absolute; right: 10px\"><button class=\"btn btn-default btn-sm editarDispositivo\" value=\""+dis.id+"\" style=\"display:none\"><span class=\"glyphicon glyphicon-edit\"></span></button></div><div style=\"font-size: 22px\">"+dis.nombre+"</div><div style=\"font-size: 16px\">"+dis.habitacion+"</div><br><input type=\"checkbox\" data-toggle=\"toggle\" data-onstyle=\"success\" data-offstyle=\"danger\" data-on=\"Encendido\" data-off=\"Apagado\" class=\"Switch toogle "+dis.topico+"\" id=\""+dis.topico+"\"><div style=\"position: relative; bottom: -10px; text-align: center; width: 100%\"><span class=\"tiempo "+dis.topico+"\" style=\"display: none\">0</span>Ultima lectura: <span class=\"lectura "+dis.topico+"\">Nunca</span></div></div>");
					}else if(dis.tipo=="Sensor"){
						$("#mainRow").append("<div class=\"col-xs-12 col-sm-6 col-md-4 col-lg-3 label label-"+dis.color+" Dispositivo "+dis.habitacion+" Sensor\" style=\"border-radius: 10px; height: 120px; margin-bottom: 10px; background-color:"+dis.color+"\"><div class=\"Estado "+dis.estado+"\" style=\"width: 15px; height: 15px; background-color: #FF0004; border-radius: 15px; position: absolute; left: 10px\"></div><div style=\"position: absolute; right: 10px\"><button class=\"btn btn-default btn-sm editarDispositivo\" value=\""+dis.id+"\" style=\"display:none\"><span class=\"glyphicon glyphicon-edit\"></span></button></div><div style=\"font-size: 22px\">"+dis.nombre+"</div><div style=\"font-size: 16px\">"+dis.habitacion+"</div><div style=\"font-size: 50px; text-align:center\" class=\"Sensor "+dis.topico+"\">#</div><div style=\"position: relative; bottom: -10px; text-align: center; width: 100%\"><span class=\"tiempo "+dis.topico+"\" style=\"display: none\">0</span>Ultima lectura: <span class=\"lectura "+dis.topico+"\">Nunca</span></div></div>");
					}
				});
			}
		}).done(function(){
			$("input[type=checkbox].toogle").bootstrapToggle();
		});
	}

	function cargarSistemas(){
		$.ajax({
			dataType: "json",
			type: "POST",	
			url: "core/sis.php",
			beforeSend : function (){
				$("#modalSistemas").html("");
				$("#IndexListaConexiones").html("");
			},
			success: function(data){
				var clase = "active";
				$.each(data, function(i, sis) {
					$("#modalSistemas").append("<li class=\""+clase+"\"><a href=\"#"+sis.nombre+"\" data-toggle=\"tab\" aria-expanded=\"true\" data-value=\""+sis.id+"\" class=\"selectorSistema\">"+sis.nombre+"</a></li>");
					clase="";
					
					$("#IndexListaConexiones").append("<button value=\""+sis.id+"\" class=\"conexiones btn btn-block btn-default\">"+sis.nombre+"</button>");
				});
			}
		}).done(function(){
			if($("#modalSistemas").html()==""){
				$("#IndexFormPredeterminado").hide();
				$("#IndexFormEliminar").hide();
				$("#modalSistemas").append("<li class=\"active\"><a href=\"#Nuevo\" data-toggle=\"tab\" aria-expanded=\"true\" class=\"\" id=\"IndexNuevoSistema\"><span class=\"glyphicon glyphicon-plus\"></span></a></li>");
			}else{
				$("#modalSistemas").append("<li class=\"\"><a href=\"#Nuevo\" data-toggle=\"tab\" aria-expanded=\"true\" class=\"\" id=\"IndexNuevoSistema\"><span class=\"glyphicon glyphicon-plus\"></span></a></li>");
			}
		});
	}
	
	function cargarMonitor(id){
		$.ajax({
			dataType: "json",
			type: "POST",	
			url: "core/mon.php",
			data: {id:id},
			beforeSend : function (){
				monitorTopIn = [];
				monitorValIn = [];
				monitorCompare = [];
				monitorTopOut = [];
				monitorValOut = [];
				monitorMail = [];
				$("#IndexTableMonitor").find("tr").remove();
				$("#IndexTableMonitor").append("<tr><th>Habitacion</th><th>Dispositivo</th><th>Comparacion</th><th>Valor</th><th>Habitacion</th><th>Dispositivo</th><th>Valor</th><th>Alerta</th><th>Acciones</th></tr>");
			},
			success: function(data){
				$.each(data, function(i, mon) {
					monitorTopIn.push(mon.topIn);
					monitorValIn.push(mon.valIn);
					monitorCompare.push(mon.compare);
					monitorTopOut.push(mon.topOut);
					monitorValOut.push(mon.valOut);
					monitorMail.push(mon.mail);
					var mail = mon.mail?'Si':'No';
					var compara = "";
					switch(mon.compare){
						case "==": 	compara = "Igual a";
						break;
						case "!=": 	compara = "Distinto a";
						break;
						case "<": 	compara = "Menor a";
						break;
						case "<=": 	compara = "Menor o igual a";
						break;
						case ">": 	compara = "Mayor a";
						break;
						case ">=": 	compara = "Mayor o igual a";
						break;
					}
					
					$("#IndexTableMonitor").append("<tr><td>"+mon.nomHabIn+"</td><td>"+mon.nomDisIn+"</td><td>"+compara+"</td><td>"+mon.valIn+"</td><td>"+mon.nomHabOut+"</td><td>"+mon.nomDisOut+"</td><td>"+mon.valOut+"</td><td>"+mail+"</td><td><button value=\""+mon.idMon+"\" class=\"btn btn-default editarMonitor\"><span class=\"glyphicon glyphicon-edit\"></span></button><button value=\""+mon.idMon+"\" class=\"btn btn-danger eliminarMonitor\"><span class=\"glyphicon glyphicon-remove\"></span></button></td></tr>");
				});
			}
		});
	}

	function reloj(){
		var fechaHora = new Date();
		var horas = fechaHora.getHours();
		var minutos = fechaHora.getMinutes();
		var segundos = fechaHora.getSeconds();

		if(horas < 10) { horas = '0' + horas; }
		if(minutos < 10) { minutos = '0' + minutos; }
		if(segundos < 10) { segundos = '0' + segundos; }
		$(".hora").html(horas);
		$(".minuto").html(minutos);
		$(".segundo").html(segundos);
		$(".fecha").html(diasSemana[fechaHora.getDay()] + ", " + fechaHora.getDate() + " de " + meses[fechaHora.getMonth()] + " de " + fechaHora.getFullYear());
	}

	function clima(){
		$.getJSON(url, function(data){
			$(".temperatura").html(data.current_observation.temp_c+"°C");
			$(".humedad").html(data.current_observation.relative_humidity);
			$(".condicion").html(data.current_observation.weather);
			$(".ubicacion").html(data.current_observation.display_location.full);
			var urlIcon = data.current_observation.icon_url.split("/");
			var icon = urlIcon[urlIcon.length-1].split(".");
			$(".icon").attr("src","fonts/climacons/"+icon[0]+".png")
		})
	}

	function MQTTconnect(){
		if(!conectado){
			if(clientId.length==0){
				clientId = "web_" + parseInt(Math.random() * 100, 10);
			}
			if(path.length>0){
				client = new Paho.MQTT.Client(hostname, Number(port), path, clientId);
			} else {
				client = new Paho.MQTT.Client(hostname, Number(port), clientId);
			}

			client.onConnectionLost = onConnectionLost;
			client.onMessageArrived = onMessageArrived;

			var options = {
				invocationContext: {host : hostname, port: port, path: client.path, clientId: clientId},
				timeout: timeout,
				keepAliveInterval:keepAlive,
				cleanSession: cleanSession,
				useSSL: ssl,
				onSuccess: onConnect,
				onFailure: onFail
			};



			if(user.length > 0){
				options.userName = user;
			}

			if(pass.length > 0){
				options.password = pass;
			}

			if(lastWillTopic.length > 0){
				var lastWillMessage = new Paho.MQTT.Message(lastWillMessage);
				lastWillMessage.destinationName = lastWillTopic;
				lastWillMessage.qos = lastWillQos;
				lastWillMessage.retained = lastWillRetain;
				options.willMessage = lastWillMessage;
			}

			client.connect(options);
		}
	}
	
	function onConnect() {
		conectado = true;
		$("#alerta").removeClass("alert-info alert-success alert-danger alert-warning");
		$("#alerta").addClass("alert-success");
		$("#estado").html('Conectado a ' + hostname + ':' + port + path);
		console.log('Conectado a ' + hostname + ':' + port + path);
		$("#alerta").show("fast");
		setTimeout(function(){$("#alerta").hide("slow")},3000);
		$('#listaLog').html("");
		subscribeDevice();
		sendMessage("1","Dispositivos/Actualizar");	
	}

	function onFail(context) {
		conectado = false;
		$("#alerta").removeClass("alert-info alert-success alert-danger alert-warning");
		$("#alerta").addClass("alert-danger");
		$("#estado").html("Conexion Fallida: " + context.errorMessage + " Reintentando");
		console.log("Conexion Fallida: " + context.errorMessage + " Reintentando");
		$("#alerta").show("fast");
		conectado = false;
		client = null;
		setTimeout(MQTTconnect, reconnectTimeout);
	}

	function onConnectionLost(responseObject) {
		$("#alerta").removeClass("alert-info alert-success alert-danger alert-warning");
		$("#alerta").addClass("alert-warning");
		$("#estado").html("Conexion Perdida: " + responseObject.errorMessage + " Reconectando");
		console.log("Conexion Perdida: " + responseObject.errorMessage + " Reconectando");
		$("#alerta").show("fast");
		conectado = false;
		client = null;
		setTimeout(MQTTconnect, reconnectTimeout);
	}

	function onMessageArrived(message) {
		var topic = message.destinationName;
		var payload = message.payloadString;
		$('#listaLog').prepend('<li>' + topic + ' = ' + payload + '</li>');
		console.log(topic+":"+payload);
		lastChange = {payload:payload,topic:topic};
		var dispositivo = document.getElementsByClassName(topic);
		$.each(dispositivo,function(i,dis){
			if($(dis).hasClass("Switch")){
				if(payload=="1"){
					$(dis).bootstrapToggle("on");
				}else{
					$(dis).bootstrapToggle("off");
				}
			}else if($(dis).hasClass("Sensor")){
				$(dis).html(payload);
			}else if($(dis).hasClass("Estado")){
				if(payload==1){
					$(dis).css("background-color","#12B500");
				}else{
					$(dis).css("background-color","#FF0004");
				}
			}else if($(dis).hasClass("tiempo")){
				var t = new Date();
				$(this).html(t.getTime());
				var lectura = document.getElementsByClassName(topic +" lectura");
				var tiempo = parseInt($(this).html());
				$(lectura).html(transcurrido(tiempo));
			}
		});
		
		$.each(monitorTopIn,function(i,topIn){
			if(topIn==topic){
				if(operacion[monitorCompare[i]](payload,monitorValIn[i])){
					if(monitorTopOut[i]!=""){
						sendMessage(monitorValOut[i],monitorTopOut[i]);
					}
					if(monitorMail[i]){
						console.log("correo");
					}
				}
			}
		});
	}
	
	function sendMessage(payload,topic){
		message = new Paho.MQTT.Message(payload);
		message.destinationName = topic;
		client.send(message);
	}
	
	function subscribeDevice(){
		if(conectado){
			$.each(subscribe,function(i,top){
				client.subscribe(top);
			});
		};
	}
	
	function unsubscribeDevice(){
		$.each(subscribe,function(i,top){
			client.unsubscribe(top);
		});
	}
	
	function actualizarTiempo(){
		$.each($("span.tiempo"),function(i, t){
			if($(t).html()!="0"){
				var clase = $(t).attr("class").split(" ");
				var lectura = document.getElementsByClassName(clase[1] +" lectura");
				$(lectura).html(transcurrido(parseInt($(t).html())));
			}
		})
	}
	
	function transcurrido(tdate) {
		var system_date = new Date(tdate);
		var user_date = new Date();
		var dif = Math.floor((user_date - system_date) / 1000);
		if (dif < 20) {return "Hace unos segundos";}
		if (dif < 40) {return "Hace menos de un minuto";}
		if (dif < 60) {return "Hace casi un minuto";}
		if (dif <= 90) {return "Hace mas de un minuto";}
		if (dif <= 3540) {return "Hace " + Math.round(dif / 60) + " minutos";}
		if (dif <= 5400) {return "Hace mas de 1 hora";}
		if (dif <= 86400) {return "Hace " + Math.round(dif / 3600) + " horas";}
		if (dif <= 129600) {return "Hace mas de 1 dia";}
		if (dif < 604800) {return "Hace " + Math.round(dif / 86400) + " dias";}
		if (dif <= 777600) {return "Hace mas de 1 semana";}
	}
	
	function fullScreen(){
		if (screenfull.enabled) {
			screenfull.toggle();
		}
	}
	
});