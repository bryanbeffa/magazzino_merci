<div class="container">
    <h1>Catalogo articoli</h1>
    <hr>
    <p>Sei interessato ad un articolo? Richiedi subito l'ordine</p>

    <!-- Filter mask -->
    <div class="py-2 border-top">
        <a type="button" id="filterToggleButton" data-toggle="collapse" href="#filterMask" class="float-right"
           aria-expanded="false" aria-controls="filterMask">
            <img class="icon-md" id="filterImage" src="/magazzino_merci/application/libs/img/svg/down-arrow.svg">
        </a>
        <p id="filterLabel">Mostra filtri di ricerca</p>
    </div>
    <div class="collapse" id="filterMask">
        <div class="container">
            <form action="<?php echo URL ?>catalog" method="post">

                <!-- Text search -->
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Articolo:</label>
                    <div class="col-sm-8">
                        <input placeholder="es banane" type="text" class="form-control" name="text_filter"
                               value="<?php echo (isset($_SESSION['text_filter'])) ? $_SESSION['text_filter'] : null ?>">
                    </div>
                </div>

                <!-- Search Category -->
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Seleziona categoria:</label>
                    <div class="col-sm-8">
                        <select class="browser-default custom-select mdb-select" name="category_filter">
                            <option value="0" selected>Tutte le categorie</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id'] ?>" <?php echo (isset($_SESSION['category_filter']) && intval($_SESSION['category_filter']) == $category['id']) ? 'selected' : null; ?>><?php echo $category['nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Expire date -->
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Data scadenza - entro il:</label>
                    <div class="col-sm-8">
                        <input placeholder="es. 20.10.2020" type="text" class="form-control"
                               onfocus="this.type = 'date';"
                               name="expire_date_filter">
                    </div>
                </div>

                <!-- Available date -->
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Data disponibilità - entro il:</label>
                    <div class="col-sm-8">
                        <input placeholder="es. 20.10.2020" type="text" class="form-control"
                               onfocus="this.type = 'date';"
                               name="available_date_filter">
                    </div>
                </div>

                <div class="container-fluid">
                    <input type="submit" value="cerca" class="btn btn-danger text-white">
                </div>
            </form>
        </div>
        <hr>
    </div>

    <div class="clearfix">
        <?php if (sizeof($stored_articles) > 0): ?>
            <?php foreach ($stored_articles as $article): ?>

                <div class="col-lg-4 float-left text-center mt-5">
                    <div class="col-7 m-auto">
                        <img src="<?php echo URL . '/application/libs/img/articles/' . ($article['percorso_immagine'] ?? 'default.jpg') ?>"
                             class="img-catalog-height">
                    </div>
                    <p class="m-0 mt-3 font-weight-bold"><?php echo $article['nome'] ?></p>
                    <p class="m-0">Quantità: <?php echo $article['quantita'] ?></p>
                    <p class="m-0">Disponibile
                        il: <?php echo date_format(date_create($article['disponibile_il']), 'd.m.Y') ?></p>
                    <p class="m-0">Scade
                        il: <?php echo date_format(date_create($article['data_scadenza']), 'd.m.Y') ?></p>
                    <?php if (UserManager::getPermission($_SESSION['email']) == BASE): ?>
                        <a class="btn btn-danger w-75" data-toggle="modal" data-target="#orderModal"
                           onclick="userConfirmOrder(<?php echo $article['id'] ?>, '<?php echo rawurlencode($article['nome']) ?>', '<?php echo rawurlencode(CategoryManager::getCategoryById($article['id_categoria'])['nome']) ?>', '<?php echo rawurlencode($article['quantita']) ?>', '<?php echo rawurlencode(date_format(date_create($article['data_scadenza']), 'd.m.Y')) ?>', '<?php echo rawurlencode(date_format(date_create($article['disponibile_il']), 'd.m.Y')) ?>')">Ordina</a>
                    <?php endif; ?>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center font-weight-bold">Nessun articolo trovato</p>
        <?php endif; ?>

        <!-- Start order modal -->
        <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ordina articolo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form method='POST' action="<?php echo URL . 'catalog/makeOrderRequest' ?>">

                        <!-- modal body-->
                        <div class="modal-body">

                            <input type="hidden" id="articleId" name="article_id">
                            <p>Sei sicuro di volere richiedere l'ordine del seguente articolo?</p>
                            <table class="table table-responsive-sm table-bordered text-center">
                                <tbody>

                                <tr>
                                    <td class="w-50 font-weight-bold">Nome articolo</td>
                                    <td class="w-50" id="articleName"></td>
                                </tr>
                                <tr>
                                    <td class="w-50 font-weight-bold">Categoria</td>
                                    <td class="w-50" id="articleCategory"></td>
                                </tr>
                                <tr>
                                    <td class="w-50 font-weight-bold">Quantità</td>
                                    <td class="w-50" id="articleQuantity"></td>
                                </tr>
                                <tr>
                                    <td class="w-50 font-weight-bold">Data scadenza</td>
                                    <td class="w-50" id="articleExpireDate"></td>
                                </tr>
                                <tr>
                                    <td class="w-50 font-weight-bold">Data disponibilità</td>
                                    <td class="w-50" id="articleAvailableDate"></td>
                                </tr>

                                </tbody>
                            </table>
                        </div>

                        <!-- Quantity commands -->
                        <div class="container">
                            <label>Data consegna (compresa tra data di scadenza e data di disponibilità)</label>
                            <input type="date" class="form-control text-center" name="delivery_date" placeholder="gg.mm.aaaa" id="deliveryDate"
                                   onchange="checkDeliveryDate(this)" required>
                            <p class="text-danger mt-3 text-center" id="deliveryDateError"></p>

                            <table class="m-auto table w-100 text-center col-lg-8">
                                <td class="w-25">
                                    <button class="btn btn-sm btn-danger w-100" onclick="decreaseQuantity(event)"
                                            style="font-size: 110%">-
                                    </button>
                                </td>
                                <td class="w-50"><input type="number" class="form-control mt-2 text-center"
                                                        name="article_quantity" id="articleUserQuantity" min="1"
                                                        value="1">
                                </td>
                                <td class="w-25">
                                    <button class="btn btn-sm btn-danger w-100" onclick="increaseQuantity(event)"
                                            style="font-size: 110%">+
                                    </button>
                                </td>
                            </table>
                        </div>
                        <!-- end modal body-->

                        <!-- modal footer-->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Annulla</button>
                            <input type="submit" class="btn btn-danger" value="Richiedi">
                        </div>
                        <!-- end modal body-->
                    </form>

                </div>
            </div>
        </div>
        <!-- End order modal -->
    </div>
    <?php if ($number_pages > 0): ?>
        <div class="my-5 text-right">
            <a href="<?php echo (($page - 1) >= DEFAULT_PAGE) ? URL . 'catalog/showStoredArticles?page=' . ($page - 1) : URL . 'catalog/showStoredArticles?page=' . $page; ?>"
               class="text-dark py-2 px-3 m-0">Precedente</a>
            <?php for ($i = max(DEFAULT_PAGE - 1, $page - MAX_NUM_PAGES); $i <= min($page + MAX_NUM_PAGES, $number_pages - 1); $i++): ?>
                <a href="<?php echo URL . 'catalog/showStoredArticles?page=' . ($i + 1) ?>"
                   class="btn <?php echo ($page == $i + 1) ? 'btn-outline-danger' : 'btn-danger' ?> py-2 px-3 m-0"><?php echo $i + 1 ?></a>
            <?php endfor; ?>
            <a href="<?php echo ($i >= ($page + 1)) ? URL . 'catalog/showStoredArticles?page=' . ($page + 1) : URL . 'catalog/showStoredArticles?page=' . $page; ?>"
               class="text-dark p-2 px-3 m-0">Successivo</a>

        </div>
    <?php endif; ?>
    <script type="text/javascript" src="/magazzino_merci/application/libs/js/manageOrder.js"></script>
    <script type="text/javascript" src="/magazzino_merci/application/libs/js/Validator.js"></script>
</div>
