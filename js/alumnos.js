function consultar(){
	$(document).ready(function(){
	    if ( $.fn.dataTable.isDataTable( '#lista_eventos' ) ) {
	        table = $('#lista_eventos').DataTable();
	        table.destroy();
	        dataTable();
	    }else{
	        dataTable();
	    }
	})
}
consultar();
//Consultar all
function dataTable(){
	let p = 'ALL';
    $.ajax({
        url: 'back-end/alumnos.php',
        type: 'GET',
        data: {p},
        success: function(response){
            let o = JSON.parse(response);
            $('#lista_eventos').dataTable( {
                retrieve: true,
                data : o,
                columns: [
                    {"data" : "alumno"}, 
                    {"data" : "indice"},
                    {"data" : "titulo"},
                    {"data" : "email"},
                    {"data" : "evento"},
                    {"data" : "cupo"},
                    {"data" : "btn"}
                            
                ]
            });
        
        }
    })
}

//Registrar evento
function registrar(){
	let cod = $('#codigo').val(), indice =$('#indice').val(), error='';
    let nom =$('#nombre').val(), titulo =$('#titulo').val(), email =$('#email').val(), evento =$('#evento').val();

    if(cod =='' || c_exp =='' || indice =='' || nom =='' || titulo =='' || email =='' || evento ==''){error+='error';} 
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
        
        $.post('back-end/alumnos.php', postData, function(response){
            consultar();
            $('#form_alumno').trigger('reset');
            //console.log(response)
            $('#result').html(response);
            setTimeout(function(){ $('#alerta_add').alert('close'); $("#nuevo_alumno").modal("hide");}, 2000);
        });  
    }
}

function list_eventos(){
    let list = 0;
    $.ajax({
        url: 'back-end/eventos.php',
        type: 'GET',
        data: {list},
        success: function(response){
            let datas = JSON.parse(response);
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
        success: function(response){
            let datas = JSON.parse(response);
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
            codigo_e: cod,
            ciudad_exp: c_exp,
            indice: indice,
            titulo: titulo,
            email: email,
            cupo: cupo
        };
        
        $.post('back-end/alumnos.php', postData, function(response){
            consultar();
            $('#form_alumno').trigger('reset');
            //console.log(response)
            $('#resulte').html(response);
            setTimeout(function(){ $('#alerta_add').alert('close'); $("#editar_alumno").modal("hide");}, 2000);
        });  
    }
}