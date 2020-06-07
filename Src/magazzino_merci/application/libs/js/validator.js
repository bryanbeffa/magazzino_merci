function checkText(element) {
    var value = element.value.trim();
    var patt = /^[\sa-zA-Z\u00C0-\u024F\u1E00-\u1EFF]+[a-zA-Z\u00C0-\u024F\u1E00-\u1EFF\s']*$/;
    if (patt.test(value) && value.length <= 50) {
        element.className = "border-success form-control";
        return true;
    } else if (element.value.length == 0) {
        element.className = "form-control";
    } else {
        element.className = "border-danger form-control";
    }

    return false;
}

function checkEmail(element) {
    var patt = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/igm;
    if (patt.test(element.value)) {
        element.className = "border-success form-control";
        return true;
    } else if (element.value.length == 0) {
        element.className = "form-control";
    } else {
        element.className = "border-danger form-control";
    }

    return false;
}

function checkAddress(element) {
    var value = element.value.trim();
    var patt = /^[0-9]*[a-zA-Z\u00C0-\u024F\u1E00-\u1EFF\s]+[a-zA-Z0-9\u00C0-\u024F\u1E00-\u1EFF\s']*$/;
    if (patt.test(value) && value.length <= 50) {
        element.className = "border-success form-control";
        return true;
    } else if (element.value.length == 0) {
        element.className = "form-control";
    } else {
        element.className = "border-danger form-control";
    }

    return false;
}

function checkCAP(element) {
    var patt = /^[0-9]{4,10}$/;
    if (patt.test(element.value)) {
        element.className = "border-success form-control";
        return true;
    } else if (element.value.length == 0) {
        element.className = "form-control";
    } else {
        element.className = "border-danger form-control";
    }

    return false;
}

function checkNumber(element) {
    var patt = /^[0-9]{1,3}$/;
    if (patt.test(element.value)) {
        element.className = "border-success form-control";
        return true;
    } else if (element.value.length == 0) {
        element.className = "form-control";
    } else {
        element.className = "border-danger form-control";
    }

    return false;
}

function checkPhoneNumber(element) {
    var patt = /^([+]?[\s0-9]+)?(\d{3}|[(]?[0-9]+[)])?([-]?[\s]?[0-9])+$/;
    if (patt.test(element.value.trim())) {
        element.className = "border-success form-control";
        return true;
    } else if (element.value.length == 0) {
        element.className = "form-control";
    } else {
        element.className = "border-danger form-control";
        return false;
    }
}

function checkPasswordStrenght(element) {
    var patt = /^(?=.*[0-9])(?=.*[A-Z]).{8,}$/;
    if (patt.test(element.value.trim())) {
        element.className = "border-success form-control";
        return true;
    } else if (element.value.length == 0) {
        element.className = "form-control";
    } else {
        element.className = "border-danger form-control";
        return false;
    }
}

function doPasswordMatch() {
    var password = document.getElementById('password');
    var confirmPassword = document.getElementById('confirmPassword');

    if (confirmPassword.value.length == 0 || password.value.length == 0) {
        return false;
    }
    if (confirmPassword.value != password.value) {
        document.getElementById('passwordError').innerHTML = 'Le password non corrispondono';
        confirmPassword.className = "border-danger form-control";
        return false;
    }

    document.getElementById('passwordError').innerHTML = ' ';
    confirmPassword.className = "border-success form-control";
    return true;
}

function enableAddUserButton() {
    var elementList = document.getElementsByTagName('input');

    document.getElementById('addUserButton').disabled = !(checkEmail(elementList['email']) && checkText(elementList['name']) &&
        checkText(elementList['surname']) && checkAddress(elementList['address']) &&
        checkText(elementList['city']) && checkCAP(elementList['cap']) &&
        checkPhoneNumber(elementList['phone_number']) && checkPasswordStrenght(elementList['password']) && checkPasswordStrenght(elementList['confirm_password']));
}

function enableAddCategoryButton() {
    var elementList = document.getElementsByTagName('input');

    document.getElementById('addCategoryButton').disabled = !(checkText(elementList['category_name']));
}

function checkDateValidity(element, errorItemId) {

    var date = new Date(element.value);
    var today = new Date();

    if (date > today) {
        element.className = "border-success form-control";
        document.getElementById(errorItemId).innerHTML = "";
        return true;
    } else if (element.value.length == 0) {
        element.className = "form-control";
    } else {
        element.className = "border-danger form-control";
        document.getElementById(errorItemId).innerHTML = "Devi selezionare una data futura";
    }

    return false;
}

function checkExpireDate(element, errorItemId) {
    var expireDate = new Date(document.getElementById('expireDate').value);
    var availableDate = new Date(element.value);

    if (expireDate < availableDate) {
        element.className = "border-danger form-control";
        document.getElementById(errorItemId).innerHTML = "La data di disponibilità deve essere precedente a quella di scadenza";
    } else if (element.value.length == 0) {
        element.className = "form-control";
    } else {
        element.className = "border-success form-control";
        return true;
    }

    return false;
}

function checkDate(element, errorItemId) {
    if (checkDateValidity(element, errorItemId)) {
        checkExpireDate(element, errorItemId);
    }
}

function checkDeliveryDate(item) {
    var errorMsg = document.getElementById('deliveryDateError');
    var expireDate = document.getElementById('articleExpireDate').innerHTML;
    var expireDateItem = expireDate.split('.');
    expireDate = new Date(  expireDateItem[2] + "-" + expireDateItem[1] + "-"  + expireDateItem[0])

    var availableDate = document.getElementById('articleAvailableDate').innerHTML;
    var availableDateItem = availableDate.split('.');
    availableDate = new Date(  availableDateItem[2] + "." + availableDateItem[1] + "-"  + availableDateItem[0])
    deliveryDate = new Date(item.value);

    if(item.value.trim() === ''){
        item.className = "form-control text-center";
        errorMsg.innerHTML = "";
        return;
    }

    if (deliveryDate >= availableDate && deliveryDate <= expireDate) {
        item.className = "border-success form-control text-center";
        errorMsg.innerHTML = "";
        return true;
    }

    item.className = "border-danger form-control text-center";
    errorMsg.innerHTML = "La data inserita non è valida";
    return false;
}


//empty inputs
function emptyInputs(event) {
    event.preventDefault();
    var elementList = document.getElementsByTagName('input');

    for (var i = 0; i < elementList.length - 1; i++) {
        if (elementList[i].name != 'permission') {
            elementList[i].value = "";
            elementList[i].className = "form-control";
        }
    }
}