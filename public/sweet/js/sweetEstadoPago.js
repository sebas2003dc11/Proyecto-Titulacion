 // para el estado de pago
 $(document).on("click", "a.pagar", function(event) {
    event.preventDefault(); // Evita que se siga el enlace
    var form = $(this).prev(".formulario-pagar"); // Obtiene el formulario anterior al botón
    var url = form.attr("action"); // Obtiene la URL de la ruta de eliminación
    var csrf_token = $('meta[name="csrf-token"]').attr("content"); // Obtiene el token CSRF

    Swal.fire({
        title: "¿Está seguro de realizar el pago?",
        // text: "¡ Se cambiará el estado  !",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2CB073",
        cancelButtonColor: "#d33",
        confirmButtonText: "<i class='fas fa-check'></i> Si, Pagar",
        cancelButtonText: "<i class='fas fa-times'></i> No, Salir",
        reverseButtons: true,
        padding: "20px",
        backdrop: true,
        position: "top",
        allowOutsideClick: true,
        allowEscapeKey: true,
        allowEnterKey: false,
    }).then((confirm) => {
        if (confirm.isConfirmed) {
            // Agrega el token CSRF al formulario
            form.append(
                ''
            );
            form.submit(); // Envía el formulario
        }
    });
});



 // para el estado de pago
 $(document).on("click", "a.noPagar", function(event) {
    event.preventDefault(); // Evita que se siga el enlace
    var form = $(this).prev(".formulario-noPagar"); // Obtiene el formulario anterior al botón
    var url = form.attr("action"); // Obtiene la URL de la ruta de eliminación
    var csrf_token = $('meta[name="csrf-token"]').attr("content"); // Obtiene el token CSRF

    Swal.fire({
        title: "¿Está seguro de eliminar el pago?",
        // text: "¡ Se cambiará el estado  !",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2CB073",
        cancelButtonColor: "#d33",
        confirmButtonText: "<i class='fas fa-check'></i> Si, Eliminar",
        cancelButtonText: "<i class='fas fa-times'></i> No, Salir",
        reverseButtons: true,
        padding: "20px",
        backdrop: true,
        position: "top",
        allowOutsideClick: true,
        allowEscapeKey: true,
        allowEnterKey: false,
    }).then((confirm) => {
        if (confirm.isConfirmed) {
            // Agrega el token CSRF al formulario
            form.append(
                ''
            );
            form.submit(); // Envía el formulario
        }
    });
});