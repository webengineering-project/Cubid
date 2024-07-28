function redirectToProfile(email) {
    window.location.href = '/profile/?profile=' + email;
}

function acceptRequest(email) {
    const form = document.createElement('form');
    form.action = '/deny.php';
    form.method = 'POST';


    const emailInput = document.createElement('input');
    emailInput.type = 'hidden';
    emailInput.name = 'email';
    emailInput.value = email;

    form.appendChild(emailInput);
    document.body.appendChild(form);

    //alert(form)
    form.submit();
}

function denyRequest(email) {
    const form = document.createElement('form');
    form.action = '/deny.php';
    form.method = 'POST';

    const emailInput = document.createElement('input');
    emailInput.type = 'hidden';
    emailInput.name = 'email';
    emailInput.value = email;

    form.appendChild(emailInput);
    document.body.appendChild(form);
    form.submit();
}
