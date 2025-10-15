consultar();
//Consultar all
function consultar(){
    let listado=0;
    $(document).ready(function(){
        let table = $('#listado_open').DataTable();
        table.destroy();    
    })
    
    $.ajax({
        url: 'back-end/open.php',
        type: 'GET',
        data: {listado},
        success: function(response){
            let o = JSON.parse(response);
            //console.log(o);
            $('#listado_open').dataTable( {
                retrieve: true,
                data : o,
                columns: [
                    {"data" : "remesa"},                                                 
                    {"data" : "pedido"},                                           
                    {"data" : "cliente"},                                           
                    {"data" : "direccion"}                                           
                ]
            });
        }
    })
}

function procesar(){
    let listado=0;    
    $.ajax({
        url: 'back-end/open.php',
        type: 'GET',
        data: {listado},
        success: function(response){
            let o = JSON.parse(response);
            o.forEach(data =>{
                let documento =data.pedido
                $.ajax({
                    url: 'back-end/open.php',
                    type: 'GET',
                    data: {documento},
                    success: function(response){
                        console.log(response)
                    }
                })  
            }); 
            consultar();
        }
    })
}
