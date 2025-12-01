function consultar(){
    if ( $.fn.dataTable.isDataTable( '#lista_eventos' ) ) {
        table = $('#lista_eventos').DataTable();
        table.destroy();
    }
    dataTable();
}

$(document).ready(function(){
    consultar();
});
//Consultar all
function dataTable(){
    $.ajax({
        url: 'back-end/alumnos.php',
        type: 'GET',
        data: { page: 1 },
	    dataType: 'json',
	    success: function(response){
	        console.log('Respuesta alumnos (lista):', response);

	        if (response && response.error) {
	            console.error('Error alumnos:', response.error);
	            return;
	        }

	        if (!Array.isArray(response)) {
	            console.error('Respuesta no es un array:', response);
	            return;
	        }

	        var o = response;
	        console.log('Registros a mostrar:', o.length);
	        
	        $('#lista_eventos').DataTable({
	            retrieve: true,
	            data : o,
	            serverSide: false,
	            paging: true,
	            pageLength: 25,
	            columns: [
	                {"data" : "alumno"}, 
	                {"data" : "indice"},
	                {"data" : "titulo"},
	                {"data" : "email"},
	                {"data" : "evento"},
	                {"data" : "cupo"},
	                {"data" : "btn", "orderable": false, "searchable": false}
	            ]
	        }); 
	    },
	    error: function(xhr, status, error){
	        console.error('Error AJAX dataTable:', status, error);
	        console.error('Respuesta servidor:', xhr.responseText);
	    }
    })  
}

//Registrar evento
function registrar(){
	let cod = $('#codigo').val(), indice =$('#indice').val(), error='';
    let nom =$('#nombre').val(), titulo =$('#titulo').val(), email =$('#email').val(), evento =$('#evento').val();

    if(cod =='' || indice =='' || nom =='' || titulo =='' || email =='' || evento ==''){error+='error';} 

    let template='';
    if(error!=''){
        template +=`
        <div class="alert alert-danger" role="alert" id="alerta_add">
            <strong>Error!</strong> Faltan datos en el formulario
        </div>`;
        $('#result').html(template);
        setTimeout(function(){ $('#alerta_add').alert('close'); }, 2000);
    }else{
        const postData = {
            nombre: nom,
            codigo: cod,
            indice: indice,
            titulo: titulo,
            email: email,
            id_evento: evento
        };
        
        $.ajax({
            url: 'back-end/alumnos.php',
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(response){
                console.log('Respuesta registrar alumno:', response);
                let templateRes = '';
                if (response.error) {
                    templateRes +=`
                    <div class="alert alert-danger" role="alert" id="alerta_add">
                        <strong>Error!</strong> ${response.error}
                    </div>`;
                } else if (response.success) {
                    templateRes +=`
                    <div class="alert alert-success" role="alert" id="alerta_add">
                        ${response.message}
                    </div>`;
                    consultar();
                    $('#form_alumno').trigger('reset');
                }
                $('#result').html(templateRes);
                setTimeout(function(){ $('#alerta_add').alert('close'); $("#nuevo_alumno").modal("hide");}, 2000);
            },
            error: function(xhr, status, error){
                console.error('Error AJAX registrar alumno:', status, error);
                console.error('Respuesta servidor:', xhr.responseText);
            }
        });  
    }
}

function list_eventos(){
    let list = 0;
    $.ajax({
        url: 'back-end/eventos.php',
        type: 'GET',
        data: {list},
	    dataType: 'json',
	    success: function(response){
	        let datas = response;
	        //console.log(datas);

            let template = '<option value="">Seleccione Opcion......</option>';
            datas.forEach(data =>{
                template +=`
                <option value="${data.id}">${data.evento}</option>
                `
            });
            $('#evento').html(template);
        }
    })
}

function buscar(id){
    let id_alumno = id;
    console.log(id_alumno)
    $('#id_alumno').val(id_alumno);
    $.ajax({
        url: 'back-end/alumnos.php',
        type: 'GET',
        data: {id_alumno},
	    dataType: 'json',
	    success: function(response){
	        if (response.error) {
	            console.error('Error obtener alumno:', response.error);
	            return;
	        }
	        let datas = response;
	        console.log(datas.alumno);
	        $('#nombre_e').val(datas.alumno);
	        $('#codigo_e').val(datas.codigo);
	        $('#indice_e').val(datas.indice);
	        $('#titulo_e').val(datas.titulo);
	        $('#email_e').val(datas.email);
	        $('#cupo_e').val(datas.cupo);

            $('#editar_alumno').modal("show");
        }
    })
    
}

function actualizar(){
    let cod = $('#codigo_e').val(), c_exp = $('#ciudad_exp_e').val(), indice =$('#indice_e').val(), error='';
    let nom =$('#nombre_e').val(), titulo =$('#titulo_e').val(), email =$('#email_e').val(), cupo = $('#cupo_e').val(), id_alumno = $('#id_alumno').val();

    if(cod =='' || c_exp =='' || indice =='' || nom =='' || titulo =='' || email =='' || cupo ==''){error+='error';} 
    let template='';
    if(error!=''){
        template +=`
        <div class="alert alert-danger" role="alert" id="alerta_add">
            <strong>Error!</strong> Faltan datos en el formulario
        </div>`;
        $('#result').html(template);
        setTimeout(function(){ $('#alerta_add').alert('close'); }, 2000);
    }else{
        const postData = {
            id_alumno: id_alumno,
            nombre: nom,
            // codigo_e y ciudad_exp ya no los usa el backend JSON
            indice: indice,
            titulo: titulo,
            email: email,
            cupo: cupo
        };
        
        $.ajax({
            url: 'back-end/alumnos.php',
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(response){
                console.log('Respuesta actualizar alumno:', response);
                let templateRes = '';
                if (response.error) {
                    templateRes +=`
                    <div class="alert alert-danger" role="alert" id="alerta_add">
                        <strong>Error!</strong> ${response.error}
                    </div>`;
                } else if (response.success) {
                    templateRes +=`
                    <div class="alert alert-success" role="alert" id="alerta_add">
                        ${response.message}
                    </div>`;
                    consultar();
                    $('#form_alumno').trigger('reset');
                }
                $('#resulte').html(templateRes);
                setTimeout(function(){ $('#alerta_add').alert('close'); $("#editar_alumno").modal("hide");}, 2000);
            },
            error: function(xhr, status, error){
                console.error('Error AJAX actualizar alumno:', status, error);
                console.error('Respuesta servidor:', xhr.responseText);
            }
        });  
    }
}