document.querySelectorAll(".delete-form").forEach(form => {
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        Swal.fire({
            title: "Sei sicuro?",
            text: "Procedere alla cancellazione? L'azione è irreversibile e il record sarà definitivamente eliminato dal sistema",
            icon: "error",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            confirmButtonText: "Si, procedi",
            cancelButtonText: "No, annulla",
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});