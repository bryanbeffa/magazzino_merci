<div class="container">
    <h1>Richieste d'ordine degli utenti</h1>
    <hr>
    <p>Qui puoi visualizzare tutte le richieste effettuate dagli utenti e accettarle/rifiutarle</p>

    <table id="availableArticlesTable" class="table text-center border-top table-responsive-lg">
        <thead>
        <th>Utente</th>
        <th>Email</th>
        <th>Telefono</th>
        <th>Indirizzo</th>
        <th>Articolo</th>
        <th>Quantità</th>
        <th>Data scadenza</th>
        <th>Data ordine</th>
        <th>Accetta</th>
        <th>Rifiuta</th>

        </thead>
        <tbody>
        <?php foreach ($unverified_order as $order): ?>
            <tr>
                <?php $user = UserManager::getUserById($order['id_utente']) ?>
                <?php $article = ArticleManager::getArticleById($order['id_articolo']) ?>
                <td><?php echo $user['nome'] . ' ' . $user['cognome'] ?></td>
                <td><?php echo $user['email'] ?></td>
                <td><?php echo $user['telefono'] ?></td>
                <td><?php echo $user['cap'] . ' ' . $user['citta'] . ' ' . $user['via'] ?></td>
                <td><?php echo $article['nome'] ?></td>
                <td><?php echo $order['quantita_ordine'] ?></td>
                <td><?php echo date_format(date_create($article['data_scadenza']), 'd.m.Y')  ?></td>
                <td><?php echo date_format(date_create($order['data_ordine']), 'd.m.Y G:i:s')  ?></td>
                <td><a class="text-primary"
                       data-toggle="modal" data-target="#acceptOrderModal"
                       onclick="acceptUserOrderRequest('<?php echo rawurlencode($order['id']) ?>', '<?php echo rawurlencode($user['id']) ?>', '<?php echo rawurlencode($article['id']) ?>','<?php echo rawurlencode($user['nome'] . ' ' . $user['cognome']) ?>', '<?php echo rawurlencode($user['email']) ?>', '<?php echo rawurlencode($user['telefono']) ?>', '<?php echo rawurlencode($user['cap'] . ' ' . $user['citta'] . ' ' . $user['via']) ?>', '<?php echo rawurlencode($article['nome']) ?>', '<?php echo rawurlencode($order['quantita_ordine']) ?>', '<?php echo rawurlencode(date_format(date_create($article['data_scadenza']), 'd.m.Y') ) ?>', '<?php echo rawurlencode($order['data_ordine']) ?>', '<?php echo rawurlencode($order['data_consegna']) ?>')">

                        <img class="bin-icon" src="/magazzino_merci/application/libs/img/svg/check.svg">
                    </a>
                </td>
                <td><a class="text-primary"
                       data-toggle="modal" data-target="#deleteOrderModal"
                       onclick="deleteUserOrderRequest('<?php echo rawurlencode($order['id']) ?>', '<?php echo rawurlencode($user['id']) ?>', '<?php echo rawurlencode($article['id']) ?>','<?php echo rawurlencode($user['nome'] . ' ' . $user['cognome']) ?>', '<?php echo rawurlencode($user['email']) ?>', '<?php echo rawurlencode($user['telefono']) ?>', '<?php echo rawurlencode($user['cap'] . ' ' . $user['citta'] . ' ' . $user['via']) ?>', '<?php echo rawurlencode($article['nome']) ?>', '<?php echo rawurlencode($order['quantita_ordine']) ?>', '<?php echo rawurlencode(date_format(date_create($article['data_scadenza']), 'd.m.Y') ) ?>', '<?php echo rawurlencode($order['data_ordine']) ?>', '<?php echo rawurlencode($order['data_consegna']) ?>')">

                        <img class="bin-icon" src="/magazzino_merci/application/libs/img/svg/close.svg">
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Start delete order modal -->
    <div class="modal fade" id="deleteOrderModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Richiesta d'ordine - rifiuta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method='POST' action="<?php echo URL . 'order/rejectOrderRequest' ?>">

                    <!-- modal body-->
                    <div class="modal-body">
                        <input type="hidden" id="userId" name="user_id">
                        <input type="hidden" id="articleId" name="article_id">
                        <input type="hidden" id="orderId" name="order_id">
                        <p><b class="text-danger">Attenzione: </b> sei sicuro di voler rifiutare la seguente richiesta?</p>

                        <table class="table table-responsive-lg table-bordered text-center">
                            <tbody>

                            <tr>
                                <td class="w-50 font-weight-bold">Utente</td>
                                <td class="w-50" id="user"></td>
                            </tr>
                            <tr>
                                <td class="w-50 font-weight-bold">Email</td>
                                <td class="w-50" id="userEmail"></td>
                            </tr>
                            <tr>
                                <td class="w-50 font-weight-bold">Telefono</td>
                                <td class="w-50" id="userPhoneNumber"></td>
                            </tr>
                            <tr>
                                <td class="w-50 font-weight-bold">Indirizzo</td>
                                <td class="w-50" id="userAddress"></td>
                            </tr>
                            <tr>
                                <td class="w-50 font-weight-bold">Articolo</td>
                                <td class="w-50" id="articleName"></td>
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
                                <td class="w-50 font-weight-bold">Data ordine</td>
                                <td class="w-50" id="orderDate"></td>
                            </tr>
                            <tr>
                                <td class="w-50 font-weight-bold">Data consegna</td>
                                <td class="w-50" id="deliveryDate"></td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <!-- end modal body-->

                    <!-- modal footer-->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Annulla</button>
                        <input type="submit" class="btn btn-danger" value="Rifiuta">
                    </div>
                    <!-- end modal body-->
                </form>

            </div>
        </div>
    </div>
    <!-- End delete order modal -->

    <!-- Start confirm order modal -->
    <div class="modal fade" id="acceptOrderModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Richiesta d'ordine - accetta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method='POST' action="<?php echo URL . 'order/acceptOrderRequest' ?>">

                    <!-- modal body-->
                    <div class="modal-body">
                        <input type="hidden" id="acceptUserId" name="user_id">
                        <input type="hidden" id="acceptArticleId" name="article_id">
                        <input type="hidden" id="acceptOrderId" name="order_id">
                        <p><b class="text-danger">Attenzione: </b>sei sicuro di voler accettare la seguente richiesta?</p>

                        <table class="table table-responsive-lg table-bordered text-center">
                            <tbody>

                            <tr>
                                <td class="w-50 font-weight-bold">Utente</td>
                                <td class="w-50" id="acceptUser"></td>
                            </tr>
                            <tr>
                                <td class="w-50 font-weight-bold">Email</td>
                                <td class="w-50" id="acceptUserEmail"></td>
                            </tr>
                            <tr>
                                <td class="w-50 font-weight-bold">Telefono</td>
                                <td class="w-50" id="acceptUserPhoneNumber"></td>
                            </tr>
                            <tr>
                                <td class="w-50 font-weight-bold">Indirizzo</td>
                                <td class="w-50" id="acceptUserAddress"></td>
                            </tr>
                            <tr>
                                <td class="w-50 font-weight-bold">Articolo</td>
                                <td class="w-50" id="acceptArticleName"></td>
                            </tr>
                            <tr>
                                <td class="w-50 font-weight-bold">Quantità</td>
                                <td class="w-50" id="acceptArticleQuantity"></td>
                            </tr>
                            <tr>
                                <td class="w-50 font-weight-bold">Data scadenza</td>
                                <td class="w-50" id="acceptArticleExpireDate"></td>
                            </tr>
                            <tr>
                                <td class="w-50 font-weight-bold">Data ordine</td>
                                <td class="w-50" id="acceptOrderDate"></td>
                            </tr>
                            <tr>
                                <td class="w-50 font-weight-bold">Data consegna</td>
                                <td class="w-50" id="acceptDeliveryDate"></td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <!-- end modal body-->

                    <!-- modal footer-->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Annulla</button>
                        <input type="submit" class="btn btn-danger" value="Accetta">
                    </div>
                    <!-- end modal body-->
                </form>

            </div>
        </div>
    </div>
    <!-- End confirm order modal -->

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