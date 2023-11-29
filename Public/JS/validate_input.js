// als je op naw.php zit
if (location.pathname.includes('/naw.php')) {
    document.addEventListener('submit', (e) => {
        // als de input niet klopt, stop de submit
        if (!validateInput()) e.preventDefault();
    });
}

const emailPattern = /^[a-zA-Z0-9!#$%&'*+-/=?^_`{|}~.]+@[a-zA-Z0-9!#$%&'*+-/=?^_`{|}~.]+\.[a-zA-Z0-9!#$%&'*+-/=?^_`{|}~.]+$/;
const telefoonPattern = /^(?:(?:\+|00(\s|\s?\-\s?)?)31(?:\s|\s?\-\s?)?(?:\(0\)[\-\s]?)?|0)[1-9](?:(?:\s|\s?\-\s?)?[0-9])(?:(?:\s|\s?-\s?)?[0-9])(?:(?:\s|\s?-\s?)?[0-9])\s?[0-9]\s?[0-9]\s?[0-9]\s?[0-9]\s?[0-9]$/;
const postcodePattern = /^[0-9]{4}\s*[a-zA-Z]{2}$/;

function inputCallback(e) {
    // on eventListener 'change'
    e.target.classList.remove('invalid-input');
    e.target.removeEventListener('input', inputCallback);
}

function validateInput() {
    const telefoonnummer = document.getElementById('telefoonnummer');
    const email = document.getElementById('email');
    const postcode = document.getElementById('postcode');
    const errorMsg = document.getElementById('errorMsg');

    if (!telefoonPattern.test(telefoonnummer.value)) {
        errorMsg.innerHTML = 'Telefoonnummer is niet correct ingevuld.';
        telefoonnummer.classList.add('invalid-input');
        telefoonnummer.addEventListener('input', inputCallback);
        window.scrollTo({top: 0, behavior: 'smooth'});
        return false;
    }

    if (!emailPattern.test(email.value)) {
        errorMsg.innerHTML = 'Email is niet correct ingevuld.';
        email.classList.add('invalid-input');
        email.addEventListener('input', inputCallback);
        window.scrollTo({top: 0, behavior: 'smooth'});
        return false;
    }

    if (!postcodePattern.test(postcode.value)) {
        errorMsg.innerHTML = 'Postcode is niet correct ingevuld.';
        postcode.classList.add('invalid-input');
        postcode.addEventListener('input', inputCallback);
        window.scrollTo({top: 0, behavior: 'smooth'});
        return false;
    }

    return true;
}