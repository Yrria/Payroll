<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // FOR MESSAGEBOX
    function alertMessage(redirectUrl, title, text, icon, iconHtml) {
        Swal.fire({
            icon: icon,
            iconHtml: iconHtml, // Custom icon using Font Awesome
            title: title,
            text: text,
            customClass: {
                popup: 'swal-custom'
            },
            showConfirmButton: true,
            confirmButtonColor: '#4CAF50',
            confirmButtonText: 'OK',
            timer: 5000
        }).then(() => {
            window.location.href = redirectUrl; // Redirect to the desired page
        });
    }

    // WARNING FOR DUPE ACCOUNT
    function warningError(title, text, icon, iconHtml) {
        Swal.fire({
            icon: icon,
            iconHtml: iconHtml, // Custom icon using Font Awesome
            title: title,
            text: text,
            customClass: {
                popup: 'swal-custom'
            },
            showConfirmButton: true,
            confirmButtonColor: '#4CAF50',
            confirmButtonText: 'OK',
            timer: 5000,
        });
    }

     // NO LINK
    function alertMessageNolink(title, text, icon, iconHtml) {
    Swal.fire({
        icon: icon,
        iconHtml: iconHtml, // Custom icon using Font Awesome
        title: title,
        text: text,
        customClass: {
            popup: 'swal-custom'
        },
        showConfirmButton: true,
        confirmButtonColor: '#4CAF50',
        confirmButtonText: 'OK',
        timer: 5000
    });
}
</script>