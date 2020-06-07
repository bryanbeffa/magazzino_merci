<?php ?>
<div class="container">
    <h1>Modifica categoria</h1>
    <p><b class="text-danger">Attenzione: </b> inserisci il nome di una categoria che non sia già presente nel sistema</p>
    <hr>

    <div class="col-md-6">
    <form class="form-group" method='POST' action='<?php echo URL . 'category/updateCategory' ?>'>

        <label class="mt-3">Nome: (max 50 caratteri) <b class="text-danger">* </b></label>
        <input type="text" class="form-control" name="name" placeholder="Vegetali" value="<?php echo ($category)? $category['nome']: null; ?>" onkeyup="checkText(this)" onchange="enableAddUserButton()" required>

        <a class="btn btn-danger mt-4" href="<?php echo URL . 'category'?>">Annulla</a>
        <input type="submit" value="Applica" class="btn btn-danger mt-4" id="updateUserButton">
    </form>
    <script type="text/javascript" src="/magazzino_merci/application/libs/js/validator.js"></script>
    </div>
</div>
