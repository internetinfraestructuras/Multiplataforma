
function alerta(titulo, texto, icono, botonSi, botonNo, funcionAceptar, funcionCancelar) {

    const swalWithBootstrapButtons = swal.mixin({
        confirmButtonClass: 'btn btn-success next',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: true
    });

    swalWithBootstrapButtons({
        title: titulo,
        html: texto,
        type: icono,
        showCancelButton: true,
        confirmButtonText: botonSi,
        cancelButtonText: botonNo,
        reverseButtons: true,
        width: 500
    }).then((result) => {
        if (result.value) {
            self[funcionAceptar]();
        } else if (result.dismiss === swal.DismissReason.cancel) {
            self[funcionCancelar]();
        }
    })
}

function alertaOk(titulo, texto, icono, botonSi, funcion) {

    const swalWithBootstrapButtons = swal.mixin({
        confirmButtonClass: 'btn btn-success',
        buttonsStyling: true
    });

    swalWithBootstrapButtons({
        title: titulo,
        html: texto,
        type: icono,
        confirmButtonText: botonSi,
        reverseButtons: true,
        width: 500
    }).then((result) => {
        if (result.value && funcion!='') {
            self[funcion]();
        }
    })
}

function alerta2(titulo, texto, icono, pie) {

    swal({
        type: icono,
        title: titulo,
        html: texto,
        footer: pie
    })
}

function confirmacion(texto, tiempo) {
    swal({
        position: 'top-end',
        type: 'success',
        title: texto,
        showConfirmButton: false,
        timer: tiempo
    });
}

function error(texto, tiempo) {
    swal({
        position: 'top-end',
        type: 'error',
        title: texto,
        showConfirmButton: false,
        timer: tiempo
    });
}




function alertaTiempo(titulo, texto, tiempo) {
    let timerInterval;
    swal({
        title: titulo,
        html: 'Por favor espere... <strong></strong>',
        timer: tiempo,
        onOpen: () => {
            swal.showLoading();
            timerInterval = setInterval(() => {
                swal.getContent().querySelector('strong')
                    .textContent = swal.getTimerLeft()
            }, 100)
        },
        onClose: () => {
            clearInterval(timerInterval)
        }
    }).then((result) => {
        if (
            // Read more about handling dismissals
            result.dismiss === swal.DismissReason.timer
        ) {
        }
    })
}