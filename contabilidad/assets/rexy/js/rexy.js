/**************************************************
    Sistema de contabilidad
    Desarrollador: Rexy Studios
    Año de creación: 2020
    Última modificación del archivo: 21-04-2020
**************************************************/
function obtener_frase(root = '') {
    $.getJSON( `${root}assets/json/frases.json`, function(frases) {
        numero_random = Math.round(Math.random() * frases.length);

        id_frase = (numero_random > 0)? numero_random-1 : numero_random
        frase = frases[id_frase]
        $("#frase_dicho").html(frase.cita)
        $("#frase_autor").html(frase.autor)
    })
}

function crear_selectpicker() {
    $("select").selectpicker()
}

function refrescar_selectpicker() {
    $("select").selectpicker('destroy');
    crear_selectpicker()
}

function crear_dataTable(id){
    $(`#${id}`).dataTable( {
        "language": {
            "lengthMenu"    : "Mostrando  _MENU_ ",
            "zeroRecords"   : "Nada que mostrar - disculpe!",
            "info"          : "Mostrando la página _PAGE_ de _PAGES_",
            "infoEmpty"     : "No se encontrarán registros",
            "infoFiltered"  : "(Filtrando _MAX_ del total de registros)",
            "search"        : "Buscar ",
            "paginate": {
                "previous"  : " Anterior ",
                "next"      : " Siguiente "
            }
        },
        "paging"            : true,
        "ordering"          : true,
        "order"             : [[0, 'desc']],
        "info"              : true,
        "fixedHeader"       : false
    });
}

function crear_datePicker(id){
    $(`#${id}`).datepicker( {
        format: 'dd-mm-yyyy',
        todayBtn: "linked",
        autoclose: true,
        autoSize: true,
        language: 'es',
        inline: true,
        sideBySide: true
    } )
}

function lanzar_msg(tipo = 1, titulo = '', texto = '', boton = '', boton_extra = '') {
    if(tipo == 1){ /* correcto */
        $("#msg_signo").addClass('fa-check-circle')
        $("#msg_modal").addClass("modal-success")
    }
    else if(tipo == 2){ /* error */
        $("#msg_signo").addClass('fa-times-circle')
        $("#msg_modal").addClass("modal-danger")
    }
    else if(tipo == 3){ /* informacion */
        $("#msg_signo").addClass('fa-times-circle')
        $("#msg_modal").addClass("modal-info")
    }
    else if(tipo == 4){ /* alerta */
        $("#msg_signo").addClass('fa-times-circle')
        $("#msg_modal").addClass("modal-warning")
    }
    else if(tipo == 5){ /* dark */
        $("#msg_signo").addClass('fa-times-circle')
        $("#msg_modal").addClass("modal-dark")
    }
    else if(tipo == 6){ /* default */
        $("#msg_signo").addClass('fa-times-circle')
        $("#msg_modal").addClass("modal-default")
    }

    $("#msg_titulo").html(titulo)
    $("#msg_texto").html(texto)
    $("#msg_boton").html(boton)
    
    if(boton_extra != ''){
        $("#msg_btn_extra").html(boton_extra)
    }
    
    $("#msg_modal").modal('show')
}

function addCommas(nStr) {
    nStr += '';
    let x = nStr.split('.');
    let x1 = x[0];
    let x2 = x.length > 1 ? '.' + x[1] : '';
    let rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}