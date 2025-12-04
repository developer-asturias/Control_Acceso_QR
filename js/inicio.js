
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
	//console.log(qr + ' - ' + evento)
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
        	//console.log(response);
            $('#qrcode').val('');
            document.getElementById("qrcode").focus();
            let text = response;
            console.log(text.substring(0,1) + ' -> ');
            console.log(text.substring(2) + ' -> ');
            let numero_v = text.substring(0,1);
            let nombre_est = text.substring(1);
            
            
            if(numero_v == 1){
                template +=`
            <div class="alert alert-success" role="alert" id="alerta_add">
                <strong>Exito!</strong> Asistencia Confirmada ${nombre_est}
            </div>`;
            }
            if(numero_v == 0){
                template +=`
            <div class="alert alert-danger" role="alert" id="alerta_add">
                <strong>Aviso!</strong> Ya cuenta con registro de asistencia. ${nombre_est}
            </div>`;
            }
            if(numero_v == 2){
                template +=`
            <div class="alert alert-danger" role="alert" id="alerta_add">
                <strong>Error!</strong> Indice no relacionado al evento o no existe.
            </div>`;
            }
            
        $('#result').html(template);
        setTimeout(function(){ $('#alerta_add').alert('close'); }, 5000);
        });
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
        success: function(response){
            console.log('Respuesta consulta:', response);
            
            if (response.error) {
                console.error('Error:', response.error);
                return;
            }
            
            $('#nombre').val(response.alumno);
            $('#indice').val(response.indice);
            $('#titulo').val(response.titulo);
        	$('#resultado').html(response.resultado);
        	
        	let btn = response.btn;
        	if (btn === 0) {
        		document.getElementById('boton').style.display = 'none';
        		document.getElementById('botonc').style.display = 'block';
        	}else{
        		document.getElementById('boton').style.display = 'block';
        		document.getElementById('botonc').style.display = 'none';
        	}
        },
        error: function(xhr, status, error){
            console.error('Error AJAX consulta:', status, error);
            console.error('Respuesta servidor:', xhr.responseText);
        }
    })
}

function registrar(){
	let ind = $('#indice').val();
	const postData = {indice: ind};
        
    $.post('back-end/inicio.php', postData, function(response){
    	console.log('Respuesta registrar:', response);
        
        try {
            let result = JSON.parse(response);
            if (result.success) {
                let template = `<div class="alert alert-success" role="alert" id="alerta_add">
                    <strong>Éxito!</strong> ${result.message}
                </div>`;
                $('#result').html(template);
                setTimeout(function(){ $('#alerta_add').alert('close'); }, 3000);
            }
        } catch(e) {
            console.error('Error al parsear respuesta:', e);
        }
        
        $("#confirma_ingreso").modal("hide");
        $('#form_confirma').trigger('reset');
        $('#qrcode').val('');
        document.getElementById("qrcode").focus();
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
        success: function(response){
            console.log('Respuesta eventos:', response);
            
            if (response && response.error) {
                console.error('Error eventos:', response.error);
                return;
            }

            if (!Array.isArray(response)) {
                console.error('Respuesta no es un array:', response);
                return;
            }

            let template = '<option value="">Seleccione Evento......</option>';
            response.forEach(data => {
                template += `<option value="${data.id}">${data.evento}</option>`;
            });
            $('#evento').html(template);
        },
        error: function(xhr, status, error){
            console.error('Error AJAX list_eventos:', status, error);
            console.error('Respuesta servidor:', xhr.responseText);
        }
    })
}
