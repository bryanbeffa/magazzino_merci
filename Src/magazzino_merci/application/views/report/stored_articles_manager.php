<div class="container">
    <h1>Articoli presenti in magazzino</h1>
    <hr>
    <p><b class="text-danger">Attenzione</b>: gli articoli eliminati non possono essere recuperati</p>
    <p>Qui è possibile visualizzare tutti gli articoli presenti in magazzino</p>

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
            <form action="<?php echo URL ?>report/showStoredArticles" method="post">

                <!-- Text search -->
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Articolo:</label>
                    <div class="col-sm-8">
                        <input placeholder="es banane" type="text" class="form-control" name="text_filter"
                               value="<?php echo (isset($_SESSION['stored_text_filter'])) ? $_SESSION['stored_text_filter'] : null ?>">
                    </div>
                </div>

                <!-- Search Category -->
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Seleziona categoria:</label>
                    <div class="col-sm-8">
                        <select class="browser-default custom-select mdb-select" name="category_filter">
                            <option value="0" selected>Tutte le categorie</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id'] ?>" <?php echo (isset($_SESSION['stored_category_filter']) && intval($_SESSION['stored_category_filter']) == $category['id']) ? 'selected' : null; ?>><?php echo $category['nome'] ?></option>
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

    <table id="storedArticlesTable" class="table text-center border-top table-responsive-sm">
        <thead>
        <th>Immagine</th>
        <th>ID</th>
        <th>Nome articolo</th>
        <th>Categoria</th>
        <th>Quantità</th>
        <th>Disponibile il</th>
        <th>Scade il</th>
        <th>Elimina</th>

        </thead>
        <tbody>
        <?php foreach ($stored_articles as $article): ?>
            <tr>
                <td><img class="img-table-height" src="<?php echo URL . '/application/libs/img/articles/' . ($article['percorso_immagine'] ?? 'default.jpg') ?>"></td>
                <td><?php echo $article['id'] ?></td>
                <td><?php echo $article['nome'] ?></td>
                <td><?php echo CategoryManager::getCategoryById($article['id_categoria'])['nome'] ?></td>
                <td><?php echo $article['quantita'] ?></td>
                <td><?php echo date_format(date_create($article['disponibile_il']), 'd.m.Y') ?></td>
                <td><?php echo date_format(date_create($article['data_scadenza']), 'd.m.Y') ?></td>
                <td>
                    <a class="text-info"
                       data-toggle="modal" data-target="#confirmDeleteModal"
                       onclick="confirmOrder(<?php echo $article['id'] ?>, '<?php echo rawurlencode($article['nome']) ?>', '<?php echo rawurlencode(CategoryManager::getCategoryById($article['id_categoria'])['nome']) ?>', '<?php echo rawurlencode($article['quantita']) ?>', '<?php echo rawurlencode(date_format(date_create($article['data_scadenza']), 'd.m.Y')) ?>', '<?php echo rawurlencode(date_format(date_create($article['disponibile_il']), 'd.m.Y')) ?>')">
                        <img class="bin-icon" src="/magazzino_merci/application/libs/img/svg/bin.svg">
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Start delete confirm modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Eliminazione articolo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method='POST' action="<?php echo URL . 'report/deleteStoredArticle' ?>">

                    <!-- modal body-->
                    <div class="modal-body">
                        <input type="hidden" id="articleId" name="article_id">

                        <p><b class="text-danger">Attenzione: </b>sei sicuro di eliminare il seguente articolo?</p>
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
                    <!-- end modal body-->

                    <!-- modal footer-->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Annulla</button>
                        <input type="submit" class="btn btn-danger" value="Elimina">
                    </div>
                    <!-- end modal body-->
                </form>

            </div>
        </div>
    </div>
    <!-- End delete confirm modal -->

    <script type="text/javascript" src="/magazzino_merci/application/libs/js/manageOrder.js"></script>
    <script>
        $(document).ready(function () {
            $('#storedArticlesTable').DataTable({
                "lengthMenu": [[10, 25, 50], [10, 25, 50]],
                "ordering": false,
                "language": {
                    "lengthMenu": "Mostra _MENU_ record per pagina",
                    "zeroRecords": "Nessun record trovato",
                    "info": "Pagina _PAGE_ di _PAGES_",
                    "infoEmpty": "Nessun record disponibile",
                    "infoFiltered": "(filtrato da _MAX_ record totali)",
                    "search": "Cerca:",
                    "paginate": {
                        "previous": "Precedente",
                        "next": "Successiva"
                    }
                }
            });
            $('.dataTables_length').addClass('bs-select');
        });
    </script>
</div>