document.addEventListener('DOMContentLoaded', function () {

    // CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    document.addEventListener('click', function (event) {
        const button = event.target.closest('.btn-delete');
        if (!button) return;

        const url = button.getAttribute('data-url');

        Swal.fire({
            title: 'Sei sicuro?',
            text: "L'azione è irreversibile e il record sarà definitivamente eliminato dal sistema.",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Si, procedi',
            cancelButtonText: 'No, annulla',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    success: function (response) {
                        button.closest('tr').remove();
                    },
                    error: function (xhr, status, error) {
                        console.error('Errore:', error);
                    }
                });
            }
        });

        /*Swal.fire({
            title: 'Sei sicuro?',
            text: "L'azione è irreversibile e il record sarà definitivamente eliminato dal sistema.",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Si, procedi',
            cancelButtonText: 'No, annulla',
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        button.closest('tr').remove();
                    }
                })
                .catch(error => console.error('Errore:', error));
            }
        });*/
    });
});