consultar();
//Consultar all
function consultar(){
    $.ajax({
        url: 'back-end/articulos.php',
        type: 'GET',
        success: function(response){
            let datas = JSON.parse(response);
            //console.log(datas);
            let template = '';
            datas.forEach(data =>{
                template +=`
                <tr>
                    <td>${data.id}</td>
                    <td>${data.name}</td>
                    <td>${data.presentacion}</td>
                    <td>${data.forma}</td>
                    <td>${data.precio}</td>
                    <td>
                    <a href="#" onclick='buscar(${data.id})' class="btn btn-primary btn-sm" title="Modificar" data-toggle="modal" data-target="#editar_articulo"><i class="fas fa-edit" ></i></a>
                    <a href="#" onclick='eliminar(${data.id})' class="btn btn-danger btn-sm" title="Eliminar"><i class="fas fa-times" ></i></a>
                    </td>
                </tr>
                `
            });
            $('#listado').html(template);
        }
    })
}

//Registrar
function registrar(){
    let nom = $('#nombre').val(), presentacion = $('#presentacion').val(), forma =$('#forma').val().toUpperCase(), precio = $('#precio').val(), cliente = $('#list_cliente').val(), error='';

    if(nom===''){ error+='Nombre<br>'; } if(presentacion===''){ error+='Presentacion<br>'; }
    let template='';
    if(error!=''){
        template +=`
        <div class="alert alert-danger" role="alert" id="alerta_add">
            <strong>Error!</strong> Faltan los sigueinte datos<br>${error}
        </div>`;
        $('#result').html(template);
    }else{
        const postData = {
            nombre: $('#nombre').val().toUpperCase(),
            presentacion: $('#presentacion').val(),
            forma: $('#forma').val().toUpperCase(),
            precio: $('#precio').val(),
            cliente: $('#list_cliente').val()
        };
        
        $.post('back-end/articulos.php', postData, function(response){
            consultar();
            $('#form_articulo').trigger('reset');
            $('#result').html(response);
            setTimeout(function(){ $('#alerta_add').alert('close'); }, 3000);
        });  
    }
     
}

//Actualizar
function editar(){
    const postData = {
        nombre: $('#nombre_e').val(),
        presentacion: $('#presentacion_e').val(),
        forma: $('#forma_e').val(),
        precio: $('#precio_e').val(),
        id: $('#id_articulo').val()
    };
    $.post('back-end/articulos.php', postData, function(response){
        consultar();
        $('#result_edit').html(response);
        setTimeout(function(){ $('#alerta1').alert('close'); }, 3000);
    });  
}

//Eliminar
function eliminar(id){
    if(confirm('Seguro que desea borrar el registro?')){
        let id_articulo = id;
        $.ajax({
            url: 'back-end/articulos.php?id_articulo='+id_articulo,
            type: 'DELETE',
            data: {id_articulo},
            success:function(response){
                $('#resultado').html(response);
                consultar();
                setTimeout(function(){ $('#alerta_delete').alert('close'); }, 3000);
                //console.log(response);
            }
        }); 
        
    } 
}

//Buscar
function buscar(id){
    let id_articulo = id;
    $.ajax({
        url: 'back-end/articulos.php',
        type: 'GET',
        data: {id_articulo},
        success:function(response){
            const data = JSON.parse(response);
            $('#nombre_e').val(data.name);
            $('#presentacion_e').val(data.presentacion);
            $('#forma_e').val(data.forma);
            $('#precio_e').val(data.precio);
            $('#id_articulo').val(data.id);
        }
    });  
}

//Listado Clientes
function list_clientes(){
    $.ajax({
        url: 'back-end/clientes.php',
        type: 'GET',
        success: function(response){
            let datas = JSON.parse(response);
            console.log(datas);
            let template = '<option value="">Seleccione Opcion......</option>';
            datas.forEach(data =>{
                template +=`
                <option value="${data.id}">${data.name}</option>
                `
            });
            $('#list_cliente').html(template);
        }
    })
}
