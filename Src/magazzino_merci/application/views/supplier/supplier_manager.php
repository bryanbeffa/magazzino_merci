<div class="container">
    <h1>Merce messa a disposizione</h1>

    <hr>
    <p>In questa pagina è possibile visualizzare la merce messa a disposizione che non è stata ancora accettata</p>
    <p><a href="<?php echo URL . 'supplier/showArticlesPage' ?>">Inserisci</a> un nuovo articolo</p>
    <hr>

    <table id="articlesTable" class="table text-center border-top table-responsive-sm">
        <thead>
        <th>Immagine</th>
        <th>Nome</th>
        <th>Categoria</th>
        <th>Data disponibilità</th>
        <th>Data scadenza</th>
        <th>Quantità</th>
        <th>Modifica</th>
        <th>Elimina</th>

        </thead>
        <tbody>
        <?php foreach ($articles as $article): ?>
            <tr><td>
                    <img class="img-table-height" src="<?php echo URL . '/application/libs/img/articles/' . ($article['percorso_immagine'] ?? 'default.jpg') ?>">
                </td>
                <td><?php echo $article['nome'] ?></td>
                <td><?php echo CategoryManager::getCategoryById($article['id_categoria'])['nome'] ?></td>
                <td><?php echo date_format(date_create($article['disponibile_il']), 'd.m.Y') ?></td>
                <td><?php echo date_format(date_create($article['data_scadenza']), 'd.m.Y') ?></td>
                <td><?php echo $article['quantita'] ?></td>

                <?php if (intval($article['id_utente']) == $user_id): ?>
                    <td>
                        <a class="text-info"
                            href="<?php echo URL . 'supplier/showSupplierUpdatePage/' . $article['id'] ?> ">
                            <img class="bin-icon" src="/magazzino_merci/application/libs/img/svg/edit.svg">
                        </a>
                    </td>
                    <td>
                        <a class="text-info"
                           data-toggle="modal" data-target="#confirmDeleteModal"
                           onclick="deleteArticle(<?php echo $article['id'] ?>, '<?php echo rawurlencode($article['nome']) ?>')">
                            <img class="bin-icon" src="/magazzino_merci/application/libs/img/svg/bin.svg">
                        </a>
                    </td>
                <?php else: ?>
                    <td></td>
                    <td></td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Start delete confirm modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Eliminazione articolo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method='POST' action="<?php echo URL . 'supplier/deleteArticle/'?>">

                    <!-- modal body-->
                    <div class="modal-body">
                        <input type="hidden" id="articleToDeleteId" name="articleToDeleteId">
                        <p id="deleteMessage"></p>
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

    <script type="text/javascript" src="/magazzino_merci/application/libs/js/manageArticles.js"></script>
    <script>
        $(document).ready(function () {
            $('#articlesTable').DataTable({
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