<?php ?>
<div class="container">
    <h1>Modifica utente</h1>
    <p><b class="text-danger">Attenzione: </b>il carattere <b class="text-danger">* </b>indica un campo obbligatorio</p>
    <hr>

    <div class="col-md-6">
    <form class="form-group" method='POST' action='<?php echo URL . 'user/updateUser' ?>'>
        <label class="mt-2">E-mail: <b class="text-danger">* </b></label>
        <input type="email" class="form-control" name="email" placeholder="Example@example.com" value="<?php echo ($user)? $user['email']: null; ?>" onkeyup="checkEmail(this)" onchange="enableAddUserButton()" required>

        <label class="mt-3">Nome: (max 50 caratteri) <b class="text-danger">* </b></label>
        <input type="text" class="form-control" name="name" placeholder="Mario" value="<?php echo ($user)? $user['nome']: null; ?>" onkeyup="checkText(this)" onchange="enableAddUserButton()" required>

        <label class="mt-3">Cognome: (max 50 caratteri) <b class="text-danger">* </b></label>
        <input type="text" class="form-control" name="surname" placeholder="Rossi" value="<?php echo ($user)? $user['cognome']: null; ?>" onkeyup="checkText(this)" onchange="enableAddUserButton()" required>

        <label class="mt-3">Via: (max 50 caratteri) <b class="text-danger">* </b></label>
        <input type="text" class="form-control" name="address" placeholder="Via Principale 12" value="<?php echo ($user)? $user['via']: null; ?>" onkeyup="checkAddress(this)" onchange="enableAddUserButton()" required>

        <label class="mt-3">Citta: (max 50 caratteri) <b class="text-danger">* </b></label>
        <input type="text" class="form-control" name="city" placeholder="Berna" value="<?php echo ($user)? $user['citta']: null; ?>"  onkeyup="checkText(this)"onchange="enableAddUserButton()" required>

        <label class="mt-3">CAP: (min 4 cifre) <b class="text-danger">* </b></label>
        <input type="text" class="form-control" name="cap" placeholder="1231" value="<?php echo ($user)? $user['cap']: null; ?>" onkeyup="checkCAP(this)" onchange="enableAddUserButton()" required>

        <label class="mt-3">Numero di telefono: <b class="text-danger">* </b></label>
        <input type="text" class="form-control" name="phone_number" placeholder="+41 080 800 08 80"  value="<?php echo ($user)? $user['telefono']: null; ?>"  onkeyup="checkPhoneNumber(this)" onfocusout="enableAddUserButton()" required>

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
                   value="<?php echo ADMIN ?>" <?php echo ($user && intval($user['id_permesso']) == ADMIN) ? 'checked' : null ?>>
            <label class="custom-control-label" for="admin">Admin</label>
        </div>

        <!-- Operator -->
        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" class="custom-control-input" id="operator" name="permission"
                   value="<?php echo OPERATORE ?>" <?php echo ($user && intval($user['id_permesso']) == OPERATORE) ? 'checked' : null ?>>
            <label class="custom-control-label" for="operator">Operatore</label>
        </div>

        <!-- Supplier -->
        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" class="custom-control-input" id="supplier" name="permission"
                   value="<?php echo FORNITORE ?>" <?php echo ($user && intval($user['id_permesso']) == FORNITORE) ? 'checked' : null ?>>
            <label class="custom-control-label" for="supplier">Fornitore</label>
        </div>
        <!-- End permission -->

        <!-- New password not required -->
        <label class="mt-3">Nuova password: (min 8 caratteri, 1 maiuscola, una cifra)</label>
        <input type="password" class="form-control" name="password" placeholder="Example1" onkeyup="checkPasswordStrenght(this)">

        <a class="btn btn-danger mt-4" href="<?php echo URL . 'user'?>">Annulla</a>
        <input type="submit" value="Applica" class="btn btn-danger mt-4" id="updateUserButton">
    </form>
    <script type="text/javascript" src="/magazzino_merci/application/libs/js/validator.js"></script>
    </div>
</div>
