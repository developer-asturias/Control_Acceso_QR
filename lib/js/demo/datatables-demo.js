// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable({
    "language": {
            "lengthMenu": "Ver _MENU_ registros",
            "zeroRecords": "No se encontro ningun dato",
            "info": "",
            "infoEmpty": "No records available",
            "infoFiltered": "",
            "search": "Buscar:",
            "paginate": {
              "first": "Primero",
              "last": "Ultimo",
              "next": "Siguiente",
              "previous": "Anterior"
          }
        }
  });
  $('#user').html(user);
  //console.log(user);
});
