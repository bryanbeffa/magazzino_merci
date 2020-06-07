<?php ?>
<div class="container">
    <h1>Aggiungi utente</h1>
    <p><b class="text-danger">Attenzione: </b>tutti i campi sono obbligatori</p>
    <hr>

    <div class="col-md-6">
        <form class="form-group" method='POST' action='<?php echo URL . 'user/addUser' ?>'>
            <label class="mt-2">E-mail:</label>
            <input type="text" class="form-control" name="email" placeholder="Example@example.com"
                   onkeyup="checkEmail(this)" onchange="enableAddUserButton()"
                   value="<?php echo (isset($_SESSION['new_user_email'])) ? $_SESSION['new_user_email'] : null ?>" required>

            <label class="mt-3">Nome: (max 50 caratteri)</label>
            <input type="text" class="form-control" name="name" placeholder="Mario"
                   value="<?php echo (isset($_SESSION['new_user_name'])) ? $_SESSION['new_user_name'] : null ?>"
                   onkeyup="checkText(this)" onchange="enableAddUserButton()" required>

            <label class="mt-3">Cognome: (max 50 caratteri)</label>
            <input type="text" class="form-control" name="surname" placeholder="Rossi"
                   value="<?php echo (isset($_SESSION['new_user_surname'])) ? $_SESSION['new_user_surname'] : null ?>"
                   onkeyup="checkText(this)" onchange="enableAddUserButton()" required>

            <label class="mt-3">Via: (max 50 caratteri)</label>
            <input type="text" class="form-control" name="address" placeholder="Via Principale 12"
                   value="<?php echo (isset($_SESSION['new_user_address'])) ? $_SESSION['new_user_address'] : null ?>"
                   onkeyup="checkAddress(this)" onchange="enableAddUserButton()" required>

            <label class="mt-3">Citta: (max 50 caratteri)</label>
            <input type="text" class="form-control" name="city" placeholder="Berna"
                   value="<?php echo (isset($_SESSION['new_user_city'])) ? $_SESSION['new_user_city'] : null ?>"
                   onkeyup="checkText(this)" onchange="enableAddUserButton()" required>

            <label class="mt-3">CAP: (min 4 cifre, max 10)</label>
            <input type="text" class="form-control" name="cap" placeholder="1231"
                   value="<?php echo (isset($_SESSION['new_user_cap'])) ? $_SESSION['new_user_cap'] : null ?>"
                   onkeyup="checkCAP(this)" onchange="enableAddUserButton()" required>

            <label class="mt-3">Numero di telefono:</label>
            <input type="text" class="form-control" name="phone_number" placeholder="+41 080 800 08 80"
                   value="<?php echo (isset($_SESSION['new_user_phone_number'])) ? $_SESSION['new_user_phone_number'] : null ?>"
                   onkeyup="checkPhoneNumber(this)" onfocusout="enableAddUserButton()" required>

            <!-- Permission -->
            <label class="mt-3">Tipo di utente:</label><br>

            <!-- Base user -->
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" class="custom-control-input" id="base" name="permission" value="<?php echo BASE ?>"
                       checked>
                <label class="custom-control-label" for="base">Base</label>
            </div>

            <!-- Admin -->
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" class="custom-control-input" id="admin" name="permission"
                       value="<?php echo ADMIN ?>" <?php echo (isset($_SESSION['new_user_permission']) && $_SESSION['new_user_permission'] == ADMIN) ? 'checked' : null ?>>
                <label class="custom-control-label" for="admin">Admin</label>
            </div>

            <!-- Operator -->
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" class="custom-control-input" id="operator" name="permission"
                       value="<?php echo OPERATORE ?>" <?php echo (isset($_SESSION['new_user_permission']) && $_SESSION['new_user_permission'] == OPERATORE) ? 'checked' : null ?>>
                <label class="custom-control-label" for="operator">Operatore</label>
            </div>

            <!-- Supplier -->
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" class="custom-control-input" id="supplier" name="permission"
                       value="<?php echo FORNITORE ?>" <?php echo (isset($_SESSION['new_user_permission']) && $_SESSION['new_user_permission'] == FORNITORE) ? 'checked' : null ?>>
                <label class="custom-control-label" for="supplier">Fornitore</label>
            </div>
            <!-- End permission -->

            <!-- New password  -->
            <label class="mt-3">Password: (min 8 caratteri, 1 maiuscola, una cifra)</label>
            <input type="password" id="password" class="form-control" name="password" placeholder="Example1"
                   onkeyup="checkPasswordStrenght(this)" onfocusout="enableAddUserButton()" required>

            <!-- Confirm pass -->
            <label class="mt-3"> Conferma password:</label>
            <input type="password" id="confirmPassword" class="form-control" name="confirm_password"
                   placeholder="Example1" onkeyup="checkPasswordStrenght(this)" onchange="doPasswordMatch()"
                   onfocusout="enableAddUserButton()" required>
            <p id="passwordError" class="font-weight-bold text-danger mt-3"></p>

            <button class="btn btn-danger mt-3" onclick="emptyInputs(event)">Cancella</button>
            <input type="submit" value="Aggiungi" class="btn btn-danger mt-3" id="addUserButton" >
        </form>
    </div>
</div>
<script type="text/javascript" src="/magazzino_merci/application/libs/js/validator.js"></script>
<script type="text/javascript" src="/magazzino_merci/application/libs/js/manageUser.js"></script>
