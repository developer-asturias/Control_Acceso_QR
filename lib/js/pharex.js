//https://routeintegrationapi.satrack.com/api/v1/routes/downloadimage/26b89803-b249-4f7e-a92e-b35aae9c676c.jpg

consultar();
//Consultar all
function consultar(){
    let listado=0;
    $(document).ready(function(){
        let table = $('#listado_pharex').DataTable();
        table.destroy();    
    })
    
    $.ajax({
        url: 'back-end/pharex.php',
        type: 'GET',
        data: {listado},
        success: function(response){
            let o = JSON.parse(response);
            //console.log(o);
            $('#listado_pharex').dataTable( {
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
        url: 'back-end/pharex.php',
        type: 'GET',
        data: {listado},
        success: function(response){
            let o = JSON.parse(response);
            o.forEach(data =>{
                let documento = 'G' + data.remesa;
                toket(documento); 
            }); 
        }
    })
    consultar()
}
toket("G64130");
function toket(doc){
    let ped = doc;
    var to= '';
    const postData = {
        grant_type: 'client_credentials',
        client_id: 'deliverymgmt-pharex',
        client_secret: 'b790e89c-4788-4c8d-bcf3-26b3a96c8a92'
    };  
    $.ajax({
        url: 'http://securityprovider.satrack.com:8080/auth/realms/satrack-base/protocol/openid-connect/token',
        type: 'POST',
        dataType : "json",
        beforeSend: function(request) {request.setRequestHeader("Content-Type", 'application/x-www-form-urlencoded');},
        data: postData,
        success: function(response){
            let o = (response);
            //console.log(o.access_token)
            to = 'bearer ' + o.access_token;
            //Consultamos si existe el documento
            $.ajax({
                url: 'https://routeintegrationapi.satrack.com/api/v1/routes/tracking/'+ped+'/102015',
                type: 'GET',
                dataType : "json",
                beforeSend: function(request) {request.setRequestHeader("Authorization", to);},
                error: function(xhr, status, error) {
                  console.log('No se encontro documento')
                },
                success: function(response){
                    let d = response;
                    console.log(d);
                }
            }) 
        }
    })   
}
