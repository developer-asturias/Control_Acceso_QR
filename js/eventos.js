function consultar() {
  $(document).ready(function () {
    if ($.fn.dataTable.isDataTable("#lista_eventos")) {
      table = $("#lista_eventos").DataTable();
      table.destroy();
      dataTable();
    } else {
      dataTable();
    }
  });
}


//Consultar all
function dataTable() {
    let p = "ALL";
    $.ajax({
        url: "back-end/eventos.php",
        type: "GET",
        data: { p: p },  // Corregido: enviar como objeto con clave-valor
        dataType: 'json',  // Especificamos que esperamos JSON
        success: function(response) {
            console.log('Respuesta del servidor:', response);
            
            // Verificamos si hay un error en la respuesta
            if (response && response.error) {
                console.error('Error del servidor:', response.error);
                return;
            }
            
            // Verificamos que la respuesta sea un array
            if (!Array.isArray(response)) {
                console.error('La respuesta no es un array válido:', response);
                return;
            }

            // Si ya existe una instancia de DataTable, la destruimos
            if ($.fn.DataTable.isDataTable("#lista_eventos")) {
                $("#lista_eventos").DataTable().destroy();
            }

            // Inicializamos la tabla con los datos
            $("#lista_eventos").DataTable({
                data: response,
                columns: [
                    { data: "evento" },
                    { data: "lugar" },
                    { data: "direccion" },
                    { data: "fecha" },
                    { data: "estado" },
                    { data: "institucion" },
                    { data: "btn" }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Error en la petición AJAX:', status, error);
            console.error('Respuesta del servidor:', xhr.responseText);
        }
    });
}
//Registrar evento
function registrar() {
  let nom = $("#nombre").val(),
    lugar = $("#lugar").val(),
    fecha = $("#fecha").val(),
    hora = $("#hora").val(),
    direccion = $("#direccion").val(),
    error = "",
    link1 = $("#link1").val(),
    link2 = $("#link2").val(),
    institucion = $("#institucion").val();

  if (nom === "") {
    error += "Nombre del Evento<br>";
  }
  if (lugar === "") {
    error += "Lugar del Evento<br>";
  }
  if (direccion === "") {
    error += "Lugar del Evento<br>";
  }
  if (fecha === "") {
    error += "Fecha del Evento<br>";
  }
  if (hora === "") {
    error += "Fecha del Evento<br>";
  }
  if (link1 === "") {
    error += "Link Facebook<br>";
  }
  if (link2 === "") {
    error += "Link Youtube<br>";
  }
  if (institucion === "") {
    error += "Instituci車n<br>";
  }

  if (error != "") {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      html: 'Faltan los siguientes datos:<br>' + error
    });
  } else {
    var inputFile = document.getElementById("archivo");
    var file = inputFile.files[0];

    var data = new FormData();
    data.append("fileToUpload", file);
    data.append("nombre_e", nom);
    data.append("lugar_e", lugar);
    data.append("direccione", direccion);
    data.append("fecha_e", fecha);
    data.append("hora_e", hora);
    data.append("link1", link1);
    data.append("link2", link2);
    data.append("institucion", institucion);

    $.ajax({
      url: "back-end/eventos.php",
      type: "POST",
      data: data,
      contentType: false,
      cache: false,
      processData: false,
      success: function (response) {
        consultar();
        $("#form_evento").trigger("reset");
        Swal.fire({
          icon: 'success',
          title: 'Evento registrado',
          text: '¡El evento ha sido registrado satisfactoriamente!',
        });
        setTimeout(function () {
          $("#nuevo_evento").modal("hide");
        }, 2000);
      },
    });
  }
}

function cerrar(id) {
  let id_e = id;
  const postData = {
    id_evento: id_e,
  };
  $.post("back-end/eventos.php", postData, function (response) {
    console.log(response);
    consultar();
  });
}

//------------Detalles del evento-------------------
function detalles(id) {
  let detalle = "S";
  let id_evento = id;   
  $.ajax({
    url: "back-end/eventos.php",
    type: "GET",
    data: { detalle, id_evento },
    success: function (response) {
      let o = JSON.parse(response);
      console.log(o);
      $("#detalle_evento").dataTable({
        retrieve: true,
        data: o,
        columns: [
          { data: "alumno" },
          { data: "codigo" },
          { data: "titulo" },
          { data: "evento" },
          { data: "asistencia" },
          { data: "cupo" },
          { data: "aplico" },
        ],
      });
    },
  });
}

//------------Contadores del evento-------------------
function contadores(id) {
  let contador = "S";
  let id_eventoc = id;
  $.ajax({
    url: "back-end/eventos.php",
    type: "GET",
    data: { contador, id_eventoc },
    success: function (response) {
      let o = JSON.parse(response);
      console.log(o.asistencia);
      $("#asis_nro").html(o.asistencia);
      $("#inv_nro").html(o.invitados);
    },
  });
}
