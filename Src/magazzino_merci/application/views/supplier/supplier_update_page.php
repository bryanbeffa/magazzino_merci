<?php ?>
<div class="container">
    <h1>Modifica articolo</h1>
    <p>Puoi modificare l'articolo desiderato </p>
    <p><b class="text-danger">Attenzione: </b> le date devono essere future</p>
    <hr>

    <div class="col-md-6">
        <form class="form-group" method='POST' action='<?php echo URL . 'supplier/updateArticle' ?>' enctype="multipart/form-data">

            <label class="mt-3">Nome: (max 50 caratteri)</label>
            <input type="text" class="form-control" name="name" placeholder="Coca cola"
                   value="<?php echo (isset($article)) ? $article['nome'] : null ?>"
                   onkeyup="checkAddress(this)" required>

            <label class="mt-3">Quantità: (min 1, max 999)</label>
            <input type="number" class="form-control" name="quantity" placeholder="2"
                   value="<?php echo (isset($article)) ? $article['quantita'] : null ?>" min="1" max="1000"
                   onkeyup="checkNumber(this)" required>

            <!-- Category select -->
            <label class="mt-3">Categoria dell'articolo: </label>
            <select class="browser-default custom-select border-success" name="category_id">

                <?php foreach($categories as $category): ?>
                <option value="<?php echo $category['id']?>" <?php echo (isset($article['id_categoria']) && $article['id_categoria'] == $category['id'])? 'selected': null ?>><?php echo $category['nome']?></option>
                <?php endforeach; ?>
            </select>

            <!-- Expiring date -->
            <label class="mt-3">Data scadenza: </label>
            <input type="date" class="form-control" name="expire_date" placeholder="gg.mm.aaaa" id="expireDate"
                   onkeyup="checkPhoneNumber(this)" onchange="checkDateValidity(this, 'expireDateErrorMessage')" required>
            <p id="expireDateErrorMessage" class="text-danger mt-3 font-weight-bold"></p>

            <!-- Available date -->
            <label>Data disponibilità: (precedente alla data di scadenza)</label>
            <input type="date" class="form-control" name="available_date" placeholder="gg.mm.aaaa"
                   onkeyup="checkPhoneNumber(this)" onchange="checkDate(this, 'availableDateErrorMessage')" required>
            <p id="availableDateErrorMessage" class="text-danger mt-3 font-weight-bold"></p>

            <button class="btn btn-danger mt-3" onclick="emptyInputs(event)">Cancella</button>
            <input type="submit" value="Aggiungi" class="btn btn-danger mt-3" id="addArticleButton" >
        </form>
    </div>
</div>
<script type="text/javascript" src="/magazzino_merci/application/libs/js/validator.js"></script>
