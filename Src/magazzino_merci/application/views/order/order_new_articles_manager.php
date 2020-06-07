<div class="container">
    <h1>Articoli disponibili</h1>
    <hr>
    <p>Scegli tra la merce messa a disposizione dai fornitori e ordina!</p>

    <table id="availableArticlesTable" class="table text-center border-top table-responsive-sm align-middle">
        <thead>
        <th class="w-25">Immagine</th>
        <th>Nome articolo</th>
        <th>Categoria</th>
        <th>Quantità</th>
        <th>Disponibile il</th>
        <th>Scade il</th>
        <th>Richiedi</th>

        </thead>
        <tbody>
        <?php foreach ($available_articles as $article): ?>
            <tr>
                <td><img class="img-table-height" src="<?php echo URL . '/application/libs/img/articles/' . ($article['percorso_immagine'] ?? 'default.jpg') ?>"></td>
                <td><?php echo $article['nome'] ?></td>
                <td><?php echo CategoryManager::getCategoryById($article['id_categoria'])['nome'] ?></td>
                <td><?php echo $article['quantita'] ?></td>
                <td><?php echo date_format(date_create($article['disponibile_il']), 'd.m.Y') ?></td>
                <td><?php echo date_format(date_create($article['data_scadenza']), 'd.m.Y') ?></td>
                <td>
                    <a class="text-info"
                       data-toggle="modal" data-target="#confirmOrderModal"
                       onclick="confirmOrder(<?php echo $article['id'] ?>, '<?php echo rawurlencode($article['nome']) ?>', '<?php echo rawurlencode(CategoryManager::getCategoryById($article['id_categoria'])['nome']) ?>', '<?php echo rawurlencode($article['quantita']) ?>', '<?php echo rawurlencode(date_format(date_create($article['data_scadenza']), 'd.m.Y')) ?>', '<?php echo rawurlencode(date_format(date_create($article['disponibile_il']), 'd.m.Y')) ?>')">
                        <img class="bin-icon" src="/magazzino_merci/application/libs/img/svg/check.svg">
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Start order confirm modal -->
    <div class="modal fade" id="confirmOrderModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Conferma ordine</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method='POST' action="<?php echo URL . 'order/orderArticle' ?>">

                    <!-- modal body-->
                    <div class="modal-body">
                        <input type="hidden" id="articleId" name="article_id">

                        <p><b class="text-danger">Attenzione: </b>sei sicuro di voler effettuare il seguente ordine?</p>
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
                        <input type="submit" class="btn btn-danger" value="Richiedi">
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
            $('#availableArticlesTable').DataTable({
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