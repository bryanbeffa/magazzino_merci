<div class="container">
    <h1>Recupero account eliminati</h1>

    <hr>
    <p>In questa pagina è possibile recuperare gli utenti eliminati</p>
    <hr>

    <table id="usersTable" class="table text-center border-top table-responsive-sm">
        <thead>
        <th>E-mail</th>
        <th>Nome</th>
        <th>Cognome</th>
        <th>Telefono</th>
        <th>Indirizzo</th>
        <th>Città</th>
        <th>Tipo utente</th>
        <th>Recupera</th>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['email'] ?></td>
                <td><?php echo $user['nome'] ?></td>
                <td><?php echo $user['cognome'] ?></td>
                <td><?php echo $user['telefono'] ?></td>
                <td><?php echo $user['via'] ?></td>
                <td><?php echo $user['cap'] . ' ' . $user['citta'] ?></td>

                <td>
                    <?php switch ($user['id_permesso']):
                        case ADMIN:
                            echo 'Admin';
                            break;
                        case BASE:
                            echo 'Base';
                            break;
                        case OPERATORE:
                            echo 'Operatore';
                            break;
                        case FORNITORE:
                            echo 'Fornitore';
                            break;
                    endswitch; ?>
                </td>
                <td>
                    <a class="text-info" href="<?php echo URL . 'user/recoveryUser/' . $user['id'] ?>">
                        <img class="bin-icon" src="/magazzino_merci/application/libs/img/svg/recovery.svg">
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
                    <h5 class="modal-title" id="exampleModalLabel">Eliminazione utente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method='POST' action="<?php echo URL . 'user/deleteUser' ?>">

                    <!-- modal body-->
                    <div class="modal-body">
                        <input type="hidden" id="userToDeleteId" name="userToDeleteId">
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

    <script type="text/javascript" src="/magazzino_merci/application/libs/js/manageUser.js"></script>
    <script>
        $(document).ready(function () {
            $('#usersTable').DataTable({
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