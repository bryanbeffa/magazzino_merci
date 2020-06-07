<?php ?>
<div class="container">
    <h1>Aggiungi Categoria</h1>
    <p><b class="text-danger">Attenzione: </b> inserisci il nome di una categoria che non sia già presente nel sistema</p>
    <hr>

    <div class="col-md-6">
        <form class="form-group" method='POST' action='<?php echo URL . 'category/addCategory' ?>'>

            <label class="mt-3">Nome: (max 50 caratteri)</label>
            <input type="text" class="form-control" name="category_name" placeholder="Vegetali"
                   value="<?php echo (isset($_SESSION['new_category_name'])) ? $_SESSION['new_category_name'] : null ?>"
                   onkeyup="checkText(this)" onchange="enableAddCategoryButton()" required>

            <input type="submit" value="Aggiungi" class="btn btn-danger mt-3" id="addCategoryButton" disabled>
        </form>
    </div>
</div>
<script type="text/javascript" src="/magazzino_merci/application/libs/js/validator.js"></script>
