$(document).ready(function () {
    $('#deleteUserBtn').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        return false;
    });

    $('#registerButton').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        return false;
    });

    $('#deleteUserBtn').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        return false;
    });

});

function submitRegisterForm()
{
    if ($('#inputPassword').val() === $('#inputPasswordRepeat').val()) {
        $(document.forms[0].submit());
    } else {
        alert('Wachtwoorden komen niet overeen');
    }
}

function submitEditUserForm()
{
    if ($('#inputPassword').val() === $('#inputPasswordRepeat').val()) {
        $(document.forms[0].submit());
    } else {
        alert('Wachtwoorden komen niet overeen');
    }
}

function confirmDelete(userId)
{
    if (window.confirm("Are you sure?")) {
        location.href = '/user/?delete=1&userIdentifier=' + userId;
    };
}