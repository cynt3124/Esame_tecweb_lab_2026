document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('projectForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return; // blocca l'AJAX
        }
        form.classList.add('was-validated');

        const $form = $(this);
        const $btn = $form.find('button[type="submit"]');
        const url = $form.attr('action');

        $btn.prop('disabled', true); // Evita click doppi

        $.ajax({
            url: url,
            method: 'POST',
            data: $form.serialize(),
            success: function (res) {
                if (res.success) {
                    const row = `
                        <tr>
                            <td>${res.data.id}</td>
                            <td>${res.data.name}</td>
                            <td>${res.data.site_name}</td>
                            <td>${res.data.department }</td>
                            <td>${res.data.created_at}</td>
                            <td>${res.data.updated_at}</td>
                            <td>
                                <a href="{{ route('employee-project.edit', [$employee->id, $project->id]) }}" class="btn btn-secondary btn-sm">
                                    <i class="bi bi-pencil-fill"></i>&nbsp;Edit
                                </a>

                                <a href="{{ route('employee-project.create', ['project_id' => $project->id])  }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-person-add"></i>&nbsp;Add Employee
                                </a>
                            </td>
                        </tr>`;

                    // Aggiunge la riga in cima alla tabella
                    $('#projectTbody').append(row);

                    // Reset form e chiusura collapse
                    $form[0].reset();
                    form.classList.remove('was-validated');

                    const collapseEl = document.getElementById('Collapse');
                    collapseEl.classList.remove('show');
                }
            },
            error: function (xhr) {
                // Gestione errori validazione
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    alert("Errore: " + Object.values(errors).flat().join('\n'));
                } else {
                    alert("Si è verificato un errore imprevisto.");
                }
            },
            complete: function() {
                $btn.prop('disabled', false);
            }
        });
    });
});