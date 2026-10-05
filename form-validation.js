(() => {
    const messageFor = (field) => {
        const v = field.validity;
        if (v.valueMissing) return field.type === 'file' ? 'Please choose a file.' : 'Please fill out this field.';
        if (v.typeMismatch) return field.type === 'email' ? 'Enter an email address in the format name@example.com.' : 'Enter a valid value.';
        if (v.patternMismatch) return field.title || 'Please match the requested format.';
        if (v.tooShort) return `Use at least ${field.minLength} characters.`;
        if (v.tooLong) return `Use no more than ${field.maxLength} characters.`;
        if (v.rangeUnderflow) return `Choose a value on or after ${field.min}.`;
        if (v.rangeOverflow) return `Choose a value on or before ${field.max}.`;
        if (v.badInput) return 'Enter a valid value.';
        return 'Please check this value.';
    };

    document.querySelectorAll('form').forEach((form) => {
        // Route browser validation through the inline, accessible messages below.
        form.noValidate = true;
        const fields = [...form.querySelectorAll('input:not([type=hidden]):not([type=submit]):not([type=button]), select, textarea')];
        fields.forEach((field, index) => {
            if (!field.id) field.id = `form-field-${index + 1}`;
            const error = document.createElement('small');
            error.className = 'field-error';
            error.id = `${field.id}-error`;
            error.setAttribute('aria-live', 'polite');
            field.insertAdjacentElement('afterend', error);
            field.dataset.errorId = error.id;

            const validate = () => {
                const value = field.type === 'file' ? field.value : field.value.trim();
                const emptyRequired = field.required && value === '';
                const invalid = emptyRequired || (value !== '' && !field.checkValidity());
                field.classList.toggle('is-invalid', invalid);
                field.setAttribute('aria-invalid', invalid ? 'true' : 'false');
                error.textContent = invalid ? messageFor(field) : '';
                return !invalid;
            };
            field.addEventListener('blur', validate);
            field.addEventListener('input', () => { if (field.classList.contains('is-invalid')) validate(); });
            field.addEventListener('change', () => { if (field.classList.contains('is-invalid')) validate(); });
        });

        form.addEventListener('submit', (event) => {
            let firstInvalid = null;
            fields.forEach((field) => {
                const requiredEmpty = field.required && (field.type === 'file' ? !field.files.length : field.value.trim() === '');
                const invalid = requiredEmpty || (field.value !== '' && !field.checkValidity());
                field.classList.toggle('is-invalid', invalid);
                field.setAttribute('aria-invalid', invalid ? 'true' : 'false');
                const error = document.getElementById(field.dataset.errorId);
                error.textContent = invalid ? messageFor(field) : '';
                if (invalid && !firstInvalid) firstInvalid = field;
            });
            if (firstInvalid) {
                event.preventDefault();
                event.stopImmediatePropagation();
                firstInvalid.focus();
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, true);
    });

    const style = document.createElement('style');
    style.textContent = `.field-error{display:block;min-height:1.2em;margin:.2rem 0 .35rem;color:#b42318;font-size:.875rem;text-align:left}.is-invalid{border-color:#b42318!important;box-shadow:0 0 0 2px rgba(180,35,24,.12)!important}.is-invalid:focus{outline-color:#b42318}`;
    document.head.appendChild(style);
})();
