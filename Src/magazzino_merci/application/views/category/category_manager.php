<div class="container">
    <h1>Lista categorie</h1>

    <hr>
    <p>In questa pagina è possibile visualizzare tutte le categorie presenti nel sistema</p>
    <p><a href="<?php echo URL . 'category/showAddCategoryPage' ?>">Aggiungi</a> una nuova categoria</p>
    <hr>

    <table id="categoryTable" class="table text-center border-top table-responsive-sm">
        <thead>
        <th>Nome categoria</th>
        <th>Numero articoli <br>in magazzino</th>
        <th>Numero articoli <br>non in magazzino</th>
        <th>Modifica</th>
        <th>Elimina</th>

        </thead>
        <tbody>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?php echo $category['nome'] ?></td>
                <td><?php echo CategoryManager::getArticlesNumberById($category['id'], true)['num_articoli'] ?></td>
                <td><?php echo CategoryManager::getArticlesNumberById($category['id'], false)['num_articoli'] ?></td>
                <td>
                    <a class="text-info" href="<?php echo URL . 'category/showUpdateCategoryPage/' . $category['id'] ?>">
                        <img class="bin-icon" src="/magazzino_merci/application/libs/img/svg/edit.svg">
                    </a>
                </td>
                <td>
                    <a class="text-info"
                       data-toggle="modal" data-target="#confirmDeleteModal"
                       onclick="deleteCategory(<?php echo $category['id'] ?>, '<?php echo rawurlencode($category['nome']) ?>')">
                        <img class="bin-icon" src="/magazzino_merci/application/libs/img/svg/bin.svg">
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Start delete confirm modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Eliminazione categoria</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method='POST' action="<?php echo URL . 'category/deleteCategory' ?>">

                    <!-- modal body-->
                    <div class="modal-body">
                        <input type="hidden" id="categoryToDeleteId" name="category_id">
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

    <script type="text/javascript" src="/magazzino_merci/application/libs/js/manageCategory.js"></script>
    <script>
        $(document).ready(function () {
            $('#categoryTable').DataTable({
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