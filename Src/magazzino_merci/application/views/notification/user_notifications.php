<div class="container">
    <h1>Notifiche</h1>
    <hr>
    <p>Qui puoi visualizzare tutte le tue notifiche</p>

    <table id="notificationsTable" class="table text-center border-top table-responsive-sm">
        <thead>

        <th>Immagine</th>
        <th>Articolo</th>
        <th>Quantità</th>
        <th>Descrizione</th>
        <th>Data elaborazione dell'ordine</th>
        <th>Dettagli</th>

        </thead>
        <tbody>
        <?php foreach ($notifications as $notification): ?>
            <tr class="<?php echo ($notification['id_tipo_operazione'] == REJECT_ORDER_REQUEST) ? 'deep-orange lighten-4' : 'green accent-1' ?>">
                <?php $article = ArticleManager::getArticleById($notification['id_articolo']) ?>
                <?php $order = OrderManager::getOrderRequest($notification['id_ordine']) ?>
                <td><img class="img-table-height" src="<?php echo URL . '/application/libs/img/articles/' . ($article['percorso_immagine'] ?? 'default.jpg') ?>"></td>
                <td><?php echo $article['nome'] ?></td>
                <td><?php echo $order['quantita_ordine'] ?></td>
                <td><?php echo OperationTypeManager::getOperationTypeById($notification['id_tipo_operazione'])['nome'] ?></td>
                <td><?php echo date_format(date_create($notification['data']), 'd.m.Y G:i') ?></td>
                <td><a class="text-primary"
                   data-toggle="modal" data-target="#acceptOrderModal"
                   onclick="showOrderDetail('<?php echo rawurlencode($order['id']) ?>', '<?php echo rawurlencode($order['accettato']) ?>', '<?php echo rawurlencode($article['nome']) ?>', '<?php echo rawurlencode($order['quantita_ordine']) ?>', '<?php echo rawurlencode(date_format(date_create($article['data_scadenza']), 'd.m.Y')) ?>', '<?php echo rawurlencode($order['data_ordine']) ?>', '<?php echo rawurlencode($order['data_consegna']) ?>')">

                    <img class="bin-icon" src="/magazzino_merci/application/libs/img/svg/info.svg">
                    </a></td>
            </tr>


        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Start details order modal -->
    <div class="modal fade" id="acceptOrderModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Dettagli ordine</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- modal body-->
                <div class="modal-body">
                    <p id="infoMessage"></p>

                    <table class="table table-responsive-sm table-bordered text-center">
                        <tbody>
                        <tr>
                            <td class="w-50 font-weight-bold">ID ordine</td>
                            <td class="w-50" id="orderId"></td>
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
    <!-- End details order modal -->

    <script type="text/javascript" src="/magazzino_merci/application/libs/js/notification.js"></script>
    <script>
        $(document).ready(function () {
            $('#notificationsTable').DataTable({
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