function toggleForms() {
    const sscCheck = document.getElementById('ssc_check');
    const hscCheck = document.getElementById('hsc_check');
    const sscForm = document.getElementById('ssc_form');
    const hscForm = document.getElementById('hsc_form');

    if (hscCheck.checked) {
        sscCheck.checked = true;
        sscCheck.disabled = true;
    } else {
        sscCheck.disabled = false;
    }

    if (sscCheck.checked) {
        sscForm.style.display = 'block';
    } else {
        sscForm.style.display = 'none';
    }

    if (hscCheck.checked) {
        hscForm.style.display = 'block';
    } else {
        hscForm.style.display = 'none';
    }

    var sscInputs = sscForm.querySelectorAll('input');
    for (var i = 0; i < sscInputs.length; i++) {
        sscInputs[i].required = sscCheck.checked;
    }

    var hscInputs = hscForm.querySelectorAll('input');
    for (var i = 0; i < hscInputs.length; i++) {
        hscInputs[i].required = hscCheck.checked;
    }
}