<div class="container">
    <h1>Situazione magazzino</h1>
    <hr>
    <p><b class="text-danger">Attenzione: </b> se un articolo scade tutte le richieste d'ordine verranno rifiutate
        automaticamente</p>

    <table id="reportTable" class="table text-center border-top table-responsive-sm">
        <thead>
        <th>Immagine</th>
        <th>Articolo</th>
        <th>Quantità</th>
        <th>Data scadenza</th>
        <th>Descrizione</th>
        <th>Data elaborazione</th>
        <th>Dettagli</th>

        </thead>
        <tbody>
        <?php foreach ($notifications as $notification): ?>
            <?php $user = (isset($notification['utente_richiedente'])) ? UserManager::getUserById($notification['utente_richiedente']) : null ?>
            <tr
                <?php switch ($notification['id_tipo_operazione']):
                    case ACCEPT_ORDER_REQUEST:
                    case ARTICLE_STORED:
                        echo 'class="green accent-1"';
                        break;
                    case ARTICLE_ALMOST_EXPIRED:
                        echo 'class="yellow lighten-3"';
                        break;
                    case NEW_ORDER_REQUEST:
                        break;
                    default:
                        echo 'class="deep-orange lighten-4"';
                        break;
                endswitch; ?>>
                <?php $article = ArticleManager::getArticleById($notification['id_articolo']) ?>
                <?php $order = OrderManager::getOrderRequest($notification['id_ordine']) ?>
                <?php $operation = OperationTypeManager::getOperationTypeById($notification['id_tipo_operazione']) ?>
                <td><img class="img-table-height"
                         src="<?php echo URL . '/application/libs/img/articles/' . ($article['percorso_immagine'] ?? 'default.jpg') ?>">
                </td>
                <td><?php echo $article['nome'] ?></td>
                <td><?php echo($order['quantita_ordine'] ?? $article['quantita']); ?></td>
                <td><?php echo date_format(date_create($article['data_scadenza']), 'd.m.Y') ?></td>
                <td><?php echo OperationTypeManager::getOperationTypeById($notification['id_tipo_operazione'])['nome'] ?></td>
                <td><?php echo date_format(date_create($notification['data']), 'd.m.Y G:i') ?></td>
                <td><a class="text-primary" data-toggle="modal"
                        <?php if ($user != null): ?>
                            data-target="#orderModal"
                            onclick="showOrderRequestDetails('<?php echo rawurlencode($user['nome'] . ' ' . $user['cognome']) ?>', '<?php echo rawurlencode($user['email']) ?>', '<?php echo rawurlencode($user['telefono']) ?>', '<?php echo rawurlencode($user['cap'] . ' ' . $user['citta'] . ' ' . $user['via']) ?>', '<?php echo rawurlencode($article['nome']) ?>', '<?php echo rawurlencode($order['quantita_ordine']) ?>', '<?php echo rawurlencode(date_format(date_create($article['data_scadenza']), 'd.m.Y')) ?>', '<?php echo rawurlencode($order['data_ordine']) ?>', '<?php echo rawurlencode($order['data_consegna']) ?>')"
                        <?php else: ?>
                            data-target="#articleModal"
                            onclick="showArticleDetails('<?php echo rawurlencode($article['id']) ?>', '<?php echo rawurlencode($article['nome']) ?>', '<?php echo rawurlencode($article['quantita']) ?>', '<?php echo rawurlencode(date_format(date_create($article['data_scadenza']), 'd.m.Y')) ?>', '<?php echo rawurlencode(date_format(date_create($article['disponibile_il']), 'd.m.Y')) ?>', '<?php echo rawurlencode($operation['nome']) ?>', '<?php echo rawurlencode(($notification['id_tipo_operazione'] == ARTICLE_DELETED || $notification['id_tipo_operazione'] == ARTICLE_EXPIRED))? 'Non disponibile': 'Disponibile' ?>')"
                        <?php endif; ?>>
                        <img class="bin-icon" src="/magazzino_merci/application/libs/img/svg/info.svg">
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Start order modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Richiesta d'ordine - dettagli</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- modal body-->
                <div class="modal-body">
                    <p>Qui è possibile visualizzare i dettagli della richiesta d'ordine</p>

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
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Chiudi</button>
                </div>
                <!-- end modal body-->

            </div>
        </div>
    </div>
    <!-- End order modal -->

    <!-- Start article modal -->
    <div class="modal fade" id="articleModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Articolo - dettagli</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- modal body-->
                <div class="modal-body">
                    <p>Qui è possibile visualizzare i dettagli dell'articolo</p>

                    <table class="table table-responsive-lg table-bordered text-center">
                        <tbody>

                        <tr>
                            <td class="w-50 font-weight-bold">ID</td>
                            <td class="w-50" id="articleId"></td>
                        </tr>

                        <tr>
                            <td class="w-50 font-weight-bold">Articolo</td>
                            <td class="w-50" id="articleNameDetail"></td>
                        </tr>
                        <tr>
                            <td class="w-50 font-weight-bold">Quantità</td>
                            <td class="w-50" id="articleQuantityDetail"></td>
                        </tr>
                        <tr>
                            <td class="w-50 font-weight-bold">Data scadenza</td>
                            <td class="w-50" id="articleExpireDateDetail"></td>
                        </tr>
                        <tr>
                            <td class="w-50 font-weight-bold">Disponibile il</td>
                            <td class="w-50" id="articleAvailableDateDetail"></td>
                        </tr>
                        <tr>
                            <td class="w-50 font-weight-bold">Operazione</td>
                            <td class="w-50" id="operation"></td>
                        </tr>
                        <tr>
                            <td class="w-50 font-weight-bold">Stato</td>
                            <td class="w-50" id="articleStatus"></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <!-- end modal body-->

                <!-- modal footer-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Chiudi</button>
                </div>
                <!-- end modal body-->

            </div>
        </div>
    </div>
    <!-- End article modal -->

    <script type="text/javascript" src="/magazzino_merci/application/libs/js/reportManager.js"></script>
    <script>
        $(document).ready(function () {
            $('#reportTable').DataTable({
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