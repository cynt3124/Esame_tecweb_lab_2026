const validationMessages = {
    required: "Questo campo è obbligatorio",
    number: "Inserire un numero valido",
    maxlength: "Il valore inserito supera la lunghezza massima consentita",
    minlength: "Il valore inserito è inferiore alla lunghezza minima consentita",
    pattern: "Numero di caratteri inferiore ad 8, valore non valido"
};

// Intercetta il submit del form
const forms = document.querySelectorAll('.needs-validation')

  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })

// Personalizza i messaggi di errore
document.querySelectorAll("input, select").forEach(input => {
    input.addEventListener("invalid", function (event) {
        event.preventDefault();

        let message = input.validationMessage;

        if (input.validity.valueMissing) {
            message = validationMessages.required;
        } else if (input.validity.badInput) {
            message = validationMessages.number;
        } else if (input.validity.tooLong) {
            message = validationMessages.maxlength;
        } else if (input.validity.tooShort) {
            message = validationMessages.minlength;
        } else if (input.validity.patternMismatch) {
            message = validationMessages.pattern;
        }

        const feedback = input.nextElementSibling;
        if (feedback) {
            feedback.textContent = message;
        }
    });
});

/*const forms = document.querySelectorAll('.needs-validation')

  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  });*/