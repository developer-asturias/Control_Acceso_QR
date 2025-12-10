function confirmar(){
	let qr = $('#qrcode').val();
	let evento = $('#evento').val();
	let template = '';
	if (qr == '') {
		template +=`
        <div class="alert alert-danger" role="alert" id="alerta_add">
            <strong>Error!</strong> Por favor llene el campo de QR
        </div>`;
        $('#result').html(template);
	}else{
		$('#result').html('');
		consulta(qr, evento);
		$('#confirma_ingreso').modal("show");
	}
}

function asistencia(){
	let qr = $('#qrcode').val();
	let evento = $('#evento').val();
	let template = '';
	if (qr == '') {
		template +=`
        <div class="alert alert-danger" role="alert" id="alerta_add">
            <strong>Error!</strong> Por favor llene el campo de QR
        </div>`;
        $('#result').html(template);
	}else{
		let indice = $('#qrcode').val();
    	const postData = {indice_a: indice, evento: evento};
            
        $.post('back-end/inicio.php', postData, function(response){
        	console.log('Respuesta:', response);
            $('#qrcode').val('');
            document.getElementById("qrcode").focus();
            
            try {
                let data = typeof response === 'string' ? JSON.parse(response) : response;
                
                if(data.code == 1){
                    template +=`
                <div class="alert alert-success" role="alert" id="alerta_add">
                    <strong>Exito!</strong> Asistencia Confirmada ${data.nombre}
                </div>`;
                }
                else if(data.code == 0){
                    template +=`
                <div class="alert alert-danger" role="alert" id="alerta_add">
                    <strong>Aviso!</strong> Ya cuenta con registro de asistencia. ${data.nombre}
                </div>`;
                }
                else if(data.code == 2){
                    template +=`
                <div class="alert alert-danger" role="alert" id="alerta_add">
                    <strong>Error!</strong> Indice no relacionado al evento o no existe.
                </div>`;
                }
                else if(data.error){
                    template +=`
                <div class="alert alert-danger" role="alert" id="alerta_add">
                    <strong>Error!</strong> ${data.error}
                </div>`;
                }
                
            } catch(e) {
                console.error('Error al procesar respuesta:', e);
                template +=`
                <div class="alert alert-danger" role="alert" id="alerta_add">
                    <strong>Error!</strong> Error al procesar la respuesta del servidor
                </div>`;
            }
            
        $('#result').html(template);
        setTimeout(function(){ $('#alerta_add').alert('close'); }, 5000);
        }, 'json');
	}
}

function leerqr(){
    $('#leer_qr').modal("show");
}

function consulta(id, evento){
	let indice = id;
	let id_evento = evento;
	$.ajax({
        url: 'back-end/inicio.php',
        type: 'GET',
        data: {indice, id_evento},
        dataType: 'json',
        success: function(o){
            console.log('Consulta exitosa:', o);
            $('#nombre').val(o.alumno || '');
            $('#indice').val(o.indice || '');
            $('#titulo').val(o.titulo || '');
        	$('#resultado').html(o.resultado || '');
        	let btn = o.btn || 0;
        	if (btn === 0) {
        		document.getElementById('boton').style.display = 'none';
        		document.getElementById('botonc').style.display = 'block';
        	}else{
        		document.getElementById('boton').style.display = 'block';
        		document.getElementById('botonc').style.display = 'none';
        	}
        },
        error: function(xhr, status, error){
            console.error('Error en consulta:', error);
            console.error('Response:', xhr.responseText);
        }
    })
}

function registrar(){
	let ind = $('#indice').val();
	let qr = $('#qrcode').val();
	let evento = $('#evento').val();
	const postData = {indice: ind, evento: evento};
    
    // CORREGIDO: Usar $.ajax en lugar de $.post para capturar errores
    $.ajax({
        url: 'back-end/inicio.php',
        type: 'POST',
        data: postData,
        dataType: 'json',
        success: function(response){
            console.log('Respuesta registrar:', response);
            
            // Mostrar mensaje de éxito
            let template = `
                <div class="alert alert-success" role="alert" id="alerta_add">
                    <strong>Éxito!</strong> ${response.message || 'Registro exitoso'}
                </div>`;
            $('#resultado').html(template);
            
            // Cerrar el modal después de 1.5 segundos
            setTimeout(function(){
                $("#confirma_ingreso").modal("hide");
                $('#form_confirma').trigger('reset');
                $('#qrcode').val('');
                document.getElementById("qrcode").focus();
                
                // Mostrar mensaje en la página principal
                let resultTemplate = `
                    <div class="alert alert-success" role="alert" id="alerta_add">
                        <strong>Éxito!</strong> ${response.message || 'Registro exitoso'}. Escanee el QR nuevamente para el siguiente invitado.
                    </div>`;
                $('#result').html(resultTemplate);
                setTimeout(function(){ 
                    $('#result').html(''); 
                }, 4000);
            }, 1500);
        },
        error: function(xhr, status, error){
            console.error('Error al registrar:', error);
            console.error('Response:', xhr.responseText);
            
            // Capturar el error del backend
            let errorMsg = 'Error desconocido';
            try {
                let errorData = JSON.parse(xhr.responseText);
                errorMsg = errorData.error || errorMsg;
            } catch(e) {
                errorMsg = xhr.responseText || errorMsg;
            }
            
            // Mostrar el error en el modal
            let template = `
                <div class="alert alert-danger" role="alert" id="alerta_add">
                    <strong>Error!</strong> ${errorMsg}
                </div>`;
            $('#resultado').html(template);
            
            // Ocultar el botón de confirmar
            document.getElementById('boton').style.display = 'none';
            document.getElementById('botonc').style.display = 'block';
        }
    });
}

function cerrar(){
	$("#confirma_ingreso").modal("hide");
	$('#form_confirma').trigger('reset');
    $('#qrcode').val('');
    document.getElementById("qrcode").focus();
}

function list_eventos(){
    $.ajax({
        url: 'back-end/eventos.php',
        type: 'GET',
        data: {list: 1},
        dataType: 'json',
        success: function(datas){
            console.log('Eventos cargados:', datas);
            let template = '<option value="">Seleccione Evento......</option>';
            datas.forEach(data =>{
                template +=`
                <option value="${data.id}">${data.evento}</option>
                `
            });
            $('#evento').html(template);
        },
        error: function(xhr, status, error){
            console.error('Error al cargar eventos:', error);
            console.error('Response:', xhr.responseText);
        }
    })
}